@extends('layouts.settings.app')

@section('content-tab')
    <div class="col-lg-12">
        <div class="card mb-3">
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
                    {{-- Digiflazz --}}
                    <h5>Digiflazz</h5>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="margin_member" class="form-label">Margin Member</label>
                            <input type="text" name="margin_member" id="margin_member" class="form-control"
                                value="Rp {{ number_format($marginMember->margin, 0, '.', '.') }}" required />
                        </div>

                        <div class="col mb-3">
                            <label for="margin_reseller" class="form-label">Margin Reseller</label>
                            <input type="text" name="margin_reseller" id="margin_reseller" class="form-control"
                                value="Rp {{ number_format($marginReseller->margin, 0, '.', '.') }}" required />
                        </div>

                        <div class="col mb-3">
                            <label for="margin_agen" class="form-label">Margin Agen</label>
                            <input type="text" name="margin_agen" id="margin_agen" class="form-control"
                                value="Rp {{ number_format($marginAgen->margin, 0, '.', '.') }}" required />
                        </div>
                    </div>

                    {{-- Vipayment - Premium Account --}}
                    <h5 class="mt-3">Vipayment - Premium Account</h5>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="vip-premium-member" class="form-label">Margin Member</label>
                            <input type="text" name="margin_premium_member" id="margin_premium_member"
                                class="form-control"
                                value="Rp {{ number_format($marginPremiumMember->margin, 0, '.', '.') }}" required />
                        </div>

                        <div class="col mb-3">
                            <label for="vip-premium-reseller" class="form-label">Margin Reseller</label>
                            <input type="text" name="margin_premium_reseller" id="margin_premium_reseller"
                                class="form-control"
                                value="Rp {{ number_format($marginPremiumReseller->margin, 0, '.', '.') }}" required />
                        </div>

                        <div class="col mb-3">
                            <label for="vip-premium-agen" class="form-label">Margin Agen</label>
                            <input type="text" name="margin_premium_agen" id="margin_premium_agen" class="form-control"
                                value="Rp {{ number_format($marginPremiumAgen->margin, 0, '.', '.') }}" required />
                        </div>
                    </div>

                    {{-- Vipayment - Social Media --}}
                    <h5 class="mt-3">Vipayment - Social Media</h5>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="margin_sosmed_member" class="form-label">Margin Member</label>
                            <input type="text" name="margin_sosmed_member" id="margin_sosmed_member" class="form-control"
                                value="Rp {{ number_format($marginSosmedMember->margin, 0, '.', '.') }}" required />
                        </div>

                        <div class="col mb-3">
                            <label for="margin_sosmed_reseller" class="form-label">Margin Reseller</label>
                            <input type="text" name="margin_sosmed_reseller" id="margin_sosmed_reseller"
                                class="form-control"
                                value="Rp {{ number_format($marginSosmedReseller->margin, 0, '.', '.') }}" required />
                        </div>

                        <div class="col mb-3">
                            <label for="margin_sosmed_agen" class="form-label">Margin Agen</label>
                            <input type="text" name="margin_sosmed_agen" id="margin_sosmed_agen" class="form-control"
                                value="Rp {{ number_format($marginSosmedAgen->margin, 0, '.', '.') }}" required />
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
            $('input[name="margin_member"], input[name="margin_reseller"], input[name="margin_agen"], input[name="margin_premium_member"], input[name="margin_premium_reseller"], input[name="margin_premium_agen"], input[name="margin_sosmed_member"], input[name="margin_sosmed_reseller"], input[name="margin_sosmed_agen"]')
                .on('input', function() {
                    var inputValue = $(this).val();
                    var numericValue = inputValue.replace(/Rp|\./g, '');
                    var integerValue = parseInt(numericValue, 10);
                    var formatted = numberFormatIdr(integerValue);
                    $(this).val(formatted);
                })
        })
    </script>
@endpush
