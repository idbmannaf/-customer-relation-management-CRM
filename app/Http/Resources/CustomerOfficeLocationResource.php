<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOfficeLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'company'=>$this->customer_company ? $this->customer_company->name : '',
            'other'=> $this->booth_id ? $this->booth_id : ($this->booth_name ? $this->booth_name : $this->serial_no)
        ];
    }
}
