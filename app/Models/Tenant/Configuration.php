<?php

namespace App\Models\Tenant;

class Configuration extends ModelTenant
{
    protected $fillable = [
        'send_auto', 
        'cron', 
        'stock',
        'sunat_alternate_server'
    ];
}