<?php

namespace App\Http\Resources\Tenant;

 
use Illuminate\Http\Resources\Json\JsonResource;

class EgresoCajaResource extends JsonResource
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
            'establishment_id' => $this->establishment_id,
            'monto' => $this->monto,
            'observacion' => $this->observacion,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),

        ];
    }
}