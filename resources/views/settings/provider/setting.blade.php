@extends('layouts.settings.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
@endpush

@section('content-tab')
    <div class="row">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="d-flex justify-content-end py-2">
            <button class="btn btn-primary btn-sm" onclick="refresh()">Refresh</button>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dataTable border-top table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mode</th>
                        <th>Type</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <!-- Modal -->
        <div class="modal fade modal-lg" id="modalDetail" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailTitle">Detail Provider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="form" method="POST">
                            @method('PUT')

                            <input type="hidden" name="slug" id="slug" class="form-control" disabled>
                            <div class="mb-3">
                                <label for="name" class="form-label">name</label>
                                <input type="text" name="name" id="name" class="form-control" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="mode" class="form-label">Mode</label>
                                <select name="mode" id="mode" class="form-control">
                                    <option value="dev">Developer</option>
                                    <option value="prod">Production</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <input type="text" name="type" id="type" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="api_key" class="form-label">API Key</label>
                                <input type="text" name="api_key" id="api_key" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="private_key" class="form-label">Private Key</label>
                                <input type="text" name="private_key" id="private_key" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" name="code" id="code" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="webhook_url" class="form-label">Webhook Url</label>
                                <input type="text" name="webhook_url" id="webhook_url" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="webhook_id" class="form-label">Webhook Id</label>
                                <input type="text" name="webhook_id" id="webhook_id" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="webhook_secret" class="form-label">Webhook Secret</label>
                                <input type="text" name="webhook_secret" id="webhook_secret" class="form-control">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-save">
                            Save
                        </button>
                        <x-button-loading />
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        var table = $('.dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.settings.provider.setting') }}",
            columnDefs: [{
                // For Responsive
                className: 'control',
                orderable: false,
                searchable: false,
                responsivePriority: 2,
                targets: 0,
                render: function(data, type, full, meta) {
                    return '';
                }
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'mode',
                    name: 'mode'
                },
                {
                    data: 'type',
                    name: 'type'
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
            responsive: true,
        });

        function refresh() {
            table.ajax.reload(null, false)
        }

        $('body').on('click', '#edit', function() {
            var slug = $(this).data('slug');
            var selectMode = $('select#mode')

            let url = "{{ route('admin.settings.provider.edit', ':slug') }}"
            url = url.replace(':slug', slug)

            $('#name').val('Loading...')
            $('#mode').val('Loading...')
            $('#type').val('Loading...')
            $('#api_key').val('Loading...')
            $('#private_key').val('Loading...')
            $('#code').val('Loading...')
            $('#username').val('Loading...')
            $('#webhook_url').val('Loading...')
            $('#webhook_id').val('Loading...')
            $('#webhook_secret').val('Loading...')

            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    $('#modalDetail').modal('show')

                    $('#slug').val(res.slug)
                    $('#name').val(res.name)
                    $('#type').val(res.type)
                    $('#api_key').val(res.api_key)
                    $('#private_key').val(res.private_key)
                    $('#code').val(res.code)
                    $('#username').val(res.username)
                    $('#webhook_url').val(res.webhook_url)
                    $('#webhook_id').val(res.webhook_id)
                    $('#webhook_secret').val(res.webhook_secret)

                    selectMode.empty();
                    selectMode.append('<option value="" disabled>Pilih Mode</option>');
                    selectMode.append('<option value="dev">Developer</option>');
                    selectMode.append('<option value="prod">Production</option>');
                    selectMode.val(res.mode);
                },
                error: function(err) {
                    $('#slug').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#name').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#mode').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#type').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#api_key').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#private_key').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#code').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#username').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#webhook_url').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#webhook_id').val('Terjadi Kesalahan, Ulangi Kembali')
                    $('#webhook_secret').val('Terjadi Kesalahan, Ulangi Kembali')
                }
            })
        })

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-save').removeClass('d-none')

            $('#modalDetail').on('hidden.bs.modal', function() {
                $('.btn-loading').addClass('d-none')
                $('.btn-save').removeClass('d-none')
                $('.modal-body form')[0].reset();
            })

            $('#name').val('')
            $('#mode').val('')
            $('#type').val('')
            $('#api_key').val('')
            $('#private_key').val('')
            $('#code').val('')
            $('#username').val('')
            $('#webhook_url').val('')
            $('#webhook_id').val('')
            $('#webhook_secret').val('')

            $('#form').submit(function(e) {
                e.preventDefault()

                $('.btn-loading').removeClass('d-none')
                $('.btn-save').addClass('d-none')

                let data = $(this).serialize()
                var slug = $('#slug').val()

                let url = "{{ route('admin.settings.provider.update', ':slug') }}"
                url = url.replace(':slug', slug)

                $.ajax({
                    url: url,
                    method: "POST",
                    data: data,
                    success: function(res) {
                        $('#modalDetail').modal('hide')
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

                        refresh()
                    },
                    error: function(err) {
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
