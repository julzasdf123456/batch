<template>
    <div class="row">
        <!-- SEARCH -->
        <div class="col-lg-4 col-md-12">
            <!-- <div style="border-bottom: dotted 3px #aeaeae; margin-bottom: 18px; padding-bottom: 10px;">
                <span class="text-muted">Search</span>
                <span class="float-right"><i class="fas fa-search text-muted text-right"></i></span>
            </div> -->
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

        <!-- PAYMENT DETAILS -->
        <div class="col-lg-4">
            <!-- <div style="border-bottom: dotted 3px #aeaeae; margin-bottom: 18px; padding-bottom: 10px;">
                <span class="text-muted">View</span>
                <span class="float-right"><i class="fas fa-mouse-pointer text-muted text-right"></i></span>
            </div> -->
            <span class="text-muted">{{ isNull(activeStudent) ? '' : activeStudent.id }}</span>
            <h4>{{ isNull(activeStudent) ? '' : (activeStudent.FirstName + ' ' + (isNull(activeStudent.MiddleName) ? '' : activeStudent.MiddleName) + ' ' + activeStudent.LastName + ' ' + (isNull(activeStudent.Suffix) ? '' : activeStudent.Suffix)) }}</h4>
            <br>
            <span class="text-muted">Payment Details</span>
            <table class="table table-hover table-sm table-borderless">
                <tbody>
                    <tr>
                        <td>{{ payables.PaymentFor }}</td>
                        <td class="text-right"><strong>{{ toMoney(parseFloat(payables.Balance)) }}</strong></td>
                    </tr>
                    <tr style="border-top: 1px solid #aeaeae;">
                        <td><strong>TOTAL PAYABLE AMOUNT</strong></td>
                        <td class="text-right text-danger"><strong><h2>{{ toMoney(getTotalPayable(payables)) }}</h2></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- FORM -->
        <div class="col-lg-4 col-md-12">
            <!-- <div style="border-bottom: dotted 3px #aeaeae; margin-bottom: 18px; padding-bottom: 10px;">
                <span class="text-muted">Transact</span>
                <span class="float-right"><i class="fas fa-dollar-sign text-muted text-right"></i></span>
            </div> -->
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <div>
                        <div class="input-group-radio-sm">
                            <label for="Cash" class="custom-radio-label-sm">OR Number</label>
                        </div>
                        <div class="ml-4">
                            <input type="number" class="form-control" placeholder="OR Number..." autofocus v-model="orNumber">
                        </div>
                    </div>
                    <!-- Cash -->
                    <div class="mt-4">
                        <div class="input-group-radio-sm">
                            <!-- <input type="radio" id="Cash" value="Cash" v-model="paymentType" class="custom-radio-sm"> -->
                            <label for="Cash" class="custom-radio-label-sm">Cash</label>
                        </div>
                        <div class="ml-4">
                            <input type="number" class="form-control" ref="cashInput" placeholder="Cash amount..." autofocus v-model="cashAmount" @keyup="getTotalPayments" @keydown.enter="handleEnterKey">
                        </div>
                    </div>
                    <!-- Check -->
                    <div class="mt-4">
                        <div class="input-group-radio-sm">
                            <!-- <input type="radio" id="Check" value="Check" v-model="paymentType" class="custom-radio-sm"> -->
                            <label for="Check" class="custom-radio-label-sm">Check</label>
                        </div>
                        <div class="ml-4 row">
                            <div class="col-lg-7 col-md-12">
                                <input title="Check number" type="text" class="form-control" placeholder="Check number..." autofocus v-model="checkNumber">
                            </div>
                            <div class="col-lg-5 col-md-12">
                                <input title="Bank" type="text" class="form-control" placeholder="Bank..." autofocus v-model="checkBank">
                            </div>
                            <div class="col-lg-12 mt-1">
                                <input title="Check amount" type="number" class="form-control" placeholder="Check amount..." autofocus v-model="checkAmount" @keyup="getTotalPayments" @keydown.enter="handleEnterKey">
                            </div>
                        </div>
                    </div>
                    <!-- Bank Transfers/Digital -->
                    <div class="mt-4">
                        <div class="input-group-radio-sm">
                            <!-- <input type="radio" id="DigitalPaymens" value="DigitalPaymens" v-model="paymentType" class="custom-radio-sm"> -->
                            <label for="DigitalPaymens" class="custom-radio-label-sm">Bank Transfers/Digital Payments</label>
                        </div>
                        <div class="ml-4 row">
                            <div class="col-lg-7 col-md-12">
                                <input title="Check number" type="text" class="form-control" placeholder="Transaction number..." autofocus v-model="digitalNumber">
                            </div>
                            <div class="col-lg-5 col-md-12">
                                <input title="Bank/Digital Wallets/Source" type="text" class="form-control" placeholder="Bank/Digital Wallets/Source..." autofocus v-model="digitalBank">
                            </div>
                            <div class="col-lg-12 mt-1">
                                <input title="Amount" type="number" class="form-control" placeholder="Amount..." autofocus v-model="digitalAmount" @keyup="getTotalPayments" @keydown.enter="handleEnterKey">
                            </div>
                        </div>
                    </div>

                    <div class="divider mt-3"></div>

                    <table class="table table-hover table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">TOTAL PAYMENT</td>
                                <td class="v-align text-right text-success"><h1 class="no-pads">{{ toMoney(totalPayments) }}</h1></td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">TOTAL PAYABLE</td>
                                <td class="v-align text-right text-danger"><h4 class="no-pads">{{ toMoney(totalPayables) }}</h4></td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">REMAINING TUITION FEE</td>
                                <td class="v-align text-right text-info"><h4 class="no-pads">{{ toMoney(reminingTuitionFee) }}</h4></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary float-right" @click="transact">Transact Payment <i class="fas fa-check-circle ico-tab-mini-left"></i></button>
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
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            school : document.querySelector("meta[name='school']").getAttribute('content'),
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
            payables : {},
            paymentType : 'Cash',
            cashAmount : '',
            checkNumber : '',
            checkBank : '',
            checkAmount : '',
            digitalNumber : '',
            digitalBank : '',
            digitalAmount : '',
            totalPayables : 0.0,
            totalPayments : 0.0,
            change : 0.0,
            orNumber : null,
            reminingTuitionFee : 0.0,
        }
    },
    methods : {
        isNull (item) {
            if (jquery.isEmptyObject(item)) {
                return true;
            } else {
                if (item) {
                    return false
                } else {
                    if (item.length < 1) {
                        return true;
                    } else {
                        return false;
                    }
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

            this.$refs.cashInput.focus()

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
        },
        getTotalPayable() {
            var total = 0

            if (!this.isNull(this.payables)) {
                total = parseFloat(this.payables.Balance)
            }
            this.totalPayables = total
            return total
        },
        getTotalPayments() {
            var cash = (this.cashAmount.length < 1 ? 0 : parseFloat(this.cashAmount))
            var check = (this.checkAmount.length < 1 ? 0 : parseFloat(this.checkAmount))
            var digital = (this.digitalAmount.length < 1 ? 0 : parseFloat(this.digitalAmount))
            
            // total payments
            this.totalPayments = cash + check + digital

            this.reminingTuitionFee = this.totalPayables - this.totalPayments

            // change
            this.change = this.totalPayments - this.totalPayables
        },
        handleEnterKey(event) {
            event.preventDefault()
            if (event.key === 'Enter') {
                this.transact()
            }
        },
        nextOR() {
            axios.get(`${ this.baseURL }/transactions/get-next-or`, {
                params : {
                    UserId : this.userId,
                }
            })
            .then(response => {
                this.orNumber = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting OR Number'
                })
            })
        },
        transact() {
            if (this.orNumber === '' || this.orNumber === null || this.orNumber.value === 0) {
                this.toast.fire({
                    icon : 'info',
                    text : 'OR Number should not be empty!'
                })
            } else {
                if (this.totalPayments <= 0) {
                    this.toast.fire({
                        icon : 'info',
                        text : 'Amount paid should be greater than zero (0)!'
                    })
                } else {
                    if (this.isNull(this.activeStudent)) {
                        this.toast.fire({
                            icon : 'info',
                            text : 'Select student first!'
                        })
                    } else {
                        Swal.fire({
                            title: "Confirm Transaction",
                            showCancelButton: true,
                            html: `
                                <p style='text-align: left;'>Enrollment payment summary:</p>
                                <ul>
                                    <li style='text-align: left;'>Amount Paid: <strong>P ${ this.toMoney(this.totalPayments) }</strong></li>
                                    <li style='text-align: left;'>Amount Payable: <strong>P ${ this.toMoney(this.totalPayables) }</strong></li>
                                    <li style='text-align: left;'>Remaining Tuition Fee: <strong>P ${ this.toMoney(this.reminingTuitionFee) }</strong></li>
                                </ul>
                                <p style='text-align: left;'>Proceed payment transaction?</p>
                            `,
                            confirmButtonText: "Yes",
                            confirmButtonColor : '#3a9971'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.post(`${ this.baseURL }/transactions/transact-enrollment`, {
                                    _token : this.token,
                                    StudentId : this.activeStudent.id,
                                    ClassId : this.activeStudent.ClassId,
                                    cashAmount : this.cashAmount,
                                    checkNumber : this.checkNumber,
                                    checkBank : this.checkBank,
                                    checkAmount : this.checkAmount,
                                    digitalNumber : this.digitalNumber,
                                    digitalBank : this.digitalBank,
                                    digitalAmount : this.digitalAmount,
                                    totalPayables : this.totalPayables,
                                    totalPayments : this.totalPayments,
                                    Payables : this.payables,
                                    ORNumber : this.orNumber
                                }) 
                                .then(response => {
                                    this.toast.fire({
                                        icon : 'success',
                                        text : 'Enrollment paid!'
                                    })
                                    
                                    if (this.school === 'SVI') {
                                        window.location.href = this.baseURL + '/transactions/print-enrollment-svi/' + response.data
                                    } else if (this.school === 'HCA') {
                                        window.location.href = this.baseURL + '/transactions/print-enrollment/' + response.data
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
            }            
        }
    },
    created() {
    },
    mounted() {
        this.nextOR()
        this.searchEnrollee()
    }
}

</script>