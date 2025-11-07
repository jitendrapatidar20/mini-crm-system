<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\PageTitle;


class AdminController extends BaseController
{
    
    public function dashboard(Request $request)
    {
        try
        {
        
            if(Auth::check()){
                $data = [];
                $PageData = PageTitle::whereId(3)->first();
                $data['title']= $PageData->title;
                $data['meta_title']= $PageData->meta_title;
                $data['meta_keyword']= $PageData->meta_keyword;
                $data['meta_description']= $PageData->meta_description;
                return view('admin.dashboard',$data);
            }
            return redirect("login")->withSuccess('Opps! You do not have access');

        }catch (\Exception $e) {
            $msg = $e->getMessage();
            Session::flash('danger',$msg);
            return redirect()->back()->withInput();
        }
  
       
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
