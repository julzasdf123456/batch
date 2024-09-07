@php
    use App\Models\Students;
    use App\Models\Users;
@endphp
<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }
    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        margin: 0;
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .90em;
    }
    @media print {
        @page {
            size: 8.14in 4.33in !important;
            size: landscape !important;
        }

        header {
            display: none;
        }

        .divider {
            width: 100%;
            margin: 10px auto;
            height: 1px;
            background-color: #dedede;
        }

        .left-indent {
            margin-left: 30px;
        }

        p {
            padding: 0px !important;
            margin: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

    }  
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 3px;
        background-color: #dedede;
      -webkit-print-color-adjust: exact;
    } 

    p {
        padding: 0px !important;
        margin: 0px;
        font-size: 1.2em;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .half {
        display: inline-table; 
        width: 49%;
    }

    .thirty {
        display: inline-table; 
        width: 30%;
    }

    .seventy {
        display: inline-table; 
        width: 69%;
    }

    .watermark {
        position: fixed;
        left: 15%;
        top: 60px;
        width: 65%;
        opacity: 0.16;
        z-index: -99;
        color: white;
        user-select: none;
    }

    .border {
        position: fixed;
        width: 100%;
        z-index: 1;
        color: white;
        left: 0;
        top: 0;
    }

    .pms {
      color: black;
      background: rgb(243, 231, 57);
      padding: 30px;
      font-size: 2em;
      -webkit-print-color-adjust: exact;
    }

    .bg-bill {
      background-color: #607D8B;
      -webkit-print-color-adjust: exact;
    }

    .text-white {
      color: white;
      -webkit-print-color-adjust: exact;
    }

    .text-muted {
      color: #898989;
      -webkit-print-color-adjust: exact;
    }

    .no-pad {
      margin: 0px; 
      padding: 0px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        font-size: 1em;
        border: 1px solid #a8a8a8;
        padding: 5px 8px 5px 8px;
    }

    .header-data {
        padding: 85px 30px 10px 50px;
    }

    .body-data {
        padding: 60px 30px 0px 30px;
    }

</style>

<div id="print-area" class="content">

    @php
        $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        $numToWords = $f->format($transaction->TotalAmountPaid);
    @endphp

    <div class='header-data'>
        <div class="half">
            <p style='font-size: 1.4em !important;'>{{ strtoupper(Students::formatNameFormal($student)) }}</p>
            <p>{{ $numToWords != null ? (ucfirst($numToWords) . ' Pesos') : '-' }}</p>
        </div>

        <div class="half">
           <p class="text-right">{{ $transaction->ORDate != null ? date('m/d/Y', strtotime($transaction->ORDate)) : '' }}</p>
           <p class="text-right">₱ {{ is_numeric($transaction->TotalAmountPaid) ? number_format($transaction->TotalAmountPaid, 2) : $transaction->TotalAmountPaid }}</p>
        </div>
    </div>

    <div class="body-data">
        <table style="width: 100%;">
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($transactionDetails as $item)
                    <tr>
                        <td>
                            @if ($i == 0)
                                {{ $classes != null ? ($classes->Year . ' ' . $classes->Section) : '-' }} {{ $classes != null && $classes->Section != null ? '' : '' }}
                            @endif
                        </td>
                        <td>{{ $item->Particulars != null ? $item->Particulars : '' }}</td>
                        <td>{{ is_numeric($item->Amount) ? number_format($item->Amount, 2) : $item->Amount }}</td>
                        <td class="text-right">{{ $student->id }}</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
                
                <tr>
                    <td>{{ is_numeric($transaction->TotalAmountPaid) ? number_format($transaction->TotalAmountPaid, 2) : $transaction->TotalAmountPaid }} | {{ $transaction->ModeOfPayment }} |</td>
                    <td></td>
                    <td></td>
                    <td class="text-right"></td>
                </tr>
            </tbody>
        </table>
        
        <p style='font-size: 1.4em !important; padding-top: 30px !important;' class="text-right">{{ env('CASHIER_NAME') }}</p>
    </div>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.location.href = "{{ route('transactions.tuitions-search') }}";
    }, 800);
</script>