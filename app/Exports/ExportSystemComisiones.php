<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportSystemComisiones implements  FromView, ShouldAutoSize
{
    use Exportable;
    
    public function records($records) {
        $this->records = $records;
        
        return $this;
    }
    
    public function a($a) {
        $this->a = $a;
        
        return $this;
    }
    
    public function d($d) {
        $this->d = $d;
        
        return $this;
    }
    
    public function view(): View {
        return view('system.comisiones.report_excel', [
            'records'=> $this->records,
            'a' => $this->a,
            'd'=>$this->d
        ]);
    }
}
