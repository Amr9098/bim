<?php

namespace Modules\Auth\Http\Controllers;

use App\Mail\EmailVerificationCodeMailtrap;
use App\Models\EmailResetPasswordCode;
use App\Models\User;
use App\Traits\User\VerificationCodeTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Http\Requests\UserEmailResetPasswordRequest;

class UserForgotPasswordViaEmailController extends Controller
{
    use VerificationCodeTrait;

    public function SendRestPasswordViaEmailOTP(UserEmailResetPasswordRequest $request)
    {

        $validation = $request->validated();
        $user = User::where([['email', $validation['email']], ["verified", true], ['ban', false]])->first();
        if (!$user) {
            return response()->json(["message" => "Invalid Email !"], 404);
        } else {

            if ($user->ResetPasswordOtp == null) {
                return $this->CreateAndSendEmailResetPasswordOtp($user);
            } else {
                $now = Carbon::now();
                $diffInMinutes = $now->diffInMinutes($user->ResetPasswordOtp->created_at);
                if ($diffInMinutes <= 5) {
                    return response()->json(['message' => 'The difference between sending the Rest Password code should be 5 minutes'], 422);
                } else {
                    return $this->CreateAndSendEmailResetPasswordOtp($user);
                }
            }
        }
    }



    public function CheckOtpForRestPasswordViaEmail(UserEmailResetPasswordRequest $request)
    {


        $validation = $request->validated();

        $user = User::where([['email', $validation['email']], ["verified", true], ['ban', false]])->first();
        if (!$user) {
            return response()->json(["message" => "Invalid Email !"], 404);
        }
        if ($user->ResetPasswordOtp == null) {
            return response()->json(["message" => "this number doesn't have OTP Code !"], 422);
        } else {
            $now = Carbon::now();
            $diffInMinutes = $now->diffInMinutes($user->ResetPasswordOtp->created_at);
            if ($diffInMinutes <= 5) {
                return $this->CheckCodeOTP($user->id, $validation['code']);
            } else {
                return response()->json(["message" => "OTP Code is expired, Send It Again"], 422);
            }
        }
    }




    public function changePasswordViaEmail(UserEmailResetPasswordRequest $request)
    {
        $validation = $request->validated();
        $user = User::where([['email', $validation['email']], ["verified", true], ['ban', false]])->first();
        if (!$user) {
            return response()->json(["message" => "Invalid Email !"], 422);
        }
        if ($user->ResetPasswordOtp == null) {
            return response()->json(["message" => "this number doesn't have OTP Code !"], 422);
        } else {
            if ($user->ResetPasswordOtp->verified == false) {
                return response()->json(["message" => "Error, please enter the code first, stop playing"], 422);
            } elseif ($user->ResetPasswordOtp->expires_at <= Carbon::now()) {
                return response()->json(['error' => 'This session has expired. Please repeat the steps from the first'], 422);
            } else {
                return $this->ChangePassword($user->id, $validation['new_password']);
            }
        }
    }




    protected function  CheckCodeOTP($user_id, $code)
    {
        $Code = EmailResetPasswordCode::where([["user_id", $user_id], ['code', $code], ['verified', false]])->first();
        if (!$Code) {
            return response()->json(["message" => "The code is incorrect, try again"], 422);
        } elseif ($Code) {
            $Code->update([
                "verified" => true,
                "expires_at" =>  Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s')
            ]);
            return response()->json(["message" => "The code is valid and you have 5 minutes to reset your password"], 200);
        }
    }

    private function CreateAndSendEmailResetPasswordOtp($user)
    {
        $code = $this->GenerateEmailOtp();
        $this->StoreEmailResetPasswordCode($user->id, $code);
        $mail = new EmailVerificationCodeMailtrap($code, $user->first_name);
        // Mail::to($user->email)->send($mail);
        return response()->json(["message" => "Please Check Your Mail, Rest Password Otp Send Successfully",  "code" => $code . "  Just to test it in normal not sent "], 200);
    }

    protected function  ChangePassword($user_id, $new_password)
    {
        $Code = EmailResetPasswordCode::where([["user_id", $user_id], ['verified', true], ['expires_at', '>=', Carbon::now()]])->first();
        if (!$Code) {
            return response()->json(["message" => "This session has expired"], 422);
        } elseif ($Code) {
            $Code->delete();
            User::findOrFail($user_id)->update([
                "password" => $new_password
            ]);
            return response()->json(["message" => "New Password change successfully"], 200);;
        }
    }
}
