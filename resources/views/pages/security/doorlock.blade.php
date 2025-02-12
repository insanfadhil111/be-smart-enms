@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Energy'])
    <div class="container-fluid py-4">
       
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @include('pages.security.nav')
                    <div class="card-header my-0 py-0">
                        <h6>Doorlock Status</h6>
                    </div>
                    <div class="card-body pt-1 mb-2">
                        @php
                            $devices = ["Entrance", "Office" , "Server Room", "Pantry"];
                            $status = [1,1,0,0];
                            $i = 0;
                            foreach ($devices as $device) :
                        @endphp
                        <div class="d-flex justify-content-between bg-gradient-light my-2 p-2 border-radius-md">
                            <div class="text-dark fw-bold">{{ $device }}</div>
                            
                            @if ($status[$i] == 1)
                                <span class="badge badge-sm bg-gradient-success">Opened</span>
                            @else
                                <span class="badge badge-sm bg-gradient-secondary">Closed</span>
                            @endif
                        </div>
                        @php
                            $i++;
                            endforeach;
                        @endphp
                    </div>
                    <div class="card-header my-0 py-0">
                        <h6>History</h6>
                    </div>
                    <div class="card-body pt-1 pb-3 ms-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Door</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    @php
                                        $names = ["John Hyter", "Doe Parlo" , "Mario", "Alfa"];
                                        $roles = ["Admin", "Manager", "Guest", "Admin"];
                                        $doors = ["Entrance", "Office" , "Server Room", "Pantry"];
                                        $i = 0;
                                        foreach ($names as $name) :
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex ps-3 pe-7 py-1 flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $name }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $roles[$i] }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-sm font-weight-bold mb-0">{{ $doors[$i] }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <p class="text-sm font-weight-bold mb-0">22/03/2022, 11:30 PM</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                        endforeach;
                                    @endphp
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    
@endpush