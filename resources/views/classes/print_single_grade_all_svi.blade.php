@php
    use App\Models\Students;
    use App\Models\Subjects;
@endphp

<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">

<style>
    /* @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    } */
    html,
    body,
    p,
    th {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .70em;
    }

    th,
    td {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .68em;
    }

    @media print {

        html,
        body {
            /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
            font-family: sans-serif;
            /* font-stretch: condensed; */
            font-size: .70em;
        }

        th,
        td {
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

    td,
    tr {
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
        width: 48%;
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

@foreach ($students as $student)
    <div id="print-area" class="content" style="height: 48vh; margin-top: 20px; padding-left: 6px; padding-right: 6px;">
        <div style="width: 100%; display: flex;">
            <div class="twenty" style="display: flex; flex-wrap: wrap;">
                <img style="width: 48px; height: 48px; object-fit: cover;" src="{{ URL::asset('imgs/logo.png') }}"
                    alt="">
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
                <td colspan="3"><strong>{{ Students::formatNameFormal($student) }}</strong></td>
            </tr>
            <tr>
                <td>Grade Level/Section: </td>
                <td><strong>{{ $class != null ? $class->Year . '-' . $class->Section : '-' }}</strong></td>
                <td>LRN: </td>
                <td><strong>{{ $student != null ? $student->LRN : '-' }}</strong></td>
            </tr>
        </table>

        {{-- GRADE --}}
        <table class="table table-bordered" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th style="font-size: .68em !important;" class="text-center" rowspan="2">SUBJECTS</th>
                    <th style="font-size: .68em !important;" class="text-center"
                        colspan="{{ $gradingPeriod === 'All' ? '4' : '1' }}">QUARTER</th>
                    <th style="font-size: .68em !important;" class="text-center" rowspan="2">REMARKS</th>
                    <th style="font-size: .68em !important;" class="text-center" rowspan="2">TEACHER</th>
                </tr>
                <tr>
                    @if ($gradingPeriod === 'First')
                        <th style="font-size: .68em !important;" class="text-center">1st</th>
                    @elseif ($gradingPeriod === 'Second')
                        <th style="font-size: .68em !important;" class="text-center">2nd</th>
                    @elseif ($gradingPeriod === 'Third')
                        <th style="font-size: .68em !important;" class="text-center">3rd</th>
                    @elseif ($gradingPeriod === 'Fourth')
                        <th style="font-size: .68em !important;" class="text-center">4th</th>
                    @elseif ($gradingPeriod === 'All')
                        <th style="font-size: .68em !important;" class="text-center">1st</th>
                        <th style="font-size: .68em !important;" class="text-center">2nd</th>
                        <th style="font-size: .68em !important;" class="text-center">3rd</th>
                        <th style="font-size: .68em !important;" class="text-center">4th</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $sumFirst = 0;
                    $sumSecond = 0;
                    $sumThird = 0;
                    $sumFourth = 0;

                    $countFirst = 0;
                    $countSecond = 0;
                    $countThird = 0;
                    $countFourth = 0;

                    $data = $student->GradeData;
                    $data = json_decode($data, true);

                    /**
                     * REORGANIZE SUBJECTS FOR PARENT SUBJECTS
                     * ==============================================
                     */
                    $groupedSubjects = [];
                    $mainSubjects = [];

                    // Collect ParentSubjects for later insertion
                    $parentSubjects = [];

                    foreach ($data as $subject) {
                        if (is_null($subject['ParentSubject'])) {
                            $mainSubjects[] = $subject; // Main subjects (ParentSubject is null)
                        } else {
                            // Group subjects by their ParentSubject
                            if (!isset($groupedSubjects[$subject['ParentSubject']])) {
                                $groupedSubjects[$subject['ParentSubject']] = [];
                            }
                            $groupedSubjects[$subject['ParentSubject']][] = $subject; // Sub-subjects

                            // Track unique ParentSubjects for later insertion
                            if (!in_array($subject['ParentSubject'], $parentSubjects)) {
                                $parentSubjects[] = $subject['ParentSubject'];
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
                        $ftGradeSum = 0;
                        $avgGradeSum = 0;
                        $fGradeAve = 0;
                        $sGradeAve = 0;
                        $tGradeAve = 0;
                        $ftGradeAve = 0;
                        $avgGradeAve = 0;
                        if ($subs != null) {
                            foreach ($subs as $item) {
                                $fGradeSum += floatval(
                                    $item['FirstGradingGrade'] != null ? $item['FirstGradingGrade'] : 0,
                                );
                                $sGradeSum += floatval(
                                    $item['SecondGradingGrade'] != null ? $item['SecondGradingGrade'] : 0,
                                );
                                $tGradeSum += floatval(
                                    $item['ThirdGradingGrade'] != null ? $item['ThirdGradingGrade'] : 0,
                                );
                                $ftGradeSum += floatval(
                                    $item['FourthGradingGrade'] != null ? $item['FourthGradingGrade'] : 0,
                                );
                                $avgGradeSum += floatval($item['AverageGrade'] != null ? $item['AverageGrade'] : 0);
                            }

                            $fGradeAve = $fGradeSum > 0 ? $fGradeSum / count($subs) : 0;
                            $sGradeAve = $sGradeSum > 0 ? $sGradeSum / count($subs) : 0;
                            $tGradeAve = $tGradeSum > 0 ? $tGradeSum / count($subs) : 0;
                            $ftGradeAve = $ftGradeSum > 0 ? $ftGradeSum / count($subs) : 0;
                            $avgGradeAve = $avgGradeSum > 0 ? $avgGradeSum / count($subs) : 0;
                        }

                        $mainSubjects[] = [
                            'Subject' => $parentSubject, // The parent name
                            'FullName' => '', // Leave blank as there's no teacher for parent
        'Heirarchy' => $groupedSubjects[$parentSubject][0]['Heirarchy'], // Sort by first child's Heirarchy
                            'id' => null,
                            'StudentId' => null,
                            'SubjectId' => null,
                            'ClassId' => null,
                            'TeacherId' => null,
                            'FullName' => null,
                            'FirstGradingGrade' => number_format($fGradeAve),
                            'SecondGradingGrade' => number_format($sGradeAve),
                            'ThirdGradingGrade' => number_format($tGradeAve),
                            'FourthGradingGrade' => number_format($ftGradeAve),
                            'AverageGrade' => number_format($avgGradeAve),
                            'Notes' => null,
                            'created_at' => null,
                            'updated_at' => null,
                            'Visibility' => 'FREAKING PARENT',
                        ];
                    }

                    // Step 3: Sort the main subjects by 'Heirarchy'
                    usort($mainSubjects, function ($a, $b) {
                        return $a['Heirarchy'] <=> $b['Heirarchy'];
                    });

                    // Step 4: Sort the sub-subjects within each parent group by 'Heirarchy'
                    foreach ($groupedSubjects as &$subSubjects) {
                        usort($subSubjects, function ($a, $b) {
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

                            @if (in_array($subject['Subject'], $avgParents))
                                {{-- CHECK IF PARENT SUBJECT IS AVERAGED, DISPLAY AVERAGE GRADE --}}
                                @if ($gradingPeriod === 'First')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subject['FirstGradingGrade']) }}<i></strong>
                                    </td>
                                @elseif ($gradingPeriod === 'Second')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subject['SecondGradingGrade']) }}<i></strong>
                                    </td>
                                @elseif ($gradingPeriod === 'Third')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subject['ThirdGradingGrade']) }}<i></strong>
                                    </td>
                                @elseif ($gradingPeriod === 'Fourth')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subject['FourthGradingGrade']) }}<i></strong>
                                    </td>
                                @elseif ($gradingPeriod === 'All')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subject['FirstGradingGrade']) }}<i></strong>
                                    </td>
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subject['SecondGradingGrade']) }}<i></strong>
                                    </td>
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subject['ThirdGradingGrade']) }}<i></strong>
                                    </td>
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subject['FourthGradingGrade']) }}<i></strong>
                                    </td>
                                @endif
                                <td class="text-center">{{ Subjects::checkPass($subject['AverageGrade']) }}</td>
                            @else
                                {{-- CHECK IF PARENT SUBJECT IS AVERAGED, DISPLAY NOTHING IF NOT --}}
                                @if ($gradingPeriod === 'First')
                                    <td class="text-center"></td>
                                @elseif ($gradingPeriod === 'Second')
                                    <td class="text-center"></td>
                                @elseif ($gradingPeriod === 'Third')
                                    <td class="text-center"></td>
                                @elseif ($gradingPeriod === 'Fourth')
                                    <td class="text-center"></td>
                                @elseif ($gradingPeriod === 'All')
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                @endif
                                <td></td>
                            @endif
                            <td></td>
                        @else
                            <td>{{ $subject['Subject'] }}</td>
                            @if ($gradingPeriod === 'First')
                                <td class="text-center">
                                    <strong><i>{{ Subjects::validateGrade($subject['FirstGradingGrade']) }}<i></strong>
                                </td>
                            @elseif ($gradingPeriod === 'Second')
                                <td class="text-center">
                                    <strong><i>{{ Subjects::validateGrade($subject['SecondGradingGrade']) }}<i></strong>
                                </td>
                            @elseif ($gradingPeriod === 'Third')
                                <td class="text-center">
                                    <strong><i>{{ Subjects::validateGrade($subject['ThirdGradingGrade']) }}<i></strong>
                                </td>
                            @elseif ($gradingPeriod === 'Fourth')
                                <td class="text-center">
                                    <strong><i>{{ Subjects::validateGrade($subject['FourthGradingGrade']) }}<i></strong>
                                </td>
                            @elseif ($gradingPeriod === 'All')
                                <td class="text-center">
                                    <strong><i>{{ Subjects::validateGrade($subject['FirstGradingGrade']) }}<i></strong>
                                </td>
                                <td class="text-center">
                                    <strong><i>{{ Subjects::validateGrade($subject['SecondGradingGrade']) }}<i></strong>
                                </td>
                                <td class="text-center">
                                    <strong><i>{{ Subjects::validateGrade($subject['ThirdGradingGrade']) }}<i></strong>
                                </td>
                                <td class="text-center">
                                    <strong><i>{{ Subjects::validateGrade($subject['FourthGradingGrade']) }}<i></strong>
                                </td>
                            @endif
                            <td class="text-center">{{ Subjects::checkPass($subject['AverageGrade']) }}</td>
                            <td>{{ $subject['FullName'] }}</td>

                            @php
                                $sumFirst += floatval(
                                    $subject['FirstGradingGrade'] != null && is_numeric($subject['FirstGradingGrade'])
                                        ? $subject['FirstGradingGrade']
                                        : 0,
                                );
                                $sumSecond += floatval(
                                    $subject['SecondGradingGrade'] != null &&
                                    is_numeric($subject['SecondGradingGrade']) != null
                                        ? $subject['SecondGradingGrade']
                                        : 0,
                                );
                                $sumThird += floatval(
                                    $subject['ThirdGradingGrade'] != null &&
                                    is_numeric($subject['ThirdGradingGrade']) != null
                                        ? $subject['ThirdGradingGrade']
                                        : 0,
                                );
                                $sumFourth += floatval(
                                    $subject['FourthGradingGrade'] != null && is_numeric($subject['FourthGradingGrade'])
                                        ? $subject['FourthGradingGrade']
                                        : 0,
                                );

                                // get total subject count
                                $countFirst =
                                    $subject['FirstGradingGrade'] != null && is_numeric($subject['FirstGradingGrade'])
                                        ? $countFirst + 1
                                        : $countFirst;
                                $countSecond =
                                    $subject['SecondGradingGrade'] != null && is_numeric($subject['SecondGradingGrade'])
                                        ? $countSecond + 1
                                        : $countSecond;
                                $countThird =
                                    $subject['ThirdGradingGrade'] != null && is_numeric($subject['ThirdGradingGrade'])
                                        ? $countThird + 1
                                        : $countThird;
                                $countFourth =
                                    $subject['FourthGradingGrade'] != null && is_numeric($subject['FourthGradingGrade'])
                                        ? $countFourth + 1
                                        : $countFourth;
                            @endphp
                        @endif
                    </tr>

                    <!-- Check if the subject has child subjects and display them -->
                    @if (isset($groupedSubjects[$subject['Subject']]))
                        @foreach ($groupedSubjects[$subject['Subject']] as $subSubject)
                            <tr>
                                <!-- Indented sub-subjects -->
                                <td class="sub-subject">{{ $subSubject['Subject'] }}</td>
                                @if ($gradingPeriod === 'First')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subSubject['FirstGradingGrade']) }}<i></strong>
                                    </td>
                                @elseif ($gradingPeriod === 'Second')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subSubject['SecondGradingGrade']) }}<i></strong>
                                    </td>
                                @elseif ($gradingPeriod === 'Third')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subSubject['ThirdGradingGrade']) }}<i></strong>
                                    </td>
                                @elseif ($gradingPeriod === 'Fourth')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subSubject['FourthGradingGrade']) }}<i></strong>
                                    </td>
                                @elseif ($gradingPeriod === 'All')
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subSubject['FirstGradingGrade']) }}<i></strong>
                                    </td>
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subSubject['SecondGradingGrade']) }}<i></strong>
                                    </td>
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subSubject['ThirdGradingGrade']) }}<i></strong>
                                    </td>
                                    <td class="text-center">
                                        <strong><i>{{ Subjects::validateGrade($subSubject['FourthGradingGrade']) }}<i></strong>
                                    </td>
                                @endif
                                <td class="text-center">{{ Subjects::checkPass($subSubject['AverageGrade']) }}</td>
                                <td>{{ $subSubject['FullName'] }}</td>
                            </tr>
                            @php
                                $sumFirst += floatval(
                                    $subSubject['FirstGradingGrade'] != null &&
                                    is_numeric($subSubject['FirstGradingGrade'])
                                        ? $subSubject['FirstGradingGrade']
                                        : 0,
                                );
                                $sumSecond += floatval(
                                    $subSubject['SecondGradingGrade'] != null &&
                                    is_numeric($subSubject['SecondGradingGrade'])
                                        ? $subSubject['SecondGradingGrade']
                                        : 0,
                                );
                                $sumThird += floatval(
                                    $subSubject['ThirdGradingGrade'] != null &&
                                    is_numeric($subSubject['ThirdGradingGrade'])
                                        ? $subSubject['ThirdGradingGrade']
                                        : 0,
                                );
                                $sumFourth += floatval(
                                    $subSubject['FourthGradingGrade'] != null &&
                                    is_numeric($subSubject['FourthGradingGrade'])
                                        ? $subSubject['FourthGradingGrade']
                                        : 0,
                                );

                                // get total subject count
                                $countFirst =
                                    $subSubject['FirstGradingGrade'] != null &&
                                    is_numeric($subSubject['FirstGradingGrade'])
                                        ? $countFirst + 1
                                        : $countFirst;
                                $countSecond =
                                    $subSubject['SecondGradingGrade'] != null &&
                                    is_numeric($subSubject['SecondGradingGrade'])
                                        ? $countSecond + 1
                                        : $countSecond;
                                $countThird =
                                    $subSubject['ThirdGradingGrade'] != null &&
                                    is_numeric($subSubject['ThirdGradingGrade'])
                                        ? $countThird + 1
                                        : $countThird;
                                $countFourth =
                                    $subSubject['FourthGradingGrade'] != null &&
                                    is_numeric($subSubject['FourthGradingGrade'])
                                        ? $countFourth + 1
                                        : $countFourth;
                            @endphp
                        @endforeach
                    @endif
                @endforeach
                @php
                    $averageFirst = 0;
                    $averageSecond = 0;
                    $averageThird = 0;
                    $averageFourth = 0;

                    if ($sumFirst > 0 && $countFirst > 0) {
                        $averageFirst = $sumFirst / $countFirst;
                    }

                    if ($sumSecond > 0 && $countSecond > 0) {
                        $averageSecond = $sumSecond / $countSecond;
                    }

                    if ($sumThird > 0 && $countThird > 0) {
                        $averageThird = $sumThird / $countThird;
                    }

                    if ($sumFourth > 0 && $countFourth > 0) {
                        $averageFourth = $sumFourth / $countFourth;
                    }
                @endphp
                <tr>
                    <td><strong>TOTAL AVERAGE</strong></td>
                    @if ($gradingPeriod === 'First')
                        <td class="text-center"><strong>{{ number_format($averageFirst, 2) }}</strong></td>
                    @elseif ($gradingPeriod === 'Second')
                        <td class="text-center"><strong>{{ number_format($averageFirst, 2) }}</strong></td>
                    @elseif ($gradingPeriod === 'Third')
                        <td class="text-center"><strong>{{ number_format($averageThird, 2) }}</strong></td>
                    @elseif ($gradingPeriod === 'Fourth')
                        <td class="text-center"><strong>{{ number_format($averageFourth, 2) }}</strong></td>
                    @elseif ($gradingPeriod === 'All')
                        <td class="text-center"><strong>{{ number_format($averageFirst, 2) }}</strong></td>
                        <td class="text-center"><strong>{{ number_format($averageSecond, 2) }}</strong></td>
                        <td class="text-center"><strong>{{ number_format($averageThird, 2) }}</strong></td>
                        <td class="text-center"><strong>{{ number_format($averageFourth, 2) }}</strong></td>
                    @endif

                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        {{-- adviser --}}
        <div style="width: 100%; height: 100px; display: flex; flex-direction: row;">
            <div class="half"
                style="display: flex; flex-direction: column; justify-content: end; align-items: center;">
                @if ($adviser != null)
                    <p class="text-center" style="margin: 0px 6px; border-bottom: 1px solid black;">
                        <strong>{{ strtoupper($adviser->FullName) }}</strong>
                    </p>
                    <p class="text-center">Class Adviser</p>
                @endif
            </div>

            <div class="half"
                style="display: flex; flex-direction: column; justify-content: end; align-items: center;">
                @if (env('APP_COMPANY_ABRV') === 'SVI')
                    <div style="display: flex; justify-content: center; align-items: center; margin-top: 20px;">
                        <img src="{{ URL::asset('imgs/signatures/svi-principal.png') }}" alt=""
                            style="width: 90px; margin-bottom: -20px;">
                    </div>
                @endif
                <p class="text-center" style="margin: 0px 6px; border-bottom: 1px solid black;">
                    <strong>{{ env('PRINCIPAL_NAME') }}, MA-English</strong>
                </p>
                <p class="text-center">School Principal</p>
            </div>
        </div>
    </div>
@endforeach
<script type="text/javascript">
    window.print();

    window.setTimeout(function() {
        window.history.go(-1)
        // window.location.href = "{{ route('transactions.miscellaneous-search') }}";
    }, 800);
</script>
