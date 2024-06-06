@extends('layouts.administrator.app')

@section('content')
    <div class="col-lg-6">
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
                <form action="{{ route('admin.setting.margin.update', $margin->id) }}" method="POST" id="form">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col">
                            <label for="margin" class="form-label">Margin</label>
                            <input type="text" name="margin" id="margin" class="form-control"
                                value="Rp {{ number_format($margin->margin, 0, '.', '.') }}" />
                        </div>
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
            $('#margin').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#margin').val(formatted);
            })
        })
    </script>
@endpush
