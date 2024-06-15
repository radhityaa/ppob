<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $deposit->invoice }}</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href={{ asset('assets/img/favicon/favicon.ico') }} />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href={{ asset('assets/vendor/css/rtl/core.css') }} />
    <link rel="stylesheet" href={{ asset('assets/vendor/css/rtl/theme-default.css') }} />
    <link rel="stylesheet" href={{ asset('assets/css/demo.css') }} />

    <!-- Page CSS -->

    <link rel="stylesheet" href={{ asset('assets/vendor/css/pages/app-invoice.css') }} />

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <div class="card invoice-preview-card">
            <div class="card-body">
                <div
                    class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
                    <div class="mb-xl-0 mb-4">
                        <div class="d-flex svg-illustration mb-4 gap-2 align-items-center">
                            <div class="app-brand-logo demo">
                                <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                        fill="#7367F0" />
                                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                                        fill="#161616" />
                                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                                        fill="#161616" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                        fill="#7367F0" />
                                </svg>
                            </div>
                            <span class="app-brand-text fw-bold fs-4"> {{ config('app.name') }} </span>
                        </div>
                        <p class="mb-2" style="width: 300px;">{{ env('WEBSITE_ADDRESS') }}</p>
                        <p class="mb-0">{{ env('WEBSITE_PHONE') }}</p>
                    </div>
                    <div>
                        <h4 class="fw-medium mb-2">{{ $deposit->invoice }}</h4>
                        <div class="pt-1">
                            <span>Tanggal:</span>
                            <span class="fw-medium">{{ $deposit->created_at->format('d M Y h:i:s') }}</span>
                        </div>
                        <div class="mb-2">
                            <span>Tanggal Exp:</span>
                            <span
                                class="fw-medium text-danger">{{ $deposit->expired_at->format('d M Y h:i:s') }}</span>
                        </div>
                        <div class="mb-2 pt-1">
                            <h2 class="fw-bold text-uppercase"><span
                                    class="@if ($deposit->status === 'paid') text-success @elseif ($deposit->status === 'unpaid') text-dark @else text-danger @endif">{{ $deposit->status }}</span>
                            </h2>
                        </div>
                        @if ($deposit->paid_at)
                            <div class="mb-2 pt-1">
                                <span>Dibayar Pada:</span>
                                <span
                                    class="fw-medium text-success">{{ $deposit->paid_at->format('d M Y h:i:s') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <div class="row p-sm-3 p-0">
                    <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                        <h6 class="mb-3">Ditagih Ke:</h6>
                        <p class="mb-1">{{ $deposit->user->name }}</p>
                        <p class="mb-1">{{ $deposit->user->username }}</p>
                        <p class="mb-1">{{ $deposit->user->phone }}</p>
                        <p class="mb-1">{{ $deposit->user->email }}</p>
                    </div>
                    <div class="col-xl-6 col-md-12 col-sm-7 col-12">
                        <h6 class="mb-4">Pembayaran:</h6>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="pe-4">Invoice:</td>
                                    <td class="fw-medium">{{ $deposit->invoice }}</td>
                                </tr>
                                <tr>
                                    <td class="pe-4">Bank:</td>
                                    <td>{{ $deposit->method }}</td>
                                </tr>
                                @php
                                    use Illuminate\Support\Str;
                                @endphp
                                @if (Str::startsWith($deposit->invoice, 'DPSM'))
                                    <tr>
                                        <td class="pe-4">Payment:</td>
                                        <td><span>Manual</span> </td>
                                    </tr>
                                @elseif ($deposit->pay_code)
                                    <tr>
                                        <td class="pe-4">Code:</td>
                                        <td><span id="pay-code">{{ $deposit->pay_code }}</span>
                                        </td>
                                    </tr>
                                @elseif($deposit->pay_url)
                                    <tr>
                                        <td class="pe-4">Payment:</td>
                                        <td><span>{{ $deposit->pay_url }}</span> <a href="{{ $deposit->pay_url }}"
                                                target="_blank"
                                                style="color: green; font-weight: 200; cursor: pointer;">(Open)</a>
                                        </td>
                                    </tr>
                                @elseif(!$deposit->pay_url && !$deposit->pay_code)
                                    <tr>
                                        <td class="pe-4">Payment:</td>
                                        <td><span>{{ $deposit->checkout_url }}</span> <a
                                                href="{{ $deposit->checkout_url }}" target="_blank"
                                                style="color: green; font-weight: 200; cursor: pointer;">(Open)</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="table-responsive border-top">
                <table class="table m-0">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Keterangan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-nowrap">Deposit Saldo Rp
                                {{ number_format($deposit->nominal, 0, '.', '.') }}</td>
                            <td class="text-nowrap">Pembayaran {{ $deposit->method }}</td>
                            <td>Rp {{ number_format($deposit->nominal, 0, '.', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                <div class="row px-5">
                    <table class="m-0 mt-3">
                        <tr>
                            <td class="px-4">Subtotal: </td>
                            <td class="fw-medium">Rp {{ number_format($deposit->nominal, 0, '.', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-4">Diskon: </td>
                            <td class="fw-medium">Rp 0</td>
                        </tr>
                        <tr>
                            <td class="px-4">Fee: </td>
                            <td class="fw-medium">Rp {{ number_format($deposit->fee, 0, '.', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-4">Total: </td>
                            <td class="fw-medium">Rp {{ number_format($deposit->total, 0, '.', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-4">Terbilang: </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-end px-4 fw-bold" style="text-transform: capitalize">{{ $terbilang }} Rupiah</div>

            <div class="card-body mx-3">
                <div class="row">
                    <div class="col-12">
                        <span class="fw-medium">Note:</span>
                        <span>
                            Ini adalah bukti pembayaran yang sah, jika bukti pembayaran tidak sesuai dengan bukti
                            pembayaran yang dibuat, mohon hubungi kami.
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </section>

</body>

</html>
