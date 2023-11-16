<?php

namespace Modules\Payment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'paid_on' => $this->paid_on,
            'details' => $this->details,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
     }
}
