@extends('layouts.administrator.app')

@section('content')
    <div class="card">
        <div class="card-body row g-3">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                    <div class="me-1">
                        <h4 class="mb-1">{{ $information->title }}</h4>
                        <p class="mb-1" style="font-size: 13px;">{{ $information->created_at->diffForHumans() }}</p>
                        <span class="badge bg-secondary rounded-pill"
                            style="font-size: 10px;">{{ $information->categoryInformation->name }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span
                            class="badge {{ $information->type === 'Informasi' ? 'bg-info' : ($information->type === 'Peringatan' ? 'bg-warning' : 'bg-danger') }}">{{ $information->type }}</span>
                    </div>
                </div>
                <div class="card academy-content border shadow-none">
                    <div class="card-body">
                        {!! $information->description !!}
                        <hr class="my-4" />
                        <h5>Dibuat Oleh</h5>
                        <div class="d-flex justify-content-start align-items-center user-name">
                            <div class="avatar-wrapper">
                                <div class="avatar me-2">
                                    <img src="{{ asset('assets/img/avatars/11.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-medium">{{ $information->user->name }}</span>
                                <small class="text-muted">{{ Auth::user()->getRoleNames()[0] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="d-flex justify-content-between">
                                <div><span style="font-size: 15px;">Informasi {{ $information->categoryInformation->name }}
                                        Lainnya</span></div>
                                <div>
                                    <a href="{{ route('information.all') }}"
                                        style="font-size: 15px; text-decoration: underline">Lihat Semua</a>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="boardInformation" class="mt-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/js/purify.min.js') }}"></script>

    <script>
        function getInformation() {
            let slug = "{{ $information->categoryInformation->slug }}"

            let url = "{!! route('information.listByCategory', ':slug') !!}"
            url = url.replace(':slug', slug)

            $.get(url, function(res) {
                $.each(res, function(key, val) {
                    let urlView = "{!! route('information.show', ':slug') !!}"
                    urlView = urlView.replace(':slug', val.slug)

                    $('#boardInformation').append(`
                        <div>
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="${urlView}" class="fs-5">${val.title}</a>
                                <span class="badge text-uppercase ${val.type === 'Informasi' ? 'bg-info' : (val.type === 'Peringatan' ? 'bg-warning' : 'bg-danger')}">${val.type}</span>
                            </div>
                                <span style="font-size: 10px; m-0">By ${val.user.name}</span>

                            <div>
                                <span style="font-size: 11px;">${val.created_at}</span>
                            </div>
                                <span class="badge bg-secondary rounded-pill" style="font-size: 10px;">${val.category.name}</span>

                            <p class="truncate-2-lines">${DOMPurify.sanitize(val.description)}</p>
                        </div>
                        <hr>
                    `)
                })
            })
        }

        $(document).ready(function() {
            getInformation()
        })
    </script>
@endpush
