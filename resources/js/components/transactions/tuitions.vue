<template>
    <div class="row">
        <!-- DETAILS -->
        <div class="col-lg-3 col-md-12">
            <!-- student info -->
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <div class="mb-3">
                        <div style="display: inline-block; vertical-align: middle;">
                            <img :src="imgPath + 'prof-img.png'" style="width: 46px; margin-right: 25px;" class="img-circle" alt="profile">
                        </div>
                        <div style="display: inline-block; height: inherit; vertical-align: middle;">
                            <h4 class="no-pads"><strong>{{ studentData.LastName + ', ' + studentData.FirstName + (isNull(studentData.MiddleName) ? '' : (' ' + studentData.MiddleName + ' ')) + (isNull(studentData.Suffix) ? '' : studentData.Suffix) }}</strong></h4>
                            <span class="text-muted text-sm">ID: <strong>{{ studentData.id }}</strong></span>
                        </div>
                    </div>

                    <table class="table table-hover table-sm">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">Address</td>
                                <td class="v-align">{{ (isNull(studentData.Sitio) ? '' : studentData.Sitio) + ', ' + studentData.BarangaySpelled + ', ' + studentData.TownSpelled }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Grade Level</td>
                                <td class="v-align">{{ isNull(studentData.Year) ? '-' : (studentData.Year + ' - ' + studentData.Section) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- classes -->
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="text-muted">Subjects Taken</span>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-hover table-sm">
                        <tbody>
                           <tr v-for="subject in subjects" :key="subject.id">
                                <td class="v-align">{{ subject.Subject }}</td>
                           </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PAYABLES -->
        <div class="col-lg-5 col-md-12">
            <h4 class="text-muted">Tuition Fee Payables</h4>

            <!-- selection -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <th class="text-muted">Particulars</th>
                        <th class="text-muted text-right">Tuition Fee</th>
                        <th class="text-muted text-right">Paid Amount</th>
                        <th class="text-muted text-right">Balance</th>
                    </thead>
                    <tbody>
                        <tr v-for="payable in payables" :key="payable.id" class="pointer">
                            <td class="v-align">
                                <input type="radio" :id="payable.id" :value="payable.id" v-model="paymentFor" @change="getActivePayable(paymentFor)" class="custom-radio-sm pointer">
                                <label :for="payable.id" class="custom-radio-label-sm pointer no-pads">{{ payable.PaymentFor }}</label>
                            </td>
                            <td class="v-align text-right"><strong>{{ toMoney(parseFloat(payable.AmountPayable)) }}</strong></td>
                            <td class="v-align text-right text-primary"><strong>{{ isNull(payable.AmountPaid) ? '-' : toMoney(parseFloat(payable.AmountPaid)) }}</strong></td>
                            <td class="v-align text-right text-danger"><strong>{{ toMoney(parseFloat(payable.Balance)) }}</strong></td>
                        </tr>
                        <tr>
                            <td class="v-align" colspan="3"><strong>TOTAL PAYABLES</strong></td>
                            <td class="v-align text-right text-danger"><h4><strong>{{ toMoney(getTotalPayables()) }}</strong></h4></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- payable breakdown -->
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <span class="text-muted">Tuition Monthly Payables</span>
                    <table class="table table-sm table-hover table-borderless">
                        <thead>
                            <th>Month</th>
                            <th class="text-right">Amount Paid</th>
                            <th class="text-right">Balance</th>
                        </thead>
                        <tbody>
                            <tr v-for="month in tuitionMonths" :key="month.id">
                                <td>
                                    <div class="input-group-radio-sm">
                                        <input disabled @change="sumTotalTuitions()" type="checkbox" :id="month.id" :value="month" class="custom-radio-sm" v-model="selectedMonths">
                                        <label :for="month.id" class="custom-radio-label-sm">{{ moment(month.ForMonth).format('MMMM YYYY') }}</label>
                                    </div>
                                </td>
                                <td class="text-right">
                                    {{ isNull(month.AmountPaid) ? '-' : toMoney(parseFloat(month.AmountPaid)) }}
                                </td>
                                <td class="text-right">
                                    <strong class="text-danger">{{ toMoney(parseFloat(month.Balance)) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <div class="col-lg-4 col-md-12">
            <!-- <div style="border-bottom: dotted 3px #aeaeae; margin-bottom: 18px; padding-bottom: 10px;">
                <span class="text-muted">Transact</span>
                <span class="float-right"><i class="fas fa-dollar-sign text-muted text-right"></i></span>
            </div> -->
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <div class="card shadow-none m-0">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-borderless table-hover">
                                <tbody>
                                    <tr>
                                        <td class="v-align"><strong>ORNumber</strong></td>
                                        <td class="v-align">
                                            <input type="number" class="form-control" placeholder="OR Number..." autofocus v-model="orNumber">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="v-align"><strong>Details</strong></td>
                                        <td class="v-align">
                                            <input type="text" class="form-control" placeholder="Payment details..." autofocus v-model="paymentDetails">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Cash -->
                    <div class="card shadow-none m-0">
                        <div class="card-header border-0">
                            <span class="card-title"><strong>Cash</strong></span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="ml-4">
                                <input type="number" class="form-control" ref="cashInput" placeholder="Cash amount..." autofocus v-model="cashAmount" @keyup="getTotalPayments" @keydown.enter="handleEnterKey">
                            </div>
                        </div>
                    </div>
                    <!-- Check -->
                    <div class="card shadow-none collapsed-card m-0">
                        <div class="card-header border-0">
                            <span class="card-title"><strong>Check</strong></span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
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
                    </div>
                    <!-- Bank Transfers/Digital -->
                    <div class="card shadow-none collapsed-card m-0">
                        <div class="card-header border-0">
                            <span class="card-title"><strong>Bank Transfers/Digital Payments</strong></span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
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
                    </div>

                    <div class="divider mt-3"></div>

                    <table class="table table-hover table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">TOTAL PAYMENT</td>
                                <td class="v-align text-right text-success"><h1 class="no-pads">{{ toMoney(totalPayments) }}</h1></td>
                            </tr>
                            <!-- <tr>
                                <td class="text-muted v-align">
                                    PERIOD PAYABLE
                                    <br>
                                    <span class="text-muted text-sm">Change</span>
                                </td>
                                <td class="v-align text-right text-muted">
                                    <h4 class="no-pads">{{ toMoney(periodPayable) }}</h4>
                                    <span class="text-muted text-sm">{{ toMoney(changeOfPeriod) }}</span>
                                </td>
                            </tr> -->
                            <tr>
                                <td class="text-muted v-align">
                                    TOTAL SELECTED PAYABLE
                                    <br>
                                    <span class="text-muted text-sm">Change</span>
                                </td>
                                <td class="v-align text-right text-danger">
                                    <h4 class="no-pads">{{ toMoney(totalSelectedTuitions) }}</h4>
                                    <span class="text-info text-sm">{{ toMoney(change) }}</span>
                                </td>
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

    <div class="right-bottom" style="bottom: 0px !important;">
        <p id="msg-display" class="msg-display shadow" style="font-size: .8em; z-index: 99999999;"><i class="fas fa-check-circle ico-tab-mini text-success"></i>Amount tendered should not be less than the total amount payable!</p>
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
    name : 'Tuitions.tuitions',
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
            imgPath : axios.defaults.imgsPath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            studentId : document.querySelector("meta[name='student-id']").getAttribute('content'),
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
            subjects : [],
            payables : [],
            activePayable : {},
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
            changeOfPeriod : 0.0,
            orNumber : null,
            paymentFor : '',
            paymentDetails : '',
            period : '',
            periodPayable : 0,
            paidAmount : 0,
            tuitionMonths : [],
            selectedMonths : [],
            totalSelectedTuitions : 0.0,
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
        getStudentDetails() {
            axios.get(`${ this.baseURL }/students/get-student-details`, {
                params : {
                    StudentId : this.studentId,
                }
            })
            .then(response => {
                this.studentData = response.data.StudentDetails
                this.subjects = response.data.Subjects
                this.payables = response.data.TuitionPayables

                // clean payables
                this.payables = this.payables.filter(obj => obj.Balance !== null && parseFloat(obj.Balance) > 0)
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
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
        getTotalPayables() {
            var total = 0
            if (this.isNull(this.payables)) {
                return 0
            }

            for (let i=0; i<this.payables.length; i++) {
                var balance = this.isNull(this.payables[i].Balance) ? 0 : parseFloat(this.payables[i].Balance)
                total += balance
            }

            return total
        },
        focusCash() {
            this.$refs.cashInput.focus()
        },
        getActivePayable(id) {
            this.activePayable = this.payables.find(obj => obj.id === id)

            this.paymentDetails = this.activePayable.PaymentFor
            this.totalPayables = this.isNull(this.activePayable) ? 0 : parseFloat(this.activePayable.Balance)
            this.focusCash()

            axios.get(`${ this.baseURL }/classes/get-tuitions-breakdown`, {
                params : {
                    PayableId : this.activePayable.id,
                }
            })
            .then(response => {
                this.tuitionMonths = response.data

                // this.periodPayable = this.isNull(this.activePayable) ? 0 : this.round((parseFloat(this.activePayable.AmountPayable)/10))
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting payable data!'
                })
            })
        }, 
        sumTotalTuitions() {
            this.totalSelectedTuitions = 0

            for(let i=0; i<this.selectedMonths.length; i++) {
                this.totalSelectedTuitions += parseFloat(this.selectedMonths[i].AmountPayable)
            }
        },
        getTotalPayments() {
            var cash = (this.cashAmount.length < 1 ? 0 : parseFloat(this.cashAmount))
            var check = (this.checkAmount.length < 1 ? 0 : parseFloat(this.checkAmount))
            var digital = (this.digitalAmount.length < 1 ? 0 : parseFloat(this.digitalAmount))
            
            // total payments
            this.totalPayments = cash + check + digital

            // change
            this.change = this.totalPayments - this.totalPayables
            this.changeOfPeriod = this.totalPayments - this.periodPayable
            this.changeOfPeriod = this.changeOfPeriod > 0 ? 0 : this.changeOfPeriod

            // try preselect tuitions
            var monthPayable = parseFloat(this.activePayable.AmountPayable) / 10
            var indices = Math.ceil(this.totalPayments / monthPayable)
            this.selectedMonths = []
            for (let i=0; i<indices; i++) {
                this.selectedMonths.push(this.tuitionMonths[i])
            }

            this.sumTotalTuitions()
        },
        handleEnterKey(event) {
            event.preventDefault()
            if (event.key === 'Enter') {
                this.transact()
            }
        },
        transact() {
            console.log(this.selectedMonths)
           
            if (this.isNull(this.activePayable)) {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please select a payable to pay!'
                })
            } else {
                if (this.paymentDetails.length < 1) {
                    this.toast.fire({
                        icon : 'info',
                        text : 'Please provide details for the payment!'
                    })
                } else {
                    if (this.orNumber.length < 1) {
                        this.toast.fire({
                            icon : 'info',
                            text : 'Please provide an OR Number for this transaction!'
                        })
                    } else {
                        // get amount paid based on the total paid amount of customer
                        var amountPaid = 0
                        if (this.totalPayments > this.totalPayables) {
                            amountPaid = this.totalPayables
                            this.remainingBalance = 0
                        } else {
                            amountPaid = this.totalPayments
                            this.remainingBalance = this.totalPayables - this.totalPayments
                        }

                        // begin transaction
                        Swal.fire({
                            title: "Confirm Transaction",
                            showCancelButton: true,
                            html: `
                                <p style='text-align: left;'>Tuition payment summary:</p>
                                <ul>
                                    <li style='text-align: left;'>Amount Paid: <h4 class='text-primary'>P ${ this.toMoney(this.totalPayments) }</h4></li>
                                    <li style='text-align: left;'>Amount Payable: <strong>P ${ this.toMoney(this.totalPayables) }</strong></li>
                                    <li style='text-align: left;'>Account Balance: <strong>P ${ this.toMoney(this.remainingBalance) }</strong></li>
                                </ul>
                                <p class='text-sm text-muted text-left no-pads mt-2'>Input Amount Paid</p>
                                <input type="number" id="numberInput" class="form-control form-control-lg" aria-label="Amount Paid...">
                                <p class='text-left mb-3'>CHANGE : <strong class='text-danger' id="change"></strong></p>
                                <p style='text-align: left;'>Proceed payment transaction?</p>
                            `,
                            confirmButtonText: "Yes",
                            confirmButtonColor : '#3a9971',
                            didOpen: () => {
                                const numberInput = Swal.getPopup().querySelector('#numberInput')
                                const outputParagraph = Swal.getPopup().querySelector('#change')

                                numberInput.focus();

                                numberInput.addEventListener('input', () => {
                                    let subtractedValue = Number(numberInput.value) - this.totalPayments
                                    outputParagraph.innerText = `${subtractedValue}`;
                                });

                                numberInput.addEventListener('keydown', (event) => {
                                    if (event.key === 'Enter') {
                                        let subtractedValue = Number(numberInput.value) - this.totalPayments

                                        if (subtractedValue < 0) {
                                            event.preventDefault()
                                            this.showSaveFader()
                                            alert('Amount tendered should not be less than the total amount payable!')
                                        } else {
                                            // Trigger the confirm button
                                            Swal.clickConfirm();
                                        }
                                    }
                                })
                            },
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.post(`${ this.baseURL }/transactions/transact-tuition`, {
                                    _token : this.token,
                                    StudentId : this.studentId,
                                    PayableId : this.activePayable.id,
                                    cashAmount : this.cashAmount,
                                    checkNumber : this.checkNumber,
                                    checkBank : this.checkBank,
                                    checkAmount : this.checkAmount,
                                    digitalNumber : this.digitalNumber,
                                    digitalBank : this.digitalBank,
                                    digitalAmount : this.digitalAmount,
                                    totalPayables : this.totalPayables,
                                    totalPayments : this.totalPayments,
                                    ORNumber : this.orNumber,
                                    Details : this.paymentDetails,
                                    Period : this.period,
                                    PaidAmount : amountPaid,
                                    Balance : this.remainingBalance,
                                    TuitionBreakdowns : this.selectedMonths,
                                }) 
                                .then(response => {
                                    this.toast.fire({
                                        icon : 'success',
                                        text : 'Tuition successfully paid!'
                                    })
                                    window.location.href = this.baseURL + '/transactions/print-tuition/' + response.data
                                })
                                .catch(error => {
                                    console.log(error.response)
                                    Swal.fire({
                                        icon : 'error',
                                        text : 'Error performing transaction'
                                    })
                                })
                            }
                        })
                    }
                }
            } 
        },
        showSaveFader() {
            var message = document.getElementById('msg-display');

            // Show the message
            message.style.opacity = 1;

            // Wait for 3 seconds
            setTimeout(function() {
                // Fade out the message
                message.style.opacity = 0;
            }, 1500);
        },
    },
    created() {
    },
    mounted() {
        this.getStudentDetails()
        this.nextOR()
    }
}

</script>