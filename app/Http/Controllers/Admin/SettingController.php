<?php

namespace App\Http\Controllers\admin;
use App\Models\Setting;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\PageTitle;

class SettingController extends BaseController
{
    public function index(Request $request)
    {
        $data = [];
        $PageData = PageTitle::whereId(3)->first();
        $data['title']= $PageData->title;
        $data['meta_title']= $PageData->meta_title;
        $data['meta_keyword']= $PageData->meta_keyword;
        $data['meta_description']= $PageData->meta_description;
        
        if ($request->ajax()) {
            $settings = Setting::all();
            return response()->json($settings);
        }

        $settings = Setting::all();
        return view('admin.settings.index', compact('settings','data'));
    }

    public function create()
    {
        $PageData = PageTitle::whereId(4)->first();
        $data['title']= $PageData->title;
        $data['meta_title']= $PageData->meta_title;
        $data['meta_keyword']= $PageData->meta_keyword;
        $data['meta_description']= $PageData->meta_description;

        return view('admin.settings.create',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'parameter_type' => 'required',
        ]);

        $setting = new Setting();
        $setting->name = $request->name;
        $setting->description = $request->description;
        $setting->parameter_type = $request->parameter_type;
        $setting->status = 1;
        $setting->save();

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Setting created successfully!']);
        }

        return redirect()->route('settings.index')->with('success', 'Setting created successfully!');
    }

    public function edit($id)
    {
        $PageData = PageTitle::whereId(4)->first();
        $data['title']= $PageData->title;
        $data['meta_title']= $PageData->meta_title;
        $data['meta_keyword']= $PageData->meta_keyword;
        $data['meta_description']= $PageData->meta_description;

        $setting = Setting::findOrFail($id);
        return view('admin.settings.edit', compact('setting','data'));
    }

    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);
        $request->validate([
            // 'name' => 'required|string|max:255',
            'description' => 'required',
            'parameter_type' => 'nullable',
        ]);

        // $setting->name = $request->name;
        $setting->description = $request->description;
        $setting->parameter_type = $request->parameter_type;
        $setting->save();

        return response()->json(['status' => 'success', 'message' => 'Setting updated successfully']);
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return response()->json(['status' => 'success', 'message' => 'Setting deleted successfully']);
    }

}
