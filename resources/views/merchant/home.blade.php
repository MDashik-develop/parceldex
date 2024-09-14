@extends('layouts.merchant_layout.merchant_layout')
@push('style_css')
    <style>
        #newsbar {
            height: 40px;
            overflow: hidden;
            position: relative;
            background: #ccc;
            margin: 20px 0;
        }

        .info-box-content2 {
            flex: 1;
            padding: 5px 20px !important;
        }

        .info-box-content {
            flex: 1;
            padding: 5px 20px !important;
        }

        .info-box .info-box-number {
            display: block;
            font-weight: 700;
            font-size: 2rem;
        }

        .info-box .info-box-number2 {
            display: block;
            font-weight: 700;
            /* font-size: 2rem; */
        }

        .news-item {
            line-height: 38px;
            display: inline-block;
        }

        .clickableDiv {
            display: block;
            text-decoration: none;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            overflow: hidden;

        }

        .small-box {
            border-radius: 1.5rem;
        }

        .small-box-footer {
            border-radius: 0 0 1.5rem 1.5rem;
        }

        .col-lg-3 {
            padding: 10px 25px;
        }

        /* .d-flex:hover{
                                              background: red !important;
                                          } */


        .info-box-content:hover {
            background: #c3dff7;
        }
    </style>
@endpush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row" style="text-align: right !important;">
                <div class="col-sm-8">
                    {{-- <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <input type="text" name="parcel_invoice" id="parcel_invoice"
                                                value="{{ $parcel_invoice }}" class="form-control"
                                                placeholder="Enter Parcel Invoice Barcode Or Merchant Order ID Or Customer Phone Number"
                                                onkeypress="return add_parcel(event)"
                                                style="font-size: 20px; box-shadow: 0 0 5px rgb(62, 196, 118);
                                padding: 3px 0px 3px 3px;
                                margin: 5px 1px 3px 0px;
                                border: 2px solid rgb(62, 196, 118);">
                                        </div>
                                    </div>

                                    <div class="col-md-2" style="margin: auto;">
                                        <button type="button" class="btn btn-info btn-block"
                                            onclick="return parcelResult()">
                                            Search
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12" id="show_order_tracking_result">

                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('merchant.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div id="newsbar">
                        <marquee onMouseOver="stop()" onMouseOut="start()">
                            @if ($news)
                                <h3 class="news-item"><a href="#" class="view-news-modal" data-toggle="modal"
                                        data-target="#viewNewsModal"
                                        details="{{ $news->short_details }}">{{ $news->title }}</a></h3>
                            @else
                                <h3 class="news-item">Don't have any news</h3>
                            @endif
                        </marquee>
                    </div>
                </div>
            </div>


            <div class="row admin_client_info justify-content-center">
                <div class="col-lg-2 col-md-4" style="padding: 0px 5px !important;">
                    <button type="button" class="btn btn-primary btn-block" href="#"
                        style=" border-radius: 4px; background-color: transparent; border: 1px solid #ccc; color: inherit; font-weight: bold !important; padding: 7px 0 !important;">
                        Available Balance ( {{ number_format($total_pending_payment, 2, '.', '') }})
                    </button>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-4 text-center"
                    @if ($total_pending_payment <= 0) style="font-size: large; padding: 0px; border-radius: 1.5rem;" @endif
                    style=" font-size: x-large; padding: 0px;!important; border-radius: 1.5rem;">
                    <a href="https://play.google.com/store/apps/details?id=com.parceldexltd.merchant&pcampaignid=web_share"
                        target="_blank"><img height="40" src="{{ asset('image/playstore.jpg') }}"></a>

                    <a href="https://play.google.com/store/apps/details?id=com.parceldexltd.merchant&pcampaignid=web_share"
                        target="_blank"><img height="40" src="{{ asset('image/appstore.jpg') }}"></a>
                </div>


                <div class="col-lg-5 col-md-8 col-sm-8">
                    <div class="form-group">
                        <input type="text" name="parcel_invoice" id="parcel_invoice" value="{{ $parcel_invoice }}"
                            class="form-control" placeholder="Enter Parcel Invoice Or Order ID Or Customer Number"
                            onkeypress="return add_parcel(event)"
                            style="font-size: 20px; 
                                padding: 3px 0px 3px 3px;   
                                margin: 5px 1px 3px 0px;
                                border: 2px solid rgb(62, 196, 118);">
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-4" style="margin: auto 0; text-align: left;">
                    <button type="button" class="btn btn-info btn-block" onclick="return parcelResult()">
                        Search
                    </button>
                </div>
            </div>

            <div class="col-md-12" id="show_order_tracking_result">

            </div>

            <!-- Info boxes -->

            <div class="row" style="margin-top: 20px;">


                <div class="col-sm-6 col-md-3">
                    <div class="info-box">
                        {{-- <span class="info-box-icon bg-info elevation-1"><i class="fas fa-truck"></i></span> --}}

                        <div class="info-box-content" style="text-align: center">
                            <h5 class="info-box-text">Today's Pickedup</h5>
                            <span class="info-box-number">{{ $today_total_pickup }}</span>
                            <a href="{{ route('merchant.parcel.filterList', 'today_parcel') }}"
                                class="small-box-footer">More
                                info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box mb-3">
                        {{-- <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span> --}}

                        <div class="info-box-content">
                            <span class="info-box-number">{{ $counter_data['today_total_delivery_complete_parcel'] }}</span>
                            <h5 class="info-box-text">Today's Delivered</h5>
                            <a href="{{ route('merchant.parcel.filterList', 'today_total_delivery_parcel') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                        <!-- /.info-box-content -->
                    </div>

                    <!-- /.info-box -->
                </div>

                <!-- /.col -->


                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box mb-3">
                        {{-- <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span> --}}

                        <div class="info-box-content">
                            <span
                                class="info-box-number">{{ $counter_data['today_total_partial_delivery_complete_parcel'] }}</span>
                            <h5 class="info-box-text">Today's Partial Delivered</h5>
                            <a href="{{ route('merchant.parcel.filterList', 'today_total_partial_delivery_parcel') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                        <!-- /.info-box-content -->
                    </div>

                    <!-- /.info-box -->
                </div>

                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box mb-3">
                        {{-- <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-ban"></i></span> --}}

                        <div class="info-box-content">
                            <span class="info-box-number">{{ $today_cancel_parcel }}</span>
                            <h5 class="info-box-text">Today's Cancelled</h5>
                            <a href="{{ route('merchant.parcel.filterList', 'today_delivery_cancel') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box">
                        {{-- <span class="info-box-icon bg-info elevation-1"><i class="far fa-copy"></i></span> --}}

                        <div class="info-box-content">
                            <span class="info-box-number">
                                {{ $counter_data['total_parcel'] }}
                            </span>
                            <h5 class="info-box-text">Total Parcel</h5>
                            <a href="{{ route('merchant.parcel.list') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>

                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box mb-3">
                        {{-- <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-sync-alt"></i></span> --}}

                        <div class="info-box-content">
                            <span class="info-box-number"> {{ $total_pending_parcel }} 
                                @if ( $counter_data['total_parcel'] > 0)
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{
                                        
                                        number_format(($total_pending_parcel * 100) / $counter_data['total_parcel'], 2)
                                        }}%</small></sup>
                                @else
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{
                                        number_format(0, 2)
                                        }}%</small></sup>
                                @endif
                                            
                                        
                                </span>
                            <h5 class="info-box-text">Parcel On Process</h5>
                            <a href="{{ route('merchant.parcel.filterList', 'pending_parcel') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>


                <div class="col-sm-6 col-md-3">
                    <div class="info-box mb-3" style="text-align: center">
                        {{-- <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-hourglass-start"></i></span> --}}

                        <div class="info-box-content">
                            {{-- <span class="info-box-number"> {{ $total_pickup_pending }}</span> --}}
                            <span class="info-box-number"> {{ $today_total_pickup }}</span>
                            <h5 class="info-box-text">Pickup Request</h5>

                            <a href="{{ route('merchant.parcel.filterList', 'pickup_pending') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>


                <!-- /.col -->

                <!-- /.col -->
                {{-- </div> --}}
                <!-- /.row -->

                <!-- Info boxes -->
                {{-- <div class="row" style="margin-top: 20px;"> --}}




                <!-- /.col -->
                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box mb-3">
                        {{-- <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chart-pie"></i></span> --}}

                        <div class="info-box-content">
                            <span class="info-box-number">{{ $counter_data['total_delivery_complete_parcel'] }}
                                @if ($counter_data['total_parcel'] > 0)
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{ 
                                                                            
                                    number_format(($counter_data['total_delivery_complete_parcel'] * 100) / $counter_data['total_parcel'], 2)
                                    }}%</small></sup>
                                @else
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{ 
                                                                            
                                    number_format(0, 2)}}%</small></sup>
                                @endif
                               

                            </span>
                            <h5 class="info-box-text">Total Delivered</h5>
                            <a href="{{ route('merchant.parcel.filterList', 'delivered_parcel') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                    </div>
                </div>

                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box mb-3">
                        {{-- <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chart-pie"></i></span> --}}

                        <div class="info-box-content">
                            <span class="info-box-number">{{ $counter_data['total_partial_delivery_complete_parcel'] }}
                               @if ($counter_data['total_parcel'] > 0)
                               <sup><small
                                style="color: #007bff; font-size: 15px;">{{ 
                                                                        
                                
                                number_format(($counter_data['total_partial_delivery_complete_parcel'] * 100) /  $counter_data['total_parcel'], 2)
                                
                                }}%</small></sup></span>
                               @else
                               <sup><small
                                style="color: #007bff; font-size: 15px;">{{ 
                                                                        
                                
                                number_format(0, 2)
                                
                                }}%</small></sup></span> 
                               @endif
                                


                            <h5 class="info-box-text">Total Partial Delivered</h5>
                            <a href="{{ route('merchant.parcel.filterList', 'p_delivered_parcel') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                    </div>
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box mb-3">
                        {{-- <span class="info-box-icon bg-danger elevation-1"><i --}}
                        {{-- class="fa fa-exclamation-triangle"></i></span> --}}

                        <div class="info-box-content">

                            <span class="info-box-number"> {{ $total_cancel_parcel }}
                                @if ($counter_data['total_parcel'] > 0)
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{ number_format(( $total_cancel_parcel * 100) / $counter_data['total_parcel'], 2) }}%</small></sup>
                                @else
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{ number_format(0, 2) }}%</small></sup>
                                @endif
                                    
                                    
                                </span>

                            <h5 class="info-box-text">Total Cancelled</h5>
                            <a href="{{ route('merchant.parcel.filterList', 'cancelled_parcel') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->


                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box mb-3">
                        {{-- <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-sync-alt"></i></span> --}}

                        <div class="info-box-content">
                            <span class="info-box-number"> {{ $total_pending_return }} 
                                @if ($total_cancel_parcel > 0)
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{ number_format(($total_pending_return * 100) / $total_cancel_parcel, 2)
                                    
                                
                                     }}%</small></sup> 
                                @else
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{number_format(0, 2)
                                    
                                
                                     }}%</small></sup>
                                @endif
                                
                                         
                                        
                                        
                                        </span>
                            <h5 class="info-box-text">Return On Process</h5>
                            <a href="{{ route('merchant.parcel.filterList', 'return_process') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                <div class="col-sm-6 col-md-3" style="text-align: center">
                    <div class="info-box mb-3">
                        {{-- <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-sync-alt"></i></span> --}}

                        <div class="info-box-content">
                            <span class="info-box-number"> {{ $total_return }} 
                                @if ($total_cancel_parcel > 0)
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{ 
                                        
                                        number_format(($total_return * 100) / $total_cancel_parcel, 2)
                                        
                                         }}%</small></sup> 
                                @else
                                <sup><small
                                    style="color: #007bff; font-size: 15px;">{{ 
                                        
                                        number_format(0, 2)
                                        
                                         }}%</small></sup>
                                @endif
                               
                                             
                                            
                                            
                                </span>
                            <h5 class="info-box-text">Total Returned</h5>
                            <a href="{{ route('merchant.parcel.filterList', 'pending_parcel') }}"
                                class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>

                        </div>

                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">

                <div class="col-md-6">

                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent" style="background: #0069d9; color: #fff">

                            <h3 class="card-title">Latest Orders</h3>

                            <div class="card-tools">
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer Name</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($recent_order as $r_order)
                                            @php
                                                $parcelStatus = returnParcelStatusNameForMerchant(
                                                    $r_order->status,
                                                    $r_order->delivery_type,
                                                    $r_order->payment_type,
                                                );
                                                $status_name = $parcelStatus['status_name'];

                                                $class = $parcelStatus['class'];
                                            @endphp
                                            <tr>



                                                <td>{{ $r_order->parcel_invoice }}</td>
                                                <td>{{ $r_order->customer_name }}</td>
                                                <td>
                                                    <div class="sparkbar" data-color="#00a65a" data-height="20">
                                                        {{ $r_order->customer_contact_number }}</div>
                                                </td>
                                                <td><span
                                                        class="badge badge-{{ $class }}">{{ $status_name }}</span>
                                                </td>

                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix" style="
    padding: 18px;
">
                            <a href="{{ route('merchant.parcel.add') }}" class="btn btn-sm btn-info float-left">Place New
                                Order</a>
                            <a href="{{ route('merchant.parcel.list') }}"
                                class="btn btn-sm btn-secondary float-right">View All Orders</a>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                </div>
                <!-- /.card -->

                <!-- /.col -->
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header border-transparent" style="background: #0069d9; color: #fff">

                                <h3 class="card-title">Paymet Summary</h3>

                                <div class="card-tools">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box mb-3">
                                {{-- <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span> --}}

                                <div class="info-box-content2 info-box-content" style="text-align: center">
                                    <span class="info-box-number2" style="font-size: 36px;">
                                        {{ number_format($counter_data['total_pending_collect_amount'], 2) }} TK</span>
                                    <span class="info-box-text" style="font-size: 16px;">Ready for Payment</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <div class="info-box mb-3">
                                {{-- <span class="info-box-icon"><i class="fa fa-credit-card"></i></span> --}}

                                <div class="info-box-content2 info-box-content" style="text-align: center">
                                    <span class="info-box-number2"
                                        style="font-size: 36px;">{{ number_format($total_customer_collect_amount_due, 2) }}
                                        TK</span>
                                    <span class="info-box-text" style="font-size: 16px;">Awaiting payment</span>

                                    <!--<span class="info-box-number2">{{ number_format($total_customer_collect_amount_due, 2) }} TK </span>-->
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <div class="info-box mb-3">
                                {{-- <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span> --}}

                                <div class="info-box-content2 info-box-content" style="text-align: center">
                                    <span class="info-box-number2" style="font-size: 36px;">
                                        {{ number_format($total_to_be_collected, 2) }} TK</span>
                                    <span class="info-box-text" style="font-size: 16px;">To Be Collected</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-6">

                            <div class="info-box mb-3">
                                {{-- <span class="info-box-icon"><i class="fas fa-tag"></i></span> --}}

                                <div class="info-box-content2 info-box-content" style="text-align: center">
                                    <span class="info-box-number2"
                                        style="font-size: 36px;">{{ number_format($total_customer_collected_amount, 2) }}
                                        TK</span>
                                    <span class="info-box-text" style="font-size: 16px;">Total Collected</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <div class="info-box mb-3">
                                {{-- <span class="info-box-icon"><i class="fa fa-credit-card"></i></span> --}}

                                <div class="info-box-content2 info-box-content" style="text-align: center">
                                    <span class="info-box-number2"
                                        style="font-size: 36px;">{{ number_format($counter_data['total_collect_amount'], 2) }}
                                        TK
                                    </span>
                                    <span class="info-box-text" style="font-size: 16px;">Total Paid</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <div class="info-box mb-3">
                                {{-- <span class="info-box-icon"><i class="fas fa-tag"></i></span> --}}

                                <div class="info-box-content2 info-box-content" style="text-align: center">
                                    <span class="info-box-number2"
                                        style="font-size: 36px;">{{ number_format($total_service_charge, 2) }} TK</span>
                                    <span class="info-box-text" style="font-size: 16px;">Total Service Charge</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <div class="content">




                <!--    <div class="content" style="margin-top: 20px;">-->






                <!--        <div class="row admin_client_info" style="justify-content: center;" >-->





                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-info">-->
                <!--                    <div class="inner">-->
                <!--                        <h2 style="font-family: cursive;"> {{ $today_total_pickup }} </h3>-->
                <!--                        <p>Today Pickup </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-bag"></i>-->
                <!--<i class="fas fa-motorcycle fa-lg"></i>-->

                <!--                    </div>-->
                <!--                     <a href="{{ route('merchant.parcel.filterList', 'today_parcel') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->

                <!--                </div>-->
                <!--            </div>-->

                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-success">-->
                <!--                    <div class="inner">-->
                <!--                        <h2 style="font-family: cursive;"> {{ $counter_data['today_total_delivery_complete_parcel'] }} </h3>-->
                <!--                        <p>Today Delivered </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-stats-bars"></i>-->
                <!--                    </div>-->
                <!--                     <a href="{{ route('merchant.parcel.filterList', 'today_total_delivery_parcel') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->

                <!--                </div>-->
                <!--            </div>-->
                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-danger">-->
                <!--                    <div class="inner">-->
                <!--<h2 style="font-family: cursive;"> {{ $counter_data['total_cancel_parcel'] }} </h3>-->

                <!--                        <h2 style="font-family: cursive;"> {{ $counter_data['today_total_cancel_parcel'] }} </h3>-->
                <!--                        <p>Today Cancelled </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-person-add"></i>-->
                <!--                    </div>-->
                <!--                    <a href="{{ route('merchant.parcel.filterList', 'today_delivery_cancel') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->

                <!--                </div>-->
                <!--            </div>-->
                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-warning">-->
                <!--                    <div class="inner">-->
                <!--<h2 style="font-family: cursive;"> {{ $counter_data['total_waiting_delivery_parcel'] }} </h3>-->

                <!--                        <h2 style="font-family: cursive;"> {{ $counter_data['today_total_waiting_pickup_parcel'] }} </h3>-->
                <!--                        <p>Pickup Pending </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-pie-graph"></i>-->
                <!--                    </div>-->
                <!--                    <a href="{{ route('merchant.parcel.filterList', 'pickup_pending') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->

                <!--                </div>-->
                <!--            </div>-->



                <!--           <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-info">-->
                <!--                    <div class="inner">-->
                <!--                        <h2 style="font-family: cursive;"> {{ $counter_data['total_parcel'] }} </h3>-->
                <!--                        <p>Total Parcel </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-bag"></i>-->
                <!--                    </div>-->
                <!--                    <a href="{{ route('merchant.parcel.list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->

                <!--                </div>-->
                <!--            </div>-->

                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-success">-->
                <!--                    <div class="inner">-->
                <!--                        <h2 style="font-family: cursive;"> {{ $counter_data['total_delivery_complete_parcel'] }} </h3>-->
                <!--                        <p>Delivered </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-stats-bars"></i>-->
                <!--                    </div>-->
                <!--                    <a href="{{ route('merchant.parcel.filterList', 'delivered_parcel') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->

                <!--                </div>-->
                <!--            </div>-->
                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-danger">-->
                <!--                    <div class="inner">-->
                <!--<h2 style="font-family: cursive;"> {{ $counter_data['total_cancel_parcel'] }} </h3>-->

                <!--                        <h2 style="font-family: cursive;"> {{ $total_cancel_parcel }} </h3>-->
                <!--                        <p>Cancelled </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-person-add"></i>-->
                <!--                    </div>-->
                <!--                     <a href="{{ route('merchant.parcel.filterList', 'cancelled_parcel') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->

                <!--                </div>-->
                <!--            </div>-->
                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-warning">-->
                <!--                    <div class="inner">-->
                <!--<h2 style="font-family: cursive;"> {{ $counter_data['total_waiting_delivery_parcel'] }} </h3>-->

                <!--                        <h2 style="font-family: cursive;"> {{ $total_pending_parcel }} </h3>-->
                <!--                        <p>Delivery Pending </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-pie-graph"></i>-->
                <!--                    </div>-->
                <!--                    <a href="{{ route('merchant.parcel.filterList', 'pending_parcel') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->

                <!--                </div>-->
                <!--            </div>-->





                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-info" style="background-color: #9c27b0!important;" >-->
                <!--                    <div class="inner">-->
                <!--                        <h3> {{ number_format($total_customer_collected_amount, 2) }} TK </h3>-->
                <!--                        <p>Collected Amount </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-bag"></i>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->

                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-success"style="-->
                <!--background-color: #00BCD4 !important;">-->
                <!--                    <div class="inner">-->
                <!--                        <h3> {{ number_format($counter_data['total_collect_amount'], 2) }} TK </h3>-->
                <!--                        <p>Paid Amount </p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-stats-bars"></i>-->
                <!--                    </div>-->
                <!--                    <a href="{{ route('merchant.account.deliveryPaymentList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->

                <!--                </div>-->
                <!--            </div>-->

                <!--<div class="col-lg-3 col-6">-->
                <!--    <div class="small-box bg-danger">-->
                <!--        <div class="inner">-->
                <!--            <h3> {{ number_format($total_charge, 2) }} TK </h3>-->
                <!--            <p>Delivery Charge </p>-->
                <!--        </div>-->
                <!--        <div class="icon">-->
                <!--            <i class="ion ion-person-add"></i>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--            <div class="col-lg-3 col-6">-->
                <!--                <div class="small-box bg-warning">-->
                <!--                    <div class="inner">-->
                <!--                        <h3> {{ number_format($counter_data['total_pending_collect_amount'], 2) }} TK </h3>-->
                <!--                        <p>Payment In Processing</p>-->
                <!--                    </div>-->
                <!--                    <div class="icon">-->
                <!--                        <i class="ion ion-pie-graph"></i>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->

                <!--             </div>-->




            </div>



            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- AREA CHART -->
                            <div class="card card-primary" style="display: none;">
                                <div class="card-header">
                                    <h3 class="card-title">Area Chart</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <canvas id="areaChart"
                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- DONUT CHART -->
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Parcel Statistics</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="donutChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- PIE CHART -->
                            <div class="card card-danger" style="display: none;">
                                <div class="card-header">
                                    <h3 class="card-title">Pie Chart</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </div>
                        <!-- /.col (LEFT) -->
                        <div class="col-md-6">
                            <!-- LINE CHART -->
                            <div class="card card-info" style="display: none;">
                                <div class="card-header">
                                    <h3 class="card-title">Line Chart</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <canvas id="lineChart"
                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- BAR CHART -->
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Last 7 Days Parcel</h3>

                                    <!--<div class="card-tools">-->
                                    <!--  <button type="button" class="btn btn-tool" data-card-widget="collapse">-->
                                    <!--    <i class="fas fa-minus"></i>-->
                                    <!--  </button>-->
                                    <!--  <button type="button" class="btn btn-tool" data-card-widget="remove">-->
                                    <!--    <i class="fas fa-times"></i>-->
                                    <!--  </button>-->
                                    <!--</div>-->
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <canvas id="barChart"
                                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- STACKED BAR CHART -->
                            <!-- /.card -->

                        </div>
                        <!-- /.col (RIGHT) -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>



            <div class="modal fade" id="viewNewsModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 id="news_title" class="modal-title">View Notice Or News Details</h4>
                            <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="showResult">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
@endsection

<script id="fa" src="{{ asset('js/Chart.min.js') }}"></script>

@push('script_js')
    <script>
        $(function() {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */

            //--------------
            //- AREA CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

            var areaChartData = {
                labels: ['Today', 'Yesterday', '2 days ago', '3 days ago', '4 days ago', '5 days ago',
                    '6 days ago'
                ],
                datasets: [{
                        label: 'Deliverd',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: [
                            {{ $today_total_delivery }},
                            {{ $yesterdayDeliveryComplete }},
                            {{ $twoDeliveryComplete }},
                            {{ $threeDeliveryComplete }},
                            {{ $fourDeliveryComplete }},
                            {{ $fiveDeliveryComplete }},
                            {{ $sixDeliveryComplete }}
                        ]
                    },
                    {
                        label: 'Pickup',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: [{{ $today_total_pickupcomplete }},
                            {{ $yesterdayPickupcomplete }},
                            {{ $towDaysAgoPickupcomplete }},
                            {{ $threeDaysAgoPickupcomplete }},
                            {{ $fourDaysAgoPickupcomplete }},
                            {{ $fiveDaysAgoPickupcomplete }},
                            {{ $sixDaysAgoPickupcomplete }}
                        ]
                    },
                ]
            }

            var areaChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                        }
                    }]
                }
            }

            // This will get the first returned node in the jQuery collection.
            new Chart(areaChartCanvas, {
                type: 'line',
                data: areaChartData,
                options: areaChartOptions
            })

            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
            var lineChartOptions = $.extend(true, {}, areaChartOptions)
            var lineChartData = $.extend(true, {}, areaChartData)
            lineChartData.datasets[0].fill = false;
            lineChartData.datasets[1].fill = false;
            lineChartOptions.datasetFill = false

            var lineChart = new Chart(lineChartCanvas, {
                type: 'line',
                data: lineChartData,
                options: lineChartOptions
            })

            //-------------
            //- DONUT CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
            var donutData = {
                labels: [
                    'Parcel Booking',
                    'Delivered',
                    'Partially Delivered',
                    'Processing',
                    'Cancelled',
                    'Deleted',
                ],
                datasets: [{
                    data: [
                        {{ $counter_data['total_parcel'] }},
                        {{ $counter_data['total_delivery_complete_parcel'] }},
                        {{ $counter_data['total_partial_delivery_complete'] }},
                        {{ $total_pending_parcel }},
                        {{ $total_cancel_parcel }},
                        {{ $total_deleted_parcel }}
                    ],
                    backgroundColor: ['#3c8dbc', '#00a65a', '#f39c12', '#00c0ef', '#f56954', '#d2d6de'],
                }]
            }
            var donutOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            })

            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData = donutData;
            var pieOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
            })

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })

            //---------------------
            //- STACKED BAR CHART -
            //---------------------
            var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
            var stackedBarChartData = $.extend(true, {}, barChartData)

            var stackedBarChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }

            new Chart(stackedBarChartCanvas, {
                type: 'bar',
                data: stackedBarChartData,
                options: stackedBarChartOptions
            })
        })
    </script>
    <script>
        $(document).ready(function() {

            $(".view-news-modal").on("click", function() {

                var title = $(this).text();
                var details = $(this).attr('details');

                $("#news_title").html(title);
                $("#showResult").html(details);
            });
        })
        //        Echo.private("App.Models.Merchant.5")
        //            .notification((notify) => {
        //
        //            console.log(notify);
        //
        //        });

        function parcelResult() {
            var parcel_invoice = $("#parcel_invoice").val();
            var merchant_order_id = $("#merchant_order_id").val();

            // alert(parcel_invoice +' '+merchant_order_id); return false;
            $.ajax({
                cache: false,
                type: "POST",
                data: {
                    parcel_invoice: parcel_invoice,
                    merchant_order_id: merchant_order_id,
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ route('merchant.returnOrderTrackingResult') }}",
                error: function(xhr) {
                    alert("An error occurred: " + xhr.status + " " + xhr.statusText);
                },
                success: function(response) {
                    $("#show_order_tracking_result").html(response);

                    // $("#parcel_invoice").val('');
                    // $("#merchant_order_id").val('');
                    return false;
                }
            });
        }
    </script>
@endpush
