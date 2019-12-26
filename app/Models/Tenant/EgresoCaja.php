<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\DocumentType;

class EgresoCaja extends ModelTenant
{
    protected $table = 'egreso_caja';
    
    protected $fillable = [
        'usuario_id',
        'monto',
        'observacion',       
    ];
    
    public function usuario() {
        return $this->belongsTo(User::class);
    }
    
    
}