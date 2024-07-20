<template>
    <div class="row">
        <div class="col-lg-4 col-md-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title text-muted"><i class="fas fa-user-circle ico-tab"></i>Student Info</span>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover table-borderless">
                        <tbody>
                            <tr title="Student name">
                                <td class="text-muted v-align"><i class="fas fa-user"></i></td>
                                <td class="v-align"><strong>{{ studentData.FirstName + ' ' + (isNull(studentData.MiddleName) ? '' : studentData.MiddleName) + ' ' + studentData.LastName + ' ' + (isNull(studentData.Suffix) ? '' : studentData.Suffix) }}</strong></td>
                            </tr>
                            <tr title="Student ID">
                                <td class="text-muted v-align"><i class="fas fa-hashtag"></i></td>
                                <td class="v-align">LRN-{{ studentData.LRN }}</td>
                            </tr>
                            <tr title="Address">
                                <td class="text-muted v-align"><i class="fas fa-map-marker-alt"></i></td>
                                <td class="v-align">{{ (isNull(studentData.Sitio) ? '' : studentData.Sitio) + ', ' + studentData.BarangaySpelled + ', ' + studentData.TownSpelled }}</td>
                            </tr>
                            <tr title="Gender">
                                <td class="text-muted v-align"><i class="fas fa-venus-mars"></i></td>
                                <td class="v-align">{{ studentData.Gender }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
        <!-- Enrollment Form -->
        <div class="col-lg-8 col-md-12"> 
            <!-- FORM -->
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title text-muted"><i class="fas fa-info-circle ico-tab"></i>Enrollment Form</span>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">School Year: </td>
                                <td class="v-align">
                                    <select class="form-control" v-model="schoolYearSelected" @change="validateSY(schoolYearSelected)">
                                        <option value="Create New">-- Create New School Year --</option>
                                        <option v-for="sy in schoolYears" :key="sy.id" :value="sy.id">{{ sy.SchoolYear }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Enroll In Class: </td>
                                <td class="v-align">
                                    <select class="form-control" v-model="classSelected" @change="getSubjectsInClass()">
                                        <option v-for="grade in gradeLevels" :key="grade.id" :value="grade.id">{{ grade.Year + ' - ' + grade.Section + (isNull(grade.Strand) ? '' : (' (' + grade.Strand + (isNull(grade.Semester) ? '' : (' • ' + grade.Semester + ' Semester')) + ')')) }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Enrollment Type: </td>
                                <td class="v-align">
                                    <div class="input-group-radio-sm">
                                        <input type="radio" id="Regular" value="Regular" v-model="type" class="custom-radio-sm">
                                        <label for="Regular" class="custom-radio-label-sm">Regular</label>

                                        <input type="radio" id="Transferee" value="Transferee" v-model="type" class="custom-radio-sm">
                                        <label for="Transferee" class="custom-radio-label-sm">Transferee</label>
                                    </div>
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
                                <td class="text-muted v-align">With Scholarship?</td>
                                <td class="v-align">
                                    <div class="input-group-radio-sm">
                                        <input type="radio" id="Yes" value="Yes" v-model="withScholarship" class="custom-radio-sm">
                                        <label for="Yes" class="custom-radio-label-sm">Yes</label>
                                        
                                        <input type="radio" id="No" value="No" v-model="withScholarship" class="custom-radio-sm">
                                        <label for="No" class="custom-radio-label-sm">No</label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    
                </div>
            </div>

            <!-- SUBJECTS -->
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title text-muted"><i class="fas fa-book ico-tab"></i>Subjects in this Class</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm">
                        <thead>
                            <th class="text-muted" colspan="2">Subject</th>
                            <th class="text-muted" colspan="2">Teacher/Instructor</th>
                        </thead>
                        <tbody>
                            <tr v-for="subject in subjects" :key="subject.id">
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
        <button @click="saveEnrollment()" class="btn-floating shadow btn-primary">Submit Enrollee <i class="fas fa-check-circle ico-tab-left-mini"></i></button>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'Enroll.enroll',
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination,
        jquery,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            token : document.querySelector("meta[name='token']").getAttribute('content'),
            studentId : document.querySelector("meta[name='studentId']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            pickerOptions: {
                enableTime: false,
                dateFormat: 'Y-m-d', 
            },
            studentData : {},
            gradeLevels : [],
            schoolYears : [],
            classSelected : '',
            schoolYearSelected : '',
            subjects : [],
            type : 'Regular',
            semester : '',
            withScholarship : 'No',
        }
    },
    methods : {
        isNull (item) {
            if (jquery.isEmptyObject(item)) {
                return true;
            } else {
                if (item.length < 1) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        toMoney(value) {
            if (this.isNumber(value)) {
                return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
            } else {
                return '-'
            }
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
        generateId() {
            return moment().valueOf()
        },
        getStudentInfo() {
            axios.get(`${ this.baseURL }/students/get-student`, {
                params : {
                    id : this.studentId,
                }
            }) // IF PORT 80 DIRECT FROM APACHE
            .then(response => {
                this.studentData = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student info'
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
        getSchoolYears() {
            axios.get(`${ this.baseURL }/school_years/get-school-years`) // IF PORT 80 DIRECT FROM APACHE
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
        validateSY(sy) {
            if (sy === 'Create New') {
                Swal.fire({
                    title: 'Add New School Year',
                    html:
                        `
                        <span class='text-muted float-left'>School Year</span>
                        <input id="schoolYear" class="form-control" type="text" placeholder="e.g.: S.Y. ${moment().format('YYYY')} - ${moment().add(1, 'Y').format('YYYY')}">
                        <span class='text-muted float-left mt-3'>Start of Classes Date</span>
                        <input id="schoolYearStart" class="form-control mt-2" type="date" placeholder="Start of Class Date">
                        `,
                    focusConfirm: false,
                    confirmButtonText: 'Save',
                    preConfirm: () => {
                        const schoolYear = document.getElementById('schoolYear').value;
                        const schoolYearStart = document.getElementById('schoolYearStart').value;
                        const syId = this.generateId()

                        if (!schoolYear || !schoolYearStart) {
                            Swal.showValidationMessage('Both fieldss are required.');
                            return false;
                        }

                        return fetch(this.baseURL + '/schoolYears', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                id : syId,
                                SchoolYear : schoolYear,
                                MonthStart : schoolYearStart
                            })
                        })
                        .then(response => response.json().then(data => ({
                            status: response.status,
                            body: data
                        })))
                        .then(({ status, body }) => {
                            if (status !== 200) {
                                console.log('Server response:', body);
                                Swal.showValidationMessage(`Request failed: ${body.message || 'Unknown error'}`);
                                return false;
                            }
                            return body
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                            console.log(error.message)
                        })
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.schoolYears.push(result.value)
                        this.schoolYearSelected = result.value.SchoolYear

                        this.toast.fire({
                            icon : 'success',
                            text : 'School Year Added!'
                        })

                        location.reload()
                    }
                })

                // =============================================
                // OLD
                // (async () => {
                //     const { value: text } = await Swal.fire({
                //         input: 'text',
                //         inputPlaceholder: 'e.g.: S.Y. ' + moment().format('YYYY') + ' - ' + moment().add(1, 'Y').format('YYYY'),
                //         inputAttributes: {
                //             'aria-label': 'Type your remarks here'
                //         },
                //         title: 'Add New School Year',
                //         showCancelButton: true
                //     })

                //     if (text) {
                //         if (text.length < 1) {
                //             this.toast.fire({
                //                 icon : 'info',
                //                 text : 'Please provide school year!',
                //             })
                //         } else { 
                //             var syId = this.generateId()
                //             axios.post(`${ this.baseURL }/schoolYears`, {
                //                 _token : this.token,
                //                 id : syId,
                //                 SchoolYear : text,
                //             }) // IF PORT 80 DIRECT FROM APACHE
                //             .then(response => {
                //                 this.toast.fire({
                //                     icon : 'success',
                //                     text : 'School year added!'
                //                 })
                //                 this.schoolYears.push(response.data)
                //                 this.schoolYearSelected = response.data.SchoolYear
                //             })
                //             .catch(error => {
                //                 console.log(error.response)
                //                 Swal.fire({
                //                     icon : 'error',
                //                     text : 'Error adding school year!'
                //                 })
                //             })
                //         }
                //     }
                // })()
            }
            
        },
        saveEnrollment() {
            if (this.isNull(this.classSelected)) {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please select a class to enroll in!'
                })
            } else {
                if (this.isNull(this.schoolYearSelected)) {
                    this.toast.fire({
                        icon : 'info',
                        text : 'Please select school year!'
                    })
                } else {
                    Swal.fire({
                        title: "Enrollment Confirmation",
                        showCancelButton: true,
                        html: `
                            <p style='text-align: left;'>By proceeding, this enrollment application shall be forwarded to the cashier for the following payment: </p>
                            <br>
                            <ul>
                                <li style='text-align: left;'>Enrollment Fees: <strong>P 500.00</strong></li>
                                <li style='text-align: left;'>Miscellaneous Fees: <strong>P 230.00</strong></li>
                            </ul>
                        `,
                        confirmButtonText: "Proceed Enrollment to Cashier",
                        confirmButtonColor : '#3a9971'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.post(`${ this.baseURL }/classes/save-enrollment`, {
                                _token : this.token,
                                StudentId : this.studentId,
                                ClassRepoId : this.classSelected,
                                SchoolYearId : this.schoolYearSelected,
                                Subjects : this.subjects,
                                Type : this.type,
                                Semester : this.semester,
                            }) 
                            .then(response => {
                                this.toast.fire({
                                    icon : 'success',
                                    text : 'Enrollment forwarded to cashier!'
                                })

                                if (this.withScholarship === 'Yes') {
                                    window.location.href = this.baseURL + '/student_scholarships/scholarship-wizzard/' + this.studentId + '/enrollment'
                                } else {
                                    window.location.href = this.baseURL + '/classes/existing-student'
                                }
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
            
        },
        getSubjectsInClass() {
            axios.get(`${ this.baseURL }/classes_repos/get-subjects-in-class`, {
                params : {
                    ClassRepoId : this.classSelected
                }
            })
            .then(response => {
                this.subjects = response.data

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
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting subjects!'
                })
            })
        },
        selectSubject(subjectClassId) {
            this.subjects = this.subjects.map(obj => {
                if (obj.SubjectClassId === subjectClassId) {
                    return { ...obj, Selected: !obj.Selected };
                }
                return obj; 
            })
        }
    },
    created() {
    },
    mounted() {
        this.getStudentInfo()
        this.getGradeLevels()
        this.getSchoolYears()
    }
}

</script>