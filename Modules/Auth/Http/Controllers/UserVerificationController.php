<?php

namespace Modules\Auth\Http\Controllers;

use App\Mail\EmailVerificationCodeMailtrap;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Traits\User\VerificationCodeTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Http\Requests\UserEmailVerificationRequest;


class UserVerificationController extends Controller
{
    use VerificationCodeTrait;


    public function ResendEmailOtp(UserEmailVerificationRequest $request)
    {

        $user = User::where('email', $request->email)->first();
        if (!$user || $user->verified == true || $user->ban == true) {
            return response()->json(["message" => "Invalid Email !"], 404);
        } else {

            if ($user->EmailOTP == null) {
                return $this->CreateAndSendEmailOtp($user);
            } else {
                $now = Carbon::now();
                $diffInMinutes = $now->diffInMinutes($user->EmailOTP->created_at);
                if ($diffInMinutes <= 3) {
                    return response()->json(['message' => 'The difference between sending the verification code should be 3 minutes'], 422);
                } else {
                    return $this->CreateAndSendEmailOtp($user);
                }
            }
        }
    }


    public  function CheckEmailOtpVerification(UserEmailVerificationRequest $request)
    {

        $user = User::where([['email', $request->email], ["verified", false], ['ban', false]])->first();
        if (!$user) {
            return response()->json(["message" => "Invalid Email !"], 404);
        } else {
            if ($user->EmailOTP == null) {
                return $this->CreateAndSendEmailOtp($user);
            } else {
                $now = Carbon::now();
                $diffInMinutes = $now->diffInMinutes($user->EmailOTP->created_at);
                if ($diffInMinutes < 15) {
                    return $this->CheckCodeOTP($request->code, $user);
                } else {
                    return response()->json(["message" => "OTP Code is expired, Send It Again "], 422);
                }
            }
        }
    }




    protected function  CheckCodeOTP($code, $user)
    {
        $Code = EmailVerificationCode::where([["user_id", $user->id], ['code', $code]])->first();

        if (!$Code) {
            return response()->json(["message" => "The code is incorrect, try again"], 422);
        } elseif ($Code) {
            User::find($user->id)->update([
                "verified" => true,
                "email_verified_at" =>  Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            return response()->json(["message" => "Email has been successfully verified 😊"], 200);
        }
    }



    private function CreateAndSendEmailOtp($user)
    {

        $code = $this->GenerateEmailOtp();
        $this->StoreEmailOtpForUser($user->id, $code);
        $mail = new EmailVerificationCodeMailtrap($code, $user->first_name);
        Mail::to($user->email)->send($mail);
        return response()->json(["message" => "Please Check Your Mail Otp Send Successfully","code" => $code . "  Just to test it in normal not sent "], 200);
    }
}
