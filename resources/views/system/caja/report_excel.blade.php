<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <div>
            <h3 align="center" class="title"><strong>Reporte Documentos</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                <tr>
                    <td>
                        <p><b>Desde: </b></p>
                    </td>
                    <td align="center">
                        <p><strong>{{$d}}</strong></p>
                    </td>
                    <td>
                        <p><strong>Hasta: </strong></p>
                    </td>
                    <td align="center">
                        <p><strong>{{$a}}</strong></p>
                    </td>
                </tr>
                
            </table>
        </div>
        <br>
        @if(!empty($records))
            <div class="">
                <div class=" ">
                    @php
                        $acum_total_taxed=0;
                        $acum_total_igv=0;
                        $acum_total=0;
                        $acum_total_taxed_usd=0;
                        $acum_total_igv_usd=0;
                        $acum_total_usd=0;
                    @endphp
                    <table class="">
                        <thead>
                            <tr>
                            <th>#</th>                                
                            <th>Cliente</th>
                            <th>RUC</th> 
                            <th>Hostname</th>                               
                            <th>Moneda</th>
                            <th>Comprobantes</th>
                            <th>Total Gravado</th>
                            <th>Total IGV</th>
                            <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>                              

                                <td class="celda">{{$loop->iteration}}</td>
                                <td class="celda">{{$value->name}}</td>                            
                                <td class="celda">{{$value->number}}</td>
                                <td class="celda">{{$value->hostname->fqdn}}</td>
                                <td class="celda">PEN</td>
                                <td class="celda">{{$value->count_doc}}</td>
                                <td class="celda">{{$value->sum_tg}}</td>
                                <td class="celda">{{$value->sum_igv}}</td>
                                <td class="celda">{{$value->sum_total}}</td>
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
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
