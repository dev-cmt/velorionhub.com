<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        return view('backend.setting', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = Setting::first();
        $input = $request->all();

        if($request->hasFile('logo'))
        {
            $file = $request->file('logo');
            $imageName = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $path = public_path('uploads/setting');
            $file->move($path.'/', $imageName);
            $input['logo'] = 'uploads/setting/'.$imageName;

            if($data){
                if($data->logo){
                    if(file_exists(public_path($data->logo))){
                        @unlink(public_path($data->logo));
                    }
                }
            }
        }

        if($request->hasFile('favicon'))
        {
            $file = $request->file('favicon');
            $imageName = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $path = public_path('uploads/setting');
            $file->move($path.'/', $imageName);
            $input['favicon'] = 'uploads/setting/'.$imageName;

            if($data){
                if($data->favicon){
                    if(file_exists(public_path($data->favicon))){
                        @unlink(public_path($data->favicon));
                    }
                }
            }
        }

        if($request->hasFile('logo_light'))
        {
            $file = $request->file('logo_light');
            $imageName = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $path = public_path('uploads/setting');
            $file->move($path.'/', $imageName);
            $input['logo_light'] = 'uploads/setting/'.$imageName;

            if($data){
                if($data->logo_light){
                    if(file_exists(public_path($data->logo_light))){
                        @unlink(public_path($data->logo_light));
                    }
                }
            }
        }

        if($request->hasFile('logo_dark'))
        {
            $file = $request->file('logo_dark');
            $imageName = time().'_'.str_replace(' ', '_', $file->getClientOriginalName());
            $path = public_path('uploads/setting');
            $file->move($path.'/', $imageName);
            $input['logo_dark'] = 'uploads/setting/'.$imageName;

            if($data){
                if($data->logo_dark){
                    if(file_exists(public_path($data->logo_dark))){
                        @unlink(public_path($data->logo_dark));
                    }
                }
            }
        }

        if($data){
            $data->update($input);
        }else{
            Setting::create($input);
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
