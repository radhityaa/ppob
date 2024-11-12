@extends('layouts.settings.app')

@section('content-tab')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">
                            <p>Setting Profit Reseller</p>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.profit.update') }}" method="POST" id="form">
                    @csrf
                    @method('PUT')

                    <div class="col-md-3 mb-3">
                        <label for="persentase" class="form-label">Persentase</label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="persentase" name="persentase"
                                placeholder="Persen (%)" value="{{ $data['persentase'] }}" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <div class="col mb-3">
                        <label for="minimal_withdrawal" class="form-label">Minimal Penarikan</label>
                        <input type="text" name="minimal_withdrawal" id="minimal_withdrawal" class="form-control"
                            value="Rp {{ number_format($data['minimal_withdrawal'], 0, '.', '.') }}" required />
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
            $('#minimal_withdrawal').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#minimal_withdrawal').val(formatted);
            })
        })
    </script>
@endpush
