<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EgresoCajaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            return [
                'id' => $row->id,                
                'monto' => $row->monto,
                'observacion' => $row->observacion,
                'usuario' => optional($row->usuario)->name,
                'created_at' => $row->created_at->format('d-m-Y'),
            ];
        });
    }
}