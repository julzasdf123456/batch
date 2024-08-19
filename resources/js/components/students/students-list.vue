<template>
    <div class="row">
        <!-- options -->
        <div class="col-lg-12">
            <div class="row">
                <!-- sy -->
                <div class="col-lg-2">
                    <div class="form-group">
                        <span class="text-muted">School Year</span>
                        <select class="form-control form-control-sm" v-model="schoolYearSelected">
                            <option v-for="sy in schoolYears" :value="sy.id">{{ sy.SchoolYear }}</option>
                        </select>
                    </div>
                </div>
                
                <!-- class -->
                <div class="col-lg-2">
                    <div class="form-group">
                        <span class="text-muted">Class/Section</span>
                        <select class="form-control form-control-sm" v-model="classSelected">
                            <option value="All">All</option>
                            <option v-for="c in gradeLevels" :value="c.id">{{ c.Year + ' - ' + c.Section + (isNull(c.Strand) ? '' : (' (' + c.Strand + (isNull(c.Semester) ? '' : (' • ' + c.Semester + ' Semester')) + ')')) }}</option>
                        </select>
                    </div>
                </div>
                
                <!-- status -->
                <div class="col-lg-2">
                    <div class="form-group">
                        <span class="text-muted">Student Status</span>
                        <select class="form-control form-control-sm" v-model="studentStatus">
                            <option value="Active">Active</option>
                            <option value="Transferred to Another School">Transferred to Another School</option>
                            <option value="Withdrawn">Withdrawn</option>
                            <option value="Dropped Out">Dropped Out</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-4">
                    <span class="text-muted">Actions</span>
                    <br>
                    <button @click="getResults()" class="btn btn-sm btn-default"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                    <button @click="printResults()" class="btn btn-sm btn-primary ml-1"><i class="fas fa-print ico-tab-mini"></i>Print</button>
                </div>
            </div>
        </div>

        <!-- results -->
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="text-muted"><i class="fas fa-list ico-tab"></i>Results</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered">
                        <thead>
                            <th style="width: 35px;"></th>
                            <th class="text-muted">LRN</th>
                            <th class="text-muted">Last Name</th>
                            <th class="text-muted">First Name</th>
                            <th class="text-muted">Middle Name</th>
                            <th class="text-muted">Gender</th>
                            <th class="text-muted">Address</th>
                            <th class="text-muted">Birth Date</th>
                            <th class="text-muted">Contact Numbers</th>
                        </thead>
                        <tbody>
                            <tr v-for="(student, index) in studentData" :key="student.id">
                                <td>{{ (index+1) }}</td>
                                <td class="v-align">{{ student.LRN }}</td>
                                <td class="v-align"><strong>{{ student.LastName }}</strong></td>
                                <td class="v-align"><strong>{{ student.FirstName + (isNull(student.Suffix) ? '' : (' ' + student.Suffix)) }}</strong></td>
                                <td class="v-align"><strong>{{ student.MiddleName }}</strong></td>
                                <th class="text-muted">{{ student.Gender }}</th>
                                <td class="v-align">{{ (isNull(student.Sitio) ? '' : student.Sitio) + ', ' + student.BarangaySpelled + ', ' + student.TownSpelled }}</td>
                                <td class="v-align">{{ isNull(student.Birthdate) ? '-' : moment(student.Birthdate).format('MMM DD, YYYY') }}</td>
                                <td class="v-align">{{ isNull(student.ContactNumber) ? '-' : student.ContactNumber }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

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
    name : 'StudentsList.students-list',
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
            semester : '',
            studentStatus : '',
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
            axios.get(`${ this.baseURL }/school_years/get-school-years`) 
            .then(response => {
                this.schoolYears = response.data

                if (this.schoolYears.length > 0) {
                    this.schoolYearSelected = this.schoolYears[0].id
                }
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting school years'
                })
            })
        },
        getResults() {
            axios.get(`${ this.baseURL }/students/get-students-list`, {
                params : {
                    SchoolYearId : this.schoolYearSelected,
                    ClassRepo : this.classSelected,
                    Status : this.studentStatus,
                }
            }) 
            .then(response => {
                this.studentData = response.data
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting students'
                })
            })
        },
        printResults() {
            window.location.href = `${this.baseURL}/students/print-students-list/${ this.schoolYearSelected }/${ this.classSelected }/${ this.studentStatus }`
        }
    },
    created() {
    },
    mounted() {
        this.getGradeLevels()
        this.getSchoolYears()
    }
}

</script>