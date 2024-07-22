<template>
    <div class="row px-2">
        <!-- student info -->
        <div class="col-lg-12">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-lg-12">
                        <div style="display: flex; padding-bottom: 15px;">
                            <div style="width: 88px; display: inline;">
                                <img id="prof-img" style="width: 65px !important;" class="profile-user-img img-fluid img-circle" :src="imgPath + 'prof-img.png'" alt="User profile picture">
                            </div>
                            <div>
                                <span>
                                    <p class="no-pads" style="font-size: 1.85em;"><strong>{{ studentData.LastName + ', ' + studentData.FirstName + (isNull(studentData.MiddleName) ? '' : (' ' + studentData.MiddleName + ' ')) + (isNull(studentData.Suffix) ? '' : studentData.Suffix) }}</strong></p>
                                    
                                    <span class="text-muted">
                                        <i class="fas fa-id-badge ico-tab-mini"></i>LRN-{{ studentData.LRN }} 
                                        <span class="badge" :class="isNull(studentData.Status) ? 'bg-success' : 'bg-danger'" title="Status">{{ isNull(studentData.Status) ? 'Studying' : studentData.Status }}</span>
                                        <span class="badge bg-warning ico-tab-left-mini" title="From what school">{{ studentData.FromSchool }}</span>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider mb-3"></div>
        </div>

        <!-- current class enrolled -->
        <div class="col-lg-6">
            <p><strong>Current Class/Strand Enrolled</strong> <i class="fas fa-arrow-down ico-tab-left-mini"></i></p>
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    
                    <h4 class="no-pads">{{ classData.Year }} - {{ classData.Section }}</h4>
                    <p class="text-muted no-pads">{{ isNull(classData.Strand) ? '' : classData.Strand }} {{ isNull(classData.Semester) ? '' : (' • ' + classData.Semester + ' Sem') }}</p>

                    <div class="divider my-3"></div>

                    <span class="text-muted"><i class="fas fa-book ico-tab-mini"></i>Subjects Enrolled</span>
                    <table class="table table-hover table-sm table-borderless">
                        <thead>
                            <th class="tet-muted">Subject</th>
                            <th class="tet-muted">Teacher</th>
                        </thead>
                        <tbody>
                            <tr v-for="s in subjects" :key="s.StudentSubjectId">
                                <td>• {{ s.Subject }}</td>
                                <td class="text-muted">{{ s.FullName }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- transfer to -->
        <div class="col-lg-6">
            <p><strong>Transfer To</strong> <i class="fas fa-forward ico-tab-left-mini"></i></p>
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">School Year: </td>
                                <td class="v-align">
                                    <select class="form-control" v-model="schoolYearSelected" @change="validateSY(schoolYearSelected)">
                                        <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">{{ sy.SchoolYear }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Select Class/Strand: </td>
                                <td class="v-align">
                                    <select class="form-control" v-model="classSelected" @change="getSubjectsInClass()">
                                        <option v-for="grade in gradeLevels" :key="grade.id" :value="grade.id">{{ grade.Year + ' - ' + grade.Section + (isNull(grade.Strand) ? '' : (' (' + grade.Strand + (isNull(grade.Semester) ? '' : (' • ' + grade.Semester + ' Semester')) + ')')) }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Semester: </td>
                                <td class="v-align">
                                    <div class="input-group-radio-sm">
                                        <input type="radio" id="Not-Applicable" value="" v-model="semester" class="custom-radio-sm">
                                        <label for="Not-Applicable" class="custom-radio-label-sm">Not Applicable</label>

                                        <input type="radio" id="1st" value="1st" v-model="semester" class="custom-radio-sm">
                                        <label for="1st" class="custom-radio-label-sm">1st Sem</label>
                                        
                                        <input type="radio" id="2nd" value="2nd" v-model="semester" class="custom-radio-sm">
                                        <label for="2nd" class="custom-radio-label-sm">2nd Sem</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Reason for Transer: </td>
                                <td class="v-align">
                                    <textarea v-model="reason" class="form-control" placeholder="Reason for transfer of class/strand..."></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="divider my-3"></div>

                    <span class="text-muted"><i class="fas fa-book ico-tab-mini"></i>Subjects in this Class</span>
                    <table class="table table-hover table-sm">
                        <thead>
                            <th class="text-muted" colspan="2">Subject</th>
                            <th class="text-muted" colspan="2">Teacher/Instructor</th>
                        </thead>
                        <tbody>
                            <tr v-for="subject in subjectsInClass" :key="subject.id">
                                <td @click="selectSubject(subject.SubjectClassId)" class="v-align" style="width: 30px; cursor: pointer;"><i class="fas fa-check-circle " :class="subject.Selected ? 'text-success' : 'text-gray'"></i></td>
                                <td @click="selectSubject(subject.SubjectClassId)" class="v-align" style="cursor: pointer;">{{ subject.Subject }}</td>
                                <td @click="selectSubject(subject.SubjectClassId)" class="v-align" style="cursor: pointer;">{{ subject.Fullname }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>

    
    <div class="right-bottom">
        <button @click="transfer()" class="btn-floating shadow btn-primary">Confirm Transfer <i class="fas fa-check-circle ico-tab-left-mini"></i></button>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    components : {
        FlatPickr,
        Swal,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            imgPath : axios.defaults.imgsPath,
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            studentId : document.querySelector("meta[name='student-id']").getAttribute('content'),
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            studentData : {},
            classData : {},
            subjects : [],
            schoolYearSelected : '',
            classSelected : '',
            gradeLevels : [],
            schoolYears : [],
            subjectsInClass : [],
            semester : '',
            reason : '',
        }
    },
    methods : {
        isNull (item) {
            if (jquery.isEmptyObject(item)) {
                return true;
            } else {
                return false;
            }
        },
        toMoney(value) {
            return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
        },
        isNumber(value) {
            return typeof value === 'number';
        },        
        round(value) {
            return Math.round((value + Number.EPSILON) * 100) / 100;
        },
        generateRandomString(length) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                result += characters.charAt(randomIndex);
            }

            return result;
        },
        generateUniqueId() {
            return moment().valueOf() + "-" + this.generateRandomString(32);
        },
        getStudentClassData() {
            axios.get(`${ this.baseURL }/students/get-student-class-details`, {
                params : {
                    StudentId : this.studentId,
                }
            })
            .then(response => {
                this.studentData = response.data.Student
                this.classData = response.data.Class
                this.subjects = response.data.Subjects
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
        },
        getSchoolYears() {
            axios.get(`${ this.baseURL }/school_years/get-school-years`) 
            .then(response => {
                this.schoolYears = response.data

                if (this.schoolYears.length > 0) {
                    this.schoolYearSelected = this.schoolYears[0].id
                }
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting school years'
                })
            })
        },
        getGradeLevels() {
            axios.get(`${ this.baseURL }/classes_repos/get-grade-levels`) // IF PORT 80 DIRECT FROM APACHE
            .then(response => {
                this.gradeLevels = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting grade levels'
                })
            })
        },
        getSubjectsInClass() {
            axios.get(`${ this.baseURL }/classes_repos/get-subjects-in-class`, {
                params : {
                    ClassRepoId : this.classSelected
                }
            })
            .then(response => {
                this.subjectsInClass = response.data

                // select semester
                const c = this.gradeLevels.find(obj => obj.id === this.classSelected)

                if (!this.isNull(c)) {
                    if (!this.isNull(c.Semester)) {
                        this.semester = c.Semester
                    } else {
                        this.semester = ''
                    }
                } else {
                    this.semester = ''
                }
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting subjects!'
                })
            })
        },
        selectSubject(subjectClassId) {
            this.subjectsInClass = this.subjectsInClass.map(obj => {
                if (obj.SubjectClassId === subjectClassId) {
                    return { ...obj, Selected: !obj.Selected };
                }
                return obj; 
            })
        },
        transfer() {
            if (this.isNull(this.classSelected)) {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please select a class to transfer the student in!'
                })
            } else {
                if (this.isNull(this.schoolYearSelected)) {
                    this.toast.fire({
                        icon : 'info',
                        text : 'Please select school year!'
                    })
                } else {
                    Swal.fire({
                        title: "Transfer Confirmation",
                        showCancelButton: true,
                        html: `
                            <p style='text-align: left;'>By proceeding, the student will be transfered to the selected class/strand. <strong>NOTE </strong> that all the payments 
                                the student has transacted on the current class shall be credited and transfered to the selected class/strand tuition fees. Proceed with caution. </p>
                        `,
                        confirmButtonText: "Transfer Now",
                        confirmButtonColor : '#3a9971'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.post(`${ this.baseURL }/classes/save-transfer`, {
                                _token : this.token,
                                StudentId : this.studentId,
                                CurrentClassId : this.classData.id,
                                SchoolYearId : this.schoolYearSelected,
                                TransferedClassId : this.classSelected,
                                Subjects : this.subjectsInClass,
                                Reason : this.reason,
                                Semester : this.semester,
                            }) 
                            .then(response => {
                                this.toast.fire({
                                    icon : 'success',
                                    text : 'Student transfered!'
                                })

                                window.location.href = this.baseURL + '/students/' + this.studentId
                            })
                            .catch(error => {
                                console.log(error.response)
                                Swal.fire({
                                    icon : 'error',
                                    text : error.response.data
                                })
                            })
                        }
                    })
                }
            }
        }
    },
    created() {
        
    },
    mounted() {
        this.getSchoolYears()
        this.getGradeLevels()
        this.getStudentClassData()
    }
}

</script>