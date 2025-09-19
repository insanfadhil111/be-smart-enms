@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid py-4">

    <div class="row">
        <div class="col-12">
            <div class="card">
                @include('pages.settings.nav')
                <div class="card-header my-0 py-0">
                    <h6>Dashboard Widget</h6>
                </div>
                <div class="card-body pt-3">
                    <div class="col-6 mx-auto">
                        @foreach ($items as $item)
                        <div class="d-flex justify-content-between bg-gradient-light my-2 p-2 border-radius-md">
                            <div class="text-dark fw-bold">{{ $item->device }}</div>
                            <div class="form-check form-switch">
                                <input class="form-check-input switch-mdp" type="checkbox"
                                    data-id="{{ $item->id }}"
                                    {{ $item->status == 1 ? 'checked' : '' }}>
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
    document.querySelectorAll('.switch-mdp').forEach(switchEl => {
        switchEl.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.checked ? 1 : 0;

            fetch("{{ route('settings.control.update') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id, status })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.message);
            })
            .catch(error => {
                alert('Gagal mengubah status widget.');
                this.checked = !this.checked;
            });
        });
    });
</script>
@endpush