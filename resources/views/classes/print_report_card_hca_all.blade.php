@php
    use App\Models\Students;
    use App\Models\Classes;
    use App\Models\Subjects;
@endphp

<link rel="stylesheet" href="{{ URL::asset('css/print_report_card_hca_all.css') }}">

@foreach ($students as $student)
<div id="print-area">
    {{-- 
    ===================================================================================================>
            PAGE 1 
    ===================================================================================================>
    --}}
    <div class="content gap-50">
        {{-- LEFT --}}
        <div class="half column">
            {{-- REPORT ON LEARNERS --}}
            <div class="column">
                <h2 class="text-center record-header">REPORT ON LEARNER’S OBSERVED VALUES</h2>

                <table class="table table-bordered mt-2 text-xl">
                    <thead>
                        <tr>
                            <th style="font-size: .68em !important;" class="bg-gray text-center" rowspan="2">Core Values</th>
                            <th style="font-size: .68em !important;" class="bg-gray text-center" rowspan="2">Behavior Statements</th>
                            <th style="font-size: .68em !important;" class="bg-gray text-center" colspan="4">Quarter</th>
                        </tr>
                        <tr>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">1</th>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">2</th>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">3</th>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="v-align" rowspan="2">1. Maka-Diyos (Integrity)</td>
                            <td class="v-align">Expresses one’s spiritual
                                beliefs while respecting the
                                spiritual beliefs of others</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="v-align">Shows adherence to ethical
                                principles by upholding
                                truth</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="v-align" rowspan="2">2. Makatao (Compassion)</td>
                            <td class="v-align">Is sensitive to individual,
                                social and cultural
                                difference</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="v-align">Demonstrates
                                contributions towards
                                solidarity</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="v-align">3. Makakalikasan</td>
                            <td class="v-align">Cares for the environment
                                and utilizes resources
                                wisely, judiciously, and
                                economically</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>

                            <td class="v-align" rowspan="2">4. Makabansa</td>
                            <td class="v-align">Demonstrates pride in
                                being a Filipino; exercises
                                the rights and
                                responsibilities of a Filipino
                                citizen</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="v-align">Demonstrates appropriate
                                behavior in carrying out
                                activities in the school,
                                community, and country</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="v-align">4. Industry</td>
                            <td class="v-align">Demonstrates diligence and initiative in doing tasks in school and in the community</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- MARKING --}}
            <div class="row mt-1">
                <div class="column w-50">
                    <p class="text-center"><strong>Marking</strong></p>
                    <p class="text-center">AO</p>
                    <p class="text-center">SO</p>
                    <p class="text-center">RO</p>
                    <p class="text-center">NO</p>
                </div>

                <div class="column w-50">
                    <p><strong>Non-numerical Rating</strong></p>
                    <p>Always Observed</p>
                    <p>Sometimes Observed</p>
                    <p>Rarely Observed</p>
                    <p>Not Observed</p>
                </div>
            </div>

            {{-- REPORT ON ATTENDANCE --}}
            <div class="column mt-2">
                <h2 class="text-center record-header">REPORT ON ATTENDANCE</h2>
                <table class="table table-bordered mt-1 text-xl">
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
            </div>

            {{-- PARENT/GUARDIAN SIGNATURE --}}
            <div class="column" style="width: 70%; margin: 10px auto;">
                <h2 class="text-center record-header">PARENT / GUARDIAN SIGNATURE</h2>

                <div class="row gap-20">
                    <div style="flex: none;">
                        <p>1<sup>st</sup> Quarter</p>
                    </div>
                    <div class="border-bottom" style="height: 100%; flex: 1;"></div>
                </div>

                <div class="row gap-20 mt-1">
                    <div style="flex: none;">
                        <p>2<sup>nd</sup> Quarter</p>
                    </div>
                    <div class="border-bottom" style="height: 100%; flex: 1;"></div>
                </div>

                <div class="row gap-20 mt-1">
                    <div style="flex: none;">
                        <p>3<sup>rd</sup> Quarter</p>
                    </div>
                    <div class="border-bottom" style="height: 100%; flex: 1;"></div>
                </div>

                <div class="row gap-20 mt-1">
                    <div style="flex: none;">
                        <p>4<sup>th</sup> Quarter</p>
                    </div>
                    <div class="border-bottom" style="height: 100%; flex: 1;"></div>
                </div>
            </div>

            {{-- CANCELLATION OF ELLIGIBILITY --}}
            <div class="column mt-2 gap-1">
                <h2 class="text-center record-header">Cancellation of Eligibility to Transfer</h2>

                <div class="row gap-70">
                    <div class="column w-50 gap-1">
                        <div class="row gap-10 mt-2">
                            <div class="row w-100 gap-10">
                                <div class="w-100">
                                    <p>Admitted in:</p>
                                </div>
                                <div class="border-bottom w-100">
                                    
                                </div>
                            </div>
                        </div>

                        <div class="row gap-10 mt-1">
                            <div class="row w-100 gap-10">
                                <div class="w-100">
                                    <p>Date:</p>
                                </div>
                                <div class="border-bottom w-100">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="row gap-20 mt-3 w-50">
                        {{-- PRINCIPAL --}}
                        <div class="column w-100 pt-4">
                            <div class="border-bottom">
                                
                            </div>
                            <p class="text-center baskerville text-sm">Principal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="half column">
            {{-- HEAD --}}
            <div class="row space-between gap-20 v-center">
                <img style="width: 60px; height: 60px; object-fit: cover;" src="{{ URL::asset('imgs/deped-logo.svg'); }}" alt="">

                <div class="column h-center v-center">
                    <h2 class="oswald text-center" style="font-weight: bold; font-size: 1.4em; padding: 0; margin: 0;">HOLY CROSS ACADEMY OF TUBIGON, INC.</h2>
                    <h2 style="font-size: .94em; padding: 0; margin: 0;">Tubigon, Bohol</h2>
                    <h2 style="font-size: .94em; padding: 0; margin: 0;">(Member: BACS-I)</h2>
                </div>

                <img style="width: 60px; height: 60px; object-fit: cover;" src="{{ URL::asset('imgs/logo.png'); }}" alt="">
            </div>

            {{-- STUDENT PROFILE --}}
            <div class="column gap-5 mt-2">
                <h2 class="baskerville-bold text-center" style="font-size: .9em; padding: 0; margin: 0;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</h2>

                <div class="row gap-10">
                    <div style="display: flex; justify-content: start;" class="w-80 gap-10">
                        <h2>NAME:</h2>
                        <div class="border-bottom" style="flex: 1;">
                            <h2>{{ $student->LastName }}, {{ $student->FirstName }}</h2>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: start;" class="w-20 gap-10">
                        <h2>Age:</h2>
                        <div class="border-bottom" style="flex: 1;">
                            
                        </div>
                    </div>
                </div>

                <div class="row gap-10">
                    <div style="display: flex; justify-content: start;" class="w-70 gap-10">
                        <h2>LRN:</h2>
                        <div class="border-bottom" style="flex: 1;">
                            <h2>{{ $student->LRN }}</h2>
                        </div>
                    </div>


                    <div style="display: flex; justify-content: start;" class="w-30 gap-10">
                        <h2>Sex:</h2>
                        <div class="border-bottom" style="flex: 1;">
                            <h2>{{ $student->Gender }}</h2>
                        </div>
                    </div>
                </div>

                <div class="row gap-10">
                    <div style="display: flex; justify-content: start;" class="w-70 gap-10">
                        <h2>Grade:</h2>
                        <div class="border-bottom" style="flex: 1;">
                            <h2>{{ $class->Year }}</h2>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: start;" class="w-30 gap-10">
                        <h2>Section:</h2>
                        <div class="border-bottom" style="flex: 1;">
                            <h2>{{ $class->Section }}</h2>
                        </div>
                    </div>
                </div>

                <div class="row gap-10">
                    <div style="display: flex; justify-content: start;" class="w-70 gap-10">
                        <h2>Shool Year:</h2>
                        <div class="border-bottom" style="flex: 1;">
                            <h2>{{ $sy->SchoolYear }}</h2>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: start;" class="w-30 gap-10">
                        <h2>Currculum:</h2>
                        <div class="border-bottom" style="flex: 1;">
                            <h2>K-12</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRADING --}}
            <div class="column mt-2">
                @php
                    $sumFirst = 0;
                    $sumSecond = 0;
                    $sumThird = 0;
                    $sumFourth = 0;
                    $sumAverage = 0;
                    $totalSubjectCount = 0;

                    $data = $student->GradeData;
                    $data = json_decode($data, true);
                    $grades = Classes::categorizeParentSubjects($data);
                    $mainSubjects = $grades['MainSubjects'];
                    $groupedSubjects = $grades['GroupSubjects'];
                    $hasOverallInc = false;
                @endphp

                <table class="table table-bordered text-sm">
                    <thead>
                        <tr>
                            <th style="font-size: .68em !important;" class="bg-gray text-center" rowspan="2">SUBJECTS</th>
                            <th style="font-size: .68em !important;" class="bg-gray text-center" colspan="4">QUARTER</th>
                            <th style="font-size: .4em !important; width: 30px;" class="bg-gray text-center" rowspan="2">Final<br>Grade</th>
                            <th style="font-size: .68em !important; width: 50px;" class="bg-gray text-center" rowspan="2">REMARKS</th>
                        </tr>
                        <tr>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">1</th>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">2</th>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">3</th>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">4</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mainSubjects as $subject)
                        @php
                            $avgParent = false;
                        @endphp
                        <!-- Main Subject Row -->
                        <tr>
                            @php
                                $aveGrade = is_numeric($subject['AverageGrade'])
                                    ? number_format($subject['AverageGrade'])
                                    : $subject['AverageGrade'];
                            @endphp
                            @if ($subject['Visibility'] === 'FREAKING PARENT')
                                <td class="bg-gray"><strong><i>{{ $subject['Subject'] }}<i></strong></td>
                                @if (in_array($subject['Subject'], $avgParents))
                                    @php
                                        $avgParent = true;
                                        $hasInc = false;
                                        if (
                                            $subject['FirstGradingGrade'] == null &&
                                            $periodGradeChecker->First != null
                                        ) {
                                            $hasInc = true;
                                        }

                                        if (
                                            $subject['SecondGradingGrade'] == null &&
                                            $periodGradeChecker->Second != null
                                        ) {
                                            $hasInc = true;
                                        }

                                        if (
                                            $subject['ThirdGradingGrade'] == null &&
                                            $periodGradeChecker->Third != null
                                        ) {
                                            $hasInc = true;
                                        }

                                        if (
                                            $subject['FourthGradingGrade'] == null &&
                                            $periodGradeChecker->Fourth != null
                                        ) {
                                            $hasInc = true;
                                        }

                                        $aveGrade = Subjects::getAverage([
                                            round(Subjects::validateNumber($subject['FirstGradingGrade'])),
                                            round(Subjects::validateNumber($subject['SecondGradingGrade'])),
                                            round(Subjects::validateNumber($subject['ThirdGradingGrade'])),
                                            round(Subjects::validateNumber($subject['FourthGradingGrade'])),
                                        ]);
                                        $hasOverallInc = $hasOverallInc ? $hasOverallInc : $hasInc;
                                    @endphp
                                    <td class="bg-gray text-right">
                                        <strong><i>{{ is_numeric($subject['FirstGradingGrade']) ? number_format($subject['FirstGradingGrade']) : $subject['FirstGradingGrade'] }}<i></strong>
                                    </td>
                                    <td class="bg-gray text-right">
                                        <strong><i>{{ is_numeric($subject['SecondGradingGrade']) ? number_format($subject['SecondGradingGrade']) : $subject['SecondGradingGrade'] }}<i></strong>
                                    </td>
                                    <td class="bg-gray text-right">
                                        <strong><i>{{ is_numeric($subject['ThirdGradingGrade']) ? number_format($subject['ThirdGradingGrade']) : $subject['ThirdGradingGrade'] }}<i></strong>
                                    </td>
                                    <td class="bg-gray text-right">
                                        <strong><i>{{ is_numeric($subject['FourthGradingGrade']) ? number_format($subject['FourthGradingGrade']) : $subject['FourthGradingGrade'] }}<i></strong>
                                    </td>

                                    <td class='bg-gray text-center'>
                                        <strong>{{ $hasInc ? '' : Subjects::validateGrade($aveGrade) }}</strong>
                                    </td>
                                    <td class='bg-gray text-center'>
                                        {{ $hasInc ? 'INC' : Subjects::checkPass($subject['AverageGrade']) }}
                                    </td>
                                @else
                                    @php
                                        $avgParent = false;
                                    @endphp
                                    <td class="bg-gray"></td>
                                    <td class="bg-gray"></td>
                                    <td class="bg-gray"></td>
                                    <td class="bg-gray"></td>
                                @endif
                            @else
                                @php
                                    $hasInc = false;
                                    if ($subject['FirstGradingGrade'] == null && $periodGradeChecker->First != null) {
                                        $hasInc = true;
                                    }

                                    if ($subject['SecondGradingGrade'] == null && $periodGradeChecker->Second != null) {
                                        $hasInc = true;
                                    }

                                    if ($subject['ThirdGradingGrade'] == null && $periodGradeChecker->Third != null) {
                                        $hasInc = true;
                                    }

                                    if ($subject['FourthGradingGrade'] == null && $periodGradeChecker->Fourth != null) {
                                        $hasInc = true;
                                    }

                                    $aveGrade = Subjects::getAverage([
                                        round(Subjects::validateNumber($subject['FirstGradingGrade'])),
                                        round(Subjects::validateNumber($subject['SecondGradingGrade'])),
                                        round(Subjects::validateNumber($subject['ThirdGradingGrade'])),
                                        round(Subjects::validateNumber($subject['FourthGradingGrade'])),
                                    ]);
                                    
                                    $hasOverallInc = $hasOverallInc ? $hasOverallInc : $hasInc;
                                @endphp
                                <td>{{ $subject['Subject'] }}</td>
                                <td class="text-right">
                                    {{ is_numeric($subject['FirstGradingGrade']) ? number_format($subject['FirstGradingGrade']) : $subject['FirstGradingGrade'] }}
                                </td>
                                <td class="text-right">
                                    {{ is_numeric($subject['SecondGradingGrade']) ? number_format($subject['SecondGradingGrade']) : $subject['SecondGradingGrade'] }}
                                </td>
                                <td class="text-right">
                                    {{ is_numeric($subject['ThirdGradingGrade']) ? number_format($subject['ThirdGradingGrade']) : $subject['ThirdGradingGrade'] }}
                                </td>
                                <td class="text-right">
                                    {{ is_numeric($subject['FourthGradingGrade']) ? number_format($subject['FourthGradingGrade']) : $subject['FourthGradingGrade'] }}
                                </td>
                                <td class='text-center'>
                                    <strong>{{ $hasInc ? '' : Subjects::validateGrade($aveGrade) }}</strong>
                                </td>
                                <td class='text-center'>
                                    {{ $hasInc ? 'INC' : Subjects::checkPass($subject['AverageGrade']) }}
                                </td>

                                @php
                                    // DO NOT INCLUDE HOMEROOM GUIDANCE ON AVERAGING
                                    if (!str_contains($subject['Subject'], "Homeroom")) {
                                        $sumFirst += floatval(
                                            $subject['FirstGradingGrade'] != null ? $subject['FirstGradingGrade'] : 0,
                                        );
                                        $sumSecond += floatval(
                                            $subject['SecondGradingGrade'] != null ? $subject['SecondGradingGrade'] : 0,
                                        );
                                        $sumThird += floatval(
                                            $subject['ThirdGradingGrade'] != null ? $subject['ThirdGradingGrade'] : 0,
                                        );
                                        $sumFourth += floatval(
                                            $subject['FourthGradingGrade'] != null ? $subject['FourthGradingGrade'] : 0,
                                        );
                                        $sumAverage += is_numeric($aveGrade) ? $aveGrade : 0;

                                        $totalSubjectCount++;
                                    }
                                @endphp
                            @endif
                        </tr>

                        <!-- Check if the subject has child subjects and display them -->
                        @if (isset($groupedSubjects[$subject['Subject']]))
                            @foreach ($groupedSubjects[$subject['Subject']] as $subSubject)
                                @php
                                    $hasInc = false;
                                    if (
                                        $subSubject['FirstGradingGrade'] == null &&
                                        $periodGradeChecker->First != null
                                    ) {
                                        $hasInc = true;
                                    }

                                    if (
                                        $subSubject['SecondGradingGrade'] == null &&
                                        $periodGradeChecker->Second != null
                                    ) {
                                        $hasInc = true;
                                    }

                                    if (
                                        $subSubject['ThirdGradingGrade'] == null &&
                                        $periodGradeChecker->Third != null
                                    ) {
                                        $hasInc = true;
                                    }

                                    if (
                                        $subSubject['FourthGradingGrade'] == null &&
                                        $periodGradeChecker->Fourth != null
                                    ) {
                                        $hasInc = true;
                                    }

                                    $aveGrade = Subjects::getAverage([
                                        round(Subjects::validateNumber($subSubject['FirstGradingGrade'])),
                                        round(Subjects::validateNumber($subSubject['SecondGradingGrade'])),
                                        round(Subjects::validateNumber($subSubject['ThirdGradingGrade'])),
                                        round(Subjects::validateNumber($subSubject['FourthGradingGrade'])),
                                    ]);
                                    $hasOverallInc = $hasOverallInc ? $hasOverallInc : $hasInc;
                                @endphp
                                <tr>
                                    <!-- Indented sub-subjects -->
                                    <td class="sub-subject indent-05">{{ $subSubject['Subject'] }}</td>
                                    <td class="text-right">
                                        {{ is_numeric($subSubject['FirstGradingGrade']) ? number_format($subSubject['FirstGradingGrade']) : $subSubject['FirstGradingGrade'] }}
                                    </td>
                                    <td class="text-right">
                                        {{ is_numeric($subSubject['SecondGradingGrade']) ? number_format($subSubject['SecondGradingGrade']) : $subSubject['SecondGradingGrade'] }}
                                    </td>
                                    <td class="text-right">
                                        {{ is_numeric($subSubject['ThirdGradingGrade']) ? number_format($subSubject['ThirdGradingGrade']) : $subSubject['ThirdGradingGrade'] }}
                                    </td>
                                    <td class="text-right">
                                        {{ is_numeric($subSubject['FourthGradingGrade']) ? number_format($subSubject['FourthGradingGrade']) : $subSubject['FourthGradingGrade'] }}
                                    </td>
                                    
                                    <td class='text-center'>
                                        @if ($avgParent)
                                            <strong></strong>
                                        @else
                                            <strong>{{ $hasInc ? '' : Subjects::validateGrade($aveGrade) }}</strong>
                                        @endif
                                        
                                    </td>
                                    <td class='text-center'>
                                        {{ $hasInc ? 'INC' : Subjects::checkPass($subSubject['AverageGrade']) }}
                                    </td>
                                    @php
                                        // DO NOT INCLUDE HOMEROOM GUIDANCE ON AVERAGING
                                        if (!str_contains($subSubject['Subject'], "Homeroom")) {
                                            $sumFirst += floatval(
                                                $subSubject['FirstGradingGrade'] != null ? $subSubject['FirstGradingGrade'] : 0,
                                            );
                                            $sumSecond += floatval(
                                                $subSubject['SecondGradingGrade'] != null ? $subSubject['SecondGradingGrade'] : 0,
                                            );
                                            $sumThird += floatval(
                                                $subSubject['ThirdGradingGrade'] != null ? $subSubject['ThirdGradingGrade'] : 0,
                                            );
                                            $sumFourth += floatval(
                                                $subSubject['FourthGradingGrade'] != null ? $subSubject['FourthGradingGrade'] : 0,
                                            );
                                            $sumAverage += is_numeric($aveGrade) ? $aveGrade : 0;

                                            $totalSubjectCount++;
                                        }
                                    @endphp
                                </tr>
                                
                            @endforeach
                        @endif
                        @endforeach

                        {{-- AVERAGE --}}
                        @php
                            $averageFirst = 0;
                            $averageSecond = 0;
                            $averageThird = 0;
                            $averageFourth = 0;
                            $genAve = 0;

                            if ($sumFirst > 0 && $totalSubjectCount > 0) {
                                $averageFirst = $sumFirst / $totalSubjectCount;
                            }

                            if ($sumSecond > 0 && $totalSubjectCount > 0) {
                                $averageSecond = $sumSecond / $totalSubjectCount;
                            }

                            if ($sumThird > 0 && $totalSubjectCount > 0) {
                                $averageThird = $sumThird / $totalSubjectCount;
                            }

                            if ($sumFourth > 0 && $totalSubjectCount > 0) {
                                $averageFourth = $sumFourth / $totalSubjectCount;
                            }

                            if ($sumAverage > 0 && $totalSubjectCount > 0) {
                                $genAve = $sumAverage / $totalSubjectCount;
                            }

                        @endphp
                        <tr>
                            <td class="text-right" colspan="5"><strong>GENERAL AVERAGE</strong></td>
                            <td class="text-center"><strong>{{ number_format($genAve) }}</strong></td>

                            <td class='text-center'>
                                {{ $hasOverallInc ? 'INC' : Subjects::checkPass($genAve) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- DESSCRIPTORS --}}
            <div class="column mt-2">
                <table class="table table-borderless baskerville text-sm">
                    <thead>
                        <th class="text-left">Descriptors</th>
                        <th>Grading Scale</th>
                        <th>Remarks</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Outstanding</td>
                            <td class="text-center">90-100</td>
                            <td class="text-center">Passed</td>
                        </tr>
                        <tr>
                            <td>Very Satisfactory</td>
                            <td class="text-center">85-90</td>
                            <td class="text-center">Passed</td>
                        </tr>
                        <tr>
                            <td>Satisfactory</td>
                            <td class="text-center">80-84</td>
                            <td class="text-center">Passed</td>
                        </tr>
                        <tr>
                            <td>Fairly Satisfactory</td>
                            <td class="text-center">75-89</td>
                            <td class="text-center">Passed</td>
                        </tr>
                        <tr>
                            <td>Did Not Meet Expectations</td>
                            <td class="text-center">Below 75</td>
                            <td class="text-center">Failed</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- CERTIFICATE OF TRANSFER --}}
            <div class="column mt-2 gap-1">
                <h2 class="text-center record-header">CERTIFICATE OF TRANSFER</h2>
                <div class="row gap-10 mt-1">
                    <div class="row w-60 gap-10">
                        <div class="w-100">
                            <p>Admitted to Grade:</p>
                        </div>
                        <div class="border-bottom w-100">
                            
                        </div>
                    </div>
    
                    <div class="row w-40 gap-10">
                        <p>Section:</p>
                        <div class="border-bottom w-100">
                            
                        </div>
                    </div>
                </div>

                <div class="row gap-10 mt-1">
                    <div class="row gap-10 w-70" style="display: flex; justify-content: start;">
                        <p>Eligible for Admission to Grade:</p>
                        <div class="border-bottom" style="flex: 1">
                            
                        </div>
                    </div>
                </div>

                <div class="row gap-10 mt-1">
                    <div class="row w-60 gap-10">
                        <p>Date:</p>
                        <div class="border-bottom w-100">
                            
                        </div>
                    </div>
                </div>
            </div>

            {{-- DEAR PARENTS --}}
            <div class="column mt-3">
                <p>
                    Dear Parent:
                </p>

                <p class="indent-1 mt-1">
                    This report card shows the ability and progress your child
                    has made in the different learning areas as well as his/her core
                    values.
                </p>
                
                <p class="indent-1 mt-1">
                    The school welcomes you should you desire to know more
                    about your child’s progress.
                </p>
            </div>

            {{-- SIGNATORIES --}}
            <div class="row gap-20 mt-5 pt-2">
                {{-- PRINCIPAL --}}
                <div class="column w-50">
                    <div class="border-bottom">
                        <h2 class="bold text-center">{{ env("PRINCIPAL_NAME") }}</h2>
                    </div>
                    <p class="text-center baskerville text-sm">Principal</p>
                </div>

                {{-- ADVISER --}}
                <div class="column w-50">
                    <div class="border-bottom">
                        <h2 class="bold text-center">{{ $adviser->FullName }}</h2>
                    </div>
                    <p class="text-center baskerville text-sm">Adviser</p>
                </div>
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