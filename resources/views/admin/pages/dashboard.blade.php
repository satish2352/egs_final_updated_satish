@extends('admin.layout.master')
@section('content')
    <style>
        @import url("https://fonts.google.com/specimen/Titillium+Web");
        .card {
            background-color: #fff;
            border-radius: 10px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
        }

        .l-bg-cherry {
            background: #d24d4d73 !important;
            color: #fff;
        }

        .l-bg-blue-dark {
            background: #a2d9c6 !important;
            color: #fff;
        }

        .l-bg-green-dark {
            background: #9b9b9b8f !important;
            color: #fff;
        }

        .l-bg-orange-dark {
            background: #e2bfda !important;
            color: #fff;
        }

        .card .card-statistic-3 .card-icon-large .fas,
        .card .card-statistic-3 .card-icon-large .far,
        .card .card-statistic-3 .card-icon-large .fab,
        .card .card-statistic-3 .card-icon-large .fal {
            font-size: 110px;
        }

        .card .card-statistic-3 .card-icon {
            text-align: center;
            line-height: 50px;
            margin-left: 15px;
            color: #000;
            position: absolute;
            right: -5px;
            top: 20px;
            opacity: 0.1;
        }

        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }

        .l-bg-green {
            background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
            color: #fff;
        }

        .l-bg-orange {
            background: linear-gradient(to right, #f9900e, #ffba56) !important;
            color: #fff;
        }

        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }
        .homeIcon1{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card1{
            background: darksalmon;
            border-radius: 10px;
        }
        .homeIcon2{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card2{
            background: #c9bcff;
            border-radius: 10px;
        }
        .homeIcon3{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card3{
            background: #ec8ca3;
            border-radius: 10px;
        }
        .homeIcon4{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card4{
            background: #d2ec8c;
            border-radius: 10px;
        }
        .homeIcon5{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card5{
            background: #8cecbf;
            border-radius: 10px;
        }
        .homeIcon6{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card6{
            background: #ec8cdb;
            border-radius: 10px;
        }
        .homeIcon7{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card7{
            background: #8cecdb;
            border-radius: 10px;
        }
        .homeIcon8{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card8{
            background: #8cec93;
            border-radius: 10px;
        }
        .homeIcon9{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card9{
            background: #e0dfeb;
            border-radius: 10px;
        }
        .homeIcon10{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card10{
            background: #85edc5;
            border-radius: 10px;
        }
        .homeIcon11{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card11{
            background: #add8f6;
            border-radius: 10px;
        }
        .media-body{
            text-align: right !important;
            font-size: 24px;
            font-family: "Anta", sans-serif;
            /* font-family: titillium -webkit-body; */
        }
        .media-body h3{
            text-align: right !important;
            font-size: 24px;
            font-family: "Anta", sans-serif;
            /* font-family: titillium -webkit-body; */
        }
    </style>
<?php $data_for_url = session('data_for_url'); ?>
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Dashboard
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
              @if (isset($status) && $return_data['status'] == 'success')
                <div class="alert alert-success" role="alert">
                    {{ $return_data['msg'] }}
                </div>
                @endif
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="col-md-12">
                                    <div class="row ">

                                        @forelse($return_data as $key => $dashboard)
                                            @if (in_array($dashboard['url'], $data_for_url))
                                                <div class="col-xl-3 col-lg-6">
                                                    <a href="{{ url($dashboard['url']) }}">
                                                        <div class="card"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="card-icon card-icon-large"></div>
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">
                                                                        {{ mb_substr($dashboard['permission_name'], 0, 18)}}</h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2 class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $dashboard['count'] }}
                                                                        </h2>
                                                                    </div>
                                                                    {{-- <div class="col-4 text-right">
                                                                    <span>12.5% <i class="fa fa-arrow-up"></i></span>
                                                                </div> --}}
                                                                </div>
                                                               
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endif
                                        @empty
                                            <h4>No Data Found For Dashboard</h4>
                                        @endforelse


                                        
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
           
        @endsection
