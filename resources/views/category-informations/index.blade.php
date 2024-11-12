@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
                <button type="button" class="btn btn-primary mb-3" id="createCategory">
                    Kategori Baru
                </button>
            </div>
        </div>
    </div>

    <!-- List Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="dataTable border-top table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Dibuat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="mt-3">
        <div class="modal fade" id="modalCreateCategory" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateCategoryTitle">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="" id="form">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="name" class="form-label">Nama Kategori</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Ex: Deposit" />
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                        <x-button-loading />
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <script>
        let url = ''
        let method = ''

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

        var table = $('.dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('information.category.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
        });

        function refresh() {
            table.ajax.reload(null, false)
        }

        $('#createCategory').on('click', function() {
            $('#modalCreateCategory').modal('show')
            $('#modalCreateCategoryTitle').text('Buat Kategori Baru')
            url = "{!! route('information.category.store') !!}"
            method = "POST"
        })

        // delete Category
        $('body').on('click', '#delete-category', function() {
            var slug = $(this).data('slug');
            let deleteUrl = "{!! route('information.category.destroy', ':slug') !!}"
            deleteUrl = deleteUrl.replace(':slug', slug);

            Swal.fire({
                title: 'Are you sure?',
                text: "Deleted data cannot be restored!",
                icon: 'warning',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#82868',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteUrl,
                        method: "DELETE",
                        success: function(res) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.message,
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            })
                            refresh()
                        },
                        error: function(response) {
                            var errorMessage = response.responseJSON
                                .message;
                            Swal.fire({
                                title: 'Error!',
                                text: errorMessage,
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            })
                        }
                    });
                }
            });
        });

        // edit category
        $('body').on('click', '#edit-category', function() {
            $('#modalCreateCategoryTitle').text('Edit Kategori')

            let slug = $(this).data('slug')
            let editUrl = "{!! route('information.category.edit', ':slug') !!}"
            editUrl = editUrl.replace(':slug', slug)
            url = "{!! route('information.category.update', ':slug') !!}"
            url = url.replace(':slug', slug)
            method = "PUT"

            $.ajax({
                url: editUrl,
                method: "GET",
                success: function(res) {
                    $('#modalCreateCategory').modal('show')

                    $('#name').val(res.name)
                },
                error: function(response) {
                    var errorMessage = response.responseJSON
                        .message;
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-primary waves-effect waves-light'
                        },
                        buttonsStyling: false
                    })
                }
            })
        })

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-save').removeClass('d-none')

            $('#modalCreateCategory').on('hidden.bs.modal', function() {
                $('.btn-loading').addClass('d-none')
                $('.btn-save').removeClass('d-none')
                $('.modal-body form')[0].reset();
                $('input[name="_method"]').remove();
            })

            $('#form').on('submit', function(e) {
                e.preventDefault()
                $('.btn-loading').removeClass('d-none')
                $('.btn-save').addClass('d-none')

                let name = $('#name').val()

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        name
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#modalCreateCategory').modal('hide')
                        refresh()

                        $('.btn-loading').addClass('d-none')
                        $('.btn-save').removeClass('d-none')

                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect waves-light'
                            },
                            buttonsStyling: false
                        })
                    },
                    error: function(err) {
                        $('#modalCreateCategory').modal('hide')
                        $('.btn-loading').addClass('d-none')
                        $('.btn-save').removeClass('d-none')

                        Swal.fire({
                            title: 'Error!',
                            text: err.responseJSON.message,
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect waves-light'
                            },
                            buttonsStyling: false
                        });
                    },
                    complete: function() {
                        $('.btn-loading').addClass('d-none')
                        $('.btn-save').removeClass('d-none')
                    }
                })
            })
        })
    </script>
@endpush
