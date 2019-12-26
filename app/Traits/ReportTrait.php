<?php

namespace App\Traits;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Establishment;
use App\Models\System\Client;
use App\Models\Tenant\Person;
/**
 * 
 */
trait ReportTrait
{
    /**
     * Get type doc
     * @param  string $documentType
     * @return int
     */
    public function getTypeDoc($documentType) {
        foreach (DocumentType::all() as $item) {
            if (mb_strtoupper($item->description) == $documentType) return $item->id;
        }
        
        return null;
    }

    public function getTypeVende($vendedor) {
        foreach (Person::all() as $item) {
            if (mb_strtoupper($item->name) == $vendedor) return $item->id;
        }
        
        return null;
    }

    public function getEstablishmentId($establishment) {
        foreach (Establishment::all() as $item) {
            if (mb_strtoupper($item->description) == $establishment) return $item->id;
        }
        
        return null;
    }

    public function getClientId($client) {
        foreach (Client::all() as $item) {
            if (mb_strtoupper($item->name) == $client) return $item->id;
        }
        
        return null;
    }
}
