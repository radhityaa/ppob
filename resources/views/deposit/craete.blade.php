@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="col-lg-6">
        <div class="card mb-4">
            <h5 class="card-header">Deposit Saldo</h5>
            <form class="card-body" method="" id="form">
                @csrf

                <h6>1. Nominal</h6>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label" for="nominal">Nominal</label>
                        <input type="text" id="nominal" name="nominal"
                            class="form-control @error('nominal') is-invalid @enderror" required />
                        @error('nominal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <hr class="mx-n4 my-4" />
                <h6>2. Pembayaran</h6>
                <div class="row mt-3">
                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="manual">
                                    <input name="customRadioTemp" class="form-check-input" type="radio" value=""
                                        id="manual" name="manual" />
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Manual</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="virtual-account">
                                    <input name="customRadioTemp" class="form-check-input" type="radio" value=""
                                        id="virtual-account" name="virtual-account" />
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Virtual Account</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="retail">
                                    <input name="customRadioTemp" class="form-check-input" type="radio" value=""
                                        id="retail" name="retail" />
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Retail</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="e-wallet">
                                    <input name="customRadioTemp" class="form-check-input" type="radio" value=""
                                        id="e-wallet" name="e-wallet" />
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">E-Wallet</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col">
                        <label for="method" class="form-label">Method</label>
                        <select id="method" name="method" class="select2 form-select method-select" required>
                            <option value="" disabled>Pilih Pembayaran Dulu</option>
                        </select>
                    </div>
                </div>

                <div class="row detail mt-3" style="display: none;">
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="fee">Fee</label>
                        <input type="text" id="fee" name="fee" class="form-control" disabled />
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="total">Total</label>
                        <input type="text" id="total" name="total" class="form-control" disabled />
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="receive">Saldo Diterima</label>
                        <input type="text" id="receive" name="receive" class="form-control" disabled />
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Deposit</button>
                    <x-button-loading />
                    <a href="{{ route('deposit.index') }}" class="btn btn-label-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi</h5>
            </div>
            <div class="card-body">
                <div>
                    <p class="fw-bold">Langkah-langkah deposit:</p>
                    {!! $settings->val1 !!}
                </div>

                <div>
                    <p class="fw-bold">Jam Operasional Deposit:</p>
                    {!! $settings->val2 !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        function numberFormatIdr(value) {
            var roundedValue = Math.round(value);
            var reverse = roundedValue.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formatted = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + formatted;
        }

        function listMethod(type) {
            let listUrl = "{{ route('payment-method.list', ':type') }}"
            listUrl = listUrl.replace(':type', type)

            $.ajax({
                url: listUrl,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    // Transformasi data sebelum memberikannya ke Select2
                    var transformedData = res.map(function(item) {
                        return {
                            id: item.code,
                            text: item.name
                        };
                    });

                    // Tambahkan opsi "Pilih Method Pembayaran" yang tidak dapat dipilih
                    transformedData.unshift({
                        id: '',
                        text: 'Pilih Method Pembayaran',
                        disabled: true,
                        selected: true
                    });

                    // Inisialisasi Select2 dengan data yang telah diubah
                    $('.method-select').empty(); // Kosongkan pilihan sebelumnya
                    $('.method-select').select2({
                        data: transformedData
                    });
                },
                error: function(res) {
                    console.log(res)
                }
            })
        }

        $('#manual').on('click', function() {
            listMethod('manual')
        })

        $('#virtual-account').on('click', function() {
            listMethod('virtual-account')
        })

        $('#retail').on('click', function() {
            listMethod('retail')
        })

        $('#e-wallet').on('click', function() {
            listMethod('e-wallet')
        })

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Pilih Pembayaran Dahulu'
            });

            $('.method-select').on('select2:select', function(e) {
                var selectedData = e.params.data
                let detailUrl = "{{ route('payment-method.detailMethod', ':code') }}"
                detailUrl = detailUrl.replace(':code', selectedData.id)

                $.ajax({
                    url: detailUrl,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        code: selectedData.id
                    },
                    success: function(res) {
                        // console.log(res)
                        var total = res.fee + parseInt($('#nominal').val().replace(/Rp|\./g,
                            ''), 10)
                        var flatFee = res.fee
                        var percentFee = res.percent_fee
                        var fee = flatFee

                        if (percentFee > 0) {
                            fee = total * (percentFee / 100)
                        }

                        $('.detail').show()
                        $('#fee').val(numberFormatIdr(fee))
                        $('#total').val(numberFormatIdr(total))
                        $('#receive').val($('#nominal').val())
                    },
                    error: function(res) {
                        console.log(res)
                    }
                })
            })

            $('#nominal').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#nominal').val(formatted);
            })

            $('#form').on('submit', function(e) {
                e.preventDefault()
                $('.btn-loading').removeClass('d-none')
                $('.btn-primary').addClass('d-none')

                var nominal = parseInt($('#nominal').val().replace(/Rp|\./g, ''))
                var method = $('#method').val()

                $.ajax({
                    url: "{{ route('deposit.store') }}",
                    method: "POST",
                    responsive: true,
                    data: {
                        nominal: nominal,
                        method: method
                    },
                    dataType: "json",
                    success: function(res) {
                        $('.btn-loading').addClass('d-none')
                        $('.btn-primary').removeClass('d-none')
                        var url = "{{ route('deposit.show', ':invoice') }}"
                        url = url.replace(':invoice', res.invoice)

                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location.href = url
                            })
                        } else {
                            $('.btn-loading').addClass('d-none')
                            $('.btn-primary').removeClass('d-none')

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    },
                    error: function(res) {
                        $('.btn-loading').addClass('d-none')
                        $('.btn-primary').removeClass('d-none')

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.responseJSON.message
                        })
                    }
                })
            })
        })
    </script>
@endpush
