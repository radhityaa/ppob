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
                @can('create recharge/title')
                    <button type="button" class="btn btn-primary mb-3" id="createRecharge">
                        <i class="ti ti-plus"></i>
                        Buat Recharge Item
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Label</th>
                        <th>Route</th>
                        <th>Image</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th width="100px"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <!-- Modal -->
        <div class="modal fade" id="modalRecharge" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRechargeItem">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="" id="form-recharge-item">
                            <div class="col mb-3">
                                <label for="recharge_title_id" class="form-label">Recharge Title</label>
                                <select name="recharge_title_id" id="recharge_title_id" class="form-control">
                                    @foreach (getRechargeTitles() as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <input type="text" id="label" name="label" class="form-control"
                                        placeholder="Kuota" required />
                                </div>
                                <div class="col mb-3">
                                    <label for="route" class="form-label">Route</label>
                                    <input type="text" id="route" name="route" class="form-control"
                                        placeholder="Route Name" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image" id="image" class="form-control" required>
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

        var table = $('.dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('item.index') }}",
            columnDefs: [{
                "targets": "_all",
                "className": "text-start"
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'recharge_title.title',
                    name: 'recharge_title.title'
                },
                {
                    data: 'label',
                    name: 'label'
                },
                {
                    data: 'route',
                    name: 'route'
                },
                {
                    data: 'src',
                    name: 'src'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        function refresh() {
            table.ajax.reload(null, false)
        }

        // Edit
        $('body').on('click', '#edit-recharge', function() {
            $('#modalRechargeItem').text('Edit Recharge')
            $('.modal-body form').append('<input type="hidden" name="_method" value="PUT">');

            var selectTitle = $('select#recharge_title_id')
            let rechargeId = $(this).data('id');
            let editUrl = "{!! route('item.show', ':id') !!}"
            editUrl = editUrl.replace(':id', rechargeId);
            url = "{!! route('item.update', ':id') !!}"
            url = url.replace(':id', rechargeId);
            method = "POST"

            $.ajax({
                url: editUrl,
                method: "GET",
                dataType: "json",
                success: function(res) {
                    $('#modalRecharge').modal('show')
                    $('#label').val(res.label)
                    $('#route').val(res.route)
                    $('#image').prop('required', false)
                    var gloData = res.recharge_title_id

                    selectTitle.empty()
                    $.get("{!! route('get.recharge-list') !!}", function(res) {
                        $.each(res.data, function(i, val) {
                            var selected = gloData === val.id ? 'selected' : ''
                            var option = '<option value="' + val.id + '"' + selected +
                                '>' + val.title + '</option>'
                            selectTitle.append(option)
                        })
                    })
                },
                error: function(err) {
                    console.log(err)
                }
            })

        })

        // delete
        $('body').on('click', '#delete-recharge', function() {
            var rechargeId = $(this).data('id');
            let deleteUrl = "{!! route('item.destroy', ':id') !!}"
            deleteUrl = deleteUrl.replace(':id', rechargeId);

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

        $('#createRecharge').on('click', function() {
            $('#modalRecharge').modal('show')
            $('#modalRechargeItem').text('Tambah Recharge Item')
            url = "{!! route('item.store') !!}"
            method = "POST"
        })

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-save').removeClass('d-none')

            $('#modalRecharge').on('hidden.bs.modal', function() {
                $('.btn-loading').addClass('d-none')
                $('.btn-save').removeClass('d-none')
                $('.modal-body form')[0].reset();
                $('input[name="_method"]').remove();
            })

            $('#form-recharge-item').on('submit', function(e) {
                e.preventDefault()
                $('.btn-loading').removeClass('d-none')
                $('.btn-save').addClass('d-none')

                var formData = new FormData($(this)[0])
                formData.append('image', $('#image')[0].files);

                $.ajax({
                    url: getUrl(),
                    method: getMethod(),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(res) {
                        console.log(res)
                        $('#modalRecharge').modal('hide')
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
                    }
                })
            })
        })
    </script>
@endpush
