<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel"
    style="height: auto;">
    <div class="offcanvas-header p-3">
        <h5 id="offcanvasBottomLabel" class="offcanvas-title"></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <table class="table">
            <thead>
            </thead>
            <tbody class="table-border-bottom-0">
                <td class="d-none" id="buyer_sku_code"></td>
                <tr>
                    <th class="fw-semibold">Tujuan</th>
                    <td class="fw-bold" id="target-detail"></td>
                </tr>
                <tr>
                    <th class="fw-semibold">Jenis</th>
                    <td id="type"></td>
                </tr>
                <tr>
                    <th class="fw-semibold">Harga</th>
                    <td id="price"></td>
                </tr>
                <tr>
                    <th class="fw-semibold">Keterangan</th>
                    <td id="description"></td>
                </tr>
                <tr>
                    <th class="fw-semibold">Multi Trx</th>
                    <td id="multi"></td>
                </tr>
                <tr>
                    <th class="fw-semibold">Cut Off System</th>
                    <td id="cut-off"></td>
                </tr>
                <tr>
                    <th class="fw-semibold">Sisa Saldo</th>
                    <td class="fw-bold" id="saldo"></td>
                </tr>
            </tbody>
        </table>

        <div class="m-3">
            <div class="d-md-flex align-items-center gap-3">
                <div class="mb-2">
                    <button type="button" id="buy" class="btn btn-primary me-2 btn-buy">Beli</button>
                    <x-button-loading />
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">
                        Batal
                    </button>
                </div>

                <div class="badge bg-danger saldo" style="display: none;">Saldo Tidak Cukup! <span
                        id="saldo"></span>
                </div>
            </div>
        </div>
    </div>
</div>
