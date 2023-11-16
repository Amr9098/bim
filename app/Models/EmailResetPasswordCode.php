<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailResetPasswordCode extends Model
{
    use HasFactory;


    protected $table = "email_reset_password_codes";

    protected $fillable = ["user_id", "expires_at", "code", "verified",];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
