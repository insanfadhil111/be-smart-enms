@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">{{ isset($subdata) ? 'Edit' : 'Create' }} Subdata</h3>
                </div>
                <div class="card-body">
                    <form action="{{ isset($subdata) ? route('subdatas.update', $subdata) : route('subdatas.store') }}"
                        method="POST">
                        @csrf
                        @if(isset($subdata))
                        @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="hargaKwh">Harga KWH</label>
                            <input type="number" class="form-control" id="hargaKwh" name="hargaKwh"
                                value="{{ old('hargaKwh', $subdata->hargaKwh ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="co2eq">CO2 EQ</label>
                            <input type="number" step="0.001" class="form-control" id="co2eq" name="co2eq"
                                value="{{ old('co2eq', $subdata->co2eq ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="hargaPdam">Harga PDAM</label>
                            <input type="number" class="form-control" id="hargaPdam" name="hargaPdam"
                                value="{{ old('hargaPdam', $subdata->hargaPdam ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="kwhAirPerMeterKubik">KWH Air Per Meter Kubik</label>
                            <input type="number" step="0.001" class="form-control" id="kwhAirPerMeterKubik"
                                name="kwhAirPerMeterKubik"
                                value="{{ old('kwhAirPerMeterKubik', $subdata->kwhAirPerMeterKubik ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="trees_eq">Trees EQ</label>
                            <input type="number" step="0.01" class="form-control" id="trees_eq" name="trees_eq"
                                value="{{ old('trees_eq', $subdata->trees_eq ?? '') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="coal_eq">Coal EQ</label>
                            <input type="number" step="0.01" class="form-control" id="coal_eq" name="coal_eq"
                                value="{{ old('coal_eq', $subdata->coal_eq ?? '') }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ isset($subdata) ? 'Update' : 'Create'
                            }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection