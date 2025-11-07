<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\BaseController;
use App\Models\{Contact, ContactEmail, ContactPhone, CustomField, CustomValue};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use App\Models\PageTitle;

class ContactController extends BaseController
{
   
    public function index(Request $request)
    {

        $data = [];
        $PageData = PageTitle::whereId(2)->first();
        $data['title']= $PageData->title;
        $data['meta_title']= $PageData->meta_title;
        $data['meta_keyword']= $PageData->meta_keyword;
        $data['meta_description']= $PageData->meta_description;

        $query = Contact::with(['emails','phones','customValues.field'])->where('is_active', true);

        if ($request->filled('name')) $query->where('name','like','%'.$request->name.'%');
        if ($request->filled('email')) $query->where('email','like','%'.$request->email.'%');
        if ($request->filled('gender')) $query->where('gender',$request->gender);

        // custom filters
        if ($request->has('custom') && is_array($request->custom)) {
            foreach ($request->custom as $fieldId => $value) {
                if ($value === null || $value === '') continue;
                $query->whereHas('customValues', function($q) use ($fieldId,$value){
                    $q->where('custom_field_id', $fieldId)->where('value','like','%'.$value.'%');
                });
            }
        }

        $contacts = $query->orderBy('id','desc')->paginate(10);
        $customFields = CustomField::all();

        return view('admin.contacts.index', compact('contacts','customFields','data'));
    }

