@extends('admin.layout.master')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Home
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">DB Backup</li>
                    </ol>
                </nav>
            </div>
            <div class="row">

                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body" style="height: 500px !important;>
                            <div class="container">
                                <div class="col-md-12">
                                    <div class="row " >
                                        <div class="alert-success" role="alert">
                                            <h3>Database backup completed successfully !<h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
