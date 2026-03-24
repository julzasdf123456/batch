<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Classes extends Model
{
    public $table = 'Classes';

    public $fillable = [
        'id',
        'SchoolYearId',
        'ClassName',
        'Year',
        'Section',
        'Adviser',
        'Strand',
        'Semester',
        'Track',
    ];

    protected $casts = [
        'id' => 'string',
        'SchoolYearId' => 'string',
        'ClassName' => 'string',
        'Year' => 'string',
        'Section' => 'string',
        'Adviser' => 'string',
        'Strand' => 'string',
        'Semester' => 'string',
        'Track' => 'string',
    ];

    public static array $rules = [
        'id' => 'string',
        'SchoolYearId' => 'nullable|string|max:50',
        'ClassName' => 'nullable|string|max:50',
        'Year' => 'nullable|string|max:50',
        'Section' => 'nullable|string|max:50',
        'Adviser' => 'nullable|string|max:50',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Strand' => 'nullable|string',
        'Semester' => 'nullable|string',
        'Track' => 'nullable|string',
    ];

    public static function populateSF10DataFront($student, $class, $adviser, $worksheet) {
        if ($class != null && $class->Semester === '1st') {
            /**
             * ================================================
             *  1ST SEMESTER DETAILS
             * ================================================
             * 
             * SCHOOL INFO
             */
            $worksheet->setCellValue('E23', strtoupper(env('APP_COMPANY')));
            if ($class->Year === 'Grade 12') {
                $worksheet->setCellValue('AF23', strtoupper(env('SCHOOL_CODE_GR12')));
            } else {
                $worksheet->setCellValue('AF23', strtoupper(env('SCHOOL_CODE')));
            }

            /**
             * GRADE LEVEL
             */
            $worksheet->setCellValue('AS23', strtoupper($class->Year));
            $worksheet->setCellValue('BA23', strtoupper($class->SchoolYear));
            $worksheet->setCellValue('BK23', strtoupper($class->Semester));
            $worksheet->setCellValue('G25', strtoupper($class->Strand));
            $worksheet->setCellValue('AS25', strtoupper($class->Section));

            /**
             * CURRENT SEM SUBJECTS
             */
            $currentSubjects = DB::table('StudentSubjects')
                ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
                ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
                ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
                ->whereRaw("StudentSubjects.StudentId='" . $student->id . "' AND StudentSubjects.ClassId='" . $class->id . "'")
                ->select(
                    'StudentSubjects.*',
                    'Subjects.Subject',
                    'Subjects.ParentSubject',
                    'Teachers.FullName',
                )
                ->orderBy('Heirarchy')
                ->get();
            
            $fsRowStart = 31;
            $fsRowOGStart = 31; // for averaging, get original starting row
            foreach($currentSubjects as $fs) {
                $worksheet->setCellValue('A' . $fsRowStart, $fs->ParentSubject);
                $worksheet->setCellValue('I' . $fsRowStart, $fs->Subject);
                $worksheet->setCellValue('AT' . $fsRowStart, $fs->FirstGradingGrade != null ? round(floatval($fs->FirstGradingGrade)) : '');
                $worksheet->setCellValue('AY' . $fsRowStart, $fs->SecondGradingGrade != null ? round(floatval($fs->SecondGradingGrade)) : '');
                $worksheet->setCellValue('BD' . $fsRowStart, '=IF(OR(AT'.$fsRowStart.'="",AY'.$fsRowStart.'=""),"",IF(ISERROR(ROUND(AVERAGE(AT'.$fsRowStart.',AY'.$fsRowStart.'),0)),"",ROUND(AVERAGE(AT'.$fsRowStart.',AY'.$fsRowStart.'),0)))');
                $worksheet->setCellValue('BI' . $fsRowStart, '=IF(OR(AT'.$fsRowStart.'="",AY'.$fsRowStart.'="",BD'.$fsRowStart.'=""),"",IF(BD'.$fsRowStart.'>=75,"PASSED","FAILED"))');

                $fsRowStart++;
            }
            
            $worksheet->setCellValue('BD43', '=ROUND(AVERAGE(BD'.$fsRowOGStart.':BD'.($fsRowStart-1).'),0)');
            $worksheet->setCellValue('BI43', '=IF(BD43>=75,"PROMOTED","FAILED")');

            /**
             * ADVISER AND PRINCIPAL
             */
            $worksheet->setCellValue('A53', strtoupper($adviser != null ? $adviser->FullName : ''));
            $worksheet->setCellValue('Y53', strtoupper(env('PRINCIPAL_NAME')));
            $worksheet->setCellValue('AZ53', strtoupper(date('m/d/Y')));

            $fsRowStart = 0;
        } elseif ($class != null && $class->Semester === '2nd') {
            /**
             * ================================================
             *  2ND SEMESTER DETAILS
             * ================================================
             * 
             * SCHOOL INFO
             */
            $worksheet->setCellValue('E70', strtoupper(env('APP_COMPANY')));
            $worksheet->setCellValue('AF70', strtoupper(env('SCHOOL_CODE')));

            /**
             * GRADE LEVEL
             */
            $worksheet->setCellValue('AS70', strtoupper($class->Year));
            $worksheet->setCellValue('BA70', strtoupper($class->SchoolYear));
            $worksheet->setCellValue('BK70', strtoupper($class->Semester));
            $worksheet->setCellValue('G72', strtoupper($class->Strand));
            $worksheet->setCellValue('AS72', strtoupper($class->Section));

            /**
             * CURRENT SEM SUBJECTS
             */
            $currentSubjects = DB::table('StudentSubjects')
                ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
                ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
                ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
                ->whereRaw("StudentSubjects.StudentId='" . $student->id . "' AND StudentSubjects.ClassId='" . $class->id . "'")
                ->select(
                    'StudentSubjects.*',
                    'Subjects.Subject',
                    'Subjects.ParentSubject',
                    'Teachers.FullName',
                )
                ->orderBy('Heirarchy')
                ->get();
            
            $fsRowStart = 78;
            $fsRowOGStart = 78; // for averaging, get original starting row
            foreach($currentSubjects as $fs) {
                $worksheet->setCellValue('A' . $fsRowStart, $fs->ParentSubject);
                $worksheet->setCellValue('I' . $fsRowStart, $fs->Subject);
                $worksheet->setCellValue('AT' . $fsRowStart, $fs->ThirdGradingGrade != null ? round(floatval($fs->ThirdGradingGrade)) : '');
                $worksheet->setCellValue('AY' . $fsRowStart, $fs->FourthGradingGrade != null ? round(floatval($fs->FourthGradingGrade)) : '');
                $worksheet->setCellValue('BD' . $fsRowStart, '=IF(OR(AT'.$fsRowStart.'="",AY'.$fsRowStart.'=""),"",IF(ISERROR(ROUND(AVERAGE(AT'.$fsRowStart.',AY'.$fsRowStart.'),0)),"",ROUND(AVERAGE(AT'.$fsRowStart.',AY'.$fsRowStart.'),0)))');
                $worksheet->setCellValue('BI' . $fsRowStart, '=IF(OR(AT'.$fsRowStart.'="",AY'.$fsRowStart.'="",BD'.$fsRowStart.'=""),"",IF(BD'.$fsRowStart.'>=75,"PASSED","FAILED"))');

                $fsRowStart++;
            }
            
            $worksheet->setCellValue('BD90', '=ROUND(AVERAGE(BD'.$fsRowOGStart.':BD'.($fsRowStart-1).'),0)');
            $worksheet->setCellValue('BI90', '=IF(BD90>=75,"PROMOTED","FAILED")');

            /**
             * ADVISER AND PRINCIPAL
             */
            $worksheet->setCellValue('A99', strtoupper($adviser != null ? $adviser->FullName : ''));
            $worksheet->setCellValue('Y99', strtoupper(env('PRINCIPAL_NAME')));
            $worksheet->setCellValue('AZ99', strtoupper(date('m/d/Y')));
            
            $fsRowStart = 0;
        }
    }

    public static function populateSF10DataBack($student, $class, $adviser, $worksheet) {
        if ($class != null && $class->Semester === '1st') {
            /**
             * ================================================
             *  1ST SEMESTER DETAILS
             * ================================================
             * 
             * SCHOOL INFO
             */
            $worksheet->setCellValue('E4', strtoupper(env('APP_COMPANY')));
            $worksheet->setCellValue('AF4', strtoupper(env('SCHOOL_CODE')));

            /**
             * GRADE LEVEL
             */
            $worksheet->setCellValue('AS4', strtoupper($class->Year));
            $worksheet->setCellValue('BA4', strtoupper($class->SchoolYear));
            $worksheet->setCellValue('BK4', strtoupper($class->Semester));
            $worksheet->setCellValue('G5', strtoupper($class->Strand));
            $worksheet->setCellValue('AS5', strtoupper($class->Section));

            /**
             * CURRENT SEM SUBJECTS
             */
            $currentSubjects = DB::table('StudentSubjects')
                ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
                ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
                ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
                ->whereRaw("StudentSubjects.StudentId='" . $student->id . "' AND StudentSubjects.ClassId='" . $class->id . "'")
                ->select(
                    'StudentSubjects.*',
                    'Subjects.Subject',
                    'Subjects.ParentSubject',
                    'Teachers.FullName',
                )
                ->orderBy('Heirarchy')
                ->get();
            
            $fsRowStart = 11;
            $fsRowOGStart = 11; // for averaging, get original starting row
            foreach($currentSubjects as $fs) {
                $worksheet->setCellValue('A' . $fsRowStart, $fs->ParentSubject);
                $worksheet->setCellValue('I' . $fsRowStart, $fs->Subject);
                $worksheet->setCellValue('AT' . $fsRowStart, $fs->FirstGradingGrade != null ? round(floatval($fs->FirstGradingGrade)) : '');
                $worksheet->setCellValue('AY' . $fsRowStart, $fs->SecondGradingGrade != null ? round(floatval($fs->SecondGradingGrade)) : '');
                $worksheet->setCellValue('BD' . $fsRowStart, '=IF(OR(AT'.$fsRowStart.'="",AY'.$fsRowStart.'=""),"",IF(ISERROR(ROUND(AVERAGE(AT'.$fsRowStart.',AY'.$fsRowStart.'),0)),"",ROUND(AVERAGE(AT'.$fsRowStart.',AY'.$fsRowStart.'),0)))');
                $worksheet->setCellValue('BI' . $fsRowStart, '=IF(OR(AT'.$fsRowStart.'="",AY'.$fsRowStart.'="",BD'.$fsRowStart.'=""),"",IF(BD'.$fsRowStart.'>=75,"PASSED","FAILED"))');

                $fsRowStart++;
            }
            
            $worksheet->setCellValue('BD23', '=ROUND(AVERAGE(BD'.$fsRowOGStart.':BD'.($fsRowStart-1).'),0)');
            $worksheet->setCellValue('BI23', '=IF(BD23>=75,"PROMOTED","FAILED")');

            /**
             * ADVISER AND PRINCIPAL
             */
            $worksheet->setCellValue('A29', strtoupper($adviser != null ? $adviser->FullName : ''));
            $worksheet->setCellValue('Y29', strtoupper(env('PRINCIPAL_NAME')));
            $worksheet->setCellValue('AZ29', strtoupper(date('m/d/Y')));
        } elseif ($class != null && $class->Semester === '2nd') {
            /**
             * ================================================
             *  2ND SEMESTER DETAILS
             * ================================================
             * 
             * SCHOOL INFO
             */
            $worksheet->setCellValue('E46', strtoupper(env('APP_COMPANY')));
            $worksheet->setCellValue('AF46', strtoupper(env('SCHOOL_CODE')));

            /**
             * GRADE LEVEL
             */
            $worksheet->setCellValue('AS46', strtoupper($class->Year));
            $worksheet->setCellValue('BA46', strtoupper($class->SchoolYear));
            $worksheet->setCellValue('BK46', strtoupper($class->Semester));
            $worksheet->setCellValue('G48', strtoupper($class->Strand));
            $worksheet->setCellValue('AS48', strtoupper($class->Section));

            /**
             * CURRENT SEM SUBJECTS
             */
            $currentSubjects = DB::table('StudentSubjects')
                ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
                ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
                ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
                ->whereRaw("StudentSubjects.StudentId='" . $student->id . "' AND StudentSubjects.ClassId='" . $class->id . "'")
                ->select(
                    'StudentSubjects.*',
                    'Subjects.Subject',
                    'Subjects.ParentSubject',
                    'Teachers.FullName',
                )
                ->orderBy('Heirarchy')
                ->get();
            
            $fsRowStart = 54;
            $fsRowOGStart = 54; // for averaging, get original starting row
            foreach($currentSubjects as $fs) {
                $worksheet->setCellValue('A' . $fsRowStart, $fs->ParentSubject);
                $worksheet->setCellValue('I' . $fsRowStart, $fs->Subject);
                $worksheet->setCellValue('AT' . $fsRowStart, $fs->ThirdGradingGrade != null ? round(floatval($fs->ThirdGradingGrade)) : '');
                $worksheet->setCellValue('AY' . $fsRowStart, $fs->FourthGradingGrade != null ? round(floatval($fs->FourthGradingGrade)) : '');
                $worksheet->setCellValue('BD' . $fsRowStart, '=IF(OR(AT'.$fsRowStart.'="",AY'.$fsRowStart.'=""),"",IF(ISERROR(ROUND(AVERAGE(AT'.$fsRowStart.',AY'.$fsRowStart.'),0)),"",ROUND(AVERAGE(AT'.$fsRowStart.',AY'.$fsRowStart.'),0)))');
                $worksheet->setCellValue('BI' . $fsRowStart, '=IF(OR(AT'.$fsRowStart.'="",AY'.$fsRowStart.'="",BD'.$fsRowStart.'=""),"",IF(BD'.$fsRowStart.'>=75,"PASSED","FAILED"))');

                $fsRowStart++;
            }
            
            $worksheet->setCellValue('BD66', '=ROUND(AVERAGE(BD'.$fsRowOGStart.':BD'.($fsRowStart-1).'),0)');
            $worksheet->setCellValue('BI66', '=IF(BD66>=75,"PROMOTED","FAILED")');

            /**
             * ADVISER AND PRINCIPAL
             */
            $worksheet->setCellValue('A72', strtoupper($adviser != null ? $adviser->FullName : ''));
            $worksheet->setCellValue('Y72', strtoupper(env('PRINCIPAL_NAME')));
            $worksheet->setCellValue('AZ72', strtoupper(date('m/d/Y')));
        }
    }

    public static function populateSF10JHS($student, $class, $adviser, $worksheet) {
        if ($class != null) {
            /**
             * SCHOOL INFO
             */
            $worksheet->setCellValue('C22', strtoupper(env('APP_COMPANY')));
            $worksheet->setCellValue('H22', strtoupper(env('SCHOOL_CODE')));
            $worksheet->setCellValue('M22', "BOHOL");
            $worksheet->setCellValue('P22', "VII");

            /**
             * GRADE LEVEL
             */
            $worksheet->setCellValue('D23', strtoupper(str_replace('Grade ', '', $class->Year)));
            $worksheet->setCellValue('F23', strtoupper($class->Section));
            $worksheet->setCellValue('H23', strtoupper(str_replace('S.Y. ', '', $class->SchoolYear)));
            $worksheet->setCellValue('F23', strtoupper($class->Section));
            $worksheet->setCellValue('L23', strtoupper($adviser != null ? $adviser->FullName : ''));

            /**
             * CURRENT SEM SUBJECTS
             */
            $currentSubjects = DB::table('StudentSubjects')
                ->leftJoin('Classes', 'StudentSubjects.ClassId', '=', 'Classes.id')
                ->leftJoin('Subjects', 'StudentSubjects.SubjectId', '=', 'Subjects.id')
                ->leftJoin('Teachers', 'Subjects.Teacher', '=', 'Teachers.id')
                ->whereRaw("StudentSubjects.StudentId='" . $student->id . "' AND StudentSubjects.ClassId='" . $class->id . "'")
                ->select(
                    'StudentSubjects.*',
                    'Subjects.Subject',
                    'Subjects.ParentSubject',
                    'Teachers.FullName',
                )
                ->orderBy('Heirarchy')
                ->get();
            
            $fsRowStart = 27;
            $fsRowOGStart = 27; // for averaging, get original starting row
            foreach($currentSubjects as $fs) {
                $worksheet->setCellValue('A' . $fsRowStart, $fs->Subject);
                $worksheet->setCellValue('G' . $fsRowStart, $fs->FirstGradingGrade != null ? round(floatval($fs->FirstGradingGrade)) : '');
                $worksheet->setCellValue('H' . $fsRowStart, $fs->SecondGradingGrade != null ? round(floatval($fs->SecondGradingGrade)) : '');
                $worksheet->setCellValue('I' . $fsRowStart, $fs->ThirdGradingGrade != null ? round(floatval($fs->ThirdGradingGrade)) : '');
                $worksheet->setCellValue('J' . $fsRowStart, $fs->FourthGradingGrade != null ? round(floatval($fs->FourthGradingGrade)) : '');
                $worksheet->setCellValue('K' . $fsRowStart, '=IF(OR(G'.$fsRowStart.'="",J'.$fsRowStart.'=""),"",IF(ISERROR(ROUND(AVERAGE(G'.$fsRowStart.',J'.$fsRowStart.'),0)),"",ROUND(AVERAGE(G'.$fsRowStart.',J'.$fsRowStart.'),0)))');
                $worksheet->setCellValue('L' . $fsRowStart, '=IF(OR(G'.$fsRowStart.'="",J'.$fsRowStart.'="",K'.$fsRowStart.'=""),"",IF(K'.$fsRowStart.'>=75,"PASSED","FAILED"))');

                $fsRowStart++;
            }
            
            $worksheet->setCellValue('J40', '=ROUND(AVERAGE(K'.$fsRowOGStart.':K'.($fsRowStart-1).'),0)');
            $worksheet->setCellValue('K40', '=IF($J$40>=90,ROUND($J$40,3),ROUND($J$40,0))');
            $worksheet->setCellValue('L40', '=IF(K40>74.5,"Promoted","Retained")');
            $worksheet->setCellValue('I14', '=K40');

            /**
             * ADVISER AND PRINCIPAL
             */
            $worksheet->setCellValue('E83', strtoupper(env('PRINCIPAL_NAME')));
            $worksheet->setCellValue('B83', strtoupper(date('m/d/Y')));
        } 
    }

    public static function categorizeParentSubjects($data) {
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
            $frGradeSum = 0;
            $fGradeAve = 0;
            $sGradeAve = 0;
            $tGradeAve = 0;
            $frGradeAve = 0;
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
                    $frGradeSum += floatval(
                        $item['FourthGradingGrade'] != null ? $item['FourthGradingGrade'] : 0,
                    );
                }

                $fGradeAve = $fGradeSum > 0 ? $fGradeSum / count($subs) : 0;
                $sGradeAve = $sGradeSum > 0 ? $sGradeSum / count($subs) : 0;
                $tGradeAve = $tGradeSum > 0 ? $tGradeSum / count($subs) : 0;
                $frGradeAve = $frGradeSum > 0 ? $frGradeSum / count($subs) : 0;
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
                'FirstGradingGrade' => number_format($fGradeAve),
                'SecondGradingGrade' => number_format($sGradeAve),
                'ThirdGradingGrade' => number_format($tGradeAve),
                'FourthGradingGrade' => number_format($frGradeAve),
                'AverageGrade' => null,
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

        return ['MainSubjects' => $mainSubjects, 'GroupSubjects' => $groupedSubjects];
    }
}
