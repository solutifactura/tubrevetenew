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
                'usuario' => optional($row->user)->name,
                'establishment_description' => optional($row->establishment)->description,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
            ];
        });
    }
}