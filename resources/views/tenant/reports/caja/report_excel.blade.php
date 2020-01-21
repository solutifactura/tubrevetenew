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
                                $acum_total_comision=0;
                                $acum_efectivo=0;
                                $acum_td=0;
                                $acum_tc=0;
                                $acum_trans=0;

                                $efectivo=0;
                                $td=0;
                                $tc=0;
                                $trans=0;

                                $acum_total_taxed_usd=0;
                                $acum_total_igv_usd=0;
                                $acum_total_usd=0;
                                $acum_total_comision_usd=0;
                                $acum_efectivo_usd=0;
                                $acum_td_usd=0;
                                $acum_tc_usd=0;
                                $acum_trans_usd=0;

                                $acum_total_egreso = 0;
                                $total_real = 0;

                                $acum_item=0;  
                      
                    @endphp
                    <table class="">
                        <thead>
                            <tr>
                                <th class="">#</th>                                       
                                <th class="">Comprobante</th>
                                <th class="">Fecha emisión</th>
                                <th class="">Cliente</th>
                                <th class="">RUC</th>
                                        
                                <th class="">Moneda</th>
                                <th class="">Total Gravado</th>
                                <th class="">Total IGV</th>
                                <th class="">Total</th>
                                <th class="">Efectivo</th>
                                <th class="">Tarjeta de Debito</th>
                                <th class="">Tarjeta de Credito</th>
                                <th class="">Transferencia</th>
                          
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $key => $value)
                            <tr>
                                <td class="celda">{{$loop->iteration}}</td>  
                                                                 
                                <td class="celda">{{$value->series}}-{{$value->number}}</td>
                                <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                                <td class="celda">{{$value->customer->name}}</td>
                                <td class="celda">{{$value->customer->number}}</td>
                                                           
                                <td class="celda">{{$value->currency_type_id}}</td>
                                <td class="celda">{{$value->total_taxed}}</td>
                                <td class="celda">{{$value->total_igv}}</td>
                                <td class="celda">{{$value->total}}</td>
                            
                            </tr>                           
                            
                            @foreach($value->payments as $item_second => $value_second)
                                    @php

                                        if($value_second->payment_method_type->description == 'Efectivo'){                                                
                                            $efectivo=$value_second->payment;                                               
                                        }else{                                                
                                            $efectivo=0;   
                                            if($value_second->payment_method_type->description == 'Tarjeta de débito'){                                                
                                                $td=$value_second->payment;                                               
                                            }else{                                                
                                                $td=0;  
                                                if($value_second->payment_method_type->description == 'Tarjeta de crédito'){                                                
                                                    $tc=$value_second->payment;                                               
                                                }else{                                                
                                                    $tc=0; 
                                                    if($value_second->payment_method_type->description == 'Transferencia'){                                                
                                                        $trans=$value_second->payment;                                               
                                                    }else{                                                
                                                        $trans=0;                                               
                                                    }                                              
                                                }                                             
                                            }                                            
                                        }
                                
                                    @endphp  
                                    <tr>
                                        <td colspan="9"></td>
                                        <td  align="center"class="celda">{{$efectivo}}</td>
                                        <td  align="center" class="celda">{{$td}}</td>
                                        <td  align="center" class="celda">{{$tc}}</td>
                                        <td  align="center" class="celda">{{$trans}}</td>
                                    </tr>

                                    @php
                                        if($value->currency_type_id == 'PEN'){

                                            if($value_second->payment_method_type->description == 'Efectivo'){                                                
                                                $acum_efectivo += $value_second->payment;                                                
                                            }
                                            if($value_second->payment_method_type->description == 'Tarjeta de débito'){                                                
                                                $acum_td += $value_second->payment;                                               
                                            }
                                            if($value_second->payment_method_type->description == 'Tarjeta de crédito'){                                                
                                                $acum_tc += $value_second->payment;                                                
                                            }
                                            if($value_second->payment_method_type->description == 'Transferencia'){                                                
                                                $acum_trans += $value_second->payment;                                                
                                            }                                    

                                        }else if($value->currency_type_id == 'USD'){
                                            
                                            if($value_second->payment_method_type->description == 'Efectivo'){                                                
                                                $acum_efectivo_usd += $value_second->payment;                                                
                                            }
                                            if($value_second->payment_method_type->description == 'Tarjeta de débito'){                                                
                                                $acum_td_usd += $value_second->payment;                                               
                                            }
                                            if($value_second->payment_method_type->description == 'Tarjeta de crédito'){                                                
                                                $acum_tc_usd += $value_second->payment;                                                
                                            }
                                            if($value_second->payment_method_type->description == 'Transferencia'){                                                
                                                $acum_trans_usd += $value_second->payment;                                                
                                            }                                            
                                        }
                                    @endphp                                         
                                       
                                @endforeach
                                    

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
                                
                                    $acum_item=$loop->iteration + 1;

                                @endphp                          

                            @endforeach

                            @foreach($sales as $key => $value)
                                <tr>
                                    <td class="celda">{{$acum_item}}</td>                                        
                                    <td class="celda">{{$value->identifier}}</td>
                                    <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                                    <td class="celda">{{$value->person->name}}</td>
                                    <td class="celda">{{$value->person->number}}</td>                                       
                                    <td class="celda">{{$value->currency_type_id}}</td>
                                    <td class="celda">{{$value->total_taxed}}</td>
                                    <td class="celda">{{$value->total_igv}}</td>
                                    <td class="celda">{{$value->total}}</td>
                                </tr>    
                                
                                @foreach($value->payments as $item_second => $value_second)
                                        
                                    @php

                                        if($value_second->payment_method_type->description == 'Efectivo'){                                                
                                                $efectivo=$value_second->payment;                                               
                                            }else{                                                
                                                $efectivo=0;   
                                                if($value_second->payment_method_type->description == 'Tarjeta de débito'){                                                
                                                    $td=$value_second->payment;                                               
                                                }else{                                                
                                                    $td=0;  
                                                    if($value_second->payment_method_type->description == 'Tarjeta de crédito'){                                                
                                                        $tc=$value_second->payment;                                               
                                                    }else{                                                
                                                        $tc=0; 
                                                        if($value_second->payment_method_type->description == 'Transferencia'){                                                
                                                            $trans=$value_second->payment;                                               
                                                        }else{                                                
                                                            $trans=0;                                               
                                                        }                                              
                                                    }                                             
                                                }                                            
                                            }
                                                                                                                  
                                    @endphp  
                                        
                                    <tr>
                                        <td colspan="9" class="celda"></td>
                                        <td class="celda" align="center">{{$efectivo}}</td>
                                        <td class="celda" align="center">{{$td}}</td>
                                        <td class="celda" align="center">{{$tc}}</td>
                                        <td class="celda" align="center">{{$trans}}</td>
                                    </tr>


                                    @php
                                        if($value->currency_type_id == 'PEN'){

                                                if($value_second->payment_method_type->description == 'Efectivo'){                                                
                                                    $acum_efectivo += $value_second->payment;                                                
                                                }
                                                if($value_second->payment_method_type->description == 'Tarjeta de débito'){                                                
                                                    $acum_td += $value_second->payment;                                               
                                                }
                                                if($value_second->payment_method_type->description == 'Tarjeta de crédito'){                                                
                                                    $acum_tc += $value_second->payment;                                                
                                                }
                                                if($value_second->payment_method_type->description == 'Transferencia'){                                                
                                                    $acum_trans += $value_second->payment;                                                
                                                }                                   

                                            }else if($value->currency_type_id == 'USD'){                                                
                                                if($value_second->payment_method_type->description == 'Efectivo'){                                                
                                                    $acum_efectivo_usd += $value_second->payment;                                                
                                                }
                                                if($value_second->payment_method_type->description == 'Tarjeta de débito'){                                                
                                                    $acum_td_usd += $value_second->payment;                                               
                                                }
                                                if($value_second->payment_method_type->description == 'Tarjeta de crédito'){                                                
                                                    $acum_tc_usd += $value_second->payment;                                                
                                                }
                                                if($value_second->payment_method_type->description == 'Transferencia'){                                                
                                                    $acum_trans_usd += $value_second->payment;                                                
                                                }                                                
                                            }
                                    @endphp                                         
                                       
                                @endforeach
                                    
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
                                
                                    $acum_item= $acum_item + $loop->iteration;
                                @endphp  
                                
                            @endforeach

                            @foreach($egresos as $key => $value)                                  
                                    
                                @php
                                    $acum_total_egreso += $value->monto;

                                @endphp
                            @endforeach

                            @php
                                $total_real = $acum_total - $acum_total_egreso;

                            @endphp


                            <tr>
                                <td class="celda" colspan="5"></td>
                                <td class="celda" >Totales PEN</td>
                                <td class="celda">{{$acum_total_taxed}}</td>
                                <td class="celda">{{$acum_total_igv}}</td>
                                <td class="celda">{{$acum_total}}</td>
                                <td  align="center " class="celda">{{$acum_efectivo}}</td>
                                        <td  class="celda" align="center">{{$acum_td}}</td>
                                        <td class="celda" align="center">{{$acum_tc}}</td>
                                        <td class="celda" align="center">{{$acum_trans}}</td>
                            
                            </tr>
                            <tr>
                                <td class="celda" colspan="5"></td>
                                <td class="celda" >Totales USD</td>
                                <td class="celda">{{$acum_total_taxed_usd}}</td>
                                <td class="celda">{{$acum_total_igv_usd}}</td>
                                <td class="celda">{{$acum_total_usd}}</td>
                                <td class="celda" align="center">{{$acum_efectivo_usd}}</td>
                                        <td class="celda" align="center">{{$acum_td_usd}}</td>
                                        <td class="celda" align="center">{{$acum_tc_usd}}</td>
                                        <td class="celda" align="center">{{$acum_trans_usd}}</td>
                             
                            </tr>
                            <tr>
                                        <td class="celda" colspan="5"></td>
                                        <td class="celda">Total Egreso de Caja</td>
                                        <td class="celda"></td>
                                        <td class="celda"></td>
                                        <td class="celda">{{$acum_total_egreso}}</td>
                                        <td class="celda" align="center"></td>
                                        <td class="celda" align="center"></td>
                                        <td class="celda" align="center"></td>
                                        <td class="celda" align="center"></td>
                                        
                                    </tr>

                                    <tr>
                                        <td class="celda" colspan="5"></td>
                                        <td class="celda" >Total</td>
                                        <td class="celda"></td>
                                        <td class="celda"></td>
                                        <td class="celda">{{$total_real}}</td>
                                        <td class="celda" align="center"></td>
                                        <td class="celda" align="center"></td>
                                        <td class="celda" align="center"></td>
                                        <td class="celda" align="center"></td>
                                        
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
