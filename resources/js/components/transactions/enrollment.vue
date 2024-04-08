<template>
    <div class="row">
        <!-- SEARCH -->
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <input type="text" placeholder="Search student name or ID..." :autofocus="true" class="form-control" v-model="search" @keyup="searchEnrollee">
                </div>
                <div class="card-body table-responsive">
                    <p class="text-muted"><i>Payment Queue</i></p>
                    <table class="table table-hover table-sm">
                        <tbody>
                            <tr v-for="student in students.data" :key="student.id" style="cursor: pointer;">
                                <td @click="fetchPayment(student.id)" class="v-align text-muted">{{ student.id }}</td>
                                <td @click="fetchPayment(student.id)" class="v-align"><strong>{{ student.FirstName + ' ' + (isNull(student.MiddleName) ? '' : student.MiddleName) + ' ' + student.LastName + ' ' + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <pagination :data="students" :limit="10" @pagination-change-page="searchEnrollee"></pagination>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <div class="col-lg-8 col-md-6">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="text-muted">{{ isNull(activeStudent) ? '' : activeStudent.id }}</span>
                    <h4>{{ isNull(activeStudent) ? '' : (activeStudent.FirstName + ' ' + (isNull(activeStudent.MiddleName) ? '' : activeStudent.MiddleName) + ' ' + activeStudent.LastName + ' ' + (isNull(activeStudent.Suffix) ? '' : activeStudent.Suffix)) }}</h4>
                </div>
                <div class="card-body table-responsive">
                    <span class="text-muted">Payment Details</span>
                    <table class="table table-hover table-sm">
                        <tbody>
                            <tr v-for="payable in payables" :key="payable.id">
                                <td>{{ payable.PaymentFor }}</td>
                                <td class="text-right"><strong>{{ toMoney(parseFloat(payable.Balance)) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary float-right">Submit Payment <i class="fas fa-check-circle ico-tab-mini-left"></i></button>
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
            students : {},
            search : '',
            activeStudent : {},
            payables : [],
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
        searchEnrollee(page = 1) {
            axios.get(`${ this.baseURL }/transactions/get-enrollment-queue`, {
                params : {
                    page : page,
                    Search : this.search,
                }
            })
            .then(response => {
                this.students = response.data
            })
            .catch(error => {
                console.log(error)
                // this.toast.fire({
                //     icon : 'error',
                //     text : 'Error searching application'
                // })
            })
        },
        fetchPayment(studentId) {
            this.activeStudent = this.students.data.filter(obj => obj.id === studentId)[0]

            if (!this.isNull(this.activeStudent)) {
                axios.get(`${ this.baseURL }/transactions/get-enrollment-payables`, {
                    params : {
                        StudentId : this.activeStudent.id,
                    }
                })
                .then(response => {
                    this.payables = response.data
                })
                .catch(error => {
                    console.log(error)
                    this.toast.fire({
                        icon : 'error',
                        text : 'Error getting payables'
                    })
                })
            }
        }
    },
    created() {
    },
    mounted() {
       this.searchEnrollee()
    }
}

</script>