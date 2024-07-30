<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $invoice->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css" media="screen">
        html {
            font-family: "Noto Sans";
            line-height: 1.15;
            margin: 0;
        }

        body {
            font-family: "Noto Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 10px;
            margin: 10pt;
        }

        table {
            font-size: 12px;
            width: 100%;
            padding-top: 10px;
            padding-bottom: 5px;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 2px;
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

    <div style="text-align: center">
        <div style="line-height: 15px; font-size: 17px;"><strong>** {{ Auth::user()->shop_name }} **</strong></div>
        <div style="line-height: 10px; font-size: 10px; padding-bottom: 6px; padding-top: 6px;">
            {{ Auth::user()->address }}</div>
        <span>===========================================</span>
    </div>

    <table>
        <tr>
            <td class="label">Tanggal</td>
            <td class="separator">:</td>
            <td>{{ $invoice->getDate() }}</td>
        </tr>
        <tr>
            <td class="label">Invoice</td>
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
            <td class="label">Tujuan</td>
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
            <td class="label">Keterangan</td>
            <td class="separator">:</td>
            <td style="word-wrap: break-word;">{{ $item->description }}</td>
        </tr>
    </table>

    <div style="text-align: center; padding-top: 14px; padding-bottom: 14px;">
        <span style="font-weight:bold; font-size: 14px;">** Serial Number **</span>
        <div style="font-size: 11px; width: 250px; word-wrap: break-word;">
            {{ $invoice->seller->sn }}
        </div>
    </div>

    <span>===========================================</span>


    <div style="text-align: center;">
        <h2 style="font-weight:bold; font-size: 13px;">TERIMA KASIH</h2>
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
