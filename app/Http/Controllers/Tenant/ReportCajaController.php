<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Resources\Tenant\DocumentCollection;
use App\Models\Tenant\Person;
use App\Models\Tenant\User;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\CajaExport;
use Illuminate\Http\Request;
use App\Traits\ReportTrait;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Document;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\EgresoCaja;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\Company;
use Carbon\Carbon;

class ReportCajaController extends Controller
{
    use ReportTrait;

    public function index() {
        $vendedores = User::all();

        $establishments = Establishment::all();

        return view('tenant.reports.caja.index', compact('vendedores','establishments'));
    }

    public function search(Request $request) {
        $vendedores = User::all();
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
                $reports = Document::with([ 'state_type', 'person', 'payments'])
                    ->where('state_type_id', '<>', 13)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();

                $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();

                $egresos = EgresoCaja::whereBetween('date_of_issue', [$d, $a])
                ->latest();


            }
            else {
                $reports = Document::with([ 'state_type', 'person', 'payments'])
                    ->where('state_type_id', '<>', 13)
                    ->where('user_id', $td)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();


                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                    ->where('user_id', $td)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();


                    $egresos = EgresoCaja::where('user_id', $td)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest();



            }
        }
        else {
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person', 'payments'])
                ->latest();

                $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                ->latest();

                $egresos = EgresoCaja::latest();


            } else {
                $reports = Document::with([ 'state_type', 'person', 'payments'])
                ->where('user_id', $td)
                ->latest()
                ->first();

                $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                ->where('user_id', $td)
                ->latest();


                $egresos = EgresoCaja::where('user_id', $td)
                ->latest();



            }
        }

        if(!is_null($establishment_id)){
            $reports = $reports->where('establishment_id', $establishment_id);
            $sales = $sales->where('establishment_id', $establishment_id);
            $egresos = $egresos->where('establishment_id', $establishment_id);

        }

        $reports = $reports->paginate(config('tenant.items_per_page'));
        $sales = $sales->paginate(config('tenant.items_per_page'));
        $egresos = $egresos->paginate(config('tenant.items_per_page'));
        // dd($reports->total());

        return view("tenant.reports.caja.index", compact("reports", "sales", "egresos", "a", "d", "td", "vendedores","establishment","establishments"));
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
                    ->latest()
                    ->get();

                    $egresos = EgresoCaja::whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                    ->where('state_type_id', '<>', 13)
                    ->where('user_id', $td)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();


                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                    ->where('user_id', $td)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();


                    $egresos = EgresoCaja::where('user_id', $td)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();

            }
        }
        else {
            if (is_null($td)) {
                $reports = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->get();

                    $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                    ->latest()
                    ->get();

                    $egresos = EgresoCaja::latest()
                    ->get();
            }
            else {
                $reports = Document::with([ 'state_type', 'person'])
                ->where('user_id', $td)
                ->latest()
                ->get();


                $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                ->where('user_id', $td)
                ->latest()
                ->get();


                $egresos = EgresoCaja::where('user_id', $td)
                ->latest()
                ->get();
            }
        }

        if(!is_null($establishment_id)){
            $reports = $reports->where('establishment_id', $establishment_id);
            $sales = $sales->where('establishment_id', $establishment_id);
            $egresos = $egresos->where('establishment_id', $establishment_id);

        }

        set_time_limit(0);

        //dd($egresos);

        $pdf = PDF::loadView('tenant.reports.caja.report_pdf', compact("reports","sales","egresos","company", "establishment"));


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
                    ->latest()
                    ->get();

                $egresos = EgresoCaja::whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();
            }
            else {
                $records = Document::with([ 'state_type', 'person'])
                    ->where('state_type_id', '<>', 13)
                    ->where('user_id', $td)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();



                $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->where('user_id', $td)
                    ->latest()
                    ->get();


                $egresos = EgresoCaja::where('user_id', $td)
                    ->whereBetween('date_of_issue', [$d, $a])
                    ->latest()
                    ->get();

            }
        }
        else {
            if (is_null($td)) {
                $records = Document::with([ 'state_type', 'person'])
                    ->latest()
                    ->get();

                $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                    ->latest()
                    ->get();

                    $egresos = EgresoCaja::latest()
                    ->get();
            }
            else {
                $records = Document::with([ 'state_type', 'person'])
                    ->where('user_id', $td)
                    ->latest()
                    ->get();

                $sales = SaleNote::with([ 'state_type', 'person', 'payments'])
                    ->where('user_id', $td)
                    ->latest()
                    ->get();


                $egresos = EgresoCaja::where('user_id', $td)
                    ->latest()
                    ->get();

            }
        }

        if(!is_null($establishment_id)){
            $records = $records->where('establishment_id', $establishment_id);
            $sales = $sales->where('establishment_id', $establishment_id);
            $egresos = $egresos->where('establishment_id', $establishment_id);
        }

        return (new CajaExport)
                ->records($records)
                ->sales($sales)
                ->egresos($egresos)
                ->company($company)
                ->establishment($establishment)
                ->download('ReporteDoc'.Carbon::now().'.xlsx');
    }
}
