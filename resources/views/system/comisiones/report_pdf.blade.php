<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <style>
            html {
                font-family: sans-serif;
                font-size: 11px;
            }

            table {
                width: 100%;
                border-spacing: 0;
                border: 1px solid black;
            }

            .celda {
                text-align: center;
                padding: 5px;
                border: 0.1px solid black;
            }

            th {
                padding: 5px;
                text-align: center;
                border-color: #0088cc;
                border: 0.1px solid black;
            }

            .title {
                font-weight: bold;
                padding: 5px;
                font-size: 20px !important;
                text-decoration: underline;
            }

            p>strong {
                margin-left: 5px;
                font-size: 13px;
            }

            thead {
                font-weight: bold;
                background: #0088cc;
                color: white;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div>
            <p align="center" class="title"><strong>Reporte Comisiones</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>
                <tr>
                    <td>
                        <p><strong>Desde: </strong>{{$d}}</p>
                    </td>
                    <td>
                        <p><strong>Hasta: </strong>{{$a}}</p>
                    </td>
                </tr>

            </table>
        </div>
        @if(!empty($reports))
            <div class="">
                <div class=" ">
                    @php
                        $acum_total_taxed=0;
                        $acum_total_igv=0;
                        $acum_total=0;
                        $acum_total_taxed_usd=0;
                        $acum_total_igv_usd=0;
                        $acum_total_usd=0;
                        $acum_comisiones=0;
                    @endphp
                    <table class="">
                        <thead>
                            <tr>
                                        <th class="">#</th>
                                        <th class="">Cliente</th>
                                        <th class="">RUC</th>
                                        <th class="">Hostname</th>
                                        <th class="">Moneda</th>
                                        <th class="">Comprobantes</th>
                                        <th class="">Promotores</th>
                                        <th class="">Total Gravado</th>
                                        <th class="">Total IGV</th>
                                        <th class="">Total</th>
                                        <th class="">Total Comisiones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $key => $value)
                                <tr>
                                    <td class="celda">{{$loop->iteration}}</td>
                                    <td class="celda">{{$value->name}}</td>
                                    <td class="celda">{{$value->number}}</td>
                                    <td class="celda">{{$value->hostname->fqdn}}</td>
                                    <td class="celda">PEN</td>
                                    <td class="celda">{{$value->count_doc}}</td>
                                    <td class="celda">{{$value->count_user}}</td>
                                    <td class="celda">{{$value->sum_tg}}</td>
                                    <td class="celda">{{$value->sum_igv}}</td>
                                    <td class="celda">{{$value->sum_total}}</td>
                                    <td class="celda">{{$value->sum_comisiones}}</td>
                                </tr>
                            @php


                                    $acum_total_taxed += $value->sum_tg;
                                    $acum_total_igv += $value->sum_igv;
                                    $acum_total += $value->sum_total;
                                    $acum_comisiones += $value->sum_comisiones;

                            @endphp
                            @endforeach
                            <tr>
                                <td class="celda" colspan="6"></td>
                                <td class="celda" >Totales PEN</td>
                                <td class="celda">{{$acum_total_taxed}}</td>
                                <td class="celda">{{$acum_total_igv}}</td>
                                <td class="celda">{{$acum_total}}</td>
                                <td class="celda">{{$acum_comisiones}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="callout callout-info">
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
