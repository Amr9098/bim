<?php

namespace App\Traits\User;

use App\Models\EmailResetPasswordCode;
use App\Models\EmailVerificationCode;
use Carbon\Carbon;

trait VerificationCodeTrait
{

    public function GenerateEmailOtp()
    {
        return mt_rand(1000, 9999);
    }


    public function StoreEmailOtpForUser($id, $code)
    {
        EmailVerificationCode::whereNotNull("user_id")->where(['user_id' => $id])->delete();
        EmailVerificationCode::create([
            "user_id" => $id,
            "code" => $code
        ]);
    }

    public function StoreEmailResetPasswordCode($id, $code)
    {
        $expiresAt = Carbon::now()->addMinutes(5)->format('Y-m-d H:i:s');
        EmailResetPasswordCode::whereNotNull("user_id")->where(['user_id' => $id])->delete();
        EmailResetPasswordCode::create([
            "user_id" => $id,
            "code" => $code,
            "expires_at" => $expiresAt,
            "verified" => false,
        ]);
    }
}
