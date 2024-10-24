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

<div id="print-area" class="content" style="height: 48vh; margin-top: 20px; padding-left: 6px; padding-right: 6px;">
    <div style="width: 100%; display: flex;">
        <div class="twenty" style="display: flex; flex-wrap: wrap;">
            <img style="width: 48px; height: 48px; object-fit: cover;" src="{{ URL::asset('imgs/logo.png'); }}" alt="">
        </div>

        <div class="sixty">
            <p class="strong text-center"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>    
            <p class="text-center">{{ strtoupper(env('APP_ADDRESS')) }}</p>

            <p class="text-center" style="margin-top: 10px;"><strong>{{ $gradingPeriod }} GRADING GRADES SUMMARY</strong></p>
            <p class="text-center">{{ $sy != null ? $sy->SchoolYear : '' }}</p>
        </div>
    </div>
    @php
        $subjects = json_decode($subjects, true);
        $groupedSubjects = [];
        $mainSubjects = [];

        // Collect ParentSubjects for later insertion
        $parentSubjects = [];

        $subjectIdsSequenced = [];

        foreach ($subjects as $subject) {
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
            $mainSubjects[] = [
                "Subject" => $parentSubject,   // The parent name
                "FullName" => "",              // Leave blank as there's no teacher for parent
                "Heirarchy" => $groupedSubjects[$parentSubject][0]["Heirarchy"], // Sort by first child's Heirarchy
                "id" => null,
                "SubjectId" => null,
                "TeacherId" => null,
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
        unset($subSubjects);

        // add sub subjects
        foreach ($mainSubjects as $item) {
            if (!isset($groupedSubjects[$item["Subject"]])) {
                $subjectIdsSequenced[] = $item['id'];
            } else {
                foreach ($groupedSubjects[$item["Subject"]] as $itemx) {
                    $subjectIdsSequenced[] = $itemx['id'];
                }
            }
        }
    @endphp
    {{-- GRADE --}}
    <table class="table table-bordered" style="margin-top: 10px;">
        <thead>
            <tr>
                <th style="font-size: .68em !important;" class="text-center" rowspan="2">STUDENTS</th>
                @foreach ($mainSubjects as $item)
                    @if (!isset($groupedSubjects[$item["Subject"]]))
                        <th style="font-size: .68em !important;" class="text-center" rowspan="2">{{ $item['Subject'] }}</th>
                    @else
                        <th style="font-size: .68em !important;" class="text-center" colspan="{{ count($groupedSubjects[$item["Subject"]]) }}">{{ $item['Subject'] }}</th>
                    @endif
                @endforeach
            </tr>
            <tr>
                @foreach ($mainSubjects as $item)
                    @if (isset($groupedSubjects[$item["Subject"]]))
                        @foreach ($groupedSubjects[$item["Subject"]] as $itemx)
                            <th style="font-size: .68em !important;" class="text-center">{{ $itemx['Subject'] }}</th>
                        @endforeach
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $item)
                <tr>
                    <td style="width: 320px;">{{ Students::formatNameFormal($item) }}</td>
                    @foreach ($subjectIdsSequenced as $subId)
                        <td>{{ Subjects::getGradeFromArray($item->id, $subId, $gradingPeriod, $subjectData) }}</td>
                    @endforeach
                </tr>
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