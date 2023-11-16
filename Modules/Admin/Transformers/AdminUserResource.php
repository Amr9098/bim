<?php

namespace Modules\Admin\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
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
            "email" => $this->email,
            "phone" =>$this->phone,
            "ban" => (bool) $this->ban,
            "verified" => (bool) $this->verified,
            "created_at" => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];


    }

}
