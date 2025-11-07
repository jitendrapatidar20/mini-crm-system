<?php
namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\BlockUser; 
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;

class BaseController extends Controller
{
    

    protected function getAttemptsStatus($ip, $agent, $email)
    {
        $result = ['status' => true, 'permanent_block' => 0];
        if (BlockUser::where('ip_address', $ip)->where('permanent_block', 1)->exists()) {
            return ['status' => false, 'permanent_block' => 1];
        }

        $recentBlock = BlockUser::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->latest()->first();

        if ($recentBlock) {
            return ['status' => false, 'permanent_block' => 0];
        }

        $attempts = Session::increment('RestrictLogin');
        if ($attempts >= 5) {
            BlockUser::create([
                'ip_address' => $ip,
                'user_agent' => $agent,
                'user_id' => optional(User::where('email', $email)->first())->id,
            ]);
            Session::put('RestrictLogin', 0);
            return ['status' => false, 'permanent_block' => 0];
        }
        return $result;
    }
    // protected function logStore(Request $request, $response = [])
    // {
    //     try {
    //         $logData = [
    //             'Request URI'  => $request->getPathInfo(),
    //             'Method'       => $request->getMethod(),
    //             'IP Address'   => $request->ip(),
    //             'GET Data'     => $request->query(),         // $_GET
    //             'Post Data'    => $request->post(),          // $_POST
    //             'All Data'     => $request->all(),           // $_REQUEST
    //             'Json Data'    => $request->json()->all(),   // JSON payload
    //             'Response'     => $response,
    //         ];
    //         Log::info('HTTP Request: ' . json_encode($logData));
    //     } catch (\Throwable $th) {
    //         // Optional: Log the error if needed
    //         Log::error('Failed to log request: ' . $th->getMessage());
    //     }
    // }
	
}
