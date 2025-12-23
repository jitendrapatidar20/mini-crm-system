<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\PageTitle;

class AdminController extends BaseController
{

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $pageData = PageTitle::find(4);

        $data = [
            'title'            => $pageData->title ?? 'Dashboard',
            'meta_title'       => $pageData->meta_title ?? '',
            'meta_keyword'     => $pageData->meta_keyword ?? '',
            'meta_description' => $pageData->meta_description ?? '',
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Logout Admin
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}
