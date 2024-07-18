<template>
   <div class="row">
        <!-- form -->
        <div class="col-lg-12 mt-2">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <span class="text-muted">Select Date</span>
                            <flat-pickr v-model="collectionDate" :config="pickerOptions" class="form-control" :readonly="false"></flat-pickr>
                        </div>
                        <div class="col-lg-2">
                            <span class="text-muted">Actions</span>
                            <br>
                            <button class="btn btn-default ico-tab-mini" @click="fetchPayments()"><i class="fas fa-eye ico-tab-mini"></i>View</button>
                            <button class="btn btn-primary" @click="printDcr()"><i class="fas fa-print ico-tab-mini"></i>Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- results -->
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-body py-0">
                    <!-- TABS -->
                    <!-- TAB HEADS -->
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active   " id="summary-tab" data-toggle="pill" href="#summary-content" role="tab" aria-controls="summary-content" aria-selected="false">Collection Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="details-tab" data-toggle="pill" href="#details-content" role="tab" aria-controls="details-content" aria-selected="false">Collection Details</a>
                        </li>
                    </ul>
                    <!-- TAB BODY -->
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <!-- 
                            ====================================================================================================================================
                            SUMMARRY 
                            ====================================================================================================================================
                        -->
                        <div class="tab-pane fade active show" id="summary-content" role="tabpanel" aria-labelledby="summary-tab">
                            <div class="p-2 table-responsive">
                                <table class="table table-sm table-hover table-bordered">
                                    <thead>
                                        <th></th>
                                        <th>OR Number</th>
                                        <th>Student</th>
                                        <th>Payment For</th>
                                        <th>Time</th>
                                        <th>Mode of<br>Payment</th>
                                        <th class="text-right">Amount Paid</th>
                                        <th style="width: 40px;"></th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in summary" :key="item.id">
                                            <td class="v-align">{{ index + 1 }}</td>
                                            <td class="v-align">{{ item.ORNumber }}</td>
                                            <td class="v-align">{{ item.FirstName + ' ' + item.LastName }}</td>
                                            <td class="v-align">{{ item.PaymentFor }}</td>
                                            <td class="v-align">{{ moment(item.created_at).format('hh:mm A') }}</td>
                                            <td class="v-align">{{ item.ModeOfPayment }}</td>
                                            <td class="v-align text-right">{{ isNull(item.TotalAmountPaid) ? '-' : toMoney(parseFloat(item.TotalAmountPaid)) }}</td>
                                            <td class="text-right">
                                                <button @click="viewDetails(item.id)" class="btn btn-link-muted btn-sm"><i class="fas fa-eye"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="v-align" colspan="6"><strong>TOTAL PAYMENTS</strong></td>
                                            <td class="v-align text-right"><strong>{{ toMoney(summaryTotal) }}</strong></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- 
                            ====================================================================================================================================
                            DETAILS 
                            ====================================================================================================================================
                        -->
                        <div class="tab-pane fade" id="details-content" role="tabpanel" aria-labelledby="details-tab">
                            <div class="p-2 table-responsive">
                                <table class="table table-sm table-hover table-bordered">
                                    <thead>
                                        <th></th>
                                        <th>OR Number</th>
                                        <th>Student</th>
                                        <th>Particulars</th>
                                        <th class="text-right">Amount Paid</th>
                                        <th style="width: 40px;"></th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in details" :key="item.id">
                                            <td class="v-align">{{ index + 1 }}</td>
                                            <td class="v-align">{{ item.ORNumber }}</td>
                                            <td class="v-align">{{ item.FirstName + ' ' + item.LastName }}</td>
                                            <td class="v-align">{{ item.Particulars }}</td>
                                            <td class="v-align text-right">{{ isNull(item.Amount) ? '-' : toMoney(parseFloat(item.Amount)) }}</td>
                                            <td class="text-right">
                                                <button @click="viewDetails(item.TransactionsId)" class="btn btn-link-muted btn-sm"><i class="fas fa-eye"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
   </div>

