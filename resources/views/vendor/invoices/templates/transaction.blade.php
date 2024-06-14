<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $invoice->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900;1,9..40,300;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700;1,9..40,800;1,9..40,900&family=Roboto+Mono:wght@400;500;600;700&display=swap"
        rel="stylesheet"> --}}

    <style type="text/css" media="screen">
        html {
            font-family: "Roboto Mono";
            line-height: 1.15;
            margin: 0;
        }

        body {
            font-family: "Roboto Mono", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 10px;
            margin: 10pt;
        }

        table {
            font-size: 13px;
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
        }

        .separator {
            width: 5px;
            text-align: start;
        }

        .label {
            width: 100px;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    {{-- @if ($invoice->logo)
        <img src="{{ $invoice->getLogo() }}" alt="logo" height="100">
    @endif --}}

    <div style="text-align: center">
        <div style="line-height: 20px; font-size: 13pt;"><strong>{{ $invoice->name }}</strong></div>
        <div style="line-height: 20px; font-size: 13pt;"><strong>** Shafa Cell **</strong></div>
        <div style="margin-top: 2px; line-height: 20px; font-size: 10pt">{{ $invoice->getDate() }}</div>

        <span style="font-size: 15pt"><strong>Struk Pembelian</strong></span>
    </div>

    <table>
        <tr>
            <td class="label">No. Invoice</td>
            <td class="separator">:</td>
            <td>{{ $invoice->getSerialNumber() }}</td>
        </tr>
        @foreach ($invoice->items as $item)
            <tr>
                <td class="label">Layanan</td>
                <td class="separator">:</td>
                <td>{{ $item->title }}</td>
            </tr>
        @endforeach
        <tr>
            <td class="label">Nomor Tujuan</td>
            <td class="separator">:</td>
            <td>{{ $invoice->seller->target }}</td>
        </tr>
        <tr>
            <td class="label">Harga</td>
            <td class="separator">:</td>
            <td>{{ $invoice->formatCurrency($item->price_per_unit) }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="separator">:</td>
            <td>{{ $invoice->status }}</td>
        </tr>
        <tr>
            <td class="label">SN</td>
            <td class="separator">:</td>
            <td>{{ $invoice->seller->sn }}</td>
        </tr>
        <tr>
            <td class="label">Keterangan</td>
            <td class="separator">:</td>
            <td>{{ $item->description }}</td>
        </tr>
    </table>

    <div style="text-align: center; font-weight:bold; margin-top: 20px;">
        <h1>TERIMA KASIH</h1>
        <div style="font-size: 0.7rem">{{ $invoice->seller->name }}</div>
        <div style="font-size: 0.7rem">{{ $invoice->seller->email }}</div>
        <div style="font-size: 0.7rem">{{ $invoice->seller->phone }}</div>
        <div style="font-size: 0.7rem">Perum Bumi Cengkong Lestari, Cengkong, Purwasari, Karawang</div>
    </div>

    <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
</body>

</html>
