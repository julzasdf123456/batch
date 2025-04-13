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

    .table-borderless th, .table-borderless td {
        border : 0px !important;
        outline: 0;
    }

    .table th, .table td {
        font-size: .85em;
        border: 1px solid #a8a8a8;
        padding: 5px 8px 5px 8px;
    }

    .border-top {
        border-top: 1px solid #636363 !important;
    }

    .header-data {
        padding: 85px 30px 10px 50px;
    }

    .body-data {
        padding: 60px 30px 0px 30px;
    }

    .mt-2 {
        margin-top: 10px;
    }
    
    .mt-3 {
        margin-top: 15px;
    }

</style>

<div id="print-area" class="content">

    <div>
        <p class="text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
        <p class="text-center">{{ env('APP_LOCATION') }}</p>

        <p class="text-center" style="margin-top: 10px;"><strong>TUITION PAYMENT SCHEDULE</strong></p>
        <p class="text-center">{{ $sy != null ? $sy->SchoolYear : '' }}</p>

        {{-- header --}}
        <table class="table table-borderless mt-3">
            <tbody>
                <tr>
                    <td>Name : </td>
                    <td><strong>{{ Students::formatNameFormal($student) }}</strong></td>
                    <td>Grade : </td>
                    <td><strong>{{ ($student->Year != null ? $student->Year : '-') . ($student->Section != null ? ' - ' . $student->Section : '') }}</strong></td>
                </tr>
                <tr>
                    <td>Address : </td>
                    <td><strong>{{ ($student->Sitio != null ? $student->Sitio : '') . ($student->BarangaySpelled != null ? ', ' . $student->BarangaySpelled : '') . ($student->TownSpelled != null ? ', ' . $student->TownSpelled : '') }}</strong></td>
                    <td>Strand : </td>
                    <td><strong>{{ ($student->Strand != null ? $student->Strand : '') . ($student->Semester != null ? ' (' . $student->Semester . ' sem)' : '') }}</strong></td>
                </tr>
            </tbody>
        </table>

        {{-- tuition table --}}
        <table class="table mt-2">
            <thead>
                <th class="">Month</th>
                <th class=" text-right">Tuition Fee</th>
                <th class=" text-right">Discount</th>
                <th class=" text-right">Amount Payable</th>
                <th class=" text-right">Amount Paid</th>
                <th class=" text-right">Balance</th>
            </thead>
            <tbody>
                @foreach ($tuitionBreakdown as $item)
                    <tr>
                        <td>{{ $item->ForMonth != null ? date('M Y', strtotime($item->ForMonth)) : '-' }}</td>
                        <td class="text-right">{{ number_format($item->Payable, 2) }}</td>
                        <td class="text-right">{{ number_format($item->Discount, 2) }}</td>
                        <td class="text-right"><strong>{{ number_format($item->AmountPayable, 2) }}</strong></td>
                        <td class="text-right">{{ $item->AmountPaid != null ? number_format($item->AmountPaid, 2) : '-' }}</td>
                        <td class="text-right"><strong>{{ $item->Balance != null ? number_format($item->Balance, 2) : '-' }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- summary --}}
        <div class="mt-3">
            <p class="text-muted text-right">Summary</p>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td style="width: 50%;"></td>
                        <td>Tuition Payable</td>
                        <td class="text-right">{{ $tuitionPayable != null ? number_format($tuitionPayable->Payable, 2) : '0' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%;"></td>
                        <td>Discount Amount</td>
                        <td class="text-right">{{ $tuitionPayable != null ? '-' . number_format($tuitionPayable->DiscountAmount, 2) : '0' }}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%;"></td>
                        <td>Less Discount</td>
                        <td class="text-right"><strong>{{ $tuitionPayable != null ? number_format($tuitionPayable->AmountPayable, 2) : '0' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="width: 50%;"></td>
                        <td>Total Amount Paid</td>
                        <td class="text-right">{{ $tuitionPayable != null ? number_format($tuitionPayable->AmountPaid, 2) : '0' }}</td>
                    </tr>
                    <tr>
                        <td class="border-top" style="width: 50%;"></td>
                        <td class="border-top">Balance</td>
                        <td class="border-top text-right" style="font-size: 1.3em;"><strong>{{ $tuitionPayable != null ? number_format($tuitionPayable->Balance, 2) : '0' }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 1800);
</script>