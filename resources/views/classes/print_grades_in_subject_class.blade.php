@php
    use App\Models\Students;
@endphp

<link rel="stylesheet" href="{{ URL::asset('css/style.css'); }}">

<style>
    /* @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    } */
    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .85em;
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
    }  
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

<div id="print-area" class="content">
    <p class="strong text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>    
    <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>

    <p class="text-center" style="margin-top: 10px;"><strong>SUBJECT GRADING MATERIAL</strong></p>
    <p class="text-center">{{ $subject != null ? $subject->Subject : '' }} • {{ $class != null ? $class->Year . '-' . $class->Section : '-' }} {{ ($class->Strand != null ? ' • ' . $class->Strand : '') . ($class->Semester != null ? '-' . $class->Semester . ' Semester' : '') }}</p>
    <p class="text-center">{{ $sy != null ? $sy->SchoolYear : '' }}</p>

    {{-- GRADES --}}
    <table class="table table-bordered" style="margin-top: 10px;">
        <thead>
            <tr>
                <th rowspan="2"></th>
                <th class="text-center" rowspan="2">STUDENT</th>
                <th class="text-center" colspan="4">QUARTER</th>
                <th class="text-center" rowspan="2">FINAL GRADE</th>
                <th class="text-center" rowspan="2">REMARKS</th>
            </tr>
            <tr>
                <th class="text-center">1st</th>
                <th class="text-center">2nd</th>
                <th class="text-center">3rd</th>
                <th class="text-center">4th</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ strtoupper(Students::formatNameFormal($item)) }}</td>
                    <td class="text-right">{{ $item->FirstGradingGrade }}</td>
                    <td class="text-right">{{ $item->SecondGradingGrade }}</td>
                    <td class="text-right">{{ $item->ThirdGradingGrade }}</td>
                    <td class="text-right">{{ $item->FourthGradingGrade }}</td>
                    <td class="text-right"><strong>{{ $item->AverageGrade }}</strong></td>
                    <td>{{ $item->Notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- DESCRIPTORS --}}
    <table class="table-md table-borderless" style="margin-top: 20px; padding: 0px 20px;">
        <thead>
            <th class="text-left">Descriptors</th>
            <th class="text-left">Grading Scale</th>
            <th class="text-left">Remarks</th>
        </thead>
        <tbody>
            <tr>
                <td>Outstanding</td>
                <td>90 - 100</td>
                <td>Passed</td>
            </tr>
            <tr>
                <td>Very Satisfactory</td>
                <td>85 - 89</td>
                <td>Passed</td>
            </tr>
            <tr>
                <td>Satisfactory</td>
                <td>80 - 84</td>
                <td>Passed</td>
            </tr>
            <tr>
                <td>Fairly Satisfactory</td>
                <td>75 - 79</td>
                <td>Passed</td>
            </tr>
            <tr>
                <td>Did Not Meet Expectations</td>
                <td>Below 75</td>
                <td>Failed</td>
            </tr>
        </tbody>
    </table>

    {{-- adviser --}}
    <div style="margin-top: 70px;">
        @if ($teacher != null)
            <p class="text-center" style="width: 36%; padding-left: 20px; border-bottom: 1px solid black;"><strong>{{ strtoupper($teacher->FullName) }}</strong></p>
            <p style="width: 36%;" class="text-center">Class Instructor</p>
        @endif
    </div>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
        // window.location.href = "{{ route('transactions.miscellaneous-search') }}";
    }, 800);
</script>