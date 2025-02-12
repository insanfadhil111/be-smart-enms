@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid py-4">

    <div class="row">
        <div class="col-12">
            <div class="card">
                @include('pages.envi.nav')
                <div class="card-header my-0 py-0">
                    <h6>Devices Control</h6>
                </div>
                <div class="card-body pt-3">
                    @php
                    $i = 0;
                    foreach ($devices as $device) :
                    @endphp
                    <div class="d-flex justify-content-between bg-gradient-light my-2 p-2 border-radius-md">
                        <div class="text-dark fw-bold">{{ $device }}</div>
                        {{-- <span class="badge badge-sm bg-gradient-success">Online</span> --}}
                        @if ($status[$i] == 1)
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="mainLamp" type="checkbox" id="mainLamp" checked="">
                            {{-- <a
                                href="{{ url('control-change-status-panel-master/'.$energy_panel_masters->id) }}"></a>
                            --}}
                            <a href="{{ url('control-change-status-panel-master/') }}"></a>
                        </div>
                        @else
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="mainLamp" type="checkbox" id="mainLamp">
                            <a href="{{ url('control-change-status-panel-master') }}"></a>
                        </div>
                        @endif
                    </div>
                    @php
                    $i++;
                    endforeach;
                    @endphp
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')

@endpush