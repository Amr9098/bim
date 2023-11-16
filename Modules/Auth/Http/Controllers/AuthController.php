<?php

namespace Modules\Auth\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Mail\EmailVe;
use App\Mail\EmailVerificationCodeMailtrap;
use App\Models\User;
use App\Traits\User\TokenTrait;
use App\Traits\User\VerificationCodeTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Http\Requests\AuthenticationRequest;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Throwable;

class AuthController extends Controller
{
    use VerificationCodeTrait, TokenTrait;

    //password hashed automatically in User model

    public function  RegistrationViaEmail(AuthenticationRequest $request)
    {
        try {
            DB::beginTransaction();
            $validReq = $request->validationData();
            $user = User::create([
                "first_name" => $validReq['first_name'],
                "last_name" => $validReq['last_name'],
                "email" => $validReq['email'],
                "password" => $validReq['password'],
            ])->assignRole("user");
            $code = $this->GenerateEmailOtp();
            $this->StoreEmailOtpForUser($user->id, $code);
            $mail = new EmailVerificationCodeMailtrap($code, $user->first_name);
            Mail::to($user->email)->send($mail);
            DB::commit();
            return response()->json(['message' => "register successfully , please check your email ", "code" => $code . "  Just to test it in normal not sent "], 201);
        } catch (Throwable $e) {
            DB::rollBack();

            return $e->getMessage();
            throw new GeneralJsonException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
    public function  RegistrationViaPhone(AuthenticationRequest $request)
    {

        try {
            $validReq = $request->validationData();

            $user = User::create([
                "first_name" => $validReq['first_name'],
                "last_name" => $validReq['last_name'],
                "phone" => $validReq['phone'],
                "password" => $validReq['password'],
                "verified" => true,
            ])->assignRole("user");
            return response()->json(["message" => 'Hello ' . $user->first_name . '👋, your registration has been successful'], 201);
        } catch (Throwable $e) {
            throw new GeneralJsonException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }




    public function login(AuthenticationRequest $request)
    {

        try {
            $credentials = $request->validated();
            $field = filter_var($credentials['email_or_phone'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $loginData = [$field => $credentials['email_or_phone'], 'password' => $credentials['password']];

            if (!JWTAuth::attempt($loginData)) {
                return response()->json(['message' => $field . ' Or Password incorrect', 'code' => 0], 422);
            }
            $user = JWTAuth::user();
            if ($user->ban) {
                return response()->json(['message' => 'Your Account is suspended, please contact us.', 'code' => 3], 422);
            }
            if (!$user->verified) {
                return response()->json(['message' => 'Please check your mobile number, verification code has been sent.', 'code' => 2], 422);
            }
            return $this->CreateUserToken($user);
        } catch (JWTException $e) {
            throw new GeneralJsonException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
