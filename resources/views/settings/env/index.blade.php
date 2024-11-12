@extends('layouts.settings.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@section('content-tab')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('message') && session('type'))
        <div class="alert alert-{{ session('type') }} alert-dismissible" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @foreach ($envDetails as $key => $value)
        <div class="accordion mt-3" id="accordionExample{{ $key }}">
            <div class="card accordion-item">
                <h2 class="accordion-header" id="headingOne{{ $key }}">
                    <button type="button" class="accordion-button" data-bs-toggle="collapse"
                        data-bs-target="#accordionOne{{ $key }}" aria-expanded="false"
                        aria-controls="accordionOne{{ $key }}">
                        {{ $key }}
                    </button>
                </h2>

                <div id="accordionOne{{ $key }}" class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample{{ $key }}">
                    <div class="accordion-body">
                        <form action="" method="POST">
                            @csrf
                            @method('PUT')

                            @foreach ($value as $item)
                                <div class="mb-3">
                                    <label for="{{ $item['key'] }}" class="form-label">
                                        {{ $item['key'] }}
                                    </label>
                                    <input type="text" name="{{ $item['key'] }}" id="{{ $item['key'] }}"
                                        class="form-control" value="{{ $item['data']['value'] }}"
                                        placeholder="{{ $item['key'] }}">
                                </div>
                            @endforeach
                            <button class="btn btn-primary" style="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <script type="text/javascript">
        let url = ''
        let method = ''
        let filter;

        function getUrl() {
            return url
        }

        function getMethod() {
            return method
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        let imagePreview = function(event, id) {
            let output = document.getElementById(id);
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-save').removeClass('d-none')

            $('#form-update').on('submit', function(e) {
                e.preventDefault()
                $('.btn-loading').removeClass('d-none')
                $('.btn-save').addClass('d-none')

                var formData = new FormData($(this)[0])
                formData.append('image_hero_dashboard', $('#image_hero_dashboard')[0].files)
                formData.append('image_hero_dashboard_dark', $('#image_hero_dashboard_dark')[0].files)
                formData.append('image_hero_element', $('#image_hero_element')[0].files)
                formData.append('image_hero_element_dark', $('#image_hero_element_dark')[0].files)

                let editUrl = "{!! route('hero.update', ':id') !!}"


                $.ajax({
                    url: editUrl,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(res) {
                        $('.btn-loading').addClass('d-none')
                        $('.btn-save').removeClass('d-none')

                        if (res.success) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.message,
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            })
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: res.message,
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            })
                        }
                    },
                })
            })
        })
    </script>
@endpush
