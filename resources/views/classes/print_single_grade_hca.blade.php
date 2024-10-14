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
                $data = json_decode($data, true);
                $sumFirst = 0;
                $sumSecond = 0;
                $sumThird = 0;

                /**
                 * REORGANIZE SUBJECTS FOR PARENT SUBJECTS
                 * ==============================================
                 */
                $groupedSubjects = [];
                $mainSubjects = [];

                // Collect ParentSubjects for later insertion
                $parentSubjects = [];

                foreach ($data as $subject) {
                    if (is_null($subject["ParentSubject"])) {
                        $mainSubjects[] = $subject; // Main subjects (ParentSubject is null)
                    } else {
                        // Group subjects by their ParentSubject
                        if (!isset($groupedSubjects[$subject["ParentSubject"]])) {
                            $groupedSubjects[$subject["ParentSubject"]] = [];
                        }
                        $groupedSubjects[$subject["ParentSubject"]][] = $subject; // Sub-subjects

                        // Track unique ParentSubjects for later insertion
                        if (!in_array($subject["ParentSubject"], $parentSubjects)) {
                            $parentSubjects[] = $subject["ParentSubject"];
                        }
                    }
                }

                // Step 2: Add missing ParentSubjects as main subjects (these will be empty placeholders)
                foreach ($parentSubjects as $parentSubject) {
                    // add average grades 
                    $subs = $groupedSubjects[$parentSubject];
                    $fGradeSum = 0;
                    $sGradeSum = 0;
                    $tGradeSum = 0;
                    $fGradeAve = 0;
                    $sGradeAve = 0;
                    $tGradeAve = 0;
                    if ($subs != null) {
                        foreach ($subs as $item) {
                            $fGradeSum += floatval($item['FirstGradingGrade'] != null ? $item['FirstGradingGrade'] : 0);
                            $sGradeSum += floatval($item['SecondGradingGrade'] != null ? $item['SecondGradingGrade'] : 0);
                            $tGradeSum += floatval($item['ThirdGradingGrade'] != null ? $item['ThirdGradingGrade'] : 0);
                        }

                        $fGradeAve = $fGradeSum > 0 ? ($fGradeSum / count($subs)) : 0;
                        $sGradeAve = $sGradeSum > 0 ? ($sGradeSum / count($subs)) : 0;
                        $tGradeAve = $tGradeSum > 0 ? ($tGradeSum / count($subs)) : 0;
                    }

                    $mainSubjects[] = [
                        "Subject" => $parentSubject,   // The parent name
                        "FullName" => "",              // Leave blank as there's no teacher for parent
                        "Heirarchy" => $groupedSubjects[$parentSubject][0]["Heirarchy"], // Sort by first child's Heirarchy
                        "id" => null,
                        "StudentId" => null,
                        "SubjectId" => null,
                        "ClassId" => null,
                        "TeacherId" => null,
                        "FirstGradingGrade" => number_format($fGradeAve),
                        "SecondGradingGrade" => number_format($sGradeAve),
                        "ThirdGradingGrade" => number_format($tGradeAve),
                        "FourthGradingGrade" => null,
                        "AverageGrade" => null,
                        "Notes" => null,
                        "created_at" => null,
                        "updated_at" => null,
                        "Visibility" => "FREAKING PARENT",
                    ];
                }

                // Step 3: Sort the main subjects by 'Heirarchy'
                usort($mainSubjects, function($a, $b) {
                    return $a['Heirarchy'] <=> $b['Heirarchy'];
                });

                // Step 4: Sort the sub-subjects within each parent group by 'Heirarchy'
                foreach ($groupedSubjects as &$subSubjects) {
                    usort($subSubjects, function($a, $b) {
                        return $a['Heirarchy'] <=> $b['Heirarchy'];
                    });
                }
                unset($subSubjects); // Break the reference
            @endphp
            @foreach ($mainSubjects as $subject)
                <!-- Main Subject Row -->
                <tr>
                    @if ($subject['Visibility'] === 'FREAKING PARENT')
                        <td><strong><i>{{ $subject['Subject'] }}<i></strong></td>
                        <td class="text-right"><strong><i>{{ number_format($subject['FirstGradingGrade']) }}<i></strong></td>
                        <td class="text-right"><strong><i>{{ number_format($subject['SecondGradingGrade']) }}<i></strong></td>
                        <td class="text-right"><strong><i>{{ number_format($subject['ThirdGradingGrade']) }}<i></strong></td>
                        <td>{{ $subject['Notes'] }}</td>
                    @else
                        <td>{{ $subject['Subject'] }}</td>
                        <td class="text-right">{{ number_format($subject['FirstGradingGrade']) }}</td>
                        <td class="text-right">{{ number_format($subject['SecondGradingGrade']) }}</td>
                        <td class="text-right">{{ number_format($subject['ThirdGradingGrade']) }}</td>
                        <td>{{ $subject['Notes'] }}</td>

                        @php
                            $sumFirst += floatval($subject['FirstGradingGrade'] != null ? $subject['FirstGradingGrade'] : 0);
                            $sumSecond += floatval($subject['SecondGradingGrade'] != null ? $subject['SecondGradingGrade'] : 0);
                            $sumThird += floatval($subject['ThirdGradingGrade'] != null ? $subject['ThirdGradingGrade'] : 0);
                        @endphp
                    @endif
                </tr>

                <!-- Check if the subject has child subjects and display them -->
                @if (isset($groupedSubjects[$subject['Subject']]))
                    @foreach ($groupedSubjects[$subject['Subject']] as $subSubject)
                        <tr>
                            <!-- Indented sub-subjects -->
                            <td class="sub-subject">{{ $subSubject['Subject'] }}</td>
                            <td class="text-right">{{ number_format($subSubject['FirstGradingGrade']) }}</td>
                            <td class="text-right">{{ number_format($subSubject['SecondGradingGrade']) }}</td>
                            <td class="text-right">{{ number_format($subSubject['ThirdGradingGrade']) }}</td>
                            <td>{{ $subSubject['Notes'] }}</td>
                        </tr>
                        @php
                            $sumFirst += floatval($subSubject['FirstGradingGrade'] != null ? $subSubject['FirstGradingGrade'] : 0);
                            $sumSecond += floatval($subSubject['SecondGradingGrade'] != null ? $subSubject['SecondGradingGrade'] : 0);
                            $sumThird += floatval($subSubject['ThirdGradingGrade'] != null ? $subSubject['ThirdGradingGrade'] : 0);
                        @endphp
                    @endforeach
                @endif
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
                <td class="text-right"><strong>{{ number_format($averageFirst) }}</strong></td>
                <td class="text-right"><strong>{{ number_format($averageSecond) }}</strong></td>
                <td class="text-right"><strong>{{ number_format($averageThird) }}</strong></td>
                <td></td>
            </tr>
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