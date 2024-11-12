@extends('layouts.settings.app')

@section('content-tab')
    <div class="col-lg-12 mb-4">
        <div class="card mb-4">
            <!-- Notifications -->
            <h5 class="card-header pb-4">Setting Notifikasi</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <label class="switch">
                            <input type="checkbox" class="switch-input" id="val1" name="val1"
                                {{ $data->val1 === 'true' ? 'checked' : '' }}>
                            <span class="switch-toggle-slider">
                                <span class="switch-on">
                                    <i class="ti ti-check"></i>
                                </span>
                                <span class="switch-off">
                                    <i class="ti ti-x"></i>
                                </span>
                            </span>
                            <span class="switch-label">Whatsapp</span>
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="switch">
                            <input type="checkbox" class="switch-input" id="val2" name="val2"
                                {{ $data->val2 === 'true' ? 'checked' : '' }}>
                            <span class="switch-toggle-slider">
                                <span class="switch-on">
                                    <i class="ti ti-check"></i>
                                </span>
                                <span class="switch-off">
                                    <i class="ti ti-x"></i>
                                </span>
                            </span>
                            <span class="switch-label">Telegram</span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- /Notifications -->
        </div>
    </div>

    {{-- Whatsapp --}}
    @if ($data->val1 === 'true')
        <div class="col-lg-12 val1-container mb-4" {{ $data->val1 === 'false' ? 'd-none' : '' }}>
            <div class="card mb-4">
                <!-- Notifications -->
                <h5 class="card-header pb-4">Setting Whatsapp Gateway</h5>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @if ($whatsapp)
                                    <tr>
                                        <td>{{ $whatsapp->phone ?? 0 }}</td>
                                        <td id="device-status">
                                            <span
                                                class="badge {{ $whatsapp->status === 'Connected' ? 'bg-label-success' : 'bg-label-danger' }} me-1">{{ $whatsapp->status }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($whatsapp->status === 'Connected')
                                                    <button type="button" onclick="disconnect()"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="ti ti-plug-off"></i></button>
                                                @else
                                                    <button type="button" onclick="connectDevice()"
                                                        class="btn btn-sm btn-success"> <i class="ti ti-plug"></i></button>
                                                @endif
                                                <button type="button" onclick="destroy()" class="btn btn-sm btn-warning">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <button class="btn btn-success mb-3" onclick="addDevice()">Tambah Device</button>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /Notifications -->
            </div>
        </div>
    @endif

    <!-- Modal Add -->
    <div class="mt-3">
        <div class="modal modal-sm fade" id="modalAdd" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddTitle">Tamnah Device</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="" id="form-add">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="phone" class="form-label">Nomor</label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        placeholder="Nomor Whatsapp" value="{{ old('phone') }}" required />
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary btn-save">Tambah</button>
                        <x-button-loading />
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Scan -->
    <div class="mt-3">
        <div class="modal modal-sm fade" id="modalScan" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalScanTitle">Scan QR</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="mb-2">
                            <span id="information"></span>
                        </div>
                        <img src="" id="qrcode">
                        <div class="mt-2">
                            <span>Status: <span id="status">Please wait...</span></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script script src="{{ asset('assets/js/socket.io.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        const socketIoUrl = '{{ config('app.wa_socket') }}'
        socket = io(`${socketIoUrl}`, {
            transport: ['websocket', 'polling', 'flashsocket'],
            forceNew: true,
            reconnection: true,
        })

        socket.on('connected', function(res) {
            console.log(res.message)
        })

        function destroy() {
            Swal.fire({
                title: 'Apakah Yakin?',
                text: "Device ini akan di hapus!",
                icon: 'warning',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#82868',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{!! route('admin.settings.whatsapp.destroy') !!}",
                        method: "DELETE",
                        success: function(res) {
                            socket.emit('deleteDevice', {
                                sender: "{{ $whatsapp->phone ?? 0 }}"
                            })

                            Swal.fire({
                                title: 'Berhasil',
                                text: "Device Terputus!",
                                icon: 'success',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });

                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
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
        }

        function connectDevice() {
            $('#modalScan').modal('show')

            socket.emit('startConnection', {
                sender: "{{ $whatsapp->phone ?? 0 }}"
            })

            socket.on('qrcode', function(res) {
                document.getElementById("qrcode").src = res.data;
                $('#information').html(res.message)
            })

            socket.on('message', function(res) {
                if (res.rc === 9) {
                    $('h4#information').addClass('text-success')
                    $('h4#information').text('Your Account Has Been Connected!')
                    document.getElementById("qrcode").src = "";

                    setTimeout(() => {
                        window.location.reload();
                    }, 1300);

                    $.ajax({
                        url: "{!! route('admin.settings.whatsapp.updateStatus') !!}",
                        method: "POST",
                        data: {
                            status: 'Connected'
                        }
                    })
                } else {
                    $.ajax({
                        url: "{!! route('admin.settings.whatsapp.updateStatus') !!}",
                        method: "POST",
                        data: {
                            status: 'Disconnected'
                        }
                    })
                }

                $('span#status').text(res.message)
            })
        }

        function disconnect() {
            Swal.fire({
                title: 'Apakah Yakin?',
                text: "Koneksi Ke Whastapp akan terputus!",
                icon: 'warning',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#82868',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{!! route('admin.settings.whatsapp.updateStatus') !!}",
                        method: "POST",
                        data: {
                            status: 'Disconnected'
                        },
                        success: function(res) {
                            socket.emit('deleteDevice', {
                                sender: "{{ $whatsapp->phone ?? 0 }}"
                            })

                            Swal.fire({
                                title: 'Berhasil',
                                text: "Device Terputus!",
                                icon: 'success',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });

                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
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
        }

        function addDevice() {
            $('#modalAdd').modal('show')
        }

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-save').removeClass('d-none')

            $('#val1').on('change', function() {
                let value = $(this).is(':checked') ? 'true' : 'false';
                $.ajax({
                    url: "{!! route('admin.settings.notification.update') !!}",
                    type: 'PUT',
                    data: {
                        val1: value
                    },
                    success: function(res) {
                        if (res.data.val1 === 'true') {
                            $('.val1-container').removeClass('d-none');
                        } else {
                            $('.val1-container').addClass('d-none');
                        }

                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    },
                    error: function(err) {
                        console.log(err);
                        Swal.fire({
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mengubah data',
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        })
                    }
                });
            });

            $('#val2').on('change', function() {
                let value = $(this).is(':checked') ? 'true' : 'false';
                $.ajax({
                    url: "{!! route('admin.settings.notification.update') !!}",
                    type: 'PUT',
                    data: {
                        val2: value
                    },
                    success: function(res) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    },
                    error: function(err) {
                        console.log(err);
                        Swal.fire({
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat mengubah data',
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        })
                    }
                });
            });

            $('#form-add').on('hidden.bs.modal', function() {
                $('.btn-loading').addClass('d-none')
                $('.btn-save').removeClass('d-none')
            })

            $('#form-add').on('submit', function(e) {
                e.preventDefault();

                $('.btn-loading').removeClass('d-none')
                $('.btn-save').addClass('d-none')

                let phone = $('#phone').val();

                $.ajax({
                    url: "{{ route('admin.settings.whatsapp.store') }}",
                    type: 'POST',
                    data: {
                        phone: phone
                    },
                    success: function(res) {
                        $('.btn-loading').addClass('d-none')
                        $('.btn-save').removeClass('d-none')

                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 1500);

                        $('#modalAdd').modal('hide');
                    },
                    error: function(err) {
                        console.log(err);
                        $('.btn-loading').addClass('d-none')
                        $('.btn-save').removeClass('d-none')

                        Swal.fire({
                            title: 'Gagal',
                            text: 'Terjadi kesalahan server',
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        })
                    }
                });
            })
        })
    </script>
@endpush
