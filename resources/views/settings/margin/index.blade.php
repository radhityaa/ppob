@extends('layouts.settings.app')

@section('content-tab')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">
                            <p>Setting Margin Products</p>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.margin.update') }}" method="POST" id="form">
                    @csrf
                    @method('PUT')

                    <div class="col mb-3">
                        <label for="margin_member" class="form-label">Margin Member</label>
                        <input type="text" name="margin_member" id="margin_member" class="form-control"
                            value="Rp {{ number_format($marginMember['margin'], 0, '.', '.') }}" required />
                    </div>

                    <div class="col mb-3">
                        <label for="margin_reseller" class="form-label">Margin Reseller</label>
                        <input type="text" name="margin_reseller" id="margin_reseller" class="form-control"
                            value="Rp {{ number_format($marginReseller['margin'], 0, '.', '.') }}" required />
                    </div>

                    <div class="col mb-3">
                        <label for="margin_agen" class="form-label">Margin Agen</label>
                        <input type="text" name="margin_agen" id="margin_agen" class="form-control"
                            value="Rp {{ number_format($marginAgen['margin'], 0, '.', '.') }}" required />
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-success">Ubah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        function numberFormatIdr(value) {
            var reverse = value.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formatted = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + formatted;
        }

        $(document).ready(function() {
            $('#margin_member').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#margin_member').val(formatted);
            })

            $('#margin_reseller').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#margin_reseller').val(formatted);
            })

            $('#margin_agen').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#margin_agen').val(formatted);
            })
        })
    </script>
@endpush