<!-- TRANSACTION HISTORY MODAL -->
<div ref="modalTransactionDetails" class="modal fade" id="modal-transaction-details" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="text-muted">Payment For</span>
                    <h4 class="no-pads">
                        {{ activeTransaction.FirstName + ' ' + activeTransaction.LastName }}
                    </h4>
                    <span>{{ activeTransaction.PaymentFor }}</span>
                </div>
            </div>
            <div class="modal-body table-responsive">
                <div class="row">
                    <!-- details -->
                    <div class="col-lg-6 table-responsive">
                        <p class="text-muted no-pads">Payment Details</p>
                        <table class="table table-sm table-hover">
                            <tbody>
                                <tr>
                                    <td class="text-muted v-align">OR Number</td>
                                    <td class="v-align text-right">{{ activeTransaction.ORNumber }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted v-align">Mode Of Payment</td>
                                    <td class="v-align text-right">{{ activeTransaction.ModeOfPayment }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted v-align">Cash Amount</td>
                                    <td class="v-align text-right">{{ activeTransaction.CashAmount }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted v-align">Check Amount</td>
                                    <td class="v-align text-right">{{ activeTransaction.CheckAmount }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted v-align">Digital Payment Amount</td>
                                    <td class="v-align text-right">{{ activeTransaction.DigitalPaymentAmount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- items/particulars -->
                     <div class="col-lg-6 table-responsive">
                        <p class="text-muted no-pads">Particulars</p>
                        <table class="table table-sm table-hover">
                            <thead>
                                <th>Particulars</th>
                                <th class="text-right">Amount</th>
                            </thead>
                            <tbody>
                                <tr v-for="item in transactionDetails" :key="item.id">
                                    <td class="v-align">{{ item.Particulars }}</td>
                                    <td class="v-align text-right">{{ toMoney(parseFloat(item.Amount)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                     </div>
                </div>
            </div>
            <div class="modal-footer">
                <div>
                    <p class="no-pads text-muted">TOTAL</p>
                    <h4 class="no-pads"><strong>{{ toMoney(parseFloat(activeTransaction.TotalAmountPaid)) }}</strong></h4>
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
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'MyDCR.my-dcr',
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
            collectionDate : '2024-07-15', //moment().format('YYYY-MM-DD'),
            summary : [],
            summaryTotal : 0,
            // modal details
            activeTransaction : {},
            transactionDetails : [],
            details : [],
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
        fetchPayments() {
            // FETCH SUMMARY
            axios.get(`${ this.baseURL }/transactions/fetch-payments`, {
                params : {
                    Date : this.collectionDate,
                }
            })
            .then(response => {
                this.summary = response.data

                // sum total summary
                this.summaryTotal = this.summary.reduce((sum, item) => sum + parseFloat(item.TotalAmountPaid), 0)
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting daily collection summary'
                })
            })

            // FETCH DETAILS
            axios.get(`${ this.baseURL }/transactions/fetch-all-transaction-details`, {
                params : {
                    Date : this.collectionDate,
                }
            })
            .then(response => {
                this.details = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting daily collection details'
                })
            })
        },
        viewDetails(transactionId) {
            this.activeTransaction = this.summary.find(obj => obj.id === transactionId)
            
            // get transaction details
            axios.get(`${ this.baseURL }/transactions/fetch-transaction-details`, {
                params : {
                    TransactionId : transactionId
                }
            })
            .then(response => {
                this.transactionDetails = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting transaction details'
                })
            })

            // show modal
            let modalElement = this.$refs.modalTransactionDetails
            $(modalElement).modal('show')
        },
        printDcr() {
            window.location.href = this.baseURL + '/transactions/print-my-dcr/' + this.collectionDate
        }
    },
    created() {
        
    },
    mounted() {
        this.fetchPayments()
    }
}

</script>