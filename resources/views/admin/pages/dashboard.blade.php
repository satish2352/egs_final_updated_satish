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

        .homeIcon1 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card1 {
            background: darksalmon;
            border-radius: 10px;
        }

        .homeIcon2 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card2 {
            background: #c9bcff;
            border-radius: 10px;
        }

        .homeIcon3 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card3 {
            background: #ec8ca3;
            border-radius: 10px;
        }

        .homeIcon4 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card4 {
            background: #d2ec8c;
            border-radius: 10px;
        }

        .homeIcon5 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card5 {
            background: #8cecbf;
            border-radius: 10px;
        }

        .homeIcon6 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card6 {
            background: #ec8cdb;
            border-radius: 10px;
        }

        .homeIcon7 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card7 {
            background: #8cecdb;
            border-radius: 10px;
        }

        .homeIcon8 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card8 {
            background: #8cec93;
            border-radius: 10px;
        }

        .homeIcon9 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card9 {
            background: #e0dfeb;
            border-radius: 10px;
        }

        .homeIcon10 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card10 {
            background: #85edc5;
            border-radius: 10px;
        }

        .homeIcon11 {
            font-size: 2.5rem;
            color: #ec671f;
        }

        .dashboard_Card11 {
            background: #add8f6;
            border-radius: 10px;
        }

        .media-body {
            text-align: right !important;
            font-size: 24px;
            font-family: "Anta", sans-serif;
            /* font-family: titillium -webkit-body; */
        }

        .media-body h3 {
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
                            <div class="container dash-height">
                                <div class="col-md-12">
                                    {{-- @if (session('sess_user_type') == 1 && session('sess_user_type') == 2) --}}
                                    {{-- @if ($sess_user_role == 1 || $sess_user_role == 2) --}}
                                    @if ($sess_user_role == '1' || $sess_user_role == '2')
                                        <div class="row">
                                            <div class="col-md-3">
                                                <a href="{{ route('list-users') }}">
                                                    <div class="card">
                                                        <div class="card-body"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">User Count</h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2
                                                                            class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $return_data['user_count'] }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-3">
                                                <a href="{{ route('list-approved-labours') }}">
                                                    <div class="card">
                                                        <div class="card-body"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">Today's Count</h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2
                                                                            class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $return_data['today_count'] }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <a href="{{ route('list-approved-labours') }}">
                                                        <div class="card-body"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">Current Year's Count
                                                                    </h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2
                                                                            class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $return_data['current_year_count'] }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <a href="{{ route('list-projects') }}">
                                                        <div class="card-body"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">Project Count</h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2
                                                                            class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $return_data['project_count'] }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <a href="{{ route('list-approved-labours') }}">
                                                        <div class="card-body"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">Today's Count</h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2
                                                                            class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $return_data['today_count'] }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <a href="{{ route('list-approved-labours') }}">
                                                        <div class="card-body"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">Current Year's Count
                                                                    </h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2
                                                                            class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $return_data['current_year_count'] }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <a href="{{ route('list-projects') }}">
                                                        <div class="card-body"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">Project Count</h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2
                                                                            class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $return_data['project_count'] }}</h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        @forelse($return_data['labourRequestCounts'] as $key => $count)
                                            <div class="col-xl-3 col-lg-6">
                                                @php
                                                    $route = '';
                                                    switch ($key) {
                                                        case 'Sent For Approval Labours':
                                                            $route = route('list-labours');
                                                            break;
                                                        case 'Approved Labours':
                                                            $route = route('list-approved-labours');
                                                            break;
                                                        case 'Not Approved Labours':
                                                            $route = route('list-disapproved-labours');
                                                            break;
                                                        case 'Resubmitted Labours':
                                                            $route = route('list-resubmitted-labours');
                                                            break;
                                                        default:
                                                            $route = '#'; // Default route if $key doesn't match any case
                                                    }
                                                @endphp

                                                <a href="{{ $route }}">
                                                    <div class="card">
                                                        <div class="card-body"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">
                                                                        {{ ucwords(str_replace('_', ' ', $key)) }}</h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2
                                                                            class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $count }}
                                                                            {{-- {{ $labourRequestCounts }} --}}
                                                                            {{-- {{ $return_data['labourRequestCounts'] }} --}}
                                                                        </h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>





                                            </div>
                                        @empty
                                            <h4>No Data Found For Dashboard</h4>
                                        @endforelse
                                    </div>
                                    <div class="row">
                                        @forelse($return_data['documentRequestCounts'] as $key => $count)
                                            <div class="col-xl-3 col-lg-6">
                                                @php
                                                    $route = '';
                                                    switch ($key) {
                                                        case 'Sent For Approval Documents':
                                                            $route = route('list-grampanchayt-doc-new');
                                                            break;
                                                        case 'Approved Documents':
                                                            $route = route('list-grampanchayt-doc-approved');
                                                            break;
                                                        case 'Not Approved Documents':
                                                            $route = route('list-grampanchayt-doc-not-approved');
                                                            break;
                                                        case 'Resubmitted Documents':
                                                            $route = route('list-grampanchayt-doc-resubmitted');
                                                            break;
                                                        default:
                                                            $route = '#'; // Default route if $key doesn't match any case
                                                    }
                                                @endphp

                                                <a href="{{ $route }}">
                                                    <div class="card">
                                                        <div class="card-body"
                                                            style="background-color:#{{ str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) . str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT) }}">
                                                            <div class="card-statistic-3 p-4">
                                                                <div class="mb-4">
                                                                    <h6 class="mb-0 dash_card_title">
                                                                        {{ ucwords(str_replace('_', ' ', $key)) }}</h6>
                                                                </div>
                                                                <div class="row align-items-center mb-2 d-flex">
                                                                    <div class="col-8">
                                                                        <h2
                                                                            class="d-flex align-items-center mb-0 dash_count">
                                                                            {{ $count }}

                                                                        </h2>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @empty
                                            <h4>No Data Found For Dashboard</h4>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 d-flex justify-content-center">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <canvas id="myPieChart" width="400" height="200"></canvas>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 d-flex justify-content-center">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <canvas id="myPieChart1" width="400" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            </div>
            {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

         
            <script>
                var ctx = document.getElementById('myPieChart').getContext('2d');
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($data['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($data['counts']) !!},
                            backgroundColor: [
                                'red',
                                'blue',
                                'yellow',
                                'green',
                                'purple',
                                'orange'
                            ]
                        }]
                    },
                    options: {
                        // Additional options can go here
                    }
                });
            </script> --}}




            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                var ctx = document.getElementById('myPieChart').getContext('2d');
            
                var counts = @json($return_data['labourRequestCounts']);
                var backgroundColors = [
                    '#ff5773',
                    '#88e0b0',
                    '#fed000',
                    '#cc33fa'
                ];
            
                var labels = Object.keys(counts);
                var data = Object.values(counts);
            
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                enabled: true
                            },
                            legend: {
                                position: 'right'
                            },
                            title: {
                            display: true,
                            text: 'Labour Pie Chart',
                            // Adding title font size
                            font: {
                                size: 20 // Adjust the size as needed
                            }
                        },
                            // Add the 3D effect
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            animation: {
                                animateRotate: true,
                                animateScale: true
                            },
                            layout: {
                                padding: {
                                    left: 50,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            },
                            indexAxis: 'y'
                        }
                    }
                });
            </script>
            {{-- <script>
                var ctx = document.getElementById('myPieChart').getContext('2d');

                var counts = @json($return_data['labourRequestCounts']);
                var backgroundColors = [
                    '#ff5773',
                    '#88e0b0',
                    '#fed000',
                    '#cc33fa'
                ];

                var labels = Object.keys(counts);
                var data = Object.values(counts);

                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        // Additional options can go here
                    }
                });
            </script> --}}
            {{-- <script>
                var ctx = document.getElementById('myPieChart1').getContext('2d');

                var counts = @json($return_data['documentRequestCounts']);
                var backgroundColors = [
                    '#ff5773',
                    '#88e0b0',
                    '#fed000',
                    '#cc33fa'

                ];

                var labels = Object.keys(counts);
                var data = Object.values(counts);

                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        // Additional options can go here
                    }
                });
            </script> --}}

            <script>
                var ctx = document.getElementById('myPieChart1').getContext('2d');
            
                var counts = @json($return_data['documentRequestCounts']);
                var backgroundColors = [
                    '#ff5773',
                    '#88e0b0',
                    '#fed000',
                    '#cc33fa'
                ];
            
                var labels = Object.keys(counts);
                var data = Object.values(counts);
            
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                enabled: true
                            },
                            legend: {
                                position: 'right'
                            },
                            title: {
                            display: true,
                            text: 'Gramsevak Docuement Pie Chart',
                            // Adding title font size
                            font: {
                                size: 20 // Adjust the size as needed
                            }
                        },
                          
                            // Add the 3D effect
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            animation: {
                                animateRotate: true,
                                animateScale: true
                            },
                            layout: {
                                padding: {
                                    left: 50,
                                    right: 0,
                                    top: 0,
                                    bottom: 0
                                }
                            },
                            indexAxis: 'y'
                        }
                    }
                });
            </script>
        @endsection
