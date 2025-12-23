<?php
namespace App\Http\Controllers\Admin\Auth;
use App\Http\Controllers\BaseController;
use App\Models\PageTitle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Role;
use App\Models\LoginHistory;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function index()
    {
        $data = [];
        $PageData = PageTitle::whereId(1)->first();
        $data['title']= $PageData->title;
        $data['meta_title']= $PageData->meta_title;
        $data['meta_keyword']= $PageData->meta_keyword;
        $data['meta_description']= $PageData->meta_description;
        return view('auth.login', $data);
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Brute-force protection
        $blockStatus = $this->getAttemptsStatus(
            $request->ip(),
            $request->header('User-Agent'),
            $request->email
        );

        if (!$blockStatus['status']) {
           $message = $blockStatus['permanent_block']
            ? 'You have been permanently blocked. Contact admin.'
            : 'You are temporarily blocked for 15 minutes.';
            return response()->json(['status' => false, 'error' => $message], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'error'  => 'Invalid credentials'
            ], 401);
        }

        if ($user->status != 1) {
            return response()->json([
                'status' => false,
                'error'  => 'Account not approved'
            ], 401);
        }

        // Admin-only login
        if ($user->role_id !== 1) {
            return response()->json([
                'status' => false,
                'error'  => 'Unauthorized access'
            ], 403);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'status'   => true,
            'message'  => 'Login successful',
            'redirect' => route('dashboard'),
        ]);
    }

}


