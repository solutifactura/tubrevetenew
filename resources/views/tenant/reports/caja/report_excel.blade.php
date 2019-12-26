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
            <h3 align="center" class="title"><strong>Reporte Cierre de Caja (Detalle de Pago)</strong></h3>
        </div>
        <br>
        <div style="margin-top:20px; margin-bottom:15px;">
            <table>
                <tr>
                    <td>
                        <p><b>Empresa: </b></p>
                    </td>
                    <td align="center">
                        <p><strong>{{$company->name}}</strong></p>
                    </td>
                    <td>
                        <p><strong>Fecha: </strong></p>
                    </td>
                    <td align="center">
                        <p><strong>{{date('Y-m-d')}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Ruc: </strong></p>
                    </td>
                    <td align="center">{{$company->number}}</td>
                    <td>
                        <p><strong>Establecimiento: </strong></p>
                    </td>
                    <td align="center">{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</td>
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
                                <th class="">#</th>
                                <th class="">Tipo Documento</th>
                                <th class="">Comprobante</th>
                                <th class="">Fecha emisión</th>
                                <th class="">Cliente</th>
                                <th class="">RUC</th>
                                <th class="">Vendedor</th>
                                <th class="">Moneda</th>
                                <th class="">Total Gravado</th>
                                <th class="">Total IGV</th>
                                <th class="">Total</th>
                          
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>
                                <td class="celda">{{$loop->iteration}}</td>  
                                <td class="celda">{{$value->document_type->id}}</td>                                  
                                <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                                <td class="celda">{{$value->customer->name}}</td>
                                <td class="celda">{{$value->customer->number}}</td>
                                <td class="celda">{{json_decode($value->vendedor)->name}}</td>
                                <td class="celda">{{$value->currency_type_id}}</td>
                                <td class="celda">{{$value->total_taxed}}</td>
                                <td class="celda">{{$value->total_igv}}</td>
                                <td class="celda">{{$value->total}}</td>
                            
                            </tr>
                            @php
                                if($value->currency_type_id == 'PEN'){
                                    $acum_total_taxed += $value->total_taxed;
                                    $acum_total_igv += $value->total_igv;
                                    $acum_total += $value->total;
                                 

                                }else if($value->currency_type_id == 'USD'){
                                    $acum_total_taxed_usd += $value->total_taxed;
                                    $acum_total_igv_usd += $value->total_igv;
                                    $acum_total_usd += $value->total;
                            
                                }
                            @endphp

                                    @foreach($value->payments as $item_second => $value_second)
                                    <tr>
                                    <td class="celda" colspan="5"  align="right">Detalle de Pago</td>
                                    <td class="celda" align="center">{{ $loop->iteration }}</td>                                   
                                    <td class="celda" >{{ $value_second->payment_method_type->description }}</td>
                                    <td class="celda">{{ $value_second->payment }}</td>
                                    <td class="celda" colspan="3"></td>      
                                    </tr>   
                                    @endforeach
                            @endforeach
                            <tr>
                                <td colspan="7"></td>
                                <td >Totales PEN</td>
                                <td>{{$acum_total_taxed}}</td>
                                <td>{{$acum_total_igv}}</td>
                                <td>{{$acum_total}}</td>
                           
                            </tr>
                            <tr>
                                <td colspan="7"></td>
                                <td >Totales USD</td>
                                <td>{{$acum_total_taxed_usd}}</td>
                                <td>{{$acum_total_igv_usd}}</td>
                                <td>{{$acum_total_usd}}</td>
                                
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
