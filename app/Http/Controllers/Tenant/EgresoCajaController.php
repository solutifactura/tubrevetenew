<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\EgresoCajaRequest;
use App\Http\Resources\Tenant\EgresoCajaCollection;
use App\Http\Resources\Tenant\EgresoCajaResource;
use App\Models\Tenant\User;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\EgresoCaja;

class EgresoCajaController extends Controller
{   
    public function index()
    {
        return view('tenant.egreso_caja.index');
    }

    public function create()
    {
        return view('tenant.egreso_caja.form');
    }

    public function record($id)
    {
        $record = new EgresoCajaResource(EgresoCaja::findOrFail($id));

        return $record;
    }

    public function tables()
    {
        $user = \auth()->user();
        return compact('user');
    }

    public function store(EgresoCajaRequest $request)
    {
        $id = $request->input('id');
        $egreso = EgresoCaja::firstOrNew(['id' => $id]);
        $egreso->user_id = $request->input('user_id');
        $egreso->establishment_id = $request->input('establishment_id');
        $egreso->monto = $request->input('monto');
        $egreso->observacion = $request->input('observacion');
        $egreso->date_of_issue = $request->input('date_of_issue');
        
        $egreso->save();

       
        return [
            'success' => true,
            'message' => ($id)?'Egreso actualizado':'Egreso registrado'
        ];
    }


    public function records()
    {
        $records = EgresoCaja::all();

        return new EgresoCajaCollection($records);
    }

    public function destroy($id)
    {
        $item = EgresoCaja::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Egreso eliminado con éxito'
        ];
    }
}