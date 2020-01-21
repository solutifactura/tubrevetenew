@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta de Comisiones</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('tenant.reports.comisiones.search')}}" class="el-form demo-form-inline el-form--inline" method="GET">
                            <tenant-calendar2 :vendedores="{{json_encode($vendedores)}}" :establishments="{{json_encode($establishments)}}" establishment="{{$establishment ?? null}}" data_d="{{$d ?? ''}}" data_a="{{$a ?? ''}}" td="{{$td ?? null}}"></tenant-calendar2>
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                    <form action="{{route('tenant.report.comisiones.report_pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$d}}" name="d">
                                        <input type="hidden" value="{{$a}}" name="a">
                                        <input type="hidden" value="{{$td}}" name="td">
                                        <input type="hidden" value="{{$establishment}}" name="establishment">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                        {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                    </form>
                                <form action="{{route('tenant.report.comisiones.report_excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$d}}" name="d">
                                    <input type="hidden" value="{{$td}}" name="td">
                                    <input type="hidden" value="{{$a}} " name="a">
                                    <input type="hidden" value="{{$establishment}}" name="establishment">
                                    <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                    {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                </form>
                                @endif
                            </div>
                            @php
                                $acum_total_taxed=0;
                                $acum_total_igv=0;
                                $acum_total=0;
                                $acum_total_comision=0;

                                $acum_total_taxed_usd=0;
                                $acum_total_igv_usd=0;
                                $acum_total_usd=0;
                                $acum_total_comision_usd=0;

                                $acum_item=0;
                            @endphp
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th class="">#</th>
                                        
                                        <th class="">Comprobante</th>
                                        <th class="">Fecha emisión</th>
                                        <th class="">Cliente</th>
                                        <th class="">RUC</th>
                                        <th class="">Vendedor</th>
                                        <th class="">Moneda</th>
                                        <th class="">Total Gravado</th>
                                        <th class="">Total IGV</th>
                                        <th class="">Total</th>
                                        <th class="">Total Comisión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>                                        
                                        <td>{{$value->series}}-{{$value->number}}</td>
                                        <td>{{$value->date_of_issue->format('Y-m-d')}}</td>
                                        <td>{{$value->person->name}}</td>
                                        <td>{{$value->person->number}}</td>
                                        <td>{{json_decode($value->vendedor)->name}}</td>
                                        <td>{{$value->currency_type_id}}</td>
                                        <td>{{$value->total_taxed}}</td>
                                        <td>{{$value->total_igv}}</td>
                                        <td>{{$value->total}}</td>
                                        <td>{{$value->total_comisiones}}</td>
                                    </tr>
                                    @php
                                        if($value->currency_type_id == 'PEN'){
                                            $acum_total_taxed += $value->total_taxed;
                                            $acum_total_igv += $value->total_igv;
                                            $acum_total += $value->total;
                                            $acum_total_comision += $value->total_comisiones;

                                        }else if($value->currency_type_id == 'USD'){
                                            $acum_total_taxed_usd += $value->total_taxed;
                                            $acum_total_igv_usd += $value->total_igv;
                                            $acum_total_usd += $value->total;
                                            $acum_total_comision_usd += $value->total_comisiones;
                                        }
                                        $acum_item=$loop->iteration + 1;
                                    @endphp
                                    @endforeach

                                    @foreach($sales as $key => $value)
                                    <tr>
                                        <td>{{$acum_item}}</td>                                        
                                        <td>{{$value->identifier}}</td>
                                        <td>{{$value->date_of_issue->format('Y-m-d')}}</td>
                                        <td>{{$value->person->name}}</td>
                                        <td>{{$value->person->number}}</td>
                                        <td>{{$value->vendedor->name}}</td>
                                        <td>{{$value->currency_type_id}}</td>
                                        <td>{{$value->total_taxed}}</td>
                                        <td>{{$value->total_igv}}</td>
                                        <td>{{$value->total}}</td>
                                        <td>{{$value->total_comisiones}}</td>
                                    </tr>
                                    @php
                                        if($value->currency_type_id == 'PEN'){
                                            $acum_total_taxed += $value->total_taxed;
                                            $acum_total_igv += $value->total_igv;
                                            $acum_total += $value->total;
                                            $acum_total_comision += $value->total_comisiones;

                                        }else if($value->currency_type_id == 'USD'){
                                            $acum_total_taxed_usd += $value->total_taxed;
                                            $acum_total_igv_usd += $value->total_igv;
                                            $acum_total_usd += $value->total;
                                            $acum_total_comision_usd += $value->total_comisiones;
                                        }
                                        $acum_item= $acum_item + $loop->iteration;
                                    @endphp
                                    @endforeach

                                    <tr>
                                        <td colspan="6"></td>
                                        <td >Totales PEN</td>
                                        <td>{{$acum_total_taxed}}</td>
                                        <td>{{$acum_total_igv}}</td>
                                        <td>{{$acum_total}}</td>
                                        <td>{{$acum_total_comision}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td >Totales USD</td>
                                        <td>{{$acum_total_taxed_usd}}</td>
                                        <td>{{$acum_total_igv_usd}}</td>
                                        <td>{{$acum_total_usd}}</td>
                                        <td>{{$acum_total_comision_usd}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            Total {{$reports->total()}}
                            <label class="pagination-wrapper ml-2">
                                {{-- {{ $reports->appends(['search' => Session::get('form_document_list')])->render()  }} --}}
                                {{$reports->appends($_GET)->render()}} 
                            </label>
                        </div>
                    </div>
                    @else
                    <div class="box box-body no-padding">
                        <strong>No se encontraron registros</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
    <script></script>
@endpush
