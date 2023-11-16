<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['transaction_id', 'amount', 'paid_on', 'details',"user_id"];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

    protected static function boot()
    {
        parent::boot();

        static::created(function ($payment) {
            $payment->transaction->updateStatus();
        });

        static::saving(function ($payment) {
            $remainingAmount = $payment->transaction->remaining_amount;
            $newRemainingAmount = $remainingAmount - $payment->amount;

            if ($payment->amount > $remainingAmount) {
                throw new \Exception('The payment amount exceeds the remaining amount for the transaction.');
            }

            if ($newRemainingAmount <= 0 && $payment->transaction->due_on < now()) {
                $payment->transaction->status = 'Paid';
            } else {
                $payment->transaction->status = $payment->transaction->getOriginal('status');
            }
        });
    }
}
