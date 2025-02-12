@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid py-4">

    <div class="row">
        <div class="col-12">
            <div class="card">
                @include('pages.energy.nav')
                <div class="card-header my-0 py-0">
                    <h6>Devices Control</h6>
                </div>
                <div class="card-body pt-3">
                    <div class="col-6 mx-auto">
                        @foreach ($items as $item)
                        <div class="d-flex justify-content-between bg-gradient-light my-2 p-2 border-radius-md">
                            <div class="text-dark fw-bold">{{ $item->device }}</div>
                            <div class="form-check form-switch">
                                <input class="form-check-input switch-mdp" type="checkbox"
                                    data-url="{{ url('switch-mdp/'.$item->id) }}" {{ $item->status == 1 ? 'checked' :
                                '' }}>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Selects all elements with the class switch-panel
        document.querySelectorAll('.switch-mdp').forEach(function (checkbox) { // Iterates over each selected element.
            checkbox.addEventListener('change', function () {
                const url = this.getAttribute('data-url');
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        // handle response data if needed
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endpush