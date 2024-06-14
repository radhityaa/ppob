@extends('layouts.administrator.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label for="payment_gateway" class="form-label">Payment Gateway</label>
                <select name="payment_gateway" id="payment_gateway" class="form-control"></select>
            </div>
            <div class="mb-3">
                <label for="provider" class="form-label">Provider Product</label>
                <select name="provider" id="provider" class="form-control"></select>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>
@endpush
