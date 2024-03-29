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
                                <td class="v-align">{{ studentData.id }}</td>
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
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title text-muted"><i class="fas fa-info-circle ico-tab"></i>Enrollment Form</span>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">School Year</td>
                                <td class="v-align">
                                    <select class="form-control" v-model="schoolYearSelected" @change="validateSY(schoolYearSelected)">
                                        <option value="Create New">-- Create New School Year --</option>
                                        <option v-for="sy in schoolYears" :key="sy.id" :value="sy.SchoolYear">{{ sy.SchoolYear }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Enroll In</td>
                                <td class="v-align">
                                    <select class="form-control" v-model="classSelected">
                                        <option v-for="grade in gradeLevels" :key="grade.id" :value="grade.id">{{ grade.Year + ' - ' + grade.Section }}</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button @click="saveEnrollment()" class="btn btn-primary float-right">Submit Enrollee <i class="fas fa-check-circle ico-tab-left-mini"></i></button>
                </div>
            </div>
        </div>
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
                (async () => {
                    const { value: text } = await Swal.fire({
                        input: 'text',
                        inputPlaceholder: 'e.g.: S.Y. ' + moment().format('YYYY') + ' - ' + moment().add(1, 'Y').format('YYYY'),
                        inputAttributes: {
                            'aria-label': 'Type your remarks here'
                        },
                        title: 'Add New School Year',
                        showCancelButton: true
                    })

                    if (text) {
                        if (text.length < 1) {
                            this.toast.fire({
                                icon : 'info',
                                text : 'Please provide school year!',
                            })
                        } else { 
                            var syId = this.generateId()
                            axios.post(`${ this.baseURL }/schoolYears`, {
                                _token : this.token,
                                id : syId,
                                SchoolYear : text,
                            }) // IF PORT 80 DIRECT FROM APACHE
                            .then(response => {
                                this.toast.fire({
                                    icon : 'success',
                                    text : 'School year added!'
                                })
                                this.schoolYears.push(response.data)
                                this.schoolYearSelected = response.data.SchoolYear
                            })
                            .catch(error => {
                                console.log(error.response)
                                Swal.fire({
                                    icon : 'error',
                                    text : 'Error adding school year!'
                                })
                            })
                        }
                    }
                })()
            }
            
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