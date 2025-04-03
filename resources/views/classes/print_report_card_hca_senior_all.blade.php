@php
    use App\Models\Students;
    use App\Models\Classes;
    use App\Models\Subjects;
@endphp

<link rel="stylesheet" href="{{ URL::asset('css/print_report_card_hca_senior_all.css') }}">

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
            {{-- REPORT ON ATTENDANCE --}}
            <div class="column">
                <h2 class="text-center record-header">REPORT ON ATTENDANCE</h2>
                <table class="table table-bordered baskerville mt-2">
                    <tbody>
                        <tr>
                            <td></td>
                            <td class="baskerville text-center">Aug</td>
                            <td class="baskerville text-center">Sept</td>
                            <td class="baskerville text-center">Oct</td>
                            <td class="baskerville text-center">Nov</td>
                            <td class="baskerville text-center">Dec</td>
                            <td class="baskerville text-center">Jan</td>
                            <td class="baskerville text-center">Feb</td>
                            <td class="baskerville text-center">Mar</td>
                            <td class="baskerville text-center">Apr</td>
                            <td class="baskerville text-center">May</td>
                            <td class="baskerville text-center">Total</td>
                        </tr>
                        <tr>
                            <td class="baskerville">No. of
                                school
                                days</td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                        </tr>
                        <tr>
                            <td class="baskerville">No. of
                                days
                                present</td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                        </tr>
                        <tr>
                            <td class="baskerville">No. of
                                days
                                absent</td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                            <td class="baskerville"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="column w-80">
                    <div style="display: flex; flex-direction: row; justify-content: start;">
                        <p class="baskerville mt-4">Lack unit/s in:</p>
                        <div class="border-bottom" style="flex: 1;"></div>
                    </div>
                    <div style="display: flex; justify-content: start;">
                        <p class="baskerville mt-2">Advance unit/s in:</p>
                        <div class="border-bottom" style="flex: 1;"></div>
                    </div>
                    <div style="display: flex; justify-content: start;">
                        <p class="baskerville mt-2">Eligible for transfer to:</p>
                        <div class="border-bottom" style="flex: 1;"></div>
                    </div>
                </div>
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
            {{-- HEAD --}}
            <div class="row space-between gap-20 v-center">
                <img style="width: 80px; height: 80px; object-fit: cover;" src="{{ URL::asset('imgs/deped-logo.svg'); }}" alt="">

                <div class="column h-center v-center">
                    <h2 class="oswald" style="font-weight: bold; font-size: .94em; padding: 0; margin: 0;">BOHOL ASSOCIATION OF CATHOLIC SCHOOLS</h2>
                    <h2 style="font-size: .94em; padding: 0; margin: 0;">DIOCESE OF TAGBILARAN</h2>
                    <h2 class="oswald" style="font-weight: bold; font-size: 1.1em; padding: 0; margin: 0;">HOLY CROSS ACADEMY OF TUBIGON, INC.</h2>
                    <h2 style="font-size: .94em; padding: 0; margin: 0;">Tubigon, Bohol</h2>
                    <h2 style="font-weight: bold; font-size: .94em; padding: 0; margin: 0;">{{ $sy->SchoolYear }}</h2>
                </div>

                <img style="width: 80px; height: 80px; object-fit: cover;" src="{{ URL::asset('imgs/logo.png'); }}" alt="">
            </div>

            {{-- STUDENT PROFILE --}}
            <div class="column gap-10 mt-5">
                <div class="row gap-10">
                    <div class="row w-60 gap-10">
                        <h2 class="bold">NAME:</h2>
                        <div class="border-bottom w-100">
                            <h2 class="bold">{{ $student->LastName }}, {{ $student->FirstName }}</h2>
                        </div>
                    </div>
    
                    <div class="row w-40 gap-10">
                        <h2 class="bold">LRN:</h2>
                        <div class="border-bottom w-100">
                            <h2 class="bold">{{ $student->LRN }}</h2>
                        </div>
                    </div>
                </div>

                <div class="row gap-10">
                    <div class="row w-60 gap-10">
                        <div class="w-100">
                            <h2 class="bold">Grade & Section:</h2>
                        </div>
                        <div class="border-bottom w-100">
                            <h2 class="bold">{{ $class->Year }} - {{ $class->Section }}</h2>
                        </div>
                    </div>
    
                    <div class="row w-20 gap-10">
                        <h2 class="bold">Age:</h2>
                        <div class="border-bottom w-100">
                            
                        </div>
                    </div>

                    <div class="row w-20 gap-10">
                        <h2 class="bold">Sex:</h2>
                        <div class="border-bottom w-100">
                            <h2 class="bold">{{ $student->Gender }}</h2>
                        </div>
                    </div>
                </div>

                <div class="row gap-10">
                    <div class="row w-100 gap-10">
                        <h2 class="bold">Track/Strand:</h2>
                        <div class="border-bottom w-100">
                            <h2 class="bold">{{ $class->Strand }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DEAR PARENTS --}}
            <div class="column mt-5">
                <p class="baskerville-italic">
                    Dear Parents,
                </p>

                <p class="baskerville-italic indent-1 mt-3">
                    This report card shows the ability and progress your child
                    has made in the different learning areas as well as his/her core
                    values.
                </p>
                
                <p class="baskerville-italic indent-1 mt-3">
                    The school welcomes you should you desire to know more
                    about your child’s progress.
                </p>
            </div>

            {{-- SIGNATORIES --}}
            <div class="row gap-20 mt-4">
                {{-- PRINCIPAL --}}
                <div class="column w-50 pt-5">
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

            {{-- CERTIFICATE OF TRANSFER --}}
            <div class="column mt-4 gap-2">
                <h2 class="text-center record-header">CERTIFICATE OF TRANSFER</h2>
                <div class="row gap-10 mt-3">
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

                <div class="row gap-10 mt-3">
                    <div class="row w-100 gap-10">
                        <div class="w-100">
                            <p>Eligible for Admission to Grade:</p>
                        </div>
                        <div class="border-bottom w-100">
                            
                        </div>
                    </div>
                </div>

                <div class="row gap-20 mt-4">
                    {{-- PRINCIPAL --}}
                    <div class="column w-50 pt-5">
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
            
            {{-- CANCELLATION OF ELLIGIBILITY --}}
            <div class="column mt-4 gap-2">
                <h2 class="text-center record-header">Cancellation of Eligibility to Transfer</h2>

                <div class="row gap-70">
                    <div class="column w-50 gap-1">
                        <div class="row gap-10 mt-3">
                            <div class="row w-100 gap-10">
                                <div class="w-100">
                                    <p>Admitted in:</p>
                                </div>
                                <div class="border-bottom w-100">
                                    
                                </div>
                            </div>
                        </div>

                        <div class="row gap-10 mt-3">
                            <div class="row w-100 gap-10">
                                <div class="w-100">
                                    <p>Dated:</p>
                                </div>
                                <div class="border-bottom w-100">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="row gap-20 mt-4 w-50">
                        {{-- PRINCIPAL --}}
                        <div class="column w-100 pt-5">
                            <div class="border-bottom">
                                
                            </div>
                            <p class="text-center baskerville text-sm">Principal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- 
    ===================================================================================================>
            PAGE 2 
    ===================================================================================================>
    --}}
    <div class="content gap-50">
        {{-- LEFT --}}
        <div class="half column">
            {{-- REPORT ON LEARNING PROGRESS AND ACHIEVEMENT --}}
            {{-- FIRST SEM --}}
            <div class="column">
                <h2 class="text-center record-header">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</h2>
                <h2 class="bold mt-2">First Semester</h2>

                @php
                    $sumFirst = 0;
                    $sumSecond = 0;
                    $sumThird = 0;
                    $sumFourth = 0;
                    $sumAverage = 0;
                    $totalSubjectCount = 0;

                    $fSemData = $student->FirstSemGradeData;
                    $fSemData = json_decode($fSemData, true);
                    $firstSem = Classes::categorizeParentSubjects($fSemData);
                    $mainSubjects = $firstSem['MainSubjects'];
                    $groupedSubjects = $firstSem['GroupSubjects'];
                    $hasOverallInc = false;
                @endphp

                <table class="table table-bordered text-sm" style="margin-top: 2px;">
                    <thead>
                        <tr>
                            <th style="font-size: .68em !important;" class="bg-gray text-center" rowspan="2">SUBJECTS</th>
                            <th style="font-size: .68em !important;" class="bg-gray text-center" colspan="3">QUARTER</th>
                            <th style="font-size: .68em !important; width: 50px;" class="bg-gray text-center" rowspan="2">REMARKS</th>
                        </tr>
                        <tr>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">1st</th>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">2nd</th>
                            <th style="font-size: .4em !important; width: 30px;" class="bg-gray text-center">Final<br>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mainSubjects as $subject)
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

                                        $aveGrade = Subjects::getAverage([
                                            round(Subjects::validateNumber($subject['FirstGradingGrade'])),
                                            round(Subjects::validateNumber($subject['SecondGradingGrade'])),
                                        ]);
                                        $hasOverallInc = $hasOverallInc ? $hasOverallInc : $hasInc;
                                    @endphp
                                    <td class="bg-gray text-right">
                                        <strong><i>{{ is_numeric($subject['FirstGradingGrade']) ? number_format($subject['FirstGradingGrade']) : $subject['FirstGradingGrade'] }}<i></strong>
                                    </td>
                                    <td class="bg-gray text-right">
                                        <strong><i>{{ is_numeric($subject['SecondGradingGrade']) ? number_format($subject['SecondGradingGrade']) : $subject['SecondGradingGrade'] }}<i></strong>
                                    </td>

                                    <td class='bg-gray text-center'>
                                        <strong>{{ $hasInc ? '' : Subjects::validateGrade($aveGrade) }}</strong>
                                    </td>
                                    <td class='bg-gray text-center'>
                                        {{ $hasInc ? 'INC' : Subjects::checkPass($subject['AverageGrade']) }}
                                    </td>
                                    <td class="bg-gray"></td>
                                @else
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

                                    $aveGrade = Subjects::getAverage([
                                        round(Subjects::validateNumber($subject['FirstGradingGrade'])),
                                        round(Subjects::validateNumber($subject['SecondGradingGrade'])),
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

                                    $aveGrade = Subjects::getAverage([
                                        round(Subjects::validateNumber($subSubject['FirstGradingGrade'])),
                                        round(Subjects::validateNumber($subSubject['SecondGradingGrade'])),
                                    ]);
                                    $hasOverallInc = $hasOverallInc ? $hasOverallInc : $hasInc;
                                @endphp
                                <tr>
                                    <!-- Indented sub-subjects -->
                                    <td class="sub-subject">{{ $subSubject['Subject'] }}</td>
                                    <td class="text-right">
                                        {{ is_numeric($subSubject['FirstGradingGrade']) ? number_format($subSubject['FirstGradingGrade']) : $subSubject['FirstGradingGrade'] }}
                                    </td>
                                    <td class="text-right">
                                        {{ is_numeric($subSubject['SecondGradingGrade']) ? number_format($subSubject['SecondGradingGrade']) : $subSubject['SecondGradingGrade'] }}
                                    </td>
                                    
                                    <td class='text-center'>
                                        <strong>{{ $hasInc ? '' : Subjects::validateGrade($aveGrade) }}</strong>
                                    </td>
                                    <td class='text-center'>
                                        {{ $hasInc ? 'INC' : Subjects::checkPass($subSubject['AverageGrade']) }}
                                    </td>
                                </tr>
                                @php
                                    // DO NOT INCLUDE HOMEROOM GUIDANCE ON AVERAGING
                                    if (!str_contains($subSubject['Subject'], "Homeroom")) {
                                        $sumFirst += floatval(
                                            $subSubject['FirstGradingGrade'] != null ? $subSubject['FirstGradingGrade'] : 0,
                                        );
                                        $sumSecond += floatval(
                                            $subSubject['SecondGradingGrade'] != null ? $subSubject['SecondGradingGrade'] : 0,
                                        );
                                        $sumAverage += is_numeric($aveGrade) ? $aveGrade : 0;

                                        $totalSubjectCount++;
                                    }
                                @endphp
                            @endforeach
                        @endif
                        @endforeach

                        {{-- AVERAGE --}}
                        @php
                            $averageFirst = 0;
                            $averageSecond = 0;
                            $genAve = 0;

                            if ($sumFirst > 0 && $totalSubjectCount > 0) {
                                $averageFirst = $sumFirst / $totalSubjectCount;
                            }

                            if ($sumSecond > 0 && $totalSubjectCount > 0) {
                                $averageSecond = $sumSecond / $totalSubjectCount;
                            }

                            $genAve = ($averageFirst + $averageSecond) / 2;

                        @endphp
                        <tr>
                            <td style="text-align: right; border: none !important;"><strong>General Average for the Semester</strong></td>
                            <td class="text-right"><strong>{{ number_format($averageFirst) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format($averageSecond) }}</strong></td>
                            <td class="text-center"><strong>{{ number_format($genAve) }}</strong></td>

                            <td class='text-center'>
                                {{ $hasOverallInc ? 'INC' : Subjects::checkPassCard($genAve) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- SECOND SEM --}}
            <div class="column">
                <h2 class="bold mt-2">Second Semester</h2>

                @php
                    $sumThird = 0;
                    $sumFourth = 0;
                    $sumAverage = 0;
                    $totalSubjectCount = 0;

                    $sSemData = $student->SecondSemGradeData;
                    $sSemData = json_decode($sSemData, true);
                    $secondSem = Classes::categorizeParentSubjects($sSemData);
                    $mainSubjects = $secondSem['MainSubjects'];
                    $groupedSubjects = $secondSem['GroupSubjects'];
                    $hasOverallInc = false;
                @endphp

                <table class="table table-bordered text-sm" style="margin-top: 2px;">
                    <thead>
                        <tr>
                            <th style="font-size: .68em !important;" class="bg-gray text-center" rowspan="2">SUBJECTS</th>
                            <th style="font-size: .68em !important;" class="bg-gray text-center" colspan="3">QUARTER</th>
                            <th style="font-size: .68em !important; width: 50px;" class="bg-gray text-center" rowspan="2">REMARKS</th>
                        </tr>
                        <tr>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">3rd</th>
                            <th style="font-size: .68em !important; width: 25px;" class="bg-gray text-center">4th</th>
                            <th style="font-size: .4em !important; width: 30px;" class="bg-gray text-center">Final<br>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mainSubjects as $subject)
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
                                        $hasInc = false;
                                        
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
                                            round(Subjects::validateNumber($subject['ThirdGradingGrade'])),
                                            round(Subjects::validateNumber($subject['FourthGradingGrade'])),
                                        ]);
                                        $hasOverallInc = $hasOverallInc ? $hasOverallInc : $hasInc;
                                    @endphp
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
                                    <td class="bg-gray"></td>
                                @else
                                    <td class="bg-gray"></td>
                                    <td class="bg-gray"></td>
                                    <td class="bg-gray"></td>
                                    <td class="bg-gray"></td>
                                @endif
                            @else
                                @php
                                    $hasInc = false;
                                    if ($subject['ThirdGradingGrade'] == null && $periodGradeChecker->Third != null) {
                                        $hasInc = true;
                                    }

                                    if ($subject['FourthGradingGrade'] == null && $periodGradeChecker->Fourth != null) {
                                        $hasInc = true;
                                    }

                                    $aveGrade = Subjects::getAverage([
                                        round(Subjects::validateNumber($subject['ThirdGradingGrade'])),
                                        round(Subjects::validateNumber($subject['FourthGradingGrade'])),
                                    ]);

                                    $hasOverallInc = $hasOverallInc ? $hasOverallInc : $hasInc;
                                @endphp
                                <td>{{ $subject['Subject'] }}</td>
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
                                        round(Subjects::validateNumber($subSubject['ThirdGradingGrade'])),
                                        round(Subjects::validateNumber($subSubject['FourthGradingGrade'])),
                                    ]);
                                    $hasOverallInc = $hasOverallInc ? $hasOverallInc : $hasInc;
                                @endphp
                                <tr>
                                    <!-- Indented sub-subjects -->
                                    <td class="sub-subject">{{ $subSubject['Subject'] }}</td>
                                    <td class="text-right">
                                        {{ is_numeric($subSubject['ThirdGradingGrade']) ? number_format($subSubject['ThirdGradingGrade']) : $subSubject['ThirdGradingGrade'] }}
                                    </td>
                                    <td class="text-right">
                                        {{ is_numeric($subSubject['FourthGradingGrade']) ? number_format($subSubject['FourthGradingGrade']) : $subSubject['FourthGradingGrade'] }}
                                    </td>
                                    
                                    <td class='text-center'>
                                        <strong>{{ $hasInc ? '' : Subjects::validateGrade($aveGrade) }}</strong>
                                    </td>
                                    <td class='text-center'>
                                        {{ $hasInc ? 'INC' : Subjects::checkPass($subSubject['AverageGrade']) }}
                                    </td>
                                </tr>
                                @php
                                    // DO NOT INCLUDE HOMEROOM GUIDANCE ON AVERAGING
                                    if (!str_contains($subSubject['Subject'], "Homeroom")) {
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
                            @endforeach
                        @endif
                        @endforeach

                        {{-- AVERAGE --}}
                        @php
                            $averageThird = 0;
                            $averageFourth = 0;
                            $genAve = 0;

                            if ($sumThird > 0 && $totalSubjectCount > 0) {
                                $averageThird = $sumThird / $totalSubjectCount;
                            }

                            if ($sumFourth > 0 && $totalSubjectCount > 0) {
                                $averageFourth = $sumFourth / $totalSubjectCount;
                            }

                            $genAve = ($averageThird + $averageFourth) / 2;

                        @endphp
                        <tr>
                            <td style="text-align: right; border: none !important;"><strong>General Average for the Semester</strong></td>
                            <td class="text-right"><strong>{{ number_format($averageThird) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format($averageFourth) }}</strong></td>
                            <td class="text-center"><strong>{{ number_format($genAve) }}</strong></td>

                            <td class='text-center'>
                                {{ $hasOverallInc ? 'INC' : Subjects::checkPassCard($genAve) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="half column">
            <div class="column">
                <h2 class="text-center record-header">REPORT ON LEARNER’S OBSERVED VALUES</h2>

                <table class="table table-bordered baskerville mt-2">
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
                            <td class="v-align" rowspan="2">1. Maka-Diyos</td>
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
                            <td class="v-align" rowspan="2">2. Makatao</td>
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
                            <td class="v-align">3. Maka
                                kalikasan</td>
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
                    </tbody>
                </table>

                <div class="row mt-4">
                    <div class="column w-50">
                        <p class="baskerville-bold text-center">Marking</p>
                        <p class="baskerville text-center">AO</p>
                        <p class="baskerville text-center">SO</p>
                        <p class="baskerville text-center">RO</p>
                        <p class="baskerville text-center">NO</p>
                    </div>

                    <div class="column w-50">
                        <p class="baskerville-bold">Non-numerical Rating</p>
                        <p class="baskerville">Always Observed</p>
                        <p class="baskerville">Sometimes Observed</p>
                        <p class="baskerville">Rarely Observed</p>
                        <p class="baskerville">Not Observed</p>
                    </div>
                </div>

                <div class="column mt-4">
                    <p class="baskerville-italic"><strong>Descriptors, Grading Scale, and Remarks</strong></p>
                    <table class="table table-bordered baskerville mt-1">
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
            </div>
        </div>
    </div>
</div>
@endforeach

<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
        // window.location.href = "{{ route('transactions.miscellaneous-search') }}";
    }, 800);
</script>