<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    public $table = 'Students';

    public $fillable = [
        'id',
        'FirstName',
        'MiddleName',
        'LastName',
        'Suffix',
        'Birthdate',
        'Gender',
        'Sitio',
        'Barangay',
        'Town',
        'ContactNumber',
        'Status',
        'CurrentGradeLevel',
        'LRN',
        'PlaceOfBirth',
        'Indigenousity',
        'Beneficiary4PsIDNumber',
        'PermanentTown',
        'PermanentBarangay',
        'PermanentSitio',
        'ZipCode',
        'PermanentZipCode',
        'FatherFirstName',
        'FatherMiddleName',
        'FatherLastName',
        'FatherContactNumber',
        'MotherFirstName',
        'MotherMiddleName',
        'MotherLastName',
        'MotherContactNumber',
        'GuardianFirstName',
        'GuardianMiddleName',
        'GuardianLastName',
        'GuardianContactNumber',
        'PSABirthCertificateNumber',
        'MotherTounge',
        'FromSchool',
        'ESCScholar',
        'JHSSchoolGraduated',
        'JHSSchoolAddress',
        'JHSDateGraduated',
        'ElementarySchoolGraduated',
        'ElementarySchoolAddress',
        'ElementaryDateGraduated',
        'JHSAverageGrade',
    ];

    protected $casts = [
        'id' => 'string',
        'FirstName' => 'string',
        'MiddleName' => 'string',
        'LastName' => 'string',
        'Suffix' => 'string',
        'Birthdate' => 'date',
        'Gender' => 'string',
        'Sitio' => 'string',
        'Barangay' => 'string',
        'Town' => 'string',
        'ContactNumber' => 'string',
        'Status' => 'string',
        'CurrentGradeLevel' => 'string',        
        'LRN' => 'string',
        'PlaceOfBirth' => 'string',
        'Indigenousity' => 'string',
        'Beneficiary4PsIDNumber' => 'string',
        'PermanentTown' => 'string',
        'PermanentBarangay' => 'string',
        'PermanentSitio' => 'string',
        'ZipCode' => 'string',
        'PermanentZipCode' => 'string',
        'FatherFirstName' => 'string',
        'FatherMiddleName' => 'string',
        'FatherLastName' => 'string',
        'FatherContactNumber' => 'string',
        'MotherFirstName' => 'string',
        'MotherMiddleName' => 'string',
        'MotherLastName' => 'string',
        'MotherContactNumber' => 'string',
        'GuardianFirstName' => 'string',
        'GuardianMiddleName' => 'string',
        'GuardianLastName' => 'string',
        'GuardianContactNumber' => 'string',
        'PSABirthCertificateNumber' => 'string',
        'MotherTounge' => 'string',
        'FromSchool' => 'string',
        'ESCScholar' => 'string',
        'JHSSchoolGraduated' => 'string',
        'JHSSchoolAddress' => 'string',
        'JHSDateGraduated' => 'string',
        'ElementarySchoolGraduated' => 'string',
        'ElementarySchoolAddress' => 'string',
        'ElementaryDateGraduated' => 'string',
        'JHSAverageGrade' => 'string',
    ];

    public static array $rules = [
        'FirstName' => 'nullable|string|max:60',
        'MiddleName' => 'nullable|string|max:60',
        'LastName' => 'nullable|string|max:60',
        'Suffix' => 'nullable|string|max:60',
        'Birthdate' => 'nullable',
        'Gender' => 'nullable|string|max:50',
        'Sitio' => 'nullable|string|max:60',
        'Barangay' => 'nullable|string|max:50',
        'Town' => 'nullable|string|max:50',
        'ContactNumber' => 'nullable|string|max:90',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Status' => 'nullable|string',
        'CurrentGradeLevel' => 'nullable|string',     
        'LRN' => 'nullable|string',
        'PlaceOfBirth' => 'nullable|string',
        'Indigenousity' => 'nullable|string',
        'Beneficiary4PsIDNumber' => 'nullable|string',
        'PermanentTown' => 'nullable|string',
        'PermanentBarangay' => 'nullable|string',
        'PermanentSitio' => 'nullable|string',
        'ZipCode' => 'nullable|string',
        'PermanentZipCode' => 'nullable|string',
        'FatherFirstName' => 'nullable|string',
        'FatherMiddleName' => 'nullable|string',
        'FatherLastName' => 'nullable|string',
        'FatherContactNumber' => 'nullable|string',
        'MotherFirstName' => 'nullable|string',
        'MotherMiddleName' => 'nullable|string',
        'MotherLastName' => 'nullable|string',
        'MotherContactNumber' => 'nullable|string',
        'GuardianFirstName' => 'nullable|string',
        'GuardianMiddleName' => 'nullable|string',
        'GuardianLastName' => 'nullable|string',
        'GuardianContactNumber' => 'nullable|string',
        'PSABirthCertificateNumber' => 'nullable|string',
        'MotherTounge' => 'nullable|string',
        'FromSchool' => 'nullable|string',
        'ESCScholar' => 'nullable|string',
        'JHSSchoolGraduated' => 'nullable|string',
        'JHSSchoolAddress' => 'nullable|string',
        'JHSDateGraduated' => 'nullable|string',
        'ElementarySchoolGraduated' => 'nullable|string',
        'ElementarySchoolAddress' => 'nullable|string',
        'ElementaryDateGraduated' => 'nullable|string',
        'JHSAverageGrade' => 'nullable|string',
    ];

    public static function formatNameFormal($student) {
        return $student->LastName . ', ' . $student->FirstName . ($student->MiddleName != null ? (' ' . $student->MiddleName . ' ') : '') . ($student->Suffix != null ? $student->Suffix . ' ' : '');
    }
}