    public function list(Request $request)
    {
        
        $query = Contact::select(['id','name','email','phone','gender','is_active'])->where('is_active', true);

        if ($request->filled('name')) $query->where('name','like','%'.$request->name.'%');
        if ($request->filled('email')) $query->where('email','like','%'.$request->email.'%');
        if ($request->filled('gender')) $query->where('gender',$request->gender);

        $contacts = $query->orderBy('id','desc')->get();
        return response()->json(['data' => $contacts]);
    }

    
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'nullable|email',
            'phone'=>'nullable|string',
            'gender'=>'nullable|in:male,female,other',
            'profile_image'=>'nullable|image|max:2048',
            'additional_file'=>'nullable|file|max:5120',
        ]);

        if ($request->hasFile('profile_image')) $data['profile_image']=$request->file('profile_image')->store('profiles','public');
        if ($request->hasFile('additional_file')) $data['additional_file']=$request->file('additional_file')->store('files','public');

        $contact = Contact::create($data);

    
        foreach ($request->input('emails', []) as $e) if ($e) ContactEmail::create(['contact_id'=>$contact->id,'email'=>$e]);
        foreach ($request->input('phones', []) as $p) if ($p) ContactPhone::create(['contact_id'=>$contact->id,'phone'=>$p]);

       
        foreach ($request->input('custom', []) as $fid => $val) {
            if ($val !== null && $val !== '') CustomValue::create(['contact_id'=>$contact->id,'custom_field_id'=>$fid,'value'=>$val]);
        }

        return response()->json(['success'=>true,'message'=>'Contact created','contact'=>$contact]);
    }

    
    public function edit(Contact $contact)
    {
        
        $contact->load(['emails','phones','customValues']);
        return response()->json(['contact'=>$contact]);
    }

   
    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'nullable|email',
            'phone'=>'nullable|string',
            'gender'=>'nullable|in:male,female,other',
            'profile_image'=>'nullable|image|max:2048',
            'additional_file'=>'nullable|file|max:5120',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($contact->profile_image) Storage::disk('public')->delete($contact->profile_image);
            $data['profile_image']=$request->file('profile_image')->store('profiles','public');
        }
        if ($request->hasFile('additional_file')) {
            if ($contact->additional_file) Storage::disk('public')->delete($contact->additional_file);
            $data['additional_file']=$request->file('additional_file')->store('files','public');
        }

        $contact->update($data);

        $contact->emails()->delete();
        foreach ($request->input('emails', []) as $e) if ($e) ContactEmail::create(['contact_id'=>$contact->id,'email'=>$e]);

        $contact->phones()->delete();
        foreach ($request->input('phones', []) as $p) if ($p) ContactPhone::create(['contact_id'=>$contact->id,'phone'=>$p]);

        foreach ($request->input('custom', []) as $fid => $val) {
            CustomValue::updateOrCreate(['contact_id'=>$contact->id,'custom_field_id'=>$fid], ['value'=>$val]);
        }

        return response()->json(['success'=>true,'message'=>'Contact updated','contact'=>$contact]);
    }

   
    public function destroy(Contact $contact)
    {
        $contact->update(['is_active'=>false]);
        return response()->json(['success'=>true,'message'=>'Contact marked inactive']);
    }

    
    public function mergePreview(Request $request)
    {
        $primary = Contact::with(['emails','phones','customValues.field'])->findOrFail($request->primary_id);
        $secondary = Contact::with(['emails','phones','customValues.field'])->findOrFail($request->secondary_id);

        
        $comparison = [
            'fields' => [
                'name'=>['primary'=>$primary->name,'secondary'=>$secondary->name],
                'email'=>['primary'=>$primary->email,'secondary'=>$secondary->email],
                'phone'=>['primary'=>$primary->phone,'secondary'=>$secondary->phone],
                'gender'=>['primary'=>$primary->gender,'secondary'=>$secondary->gender],
            ],
            'emails'=>['primary'=>$primary->emails->pluck('email')->toArray(),'secondary'=>$secondary->emails->pluck('email')->toArray()],
            'phones'=>['primary'=>$primary->phones->pluck('phone')->toArray(),'secondary'=>$secondary->phones->pluck('phone')->toArray()],
            'customs'=>[]
        ];

        $pCustom = $primary->customValues->keyBy('custom_field_id');
        $sCustom = $secondary->customValues->keyBy('custom_field_id');
        $all = $pCustom->keys()->merge($sCustom->keys())->unique();

        foreach ($all as $fid) {
            $field = CustomField::find($fid);
            $comparison['customs'][] = [
                'field_id' => $fid,
                'field_name' => $field?->name ?? "Field-$fid",
                'primary' => $pCustom->has($fid) ? $pCustom->get($fid)->value : null,
                'secondary' => $sCustom->has($fid) ? $sCustom->get($fid)->value : null,
            ];
        }

        return response()->json(['primary'=>$primary,'secondary'=>$secondary,'comparison'=>$comparison]);
    }

   
    public function doMerge(Request $request)
    {
        $request->validate([
            'master_id'=>'required|exists:contacts,id',
            'secondary_id'=>'required|exists:contacts,id|different:master_id'
        ]);

        $master = Contact::with(['emails','phones','customValues'])->findOrFail($request->master_id);
        $secondary = Contact::with(['emails','phones','customValues'])->findOrFail($request->secondary_id);

        $changes = [
            'added_emails'=>[], 'added_phones'=>[], 'custom_field_changes'=>[], 'field_overrides'=>[]
        ];

        
        $mEmails = $master->emails->pluck('email')->toArray();
        foreach ($secondary->emails as $e) {
            if (!in_array($e->email, $mEmails)) {
                ContactEmail::create(['contact_id'=>$master->id,'email'=>$e->email]);
                $changes['added_emails'][] = $e->email;
            }
        }

       
        $mPhones = $master->phones->pluck('phone')->toArray();
        foreach ($secondary->phones as $p) {
            if (!in_array($p->phone, $mPhones)) {
                ContactPhone::create(['contact_id'=>$master->id,'phone'=>$p->phone]);
                $changes['added_phones'][] = $p->phone;
            }
        }

        // Custom fields
        $masterCustoms = $master->customValues->keyBy('custom_field_id');
        foreach ($secondary->customValues as $cv) {
            $fid = $cv->custom_field_id;
            if (!$masterCustoms->has($fid) || !$masterCustoms->get($fid)->value) {
                CustomValue::create(['contact_id'=>$master->id,'custom_field_id'=>$fid,'value'=>$cv->value]);
                $changes['custom_field_changes'][$fid] = ['old'=>null,'new'=>$cv->value];
            } else {
                $existing = $masterCustoms->get($fid);
                if ($existing->value != $cv->value) {
                    $new = $existing->value . ' | ' . $cv->value;
                    $changes['custom_field_changes'][$fid] = ['old'=>$existing->value,'new'=>$new];
                    $existing->value = $new;
                    $existing->save();
                }
            }
        }

       
        foreach (['name','email','phone','gender'] as $f) {
            if (!$master->$f && $secondary->$f) {
                $changes['field_overrides'][$f] = ['from'=>null,'to'=>$secondary->$f];
                $master->$f = $secondary->$f;
            }
        }
        $master->save();

        // mark secondary as merged and inactive
        $secondary->merged_into = $master->id;
        $secondary->is_active = false;
        $secondary->save();


        return response()->json(['success'=>true,'message'=>"Merged contact {$secondary->id} into {$master->id}",'changes'=>$changes]);
    }
}