<template>
    <div class="row">
        <!-- FROM -->
        <div class="col-lg-6">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Student Source</span>
                </div>
                <div class="card-body">
                    <span class="text-muted text-sm">LRN - {{ student.LRN }}</span>
                    <h4 class="no-pads">{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</h4>
                    <span class="text-muted text">{{ student.BarangaySpelled + ', ' + student.TownSpelled }}</span>
                    <br>
                    <span class="text-muted text">{{ student.Year + ' ' + student.Section + (isNull(student.Strand) ? '' : (' ' + student.Strand)) }}</span>

                    <div class="divider my-3"></div>

                    <span class="text-muted text-xs">Transactions</span>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm table-bordered">
                            <thead>
                                <th>OR Number</th>
                                <th>Date</th>
                                <th>Particulars</th>
                                <th>Amount Paid</th>
                            </thead>
                            <tbody>
                                <tr v-for="item in detailedTransactions" :key="item.id">
                                    <td>{{ item.ORNumber }}</td>
                                    <td>{{ moment(item.ORDate).format('MMM DD, YYYY') }}</td>
                                    <td>{{ item.Particulars }}</td>
                                    <td class="text-right text-success "><strong>{{ toMoney(parseFloat(item.Amount)) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- TO -->
        <div class="col-lg-6">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-arrow-right ico-tab"></i>Merge to Student</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="text" class="form-control" v-model="search" @keyup="searchStudent()" placeholder="Search LRN, Name, or ID..." autofocus>
                        </div>

                        <div class="col-lg-12 table-responsive">
                            <table class="table table-hover mt-3">
                                <tbody>
                                    <tr v-for="res in results.data" :key="res.id">
                                        <td>
                                            <p class="no-pads"><strong>{{ res.LastName + ', ' + res.FirstName + (isNull(res.MiddleName) ? '' : (' ' + res.MiddleName + ' ')) + (isNull(res.Suffix) ? '' : res.Suffix) }}</strong></p>
                                            <p class="no-pads text-sm text-muted">{{ 'LRN-' + res.LRN + ' • ' + (res.BarangaySpelled + ', ' + res.TownSpelled)}}</p>
                                            <p class="no-pads text-sm text-muted">{{ res.Year + ' ' + res.Section + (isNull(res.Strand) ? '' : (' ' + res.Strand)) }}</p>
                                        </td>
                                        <td class="v-align text-right">
                                            <button v-if="studentId !== res.id" @click="mergeStudent(res.id)" class="btn btn-primary"><i class="fas fa-link ico-tab-mini"></i>Merge</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <pagination :data="results" :limit="10" @pagination-change-page="searchStudent"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            studentId : document.querySelector("meta[name='studentId']").getAttribute('content'),
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            student : {},
            detailedTransactions : [],
            search : '',
            results : {},
        }
    },
    methods : {
        isNull (value) {
            // Check for null or undefined
            if (value === null || value === undefined) {
                return true;
            }

            // Check for empty string
            if (typeof value === 'string' && value.trim() === '') {
                return true;
            }

            // Check for empty array
            if (Array.isArray(value) && value.length === 0) {
                return true;
            }

            // Check for empty object
            if (typeof value === 'object' && !Array.isArray(value) && Object.keys(value).length === 0) {
                return true;
            }

            // Check for NaN
            if (typeof value === 'number' && isNaN(value)) {
                return true;
            }

            // If none of the above, it's not null, empty, or undefined
            return false;
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
        getFromStudent() {
            axios.get(`${ this.baseURL }/students/get-student-details`, {
                params : {
                    StudentId : this.studentId
                }
            })
            .then(response => {
                this.student = response.data.StudentDetails

                this.getDetailedTransactions()
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
        },
        getDetailedTransactions() {
            axios.get(`${ this.baseURL }/transactions/fetched-detailed-transactions-per-student`, {
                params : {
                    StudentId : this.studentId
                }
            })
            .then(response => {
                this.detailedTransactions = response.data
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting detailed transactions'
                })
            })
        },
        searchStudent(page = 1) {
            axios.get(`${ this.baseURL }/students/search-students-paginated`, {
                params : {
                    page : page,
                    SearchParams : this.search
                }
            })
            .then(response => {
                this.results = response.data
            })
            .catch(error => {
                console.log(error.response)
                // this.toast.fire({
                //     icon : 'error',
                //     text : 'Error getting student data!'
                // })
            })
        },
        mergeStudent(studentId) {
            Swal.fire({
                title: "Merge Confirmation",
                showCancelButton: true,
                text : `Transactions on the Source Student will be moved and merged to the selected student. It will also delete some other data. This cannot be undone, proceed with caution.`,
                confirmButtonText: "Proceed Merge",
                confirmButtonColor : '#3a9971'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ this.baseURL }/classes/do-merger`, {
                        _token : this.token,
                        SourceStudentId : this.studentId,
                        DestinationtudentId : studentId,
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Students merged!'
                        })
                        window.history.go(-1)
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error merging students!'
                        })
                    })
                }
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.getFromStudent()
    }
}

</script>