<?php
namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use App\Models\BlockUser;
use App\Models\User;

class BaseController extends Controller
{
    protected function getAttemptsStatus($ip, $agent, $email)
    {
        $key = 'login_attempts:' . md5($ip.'|'.$email);
        $blockKey = 'blocked:' . md5($ip.'|'.$email);

        $maxAttempts  = 5;
        $blockMinutes = 15;

        // Permanent block (DB)
        $isPermanentlyBlocked = BlockUser::where(function ($q) use ($ip, $email) {
                $q->where('ip_address', $ip)
                ->orWhere('email', $email);
            })
            ->where('permanent_block', true)
            ->exists();

        if ($isPermanentlyBlocked) {
            return ['status' => false, 'permanent_block' => 1];
        }

        //Temporary block (Cache)
        if (Cache::has($blockKey)) {
            return ['status' => false, 'permanent_block' => 0];
        }

        // Increment attempts safely
        $attempts = Cache::get($key, 0) + 1;

        Cache::put($key, $attempts, now()->addMinutes($blockMinutes));

        if ($attempts >= $maxAttempts) {

            BlockUser::create([
                'ip_address'      => $ip,
                'email'           => $email,
                'user_agent'      => $agent,
                'user_id'         => User::where('email', $email)->value('id'),
                'permanent_block' => false,
            ]);

            Cache::put($blockKey, true, now()->addMinutes($blockMinutes));
            Cache::forget($key);

            return ['status' => false, 'permanent_block' => 0];
        }

        return ['status' => true, 'permanent_block' => 0];
    }
}
