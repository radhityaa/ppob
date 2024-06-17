@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">
                        <p>Whatsapp Account</p>
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th>Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $device->phone }}</td>
                            <td> <span
                                    class="badge {{ $device->status == 'Connected' ? 'bg-success' : 'bg-danger' }}">{{ $device->status == 'Connected' ? 'Connected' : 'Disconnected' }}</span>
                            </td>
                            <td>
                                @if ($device->status == 'Disconnected')
                                    <a href="{{ route('whatsapp.scan', $device->phone) }}" class="btn btn-success btn-sm"><i
                                            class="ti ti-qrcode me-2"></i>Scan</a>
                                @else
                                    <a href="{{ route('whatsapp.scan', $device->phone) }}" class="btn btn-danger btn-sm"><i
                                            class="ti ti-logout me-2"></i>Logout</a>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
@endpush
