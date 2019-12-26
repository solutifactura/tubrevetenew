<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\DocumentCollection;
use App\Models\Tenant\Person;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\ComisionesExport;
use Illuminate\Http\Request;
use App\Traits\ReportTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Company;
use Carbon\Carbon;

class ReportComisionesController extends Controller
{
    use ReportTrait;
    
    public function index() {
        $vendedores = Person::query()
        ->whereType('vendedores')
        ->orderBy('name')
            ->get();

        $establishments = Establishment::all();
        
        return view('tenant.reports.comisiones.index', compact('vendedores','establishments'));
    }
    
    public function search(Request $request) {
        $vendedores = Person::query()
        ->whereType('vendedores')
        ->orderBy('name')
            ->get();
        $td = $this->getTypeVende($request->vendedor);
        $establishments = Establishment::all();

        $d = null;
        $a = null;
        $establishment = $request->establishment;
        $establishment_id = $this->getEstablishmentId($establishment);


        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;
            
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->where('state_type_id', '<>', 13)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                    
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->where('state_type_id', '<>', 13)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('vendedor_id', $td);
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                    
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('user_id', $td);
            }
        }
        else {
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest();
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                   
                    ->latest();
            } else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->where('vendedor_id', $td);
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                   
                    ->latest()
                    ->where('user_id', $td);
            }
        }

        if(!is_null($establishment_id)){
            $reports = $reports->where('establishment_id', $establishment_id);
            $sales = $sales->where('establishment_id', $establishment_id);
        }

        $reports = $reports->paginate(config('tenant.items_per_page'));
        $sales = $sales->paginate(config('tenant.items_per_page'));
        // dd($reports->total());
        return view("tenant.reports.comisiones.index", compact("reports", "sales", "a", "d", "td", "vendedores","establishment","establishments"));
    }
    
    public function pdf(Request $request) {
        
        $company = Company::first();
        $establishment = Establishment::first();
        $td = $request->td;
        $establishment_id = $this->getEstablishmentId($request->establishment);

        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;
            
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->where('state_type_id', '<>', 13)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                    
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->where('state_type_id', '<>', 13)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('vendedor_id', $td)
                    ->get();

                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                    
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('user_id', $td);
            }
        }
        else {
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->get();
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                   
                    ->latest();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->where('vendedor_id', $td)
                    ->get();
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                   
                    ->latest()
                    ->where('user_id', $td);
            }
        }

        if(!is_null($establishment_id)){
            $reports = $reports->where('establishment_id', $establishment_id);
            $sales = $sales->where('establishment_id', $establishment_id);
        }

        set_time_limit(0); 

        $pdf = PDF::loadView('tenant.reports.comisiones.report_pdf', compact("reports", "sales", "company", "establishment"));
        $filename = 'Reporte_Documentos'.date('YmdHis');
        
        return $pdf->download($filename.'.pdf');
    }
    
    public function excel(Request $request) {
        $company = Company::first();
        $establishment = Establishment::first();
        $td= $request->td;
        $establishment_id = $this->getEstablishmentId($request->establishment);
       
        if ($request->has('d') && $request->has('a') && ($request->d != null && $request->a != null)) {
            $d = $request->d;
            $a = $request->a;
            
            if (is_null($td)) {
                $records = Document::with([ 'state_type', 'person'])
                    ->where('state_type_id', '<>', 13)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
                
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                    
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();
            }
            else {
                $records = Document::with([ 'state_type', 'person'])
                    ->where('state_type_id', '<>', 13)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('vendedor_id', $td)
                    ->get();


                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                    
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->where('user_id', $td);
            }
        }
        else {
            if (is_null($td)) {
                $records = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->get();
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                   
                    ->latest();
            }
            else {
                $records = Document::with([ 'state_type', 'person'])
                    ->where('vendedor_id', $td)
                    ->latest()
                    ->get();
                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])                   
                    ->latest()
                    ->where('user_id', $td);
            }
        }

        if(!is_null($establishment_id)){
            $records = $records->where('establishment_id', $establishment_id);
            $sales = $sales->where('establishment_id', $establishment_id);
        }
        
        return (new ComisionesExport)
                ->records($records)
                ->sales($sales)
                ->company($company)
                ->establishment($establishment)
                ->download('ReporteDoc'.Carbon::now().'.xlsx');
    }
}
