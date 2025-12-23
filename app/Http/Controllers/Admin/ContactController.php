<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\{Contact, ContactEmail, ContactPhone, CustomField, CustomValue};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        $query = Contact::with(['emails','phones','customValues.field'])
            ->where('is_active', true)
            ->whereNull('merged_into'); 

        if ($request->filled('name')) $query->where('name','like','%'.$request->name.'%');
        if ($request->filled('email')) $query->where('email','like','%'.$request->email.'%');
        if ($request->filled('gender')) $query->where('gender',$request->gender);

        if ($request->has('custom') && is_array($request->custom)) {
            foreach ($request->custom as $fieldId => $value) {
                if ($value === null || $value === '') continue;
                $query->whereHas('customValues', function($q) use ($fieldId,$value){
                    $q->where('custom_field_id', $fieldId)
                      ->where('value','like','%'.$value.'%');
                });
            }
        }

        $contacts = $query->orderBy('id','desc')->paginate(10);
        $customFields = CustomField::all();

        return view('admin.contacts.index', compact('contacts','customFields','data'));
    }

    public function list(Request $request)
    {
        $query = Contact::select(['id','name','email','phone','gender','is_active'])
            ->where('is_active', true)
            ->whereNull('merged_into'); 

        if ($request->filled('name')) $query->where('name','like','%'.$request->name.'%');
        if ($request->filled('email')) $query->where('email','like','%'.$request->email.'%');
        if ($request->filled('gender')) $query->where('gender',$request->gender);

        return response()->json(['data' => $query->orderBy('id','desc')->get()]);
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

        if ($request->hasFile('profile_image'))
            $data['profile_image']=$request->file('profile_image')->store('profiles','public');

        if ($request->hasFile('additional_file'))
            $data['additional_file']=$request->file('additional_file')->store('files','public');

        $contact = Contact::create($data);

        foreach ($request->input('emails', []) as $e)
            if ($e) ContactEmail::create(['contact_id'=>$contact->id,'email'=>$e]);

        foreach ($request->input('phones', []) as $p)
            if ($p) ContactPhone::create(['contact_id'=>$contact->id,'phone'=>$p]);

        foreach ($request->input('custom', []) as $fid => $val)
            if ($val !== null && $val !== '')
                CustomValue::create([
                    'contact_id'=>$contact->id,
                    'custom_field_id'=>$fid,
                    'value'=>$val
                ]);

        return response()->json(['success'=>true,'message'=>'Contact created']);
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
        foreach ($request->input('emails', []) as $e)
            if ($e) ContactEmail::create(['contact_id'=>$contact->id,'email'=>$e]);

        $contact->phones()->delete();
        foreach ($request->input('phones', []) as $p)
            if ($p) ContactPhone::create(['contact_id'=>$contact->id,'phone'=>$p]);

        foreach ($request->input('custom', []) as $fid => $val) {
            CustomValue::updateOrCreate(
                ['contact_id'=>$contact->id,'custom_field_id'=>$fid],
                ['value'=>$val]
            );
        }

        return response()->json(['success'=>true,'message'=>'Contact updated']);
    }

    public function destroy(Contact $contact)
    {
        $contact->update(['is_active'=>false]);
        return response()->json(['success'=>true,'message'=>'Contact marked inactive']);
    }

    public function mergePreview(Request $request)
    {
        $primary = Contact::with(['emails','phones','customValues.field'])
            ->whereNull('merged_into')->findOrFail($request->primary_id);

        $secondary = Contact::with(['emails','phones','customValues.field'])
            ->whereNull('merged_into')->findOrFail($request->secondary_id);

        $comparison = ['customs'=>[]];

        $pCustom = $primary->customValues->keyBy('custom_field_id');
        $sCustom = $secondary->customValues->keyBy('custom_field_id');
        $all = $pCustom->keys()->merge($sCustom->keys())->unique();

        foreach ($all as $fid) {
            $field = CustomField::find($fid);
            $comparison['customs'][] = [
                'field_name'=>$field?->name,
                'primary'=>$pCustom[$fid]->value ?? null,
                'secondary'=>$sCustom[$fid]->value ?? null
            ];
        }

        return response()->json(['comparison'=>$comparison]);
    }

    public function doMerge(Request $request)
    {
        $request->validate([
            'master_id'=>'required|different:secondary_id',
            'secondary_id'=>'required'
        ]);

        $master = Contact::where('is_active',1)
            ->whereNull('merged_into')
            ->with(['emails','phones','customValues'])
            ->findOrFail($request->master_id);

        $secondary = Contact::where('is_active',1)
            ->whereNull('merged_into')
            ->with(['emails','phones','customValues'])
            ->findOrFail($request->secondary_id);

        // Emails
        foreach ($secondary->emails as $e) {
            if (!$master->emails->pluck('email')->contains($e->email)) {
                ContactEmail::create(['contact_id'=>$master->id,'email'=>$e->email]);
            }
        }

        // Phones
        foreach ($secondary->phones as $p) {
            if (!$master->phones->pluck('phone')->contains($p->phone)) {
                ContactPhone::create(['contact_id'=>$master->id,'phone'=>$p->phone]);
            }
        }

        //FIELD MERGE (FIXED)
        $masterCustoms = $master->customValues->keyBy('custom_field_id');

        foreach ($secondary->customValues as $cv) {
            if (!$masterCustoms->has($cv->custom_field_id)) {
                CustomValue::create([
                    'contact_id'=>$master->id,
                    'custom_field_id'=>$cv->custom_field_id,
                    'value'=>$cv->value
                ]);
            } else if ($masterCustoms[$cv->custom_field_id]->value !== $cv->value) {
                $masterCustoms[$cv->custom_field_id]->update([
                    'value'=>json_encode([
                        'master'=>$masterCustoms[$cv->custom_field_id]->value,
                        'secondary'=>$cv->value
                    ])
                ]);
            }
        }

        // Core fields
        foreach (['name','email','phone','gender'] as $f) {
            if (!$master->$f && $secondary->$f) {
                $master->$f = $secondary->$f;
            }
        }
        $master->save();

        // Mark secondary
        $secondary->update([
            'is_active'=>false,
            'merged_into'=>$master->id,
            'merged_at'=>now()
        ]);

        return response()->json(['success'=>true,'message'=>'Contacts merged successfully']);
    }
}
