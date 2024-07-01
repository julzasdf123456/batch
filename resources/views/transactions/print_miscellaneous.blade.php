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
        font-size: .72em;
    }
    @media print {
        @page {
            orientation: portrait;
            margin: 0;
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

</style>

<div id="print-area" class="content">
    <div class="bg-bill" style="padding: 25px 30px 15px 30px;">
        <div class="half">
           <img src="{{ URL::asset('imgs/logo.png') }}" width="80px;" style="margin-bottom: 10px;"> 
           <h1 class="text-white no-pad">ACKNOWLEDGEMENT RECEIPT</h1>
           <h3 class="text-white no-pad">{{ $transaction->ORNumber }}</h3>
        </div>
        
        <div class="half">
           <p class="text-right text-white" style="padding-bottom: 2px; font-size: 1.52em;"><strong>{{ env('APP_COMPANY') }}</strong></p>
           <p class="text-right text-white" style="padding-bottom: 2px;">{{ env('APP_ADDRESS') }}</p>
           <p class="text-right text-white" style="padding-bottom: 2px;">{{ env('APP_POSTAL') }}</p>
        </div>
    </div>
  
    <div style="padding: 10px 30px 15px 30px;">
        <div class="half">
           <span class="text-muted">Payment From:</span><br><br>
           <h1 class="no-pad">{{ Students::formatNameFormal($student) }}</h1>
           <p class="no-pad">{{ $student->BarangaySpelled . ', ' . $student->BarangaySpelled }}</p>
           <p class="no-pad"><span class="text-muted">ID Number:</span> {{ $student->id }}</p>
           {{-- <p class="no-pad text-muted">Date Connected: {{ $customer->DateConnected != null ? date('F d, Y', strtotime($customer->DateConnected)) : '' }}</p>
           <p class="no-pad text-muted">Subscription: {{ $customerTechnical->SpeedSubscribed }} mbps</p> --}}
        </div>
  
        <div class="half">
           <p class="text-muted text-right no-pad">OR Number:</p>
           <p class="no-pad text-right">{{ $transaction->ORNumber }}</p>
           <p class="text-muted text-right no-pad">OR Date:</p>
           <p class="no-pad text-right">{{ $transaction->ORDate != null ? date('F d, Y', strtotime($transaction->ORDate)) : '' }}</p>
           <p class="text-muted text-right no-pad">Cashier/Teller:</p>
           <p class="no-pad text-right">{{ $transaction->UserId != null ? Users::find($transaction->UserId)->name : '-' }}</p>
        </div>
    </div>
  
    <div class="divider"></div>
  
    <div style="padding: 10px 30px 15px 30px;">
        <span class="text-muted">Payment Particulars:</span>
        <br>
        <br>
        <table class="table">
            <tbody>
                @foreach ($transactionDetails as $item)
                    <tr>
                        <td>{{ $item->Particulars }}</td>
                        <td class="text-right">₱ {{ $item->Amount != null ? number_format($item->Amount, 2) : 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="no-pad text-right" style="margin-top: 15px;">Total Amount Paid</p>
        <h1 class="text-right">₱ {{ is_numeric($transaction->TotalAmountPaid) ? number_format($transaction->TotalAmountPaid, 2) : $transaction->TotalAmountPaid }}</h1>
    </div>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.location.href = "{{ route('transactions.miscellaneous-search') }}";
    }, 800);
</script>