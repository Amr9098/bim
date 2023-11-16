<?php

namespace Modules\User\Transformers;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AuthUserDataResource extends JsonResource
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
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->when($this->email, $this->email),
            "phone" => $this->when($this->phone, $this->phone),
            "ban" => (bool) $this->ban,
            "verified" => (bool) $this->verified,
            "created_at" => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            // "updated_at" => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),

        ];
    }
}
