@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])

<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-lg-12">

            {{-- Form Subdata --}}
            <div class="card mb-4">
                @include('pages.settings.nav')
                <div class="card-header">
                    <h6 class="mb-0 text-bolder">Plant Subdata Setting</h6>
                </div>
                <div class="card-body ms-4">
                    <form action="{{ route('subdatas.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Subdata Fields --}}
                        <h6 class="mt-0">Profit Ratio for Plant Setting :</h6>

                        <div class="form-group row">
                            <label for="hargaKwh" class="col-sm-3 col-form-label text-center">Electricity Price:</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.1" class="form-control" id="hargaKwh" name="hargaKwh"
                                    value="{{ old('hargaKwh', $subdata->hargaKwh) }}" required>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" disabled>
                                    <option>IDR/kWh</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="hargaPdam" class="col-sm-3 col-form-label text-center">Water Price:</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.1" class="form-control" id="hargaPdam" name="hargaPdam"
                                    value="{{ old('hargaPdam', $subdata->hargaPdam) }}" required>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" disabled>
                                    <option>IDR/m3</option>
                                </select>
                            </div>
                        </div>

                        <h6 class="mt-2">Coefficient of Environmental Contribution:</h6>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-center">1 kWh =</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.001" class="form-control" id="co2eq" name="co2eq"
                                    value="{{ old('co2eq', $subdata->co2eq) }}" required>
                            </div>
                            <div class="col-sm-2">
                                <span>kg CO₂ Reduction</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-center">1 kWh =</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.01" class="form-control" id="trees_eq" name="trees_eq"
                                    value="{{ old('trees_eq', $subdata->trees_eq) }}" required>
                            </div>
                            <div class="col-sm-2">
                                <span">Trees</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-center">1 kWh =</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.001" class="form-control" id="coal_eq" name="coal_eq"
                                    value="{{ old('coal_eq', $subdata->coal_eq) }}" required>
                            </div>
                            <div class="col-sm-2">
                                <span">kg Coal</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-center">1 kWh =</label>
                            <div class="col-sm-3">
                                <input type="number" step="0.001" class="form-control" id="kwhAirPerMeterKubik"
                                    name="kwhAirPerMeterKubik"
                                    value="{{ old('kwhAirPerMeterKubik', $subdata->kwhAirPerMeterKubik) }}" required>
                            </div>
                            <div class="col-sm-2">
                                <span>m3 Water</span>
                            </div>
                        </div>

                        <h6>String Configuration:</h6>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-center">Decimal Separator:</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="decimal_sep" name="decimal_sep"
                                    value="{{ old('decimal_sep', $subdata->decimal_sep) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label text-center">Thousand Separator:</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="thousand_sep" name="thousand_sep"
                                    value="{{ old('thousand_sep', $subdata->thousand_sep) }}" required>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <hr class="my-4">

                        {{-- Luas Bangunan --}}
                        <hr class="my-4">
                        <h6 class="mb-3">Luas Bangunan</h6>

                        <div class="form-group row">
                            <label for="luas_bangunan" class="col-sm-3 col-form-label text-center">Luas Bangunan (m²)</label>

                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="luas_bangunan" id="luas_bangunan"
                                    value="{{ old('luas_bangunan', $subdata->luas_bangunan) }}" step="0.01" min="1">
                            </div>
                        </div>

                        {{-- IKE Config Section --}}
                        <hr class="my-4">
                        <h6 class="mb-3">IKE Threshold Configuration</h6>

                        @foreach ($ikeConfigs as $index => $ike)
                            <div class="form-group row align-items-center">
                                <label class="col-sm-2 col-form-label text-center">{{ $ike->kategori }}</label>

                                <div class="col-sm-2">
                                    <input type="number" step="0.01" class="form-control"
                                        name="ike_configs[{{ $index }}][batas_bawah]"
                                        value="{{ old("ike_configs.$index.batas_bawah", $ike->batas_bawah) }}" required>
                                </div>

                                <div class="col-sm-1 d-flex justify-content-center align-items-center">
                                    <span>s.d</span>
                                </div>

                                <div class="col-sm-2">
                                    <input type="number" step="0.01" class="form-control"
                                        name="ike_configs[{{ $index }}][batas_atas]"
                                        value="{{ old("ike_configs.$index.batas_atas", $ike->batas_atas) }}" required>
                                </div>

                                <div class="col-sm-2">
                                    <input type="color" class="form-control form-control-color"
                                        name="ike_configs[{{ $index }}][warna]"
                                        value="{{ old("ike_configs.$index.warna", $ike->warna ?? '#000000') }}" title="Choose color">
                                </div>

                                {{-- Hidden kategori supaya tetap terkirim --}}
                                <input type="hidden" name="ike_configs[{{ $index }}][kategori]" value="{{ $ike->kategori }}">
                            </div>
                        @endforeach

                        <div class="form-group row mt-4 text-center">
                            <div class="col-sm-6">
                                <a href="{{ route('subdatas.reset') }}" class="btn btn-secondary">Reset</a>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
