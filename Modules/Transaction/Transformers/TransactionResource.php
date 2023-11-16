<?php

namespace Modules\Transaction\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Payment\Transformers\PaymentResource;

class TransactionResource extends JsonResource
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
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'remaining_amount' => $this->remaining_amount,
            'due_on' => $this->due_on,
            'vat' => $this->vat,
            'is_vat_inclusive' => $this->is_vat_inclusive,
            'status' => $this->status,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}
