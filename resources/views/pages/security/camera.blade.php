@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Camera'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @include('pages.security.nav')
                    <div class="card-header my-0 py-0">
                        <h6>Camera Depan</h6>
                    </div>
                    <div class="card-body pt-0 mb-0 text-center">
                        <a href="http://admin:LabIoT123@203.6.149.118:89/ISAPI/Streaming/channels/102/httpPreview">
                            <img src="{{ asset('img\stock_cam.jpeg') }}" alt="cam" >
                        </a>
                        <div class="mt-3">
                            <a class="btn btn-info "href="http://admin:LabIoT123@203.6.149.118:89/ISAPI/Streaming/channels/102/httpPreview" target="_blank">Open CCTV</a>
                        </div> 
                    </div>
                    <div class="card-header my-0 py-0">
                        <h6>Camera Belakang</h6>
                    </div>
                    <div class="card-body pt-0 mb-0 text-center">
                        <a href="http://admin:LabIoT123@203.6.149.118:89/ISAPI/Streaming/channels/102/httpPreview">
                            <img src="{{ asset('img\stock_cam.jpeg') }}" alt="cam" >
                        </a>
                        <div class="mt-3">
                            <a class="btn btn-info " href="http://admin:LabIoT123@203.6.149.118:89/ISAPI/Streaming/channels/102/httpPreview" target="_blank">Open CCTV</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
