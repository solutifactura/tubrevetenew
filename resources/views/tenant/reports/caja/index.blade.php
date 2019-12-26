@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">Consulta de Cierre de Caja (Detalle de Pago)</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <form action="{{route('tenant.reports.caja.search')}}" class="el-form demo-form-inline el-form--inline" method="GET">
                            <tenant-calendar2 :vendedores="{{json_encode($vendedores)}}" :establishments="{{json_encode($establishments)}}" establishment="{{$establishment ?? null}}" data_d="{{$d ?? ''}}" data_a="{{$a ?? ''}}" td="{{$td ?? null}}"></tenant-calendar2>
                        </form>
                    </div>
                    @if(!empty($reports) && $reports->count())
                    <div class="box">
                        <div class="box-body no-padding">
                            <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                    <form action="{{route('tenant.report.caja.report_pdf')}}" class="d-inline" method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$d}}" name="d">
                                        <input type="hidden" value="{{$a}}" name="a">
                                        <input type="hidden" value="{{$td}}" name="td">
                                        <input type="hidden" value="{{$establishment}}" name="establishment">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                        {{-- <label class="pull-right">Se encontraron {{$reports->count()}} registros.</label> --}}
                                    </form>
                                <form action="{{route('tenant.report.caja.report_excel')}}" class="d-inline" method="POST">
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

                            @endphp
                            <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                                <thead class="">
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
                                    @foreach($reports as $key => $value)
                                    <tr>
                                    <td>{{$loop->iteration}}</td>
                                        
                                        <td>{{$value->series}}-{{$value->number}}</td>
                                        <td>{{$value->date_of_issue->format('Y-m-d')}}</td>
                                        <td>{{$value->person->name}}</td>
                                        <td>{{$value->person->number}}</td>
                                       
                                        <td>{{$value->currency_type_id}}</td>
                                        <td>{{$value->total_taxed}}</td>
                                        <td>{{$value->total_igv}}</td>
                                        <td>{{$value->total}}</td>

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
                                         <td  align="center">{{$efectivo}}</td>
                                         <td  align="center">{{$td}}</td>
                                         <td  align="center">{{$tc}}</td>
                                         <td  align="center">{{$trans}}</td>
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

                                    


                                    @endforeach

                                    @foreach($sales as $key => $value)
                                    <tr>
                                    <td>{{$loop->iteration}}</td>
                                        
                                        <td>{{$value->identifier}}</td>
                                        <td>{{$value->date_of_issue->format('Y-m-d')}}</td>
                                        <td>{{$value->person->name}}</td>
                                        <td>{{$value->person->number}}</td>
                                       
                                        <td>{{$value->currency_type_id}}</td>
                                        <td>{{$value->total_taxed}}</td>
                                        <td>{{$value->total_igv}}</td>
                                        <td>{{$value->total}}</td>
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
                                         <td  align="center">{{$efectivo}}</td>
                                         <td  align="center">{{$td}}</td>
                                         <td  align="center">{{$tc}}</td>
                                         <td  align="center">{{$trans}}</td>
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
                                        <td colspan="5"></td>
                                        <td >Totales PEN</td>
                                        <td>{{$acum_total_taxed}}</td>
                                        <td>{{$acum_total_igv}}</td>
                                        <td>{{$acum_total}}</td>
                                        <td  align="center">{{$acum_efectivo}}</td>
                                        <td  align="center">{{$acum_td}}</td>
                                        <td  align="center">{{$acum_tc}}</td>
                                        <td  align="center">{{$acum_trans}}</td>
                                        
                                    </tr>
                                   
                                    <tr>
                                        <td colspan="5"></td>
                                        <td >Totales USD</td>
                                        <td>{{$acum_total_taxed_usd}}</td>
                                        <td>{{$acum_total_igv_usd}}</td>
                                        <td >{{$acum_total_usd}}</td>                                        
                                        <td  align="center">{{$acum_efectivo_usd}}</td>
                                        <td  align="center">{{$acum_td_usd}}</td>
                                        <td  align="center">{{$acum_tc_usd}}</td>
                                        <td  align="center">{{$acum_trans_usd}}</td>
                                       
                                    </tr>

                                    <tr>
                                        <td colspan="5"></td>
                                        <td >Total Egreso de Caja</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{$acum_total_egreso}}</td>
                                        <td  align="center"></td>
                                        <td  align="center"></td>
                                        <td  align="center"></td>
                                        <td  align="center"></td>
                                        
                                    </tr>

                                    <tr>
                                        <td colspan="5"></td>
                                        <td >Total</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{$total_real}}</td>
                                        <td  align="center"></td>
                                        <td  align="center"></td>
                                        <td  align="center"></td>
                                        <td  align="center"></td>
                                        
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
