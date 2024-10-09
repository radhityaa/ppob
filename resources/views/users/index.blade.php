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
                @can('create users')
                    <button type="button" class="btn btn-primary mb-3" id="createUser">
                        <i class="ti ti-plus"></i>
                        Buat User
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic dataTable table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Saldo</th>
                        <th>Status</th>
                        <th width="100px"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <!-- Modal -->
        <div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUserTitle">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="" id="form-user">
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
                                        placeholder="Username" />
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
                                <div class="col-lg-6 mb-3">
                                    <label for="saldo" class="form-label">Saldo</label>
                                    <input type="text" id="saldo" name="saldo" class="form-control"
                                        placeholder="Saldo" required />
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" name="status" id="status" required>
                                        <option value="aktif">Aktif</option>
                                        <option value="band">Banned</option>
                                        <option value="suspend">Suspend</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control"
                                        autocomplete="off" />
                                    <small class="form-text">Isi jika ingin diganti</small>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-control" name="role" id="role" required>
                                        @foreach (getAllRoles() as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
            ajax: "{{ route('users.index') }}",
            columnDefs: [{
                "targets": "_all",
                "className": "text-start"
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
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'saldo',
                    name: 'saldo'
                },
                {
                    data: 'status',
                    name: 'status'
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

        function numberFormatIdr(value) {
            var reverse = value.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formatted = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + formatted;
        }

        // Edit User
        $('body').on('click', '#edit-user', function() {
            $('#modalUserTitle').text('Edit User')
            $('#username').prop('disabled', true)

            var selectStatus = $('select#status')
            var selectRole = $('select#role')

            let userUsername = $(this).data('username');
            let editUrl = "{!! route('users.show', ':username') !!}"
            editUrl = editUrl.replace(':username', userUsername);
            url = "{!! route('users.update', ':username') !!}"
            url = url.replace(':username', userUsername);
            method = "PUT"

            $.ajax({
                url: editUrl,
                method: "GET",
                dataType: "json",
                success: function(res) {
                    $('#modalUser').modal('show')
                    var gloData = res.roles

                    $('#name').val(res.name)
                    $('#phone').val(res.phone)
                    $('#email').val(res.email)
                    $('#username').val(res.username)
                    $('#saldo').val(numberFormatIdr(res.saldo))

                    selectStatus.empty();
                    selectStatus.append('<option value="" disabled>Pilih Status</option>');
                    selectStatus.append('<option value="aktif">Aktif</option>');
                    selectStatus.append('<option value="ban">Banned</option>');
                    selectStatus.append('<option value="suspend">suspend</option>');
                    $('#status').val(res.status);

                    selectRole.empty();
                    $.ajax({
                        url: "{{ route('roles.list') }}",
                        method: "GET",
                        dataType: "json",
                        success: function(res) {
                            $.each(res.data, function(i, val) {
                                var selected = gloData[0].id === val
                                    .id ? 'selected' : '';
                                var option = '<option value="' + val.id + '" ' +
                                    selected + '>' + val.name + '</option>';
                                selectRole.append(option);
                            });
                        }
                    })
                },
                error: function(err) {
                    console.log(err)
                }
            })

        })

        // delete User
        $('body').on('click', '#delete-user', function() {
            var userUsername = $(this).data('username');
            let deleteUrl = "{!! route('users.destroy', ':username') !!}"
            deleteUrl = deleteUrl.replace(':username', userUsername);

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

        $('#createUser').on('click', function() {
            $('#modalUser').modal('show')
            $('#username').prop('disabled', false)
            $('#modalUserTitle').text('Tambah User Baru')
            url = "{!! route('users.store') !!}"
            method = "POST"
        })

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-save').removeClass('d-none')
            $('#username').prop('disabled', false)

            $('#saldo').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#saldo').val(formatted);
            })

            $('#modalUser').on('hidden.bs.modal', function() {
                $('.btn-loading').addClass('d-none')
                $('.btn-save').removeClass('d-none')
                $('.modal-body form')[0].reset();
                $('input[name="_method"]').remove();
                $('#username').prop('disabled', false)
            })

            $('#form-user').on('submit', function(e) {
                e.preventDefault()
                $('.btn-loading').removeClass('d-none')
                $('.btn-save').addClass('d-none')

                let name = $('#name').val()
                let username = $('#username').val()
                let phone = $('#phone').val()
                let email = $('#email').val()
                let saldo = $('#saldo').val()
                let status = $('#status').val()
                let password = $('#password').val()
                let role = $('#role').val()

                $.ajax({
                    url: getUrl(),
                    method: getMethod(),
                    data: {
                        name: name,
                        username: username,
                        shop_name: name,
                        phone: phone,
                        email: email,
                        saldo: saldo,
                        status: status,
                        role: role,
                        password: password,
                    },
                    dataType: "json",
                    success: function(res) {
                        $('#modalUser').modal('hide')
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
