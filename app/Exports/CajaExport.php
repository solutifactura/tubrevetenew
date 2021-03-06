<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class CajaExport implements  FromView, ShouldAutoSize
{
    use Exportable;
    
    
    public function records($records) {
        $this->records = $records;
        
        return $this;
    }

    public function sales($sales) {
        $this->sales = $sales;
        
        return $this;
    }

    public function egresos($egresos) {
        $this->egresos = $egresos;
        
        return $this;
    }
    
    public function company($company) {
        $this->company = $company;
        
        return $this;
    }
    
    public function establishment($establishment) {
        $this->establishment = $establishment;
        
        return $this;
    }
    
    public function view(): View {
        return view('tenant.reports.caja.report_excel', [            
            'records'=> $this->records,
            'sales'=> $this->sales,
            'egresos'=> $this->egresos,
            'company' => $this->company,
            'establishment'=>$this->establishment
        ]);
    }
}
