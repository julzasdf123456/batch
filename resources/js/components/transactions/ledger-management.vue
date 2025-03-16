<template>
    <div class="row">
        <!-- student search -->
        <div class="col-lg-4">
            <div class="card shadow-none" style="height: 85vh;">
                <div class="card-header">
                    <span class="card-title text-muted">Search Student</span>
                    <input type="text" v-model="studentSearch" class="form-control" @keyup="searchStudent" autofocus placeholder="Search name or id...">
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm">
                        <tbody>
                            <tr v-for="student in studentResults.data" :key="student.id" class="pointer" @click="getStudentDetails(student.id)">
                                <td>
                                    <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                    <br>
                                    <span class="text-muted text-sm">{{ student.id }}</span>
                                    <br>
                                    <span class="text-muted text-sm">{{ isNull(student.Year) ? '-' : (student.Year + ' - ' + (isNull(student.Strand) ? '' : (student.Strand + ' ')) + student.Section) }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <pagination :data="studentResults" :limit="10" @pagination-change-page="searchStudent"></pagination>
                </div>
            </div>
        </div>

        <!-- management -->
         <div class="col-lg-8 pl-4" v-if="!isNull(studentData)">
            <div style="display: flex; padding-bottom: 15px;">
                <div style="width: 88px; display: inline;">
                    <img id="prof-img" style="width: 65px !important; height: 65px !important; cursor: pointer; object-fit: cover;" title="Change profile photo" class="profile-user-img img-circle" :src="imagePreview" @error="handleImageError">
                    <input type="file" ref="fileInput" @change="onFileChange" class="gone" />
                </div>
                <div>
                    <span>
                        <p class="no-pads" style="font-size: 1.85em;"><strong>{{ studentData.LastName + ', ' + studentData.FirstName + (isNull(studentData.MiddleName) ? '' : (' ' + studentData.MiddleName + ' ')) + (isNull(studentData.Suffix) ? '' : studentData.Suffix) }}</strong></p>
                        
                        <span class="text-muted">
                            <i class="fas fa-id-badge ico-tab-mini"></i>LRN-{{ studentData.LRN }} | 
                            <i class="fas fa-lightbulb ico-tab-mini"></i>{{ isNull(studentData.Year) ? '-' : (studentData.Year + ' - ' + studentData.Section) }} {{ isNull(studentData.Strand) ? '' : (' • ' + studentData.Strand) }} {{ isNull(studentData.Semester) ? '' : (' • ' + studentData.Semester + ' Sem') }}
                            <span class="badge" :class="isNull(studentData.Status) ? 'bg-success' : 'bg-danger'" title="Status">{{ isNull(studentData.Status) ? 'Studying' : studentData.Status }}</span>
                            <span class="badge bg-success ico-tab-left-mini" v-if="!isNull(studentData.ESCScholar) && studentData.ESCScholar==='Yes' ? true : false">ESC Scholar/Grantee</span>
                        </span>
                    </span>
                </div>
            </div>

            <div class="divider my-4"></div>

            <p class="text-muted">Ledger Profile</p>

            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <th class="text-muted">Description</th>
                            <th class="text-muted text-center">Category</th>
                            <th class="text-muted text-right">Amount Payable</th>
                            <th class="text-muted text-right">Amount Paid</th>
                            <th class="text-muted text-right">Balance</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr class="pointer" v-for="payable in payables" :key="payable.id">
                                <td @click="transactionHistory(payable.id)" class="v-align"><strong>{{ payable.PaymentFor }}</strong></td>
                                <td @click="transactionHistory(payable.id)" class="v-align text-center"><span class="badge bg-info">{{ payable.Category }}</span></td>
                                <td @click="transactionHistory(payable.id)" class="v-align text-right"><strong>{{ isNull(payable.AmountPayable) ? '0' : toMoney(parseFloat(payable.AmountPayable)) }}</strong></td>
                                <td @click="transactionHistory(payable.id)" class="v-align text-right">{{ isNull(payable.AmountPaid) ? '0' : toMoney(parseFloat(payable.AmountPaid)) }}</td>
                                <td @click="transactionHistory(payable.id)" class="v-align text-right" :class="parseFloat(payable.Balance) > 0 ? 'text-danger' : 'text-success'"><strong>{{ isNull(payable.Balance) ? '0' : toMoney(parseFloat(payable.Balance)) }}</strong></td>
                                <td class="v-align text-right">
                                    <button class="btn btn-sm btn-default" @click="showAdjustmentModal(payable.id)"><i class="fas fa-pen ico-tab-mini"></i>Adjust</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
         </div>
    </div>

     <!-- TRANSACTION HISTORY MODAL -->
     <div ref="modalTransactionHistory" class="modal fade" id="modal-transaction-history" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="max-width: 90% !important; margin-top: 30px;">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="no-pads">
                            {{ paymentFor }}
                            <!-- <div id="loader" class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div> -->
                        </h4>
                    </div>
                </div>
                <div class="modal-body table-responsive">
                    <!-- TUITION FEES -->
                    <div>
                        <div class="row">
                            <!-- Total -->
                            <div class="col-lg-12 mb-2">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p class="text-muted text-sm no-pads">Net Amount Payable <i class="fas fa-eye"></i></p>
                                        <h1 class="text-primary">₱ {{ isNull(activePayable.AmountPayable) ? '-' : toMoney(parseFloat(activePayable.AmountPayable)) }}</h1>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p class="text-muted text-sm no-pads">Payable <i class="fas fa-plus-circle"></i></p>
                                                <h4 class="text-muted">₱ {{ isNull(activePayable.Payable) ? '-' : toMoney(parseFloat(activePayable.Payable)) }}</h4>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <p class="text-muted text-sm no-pads">Discount <i class="fas fa-minus-circle"></i></p>
                                                <h4 class="text-muted">₱ {{ isNull(activePayable.DiscountAmount) ? '-' : toMoney(parseFloat(activePayable.DiscountAmount)) }}</h4>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-lg-6">
                                        <p class="text-muted text-right text-sm no-pads">Balance <i class="fas fa-dollar-sign"></i></p>
                                        <h1 class="text-right" :class="isNull(activePayable.Balance) ? 'text-success' : (activePayable.Balance <= 0 ? 'text-success' : 'text-danger')">₱ {{ isNull(activePayable.Balance) ? '0.00' : toMoney(parseFloat(activePayable.Balance)) }}</h1>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <p class="text-muted text-right text-sm no-pads">Total Amount Paid <i class="fas fa-check-circle"></i></p>
                                                <h4 class="text-muted text-right">₱ {{ isNull(activePayable.AmountPaid) ? '-' : toMoney(parseFloat(activePayable.AmountPaid)) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- options -->
                            <div class="col-lg-12 pb-2">
                                <button @click="printTuitionLedger()" class="btn btn-default btn-xs"><i class="fas fa-print ico-tab-mini"></i>Print Tuition Ledger</button>
                                <!-- <button class="btn btn-default btn-xs ml-1"><i class="fas fa-print ico-tab-mini"></i>Print All Ledger</button> -->
                            </div>

                            <!-- Tuition/Payable Inclusions -->
                            <div class="col-lg-5 table-responsive">
                                <span class="text-muted">Payable Breakdown</span>
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <th class="text-muted">Item</th>
                                        <th class="text-muted text-right">Amount</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="inc in payableInclusions" :key="inc.id">
                                            <td class="v-align"><i class="fas fa-check text-success ico-tab-mini"></i>{{ inc.ItemName }}</td>
                                            <td class="v-align text-right">{{ toMoney(parseFloat(inc.Amount)) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Monthly Breakdown -->
                            <div class="col-lg-7 table-responsive" v-if="isModalTuition">
                                <span class="text-muted">Tuition Fee Monthly Balance Breakdown</span>
                                <table class="table table-hover table-sm table-bordered">
                                    <thead>
                                        <th class="text-muted">Month</th>
                                        <th class="text-muted text-right">Tuition Fee</th>
                                        <th class="text-muted text-right">Discount</th>
                                        <th class="text-muted text-right">Amount Payable</th>
                                        <th class="text-muted text-right">Amount Paid</th>
                                        <th class="text-muted text-right">Balance</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in tuitionsBreakdown" :key="item.id">
                                            <td>
                                                <i v-if="parseFloat(item.Balance) <= 0" class="fas fa-check-circle text-success ico-tab-mini"></i>
                                                <i v-if="parseFloat(item.Balance) > 0" class="fas fa-info-circle text-muted ico-tab-mini"></i>
                                                {{ moment(item.ForMonth).format('MMMM YYYY') }}
                                            </td>
                                            <td class='text-right'>{{ toMoney(parseFloat(item.Payable)) }}</td>
                                            <td class='text-right'>{{ isNull(item.Discount) ? '-' : toMoney(parseFloat(item.Discount)) }}</td>
                                            <td class='text-right'>{{ isNull(item.AmountPayable) ? '-' : toMoney(parseFloat(item.AmountPayable)) }}</td>
                                            <td class='text-right'>{{ isNull(item.AmountPaid) ? '-' : toMoney(parseFloat(item.AmountPaid)) }}</td>
                                            <td class='text-right' :class="parseFloat(item.Balance) > 0 ? 'text-danger' : 'text-success'"><strong>{{ toMoney(parseFloat(item.Balance)) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>                      
                                <br>
                            </div>
                        </div>  
                    </div>

                    <!-- TRANSACTION HISTORY LOGS --> 
                    <div class="table-responsive">
                        <span class="text-muted">Transaction Logs</span>
                        <table class="table table-hover table-sm table-bordered">
                            <thead>
                                <th class="text-muted">Payment For</th>
                                <th class="text-muted">Mode of Payment</th>
                                <th class="text-muted">Period</th>
                                <th class="text-muted">OR Number</th>
                                <th class="text-muted">OR Date</th>
                                <th class="text-muted">Cashier</th>
                                <th class="text-muted text-right">Cash Amount</th>
                                <th class="text-muted text-right">Check Amount</th>
                                <th class="text-muted text-right">Transfer Amount</th>
                                <th class="text-muted text-right">Total Amount Paid</th>
                            </thead>
                            <tbody>
                                <tr v-for="hist in payableTransactionHistory" :key="hist.id">
                                    <td class="v-align">{{ hist.PaymentFor }}</td>
                                    <td class="v-align">{{ hist.ModeOfPayment }}</td>
                                    <td class="v-align">{{ hist.Period }}</td>
                                    <td class="v-align">{{ hist.ORNumber }}</td>
                                    <td class="v-align">{{ hist.ORDate.length < 1 ? '-' : moment(hist.ORDate).format('MMM DD, YYY') }}</td>
                                    <td class="v-align">{{ hist.name }}</td>
                                    <td class="v-align text-right">{{ isNull(hist.CashAmount) ? '-' : toMoney(parseFloat(hist.CashAmount)) }}</td>
                                    <td class="v-align text-right">{{ isNull(hist.CheckAmount) ? '-' : toMoney(parseFloat(hist.CheckAmount)) }}</td>
                                    <td class="v-align text-right">{{ isNull(hist.DigitalPaymentAmount) ? '-' : toMoney(parseFloat(hist.DigitalPaymentAmount)) }}</td>
                                    <td class="v-align text-right"><strong>{{ isNull(hist.TotalAmountPaid) ? '-' : toMoney(parseFloat(hist.TotalAmountPaid)) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- MANUAL UPDATE HISTORY LOGS --> 
                    <div class="table-responsive">
                        <span class="text-muted">Manual Updating Logs</span>
                        <table class="table table-hover table-sm table-bordered">
                            <thead>
                                <th class="text-muted">Timestamp</th>
                                <th class="text-muted">User</th>
                                <th class="text-muted">Previous Amnt. Payable</th>
                                <th class="text-muted">Previous Paid Amount</th>
                                <th class="text-muted">Previous Balance</th>
                                <th class="text-muted">New Amnt. Payable</th>
                                <th class="text-muted">New Paid Amount</th>
                                <th class="text-muted">New Balance</th>
                            </thead>
                            <tbody>
                                <tr v-for="log in updateLogs" :key="log.id">
                                    <td class="v-align">{{ log.created_at.length < 1 ? '-' : moment(log.ORDate).format('MMM DD, YYYY HH:mm A') }}</td>
                                    <td class="v-align">{{ log.name }}</td>
                                    <td class="v-align text-right">{{ isNull(log.OGTotalPayable) ? '-' : toMoney(parseFloat(log.OGTotalPayable)) }}</td>
                                    <td class="v-align text-right">{{ isNull(log.OGPaidAmount) ? '-' : toMoney(parseFloat(log.OGPaidAmount)) }}</td>
                                    <td class="v-align text-right">{{ isNull(log.OGBalance) ? '-' : toMoney(parseFloat(log.OGBalance)) }}</td>
                                    <td class="v-align text-right">{{ isNull(log.NewTotalPayable) ? '-' : toMoney(parseFloat(log.NewTotalPayable)) }}</td>
                                    <td class="v-align text-right">{{ isNull(log.NewPaidAmount) ? '-' : toMoney(parseFloat(log.NewPaidAmount)) }}</td>
                                    <td class="v-align text-right">{{ isNull(log.NewBalance) ? '-' : toMoney(parseFloat(log.NewBalance)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADJUSTMENT MODAL -->
    <div ref="modalAdjustments" class="modal fade" id="modal-transaction-history" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <span class="text-muted text-sm">Adjustments For</span>
                        <h4 class="no-pads">
                            {{ paymentFor }}
                            <!-- <div id="loader" class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div> -->
                        </h4>
                    </div>
                </div>
                <div class="modal-body table-responsive">
                    <table class="table table-hover table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td class="v-align">Total Amount Payable</td>
                                <td class="v-align">
                                    <input @keyup="adjust('total')" type="number" step="any" class="text-right form-control" style="font-weight: 700;" v-model="editingPayable.AmountPayable">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Amount Paid</td>
                                <td class="v-align">
                                    <input @keyup="adjust('paid')" type="number" step="any" class="text-right form-control text-success" style="font-weight: 700;" v-model="editingPayable.AmountPaid">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Balance</td>
                                <td class="v-align">
                                    <input @keyup="adjust('balance')" type="number" step="any" class="text-right form-control text-danger" style="font-weight: 700; font-size: 1.5em;" v-model="editingPayable.Balance">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" @click="updatePayable()"><i class="fas fa-shield-alt ico-tab-mini"></i>Save</button>
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
import jquery from 'jquery';
import Swal from 'sweetalert2';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import { Toast } from 'bootstrap';

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
            imgPath : axios.defaults.imgsPath,
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            studentSearch : '',
            studentResults : {},
            studentId : null,
            studentData : {},
            subjects : [],
            scholarships : [],
            payables : [],
            activePayable : {},
            paymentFor : '',
            payableTransactionHistory : [],
            tuitionsBreakdown : [],
            isModalTuition : false,
            allTransactions : [],
            payableInclusions : [],
            editingPayable : {},
            updateLogs : [],
            imagePreview : null,
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
        roundThree(value) {
            return Math.round((value + Number.EPSILON) * 1000) / 1000;
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
        searchStudent(page = 1) {
            axios.get(`${ this.baseURL }/students/search-students-paginated`, {
                params : {
                    page : page,
                    SearchParams : this.studentSearch,
                }
            })
            .then(response => {
                this.studentResults = response.data
            })
            .catch(error => {
                console.log(error.response)
            })
        },
        getStudentDetails(id) {
            this.studentId = id
            axios.get(`${ this.baseURL }/students/get-student-details`, {
                params : {
                    StudentId : this.studentId,
                }
            })
            .then(response => {
                this.studentData = response.data.StudentDetails
                this.subjects = response.data.Subjects
                this.payables = response.data.TuitionPayables
                this.scholarships = response.data.Scholarships
                // concat other payables
                this.payables = this.payables.concat(response.data.OtherPayables)

                this.imagePreview = `${ this.imgPath }student-imgs/${ this.studentId }.jpg`
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
        },
        transactionHistory(id) {
            this.getActivePayable(id)
            this.paymentFor = this.activePayable.PaymentFor
            this.isModalTuition = this.activePayable.Category === 'Tuition Fees' ? true : false
            this.getTransactionHistory(id)

            // init modal
            let modalElement = this.$refs.modalTransactionHistory
            $(modalElement).modal('show')
        },
        getActivePayable(id) {
            this.activePayable = this.payables.find(obj => obj.id === id)
        },
        getTransactionHistory(payableId) {
            axios.get(`${ this.baseURL }/transactions/get-transactions-from-payable`, {
                params : {
                    PayableId : payableId,
                }
            })
            .then(response => {
                this.tuitionsBreakdown = response.data.TuitionLogs
                this.payableTransactionHistory = response.data.Transactions
                this.payableInclusions = response.data.PayableInclusions
                this.updateLogs = response.data.UpdateLogs
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting transaction history data!'
                })
            })
        },
        showAdjustmentModal(id) {
            this.getActivePayable(id)
            this.paymentFor = this.activePayable.PaymentFor
            this.editingPayable = this.payables.find(obj => obj.id === id)
            // init modal
            let modalElement = this.$refs.modalAdjustments
            $(modalElement).modal('show')
        },
        adjust(input) {
            var total = this.isNull(this.editingPayable.AmountPayable) ? 0 : parseFloat(this.editingPayable.AmountPayable)
            var paid = this.isNull(this.editingPayable.AmountPaid) ? 0 : parseFloat(this.editingPayable.AmountPaid)
            var balance = this.isNull(this.editingPayable.Balance) ? 0 : parseFloat(this.editingPayable.Balance)

            if (input === 'total' | input === 'paid') {
                this.editingPayable.Balance = total - paid
            } else if (input === 'balance') {
                this.editingPayable.AmountPaid = total - balance
            }
        },
        updatePayable() {
            Swal.fire({
                title: "Warning",
                text : `Updating a payable will affect the student's ledger balances. Proceed with caution.`,
                showCancelButton: true,
                confirmButtonText: "Update Payable",
                confirmButtonColor : '#e03822'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ this.baseURL }/transactions/update-payable`, {
                        _token : this.token,
                        id : this.editingPayable.id,
                        TotalPayable : this.editingPayable.AmountPayable,
                        PaidAmount : this.editingPayable.AmountPaid,
                        Balance : this.editingPayable.Balance,
                    })
                    .then(response => {
                        let modalElement = this.$refs.modalAdjustments
                        $(modalElement).modal('hide')

                        this.toast.fire({
                            title : 'Payable updated!',
                            icon: 'success',
                        })

                        this.activePayable = null
                        this.editingPayable = null
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error adding second sem'
                        })
                    })
                }
            })
        }
    },
    created() {
        
    },
    mounted() {
        
    }
}

</script>