<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportAccountingConcarExport implements  FromView
{
    use Exportable;

    protected $data;

    public function data($data)
    {
        $this->data = $data;

        return $this;
    }
    
    public function view(): View {
        return view('tenant.accounting.templates.excel_concar', $this->data);
    }
}
