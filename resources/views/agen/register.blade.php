@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="col-md-12">
        <div class="d-md-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">{{ $title ?? '' }}</h4>
            <button class="btn btn-primary" onclick="register()">Daftar</button>
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
                        <th>Nama Toko</th>
                        <th>Nomor HP</th>
                        <th>Email</th>
                        <th>Bergabung Sejak</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <!-- Modal -->
        <div class="modal fade" id="modalRegister" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRegisterTitle">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="" id="form-register">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Nama Lengkap" required />
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" name="username" class="form-control"
                                        placeholder="Username" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label for="shop_name" class="form-label">Nama Toko</label>
                                    <input type="shop_name" id="shop_name" name="shop_name" class="form-control" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="phone" class="form-label">No. HP</label>
                                    <input type="number" id="phone" name="phone" class="form-control"
                                        placeholder="Nomor HP" required />
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="Alamat Email" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea name="address" id="address" class="form-control" required></textarea>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary btn-register">Daftarkan</button>
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
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        var table = $('.dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('agen.index') }}",
            columnDefs: [{
                // For Responsive
                className: 'control',
                orderable: false,
                searchable: false,
                responsivePriority: 3,
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
                    data: 'shop_name',
                    name: 'shop_name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }
            ],
            responsive: true,
        })

        function refresh() {
            table.ajax.reload(null, false)
        }

        function register() {
            $('#modalRegister').modal('show')
            $('#modalRegisterTitle').text('Daftar Agen')
        }

        $(document).ready(function() {
            let btnRegister = $('.btn-register')
            let btnLoading = $('.btn-loading')

            $('#modalRegister').on('show.bs.modal', function() {
                btnRegister.removeClass('d-none')
                btnLoading.addClass('d-none')
                $('.modal-body form')[0].reset();
            })

            $('#form-register').on('submit', function(e) {
                e.preventDefault()
                btnRegister.addClass('d-none')
                btnLoading.removeClass('d-none')

                var formData = new FormData()
                formData.append('name', $('#name').val())
                formData.append('username', $('#username').val())
                formData.append('email', $('#email').val())
                formData.append('phone', $('#phone').val())
                formData.append('password', $('#password').val())
                formData.append('shop_name', $('#shop_name').val())
                formData.append('address', $('#address').val())

                $.ajax({
                    url: "{{ route('agen.store') }}",
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            btnRegister.removeClass('d-none')
                            btnLoading.addClass('d-none')
                            $('#modalRegister').modal('hide')
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            refresh()
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        btnRegister.removeClass('d-none')
                        btnLoading.addClass('d-none')
                    },
                    error: function(err) {
                        btnRegister.removeClass('d-none')
                        btnLoading.addClass('d-none')

                        if (err.responseJSON && err.responseJSON.fields) {
                            var errors = err.responseJSON.fields
                            $.each(errors, function(index, value) {
                                Swal.fire({
                                    icon: 'error',
                                    title: value[0],
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            })
                        } else if (err.responseJSON && err.responseJSON.message) {
                            Swal.fire({
                                icon: 'error',
                                title: err.responseJSON.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'A server error occurred.',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        refresh()
                    },
                    complete: function() {
                        btnRegister.removeClass('d-none')
                        btnLoading.addClass('d-none')
                    }
                })
            })
        })
    </script>
@endpush
