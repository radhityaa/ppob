@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="" method="" enctype="multipart/form-data" id="form-update">
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Hero Title"
                        value="{{ $data->title }}">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description"
                        placeholder="Hero Description" value="{{ $data->description }}">
                </div>
                <div class="mb-3">
                    <label for="button_text" class="form-label">Button Text</label>
                    <input type="text" class="form-control" id="button_text" name="button_text"
                        placeholder="Hero Button Text" value="{{ $data->button_text }}">
                </div>
                <div class="mb-3">
                    <label for="button_url" class="form-label">Button URL</label>
                    <input type="text" class="form-control" id="button_url" name="button_url"
                        placeholder="Hero Button URL" value="{{ $data->button_url }}">
                </div>
                <div class="mb-3">
                    <label for="small_text" class="form-label">Small Text</label>
                    <input type="text" class="form-control" id="small_text" name="small_text" placeholder="Small Text"
                        value="{{ $data->small_text }}">
                </div>
                <div class="row mb-5">
                    <div class="col-lg-6">
                        <label for="image_hero_dashboard" class="form-label">Image Hero Dashboard</label>
                        <div class="py-4">
                            <img id="imageHeroDashboardPreviewId"
                                src="{{ asset('assets/img/front-pages/landing-page/' . $data->image_hero_dashboard) }}"
                                width="300" height="300" class="img-fluid rounded" id="image_hero_dashboard">
                        </div>
                        <div>
                            <input onchange="imagePreview(event, 'imageHeroDashboardPreviewId')" type="file"
                                class="form-control" id="image_hero_dashboard" name="image_hero_dashboard">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label for="image_hero_dashboard_dark" class="form-label">Image Hero Dashboard Dark</label>
                        <div class="py-4">
                            <img id="imageHeroDashboardDarkPreviewId"
                                src="{{ asset('assets/img/front-pages/landing-page/' . $data->image_hero_dashboard_dark) }}"
                                width="300" height="300" class="img-fluid rounded">
                        </div>
                        <div>
                            <input onchange="imagePreview(event, 'imageHeroDashboardDarkPreviewId')" type="file"
                                class="form-control" id="image_hero_dashboard_dark" name="image_hero_dashboard_dark">
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-lg-6">
                        <label for="image_hero_element" class="form-label">Image Hero Element</label>
                        <div class="py-3">
                            <img id="imageHeroElementPreviewId"
                                src="{{ asset('assets/img/front-pages/landing-page/' . $data->image_hero_element) }}"
                                width="300" height="300" class="img-fluid rounded">
                        </div>
                        <div>
                            <input onchange="imagePreview(event, 'imageHeroElementPreviewId')" type="file"
                                class="form-control" id="image_hero_element" name="image_hero_element">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label for="image_hero_element_dark" class="form-label">Image Hero Element Dark</label>
                        <div class="py-3">
                            <img id="imageHeroElementDarkPreviewId"
                                src="{{ asset('assets/img/front-pages/landing-page/' . $data->image_hero_element_dark) }}"
                                width="300" height="300" class="img-fluid rounded">
                        </div>
                        <div>
                            <input onchange="imagePreview(event, 'imageHeroElementDarkPreviewId')" type="file"
                                class="form-control" id="image_hero_element_dark" name="image_hero_element_dark">
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-save">Update</button>
                    <x-button-loading />
                    <button type="reset" class="btn btn-warning">Reset</button>
                </div>
            </form>
        </div>
    </div>
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
                editUrl = editUrl.replace(':id', '{{ $data->id }}');

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
