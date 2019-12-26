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
            'usuario_id' => $this->usuario_id,
            'monto' => $this->monto,
            'observacion' => $this->observacion,

        ];
    }
}