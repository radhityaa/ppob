@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">
                        <p>{{ $title }}</p>
                    </h5>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('message-template.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <input type="text" name="type" id="type" class="form-control" value="{{ $data->type }}"
                        disabled>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea name="message" id="message" placeholder="message" class="form-control">{{ $data->message }}</textarea>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('message-template.index') }}" class="btn btn-dark">Back</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('page-js')
@endpush
