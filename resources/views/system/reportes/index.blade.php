@extends('system.layouts.app')

@section('content')

<div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta de Documentos</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('system.reportes.search')}}" class="el-form demo-form-inline el-form--inline" method="GET">
                        <system-calendar :clients="{{json_encode($clients)}}" client="{{$client ?? null}}" data_d="{{$d ?? ''}}" data_a="{{$a ?? ''}}"></system-calendar>
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                    <form action="{{route('system.report_pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$d}}" name="d">
                                        <input type="hidden" value="{{$a}}" name="a">
                                        <input type="hidden" value="{{$client}}" name="client">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                        {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                    </form>
                                <form action="{{route('system.report_excel')}}" class="d-inline" method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$d}}" name="d">                                   
                                    <input type="hidden" value="{{$a}} " name="a">
                                    <input type="hidden" value="{{$client}}" name="client">
                                    <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                    {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                </form>
                                @endif
                            </div>
                            @php
                                $acum_total_taxed=0;
                                $acum_total_igv=0;
                                $acum_total=0;

                                $acum_total_taxed_usd=0;
                                $acum_total_igv_usd=0;
                                $acum_total_usd=0;
                            @endphp
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
                                    <tr>
                                        <th class="">#</th>                                        
                                        <th class="">Cliente</th>
                                        <th class="">RUC</th>
                                        <th class="">Hostname</th>
                                        <th class="">Moneda</th>
                                        <th class="">Comprobantes</th>
                                        <th class="">Total Gravado</th>
                                        <th class="">Total IGV</th>
                                        <th class="">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>                                        
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->number}}</td>
                                        <td>{{$value->hostname->fqdn}}</td>
                                        <td>PEN</td>
                                        <td>{{$value->count_doc}}</td>
                                        <td>{{$value->sum_tg}}</td>
                                        <td>{{$value->sum_igv}}</td>
                                        <td>{{$value->sum_total}}</td>
                                    </tr>
                                    @php
                                        
                                            $acum_total_taxed += $value->sum_tg;
                                            $acum_total_igv += $value->sum_igv;
                                            $acum_total += $value->sum_total;
                                        

                                    @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="5"></td>
                                        <td >Totales PEN</td>
                                        <td>{{$acum_total_taxed}}</td>
                                        <td>{{$acum_total_igv}}</td>
                                        <td>{{$acum_total}}</td>
                                    </tr>
                                   
                                </tbody>
                            </table>
                          
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