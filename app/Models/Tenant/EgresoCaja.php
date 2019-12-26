<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User;
use App\Models\Tenant\Establishment;

class EgresoCaja extends ModelTenant
{
    protected $table = 'egreso_caja';
    protected $with = ['user'];
    
    protected $fillable = [
        'user_id',
        'establishment_id',
        'monto',
        'observacion', 
        'date_of_issue',      
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];
    
        
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function establishment() {
        return $this->belongsTo(Establishment::class);
    }
}