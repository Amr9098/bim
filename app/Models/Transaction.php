<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['amount', 'user_id', 'due_on', 'vat', 'is_vat_inclusive', 'status', 'remaining_amount'];



    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateStatus()
    {
        $totalPaid = $this->payments()->sum('amount');

        $this->remaining_amount = $this->getRemainingAmountAttribute();

        if ($totalPaid >= $this->getTotalAmountAttribute()) {
            $this->status = 'Paid';
        } elseif ($this->due_on < now()) {
            $this->status = 'Overdue';
        } else {
            $this->status = 'Outstanding';
        }

        $this->save();
    }

    public function getRemainingAmountAttribute()
    {
        $totalPaid = $this->payments()->sum('amount');
        return $this->getTotalAmountAttribute() - $totalPaid;
    }

    public function getTotalAmountAttribute()
    {
        $vatAmount = $this->is_vat_inclusive ? $this->amount - ($this->amount / (1 + ( $this->vat / 100))) : $this->amount * ($this->vat / 100);
        return ceil($this->amount + $vatAmount);
    }

}
