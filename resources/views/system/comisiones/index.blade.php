@extends('system.layouts.app')

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
                        <form action="{{route('system.comisiones.search')}}" class="el-form demo-form-inline el-form--inline" method="GET">
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
                                $acum_comisiones=0;

                                $total_doc=0;
                                $total_sub=0;
                                $total_igv=0;
                                $total=0;
                                $total_comisiones=0;

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
                                        <th class="">Comprobantes / Notas de Ventas</th>
                                        <th class="">Promotores</th>
                                        <th class="">Total Gravado</th>
                                        <th class="">Total IGV</th>
                                        <th class="">Total</th>
                                        <th class="">Total Comisiones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $key => $value)
                                        @php
                                            $total_doc = $value->count_doc + $value->count_doc2;
                                            $total_sub = $value->sum_tg + $value->sum_tg2;
                                            $total_igv = $value->sum_igv + $value->sum_igv2;
                                            $total = $value->sum_total + $value->sum_total2;
                                            $total_comisiones = $value->sum_comisiones + $value->sum_comisiones2;
                                        @endphp
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->number}}</td>
                                        <td>{{$value->hostname->fqdn}}</td>
                                        <td>PEN</td>
                                        <td>{{$total_doc}}</td>
                                        <td>{{$value->count_user}}</td>
                                        <td>{{$total_sub}}</td>
                                        <td>{{$total_igv}}</td>
                                        <td>{{$total}}</td>
                                        <td>{{$total_comisiones}}</td>
                                    </tr>
                                    @php

                                            $acum_total_taxed += $total_sub;
                                            $acum_total_igv += $total_igv;
                                            $acum_total += $total;
                                            $acum_comisiones += $total_comisiones;


                                    @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="6"></td>
                                        <td >Totales PEN</td>
                                        <td>{{$acum_total_taxed}}</td>
                                        <td>{{$acum_total_igv}}</td>
                                        <td>{{$acum_total}}</td>
                                        <td>{{$acum_comisiones}}</td>
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
