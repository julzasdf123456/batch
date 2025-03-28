@php
    use App\Models\Students;
@endphp

<link rel="stylesheet" href="{{ URL::asset('css/style.css'); }}">

<style>
    @font-face {
        font-family: 'Baskerville';
        src: url('/fonts/LibreBaskerville-Regular.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Oswald';
        src: url('/fonts/Oswald-VariableFont_wght.ttf') format('truetype');
    }

    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: 'sans-serif;'
        /* font-stretch: condensed; */
        font-size: .88em !important;
    }

    table tbody th,td,
    table thead th {
        font-family: 'sans-serif;'
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .70em;
    }
    @media print {
        @page {
            /* margin: 10px; */
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

        .content {
            page-break-before: always; /* Old syntax */
            break-before: page;
            width: 100%;
            display: flex;
            flex-direction: row;
            padding: 20px 30px;
        }

        .half {
            width: 50%;
        }
    }

    .content {
        page-break-before: always; /* Old syntax */
        break-before: page;
        width: 94%;
        display: flex;
        flex-direction: row;
        padding: 20px 30px;
    }

    .half {
        width: 48.8%;
    }

    /**
    * START NOT ON PRINT
    */
    .column {
        display: flex;
        flex-direction: column;
    }

    .row {
        display: flex;
        flex-direction: row;
    }

    .space-between {
        justify-content: space-between;
    }

    .v-center {
        align-items: center;
    }

    .h-center {
        justify-content: center;
    }

    .vh-center {
        align-items: center;
        justify-content: center;
    }

    .gap-5 {
        gap: 5px;
    }

    .gap-10 {
        gap: 10px;
    }

    .gap-15 {
        gap: 15px;
    }

    .gap-20 {
        gap: 20px;
    }

    .gap-30 {
        gap: 30px;
    }

    .record-header {
        font-family: 'Baskerville', Times, serif;
        font-size: 1.2em;
        font-weight: bold;
    }

    .record-header-normal {
        font-family: 'Baskerville', Times, serif;
        font-size: 1.2em;
        font-weight: normal;
    }

    .baskerville {
        font-family: 'Baskerville', Times, serif;
    }

    .oswald {
        font-family: 'Oswald', sans-serif;
    }

    .mt-1 {
        margin-top: 6px;
    }

    .mt-2 {
        margin-top: 10px;
    }

    .mt-3 {
        margin-top: 16px;
    }

    .mt-4 {
        margin-top: 24px;
    }

    .mt-50 {
        margin-top: 52px;
    }

    .border-bottom {
        border-bottom: 1px solid black;
    }

    /**
    * END NOT ON PRINT
    */

    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

    p {
        padding: 0px !important;
        margin: 0px;
    }

    td, tr {
        padding: 4px 6px;
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

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-md {
        width: 60%;
        margin: 0 auto;
        border-collapse: collapse;
    }

    .table-bordered td,
    .table-bordered th {
        border: 1px solid black;
    }

    .table-borderless td,
    .table-borderless tr,
    .table-borderless th {
        border: none;
        outline: none;
    }

</style>

@foreach ($students as $student)
<div id="print-area">
    {{-- PAGE 1 --}}
    <div class="content gap-30">
        {{-- LEFT --}}
        <div class="half column">
            {{-- REPORT ON ATTENDANCE --}}
            <div class="column">
                <h2 class="text-center record-header">REPORT ON ATTENDANCE</h2>
                <table class="table table-bordered baskerville">
                    <tbody>
                        <tr>
                            <td></td>
                            <td class="text-center">Aug</td>
                            <td class="text-center">Sept</td>
                            <td class="text-center">Oct</td>
                            <td class="text-center">Nov</td>
                            <td class="text-center">Dec</td>
                            <td class="text-center">Jan</td>
                            <td class="text-center">Feb</td>
                            <td class="text-center">Mar</td>
                            <td class="text-center">Apr</td>
                            <td class="text-center">May</td>
                            <td class="text-center">Total</td>
                        </tr>
                        <tr>
                            <td>No. of
                                school
                                days</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>No. of
                                days
                                present</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>No. of
                                days
                                absent</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <p class="baskerville mt-4">Lack unit/s in: ______________________________</p>
                <p class="baskerville mt-1">Advance unit/s in: ___________________________</p>
                <p class="baskerville mt-1">Eligible for transfer to: ________________________</p>
            </div>

            {{-- PARENT/GUARDIAN SIGNATURE --}}
            <div class="column mt-50">
                <h2 class="text-center record-header">PARENT / GUARDIAN SIGNATURE</h2>

                <div class="row gap-20">
                    <div style="flex: none;">
                        <p class="record-header-normal">1<sup>st</sup> Quarter</p>
                    </div>
                    <div class="border-bottom" style="height: 100%; flex: 1;"></div>
                </div>

                <div class="row gap-20 mt-3">
                    <div style="flex: none;">
                        <p class="record-header-normal">2<sup>nd</sup> Quarter</p>
                    </div>
                    <div class="border-bottom" style="height: 100%; flex: 1;"></div>
                </div>

                <div class="row gap-20 mt-3">
                    <div style="flex: none;">
                        <p class="record-header-normal">3<sup>rd</sup> Quarter</p>
                    </div>
                    <div class="border-bottom" style="height: 100%; flex: 1;"></div>
                </div>

                <div class="row gap-20 mt-3">
                    <div style="flex: none;">
                        <p class="record-header-normal">4<sup>th</sup> Quarter</p>
                    </div>
                    <div class="border-bottom" style="height: 100%; flex: 1;"></div>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="half column">
            <div class="row space-between gap-20 v-center">
                <img style="width: 72px; height: 72px; object-fit: cover;" src="{{ URL::asset('imgs/deped-logo.svg'); }}" alt="">

                <div class="column h-center v-center">
                    <h2 class="oswald" style="font-weight: bold; font-size: 1.1em; padding: 0; margin: 0;">BOHOL ASSOCIATION OF CATHOLIC SCHOOLS</h2>
                    <h2 style="font-weight: bold; font-size: 1.1em; padding: 0; margin: 0;">DIOCESE OF TAGBILARAN</h2>
                    <h2 class="oswald" style="font-weight: bold; font-size: 1.3em; padding: 0; margin: 0;">HOLY CROSS ACADEMY OF TUBIGON, INC.</h2>
                </div>

                <img style="width: 72px; height: 72px; object-fit: cover;" src="{{ URL::asset('imgs/logo.png'); }}" alt="">
            </div>
        </div>
    </div>
</div>
@endforeach

<script type="text/javascript">
    // window.print();

    // window.setTimeout(function(){
    //     window.history.go(-1)
    //     // window.location.href = "{{ route('transactions.miscellaneous-search') }}";
    // }, 800);
</script>