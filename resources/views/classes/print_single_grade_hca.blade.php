@php
    use App\Models\Students;
    use App\Models\Subjects;
@endphp

<link rel="stylesheet" href="{{ URL::asset('css/style.css'); }}">

<style>
    /* @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    } */
    html, body, p, th {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .70em;
    }

    th,td {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .68em;
    }
    @media print {
        html, body {
            /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
            font-family: sans-serif;
            /* font-stretch: condensed; */
            font-size: .70em;
        }

        th,td {
            font-family: sans-serif;
            /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
            /* font-stretch: condensed; */
            /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
            font-size: .68em;
        }

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

    .half {
        display: inline-table; 
        width: 49%;
    }

    .twenty {
        display: inline-table; 
        width: 19.8%;
    }

    .sixty {
        display: inline-table; 
        width: 59.8%;
    }

    .sub-subject {
        padding-left: 15px;
    }

</style>

<div id="print-area" class="content half">
    <div style="width: 100%; display: flex;">
        <div class="twenty" style="display: flex; flex-wrap: wrap;">
            <img style="width: 48px; height: 48px; object-fit: cover;" src="{{ URL::asset('imgs/logo.png'); }}" alt="">
        </div>

        <div class="sixty">
            <p class="strong text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>    
            <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>

            <p class="text-center" style="margin-top: 10px;"><strong>GRADE STUB</strong></p>
            <p class="text-center">{{ $sy != null ? $sy->SchoolYear : '' }}</p>
        </div>
    </div>

    <table class="table table-borderless" style="margin-top: 15px;">
        <tr>
            <td>Student: </td>
            <td><strong>{{ Students::formatNameFormal($student) }}</strong></td>
            <td>LRN: </td>
            <td><strong>{{ $student != null ? $student->LRN : '-' }}</strong></td>
        </tr>
        <tr>
            <td>Grade Level/Section: </td>
            <td colspan="3"><strong>{{ $class != null ? $class->Year . '-' . $class->Section : '-' }}</strong></td>
        </tr>
    </table>

    {{-- GRADE --}}
    <table class="table table-bordered" style="margin-top: 10px;">
        <thead>
            <tr>
                <th style="font-size: .68em !important;" class="text-center" rowspan="2">SUBJECTS</th>
                <th style="font-size: .68em !important;" class="text-center" colspan="3">QUARTER</th>
                <th style="font-size: .68em !important;" class="text-center" rowspan="2">REMARKS</th>
            </tr>
            <tr>
                <th style="font-size: .68em !important;" class="text-center">1st</th>
                <th style="font-size: .68em !important;" class="text-center">2nd</th>
                <th style="font-size: .68em !important;" class="text-center">3rd</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sumFirst = 0;
                $sumSecond = 0;
                $sumThird = 0;

                /**
                 * REORGANIZE SUBJECTS FOR PARENT SUBJECTS
                 */
                $groupedSubjects = [];
                $mainSubjects = [];
                foreach ($data as $subject) {
                    if (is_null($subject->ParentSubject)) {
                        $mainSubjects[] = $subject; // Subjects without ParentSubject
                    } else {
                        // Group subjects with the same ParentSubject
                        if (!isset($groupedSubjects[$subject->ParentSubject])) {
                            $groupedSubjects[$subject->ParentSubject] = [];
                        }
                        $groupedSubjects[$subject->ParentSubject][] = $subject;
                    }
                }
                $mainSubjects[] = $groupedSubjects;
            @endphp
            {{-- @foreach ($data as $item)
                <tr>
                    <td>{{ strtoupper($item->Subject) }}</td>
                    <td class="text-right">{{ $item->FirstGradingGrade }}</td>
                    <td class="text-right">{{ $item->SecondGradingGrade }}</td>
                    <td class="text-right">{{ $item->ThirdGradingGrade }}</td>
                    <td>{{ $item->Notes }}</td>
                </tr>
                @php
                    $sumFirst += floatval($item->FirstGradingGrade);
                    $sumSecond += floatval($item->SecondGradingGrade);
                    $sumThird += floatval($item->ThirdGradingGrade);
                @endphp
            @endforeach
            @php
                $averageFirst = 0;
                $averageSecond = 0;
                $averageThird = 0;

                if ($sumFirst > 0 && count($data) > 0) {
                    $averageFirst = $sumFirst / count($data);
                }

                if ($sumSecond > 0 && count($data) > 0) {
                    $averageSecond = $sumSecond / count($data);
                }

                if ($sumThird > 0 && count($data) > 0) {
                    $averageThird = $sumThird / count($data);
                }
            @endphp
            <tr>
                <td><strong>TOTAL AVERAGE</strong></td>
                <td class="text-right"><strong>{{ number_format($averageFirst, 2) }}</strong></td>
                <td class="text-right"><strong>{{ number_format($averageSecond, 2) }}</strong></td>
                <td class="text-right"><strong>{{ number_format($averageThird, 2) }}</strong></td>
                <td></td>
            </tr> --}}
            @foreach ($mainSubjects as $subject)
                <!-- Main Subject Row -->
                @if (is_array($subject))
                    @foreach ($subject as $key => $item)
                        <tr>
                            <!-- Indented sub-subjects -->
                            <td><strong>{{ $key }}</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <!-- Sub-subjects (children) for each ParentSubject -->
                        @if (isset($groupedSubjects[$key]))
                            @foreach ($groupedSubjects[$key] as $subSubject)
                                <tr>
                                    <!-- Indented sub-subjects -->
                                    <td class="sub-subject">{{ $subSubject->Subject }}</td>
                                    <td class="text-right">{{ $subSubject->FirstGradingGrade }}</td>
                                    <td class="text-right">{{ $subSubject->SecondGradingGrade }}</td>
                                    <td class="text-right">{{ $subSubject->ThirdGradingGrade }}</td>
                                    <td>{{ $subSubject->Notes }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td>{{ $subject->Subject }}</td>
                        <td class="text-right">{{ $subject->FirstGradingGrade }}</td>
                        <td class="text-right">{{ $subject->SecondGradingGrade }}</td>
                        <td class="text-right">{{ $subject->ThirdGradingGrade }}</td>
                        <td>{{ $subject->Notes }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    {{-- adviser --}}
    <div style="width: 100%; margin-top: 30px;">
        <div class="half">
            @if ($adviser != null)
                <p class="text-center" style="margin: 0px 6px; border-bottom: 1px solid black;"><strong>{{ strtoupper($adviser->FullName) }}</strong></p>
                <p class="text-center">Class Adviser</p>
            @endif
        </div>

        <div class="half">
            <p class="text-center" style="margin: 0px 6px; border-bottom: 1px solid black;"><strong>{{ env("PRINCIPAL_NAME") }}</strong></p>
            <p class="text-center">Principal</p>
        </div>
    </div>
    
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
        // window.location.href = "{{ route('transactions.miscellaneous-search') }}";
    }, 800);
</script>