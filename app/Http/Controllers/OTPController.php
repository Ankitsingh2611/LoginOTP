<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;
use Carbon\carbon;


class OTPController extends Controller
{
    public function loginwithotp()
    {
        return view('auth.loginwithotp');
    }

    public function loginwithotppost(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'email' => 'required|email|max:60'
        ]);
        $checkUser = User::where('email',$request->email)->first();
        if(is_null($checkUser)) {
            return redirect()->route('login.with.otp')->with('error','Your email is not associated with us.');
        } else {
            $otp = rand(123456,999999);
            $now = now();

            $userUPdateOTP = User::where('email',$request->email)->update([
                'otp' => $otp,
                'expire_at' => $now->addMinutes(10),
            ]);

            Mail::send('emails.emailLoginOTP', ['otp'=>$otp], function ($message) use($request) {
                $message->to($request->email);
                $message->subject('Login OTP');
            });
            return redirect()->route('confirm.login.with.otp')->with('success','Please check your email for OTP');
        }
    }
    public function confirmloginwithotppost(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'email' => 'required|email|max:60',
            'otp' => 'required|max:6'
        ]);
        $checkUser = User::where('otp',$request->otp)->where('email',$request->email)->first();
        if(is_null($checkUser)) {
            return redirect()->route('confirm.login.with.otp')->with('error','Your OTP or Email is not correct.');
        } else {
            $now = now();
            $currentTime = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            $optTime = Carbon::createFromFormat('Y-m-d H:i:s', $checkUser->expire_at);

            //echo "current time:".$currentTime ."<br/>optTime:".$optTime;
            $timeDifference = $optTime->diffInMinutes($currentTime);
            //echo "<br/>diff:".$timeDifference; exit();

            $UserUpdate = User::where('email',$request->email)->update([
                'expire_at' => Null,
                'otp' => Null
            ]);
            Auth::login($checkUser);






            // $userUPdateOTP = User::where('email',$request->email)->update([
            //     'otp' => $otp,
            //     'expire_at' => $now->addMinutes(10),
            // ]);

            // Mail::send('emails.emailLoginOTP', ['otp'=>$otp], function ($message) use($request) {
            //     $message->to($request->email);
            //     $message->subject('Login OTP');
            // });
            return redirect()->route('home')->with('success','Welcome to home.');
        }
    }
}
