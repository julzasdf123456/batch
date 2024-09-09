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
        font-size: .91em;
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
        width: 28%;
    }

    .seventy {
        display: inline-table; 
        width: 68%;
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
        padding: 40px 30px 0px 30px;
    }


    .or-data {
        padding: 40px 1px 10px 1px;
    }

    .figure-data {
        padding: 40px 1px 10px 1px;
        display: inline;
    }

</style>

<div id="print-area" class="content">

    @php
        $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
        $numToWords = $f->format($transaction->TotalAmountPaid);
    @endphp

    <div class="thirty">
        <div class="figure-data">
            <p style='font-size: 1.1em !important; font-family: sans-serif !important; font-stretch: condensed !important; padding-top: 93px !important; padding-left: 140px !important; text-align: left !important;'>{{ is_numeric($transaction->TotalAmountPaid) ? number_format($transaction->TotalAmountPaid, 2) : $transaction->TotalAmountPaid }}</p>

            {{-- TOTAL --}}
            <p style='font-size: 1.1em !important; font-family: sans-serif !important; font-stretch: condensed !important; padding-top: 88px !important; padding-left: 140px !important; text-align: left !important;'>{{ is_numeric($transaction->TotalAmountPaid) ? number_format($transaction->TotalAmountPaid, 2) : $transaction->TotalAmountPaid }}</p>
        </div>
    </div>

    <div class="seventy">
        <div class="or-data">
            <p style='font-size: 1.2em !important; padding-top: 37px !important; padding-left: 89px !important;' class="text-right">{{ $transaction->ORDate != null ? date('m/d/Y', strtotime($transaction->ORDate)) : '' }}</p>

            {{-- NAME --}}
            <p style='font-size: 1.2em !important; padding-top: 20px !important; padding-left: 120px !important; text-align: left !important;'>{{ strtoupper(Students::formatNameFormal($student)) }}</p>

            {{-- ADDRESS --}}
            <p style='font-size: 1.2em !important; padding-top: 13px !important; padding-left: 130px !important; text-align: left !important;'>{{ ($student->BarangaySpelled != null ? ($student->BarangaySpelled . ', ') : '-') . ($student->TownSpelled != null ? ($student->TownSpelled) : '-') }}</p>
            
            {{-- AMOUNT IN WORDS --}}
            <p style='font-size: 1.2em !important; padding-top: 48px !important; padding-left: 50px !important; text-align: left !important;'>{{ $numToWords != null ? (strtoupper($numToWords)) : '-' }}</p>
            
            {{-- AMOUNT --}}
            <p style='font-size: 1.2em !important; padding-top: 17px !important; padding-left: 66px !important; text-align: left !important;'>{{ is_numeric($transaction->TotalAmountPaid) ? number_format($transaction->TotalAmountPaid, 2) : $transaction->TotalAmountPaid }}</p>

            
            {{-- CASHIER --}}
            <p style='font-size: 1em !important; padding-top: 20px !important; padding-left: 192px !important; text-align: left !important; font-family: sans-serif !important; font-stretch: condensed !important;'>{{ strtoupper(env('CASHIER_NAME')) }}</p>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        // window.history.go(-1)
        window.location.href = "{{ route('transactions.miscellaneous-search') }}";
    }, 800);
</script>