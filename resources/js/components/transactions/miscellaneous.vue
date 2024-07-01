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
        <div class="col-lg-6 col-md-12">
            <span class="text-muted">Select Miscellaneous Payables</span>
            <div style="display: flex; gap: 10px;">
                <select v-model="miscSelected" class="form-control" @change="addPayable">
                    <option value="Add New">-- Add New --</option>
                    <option v-for="misc in miscPayables" :value="misc.id">{{ misc.Payable }}</option>
                </select>
                <button @click="addPayable()" class="btn btn-primary">Add</button>
            </div>

            <table class="table table-hover table-bordered mt-3">
                <thead>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total Amount</th>
                </thead>
                <tbody>
                    <tr v-for="item in payableItems" :key="item.id">
                        <td>
                            {{ item.Payable }}
                        </td>
                        <td>
                            <input class="table-input text-right" :class="tableInputTextColor" v-model="item.Price" @keyup="inputEnter(item.Price, item.Quantity, item.id)" @keyup.enter="inputEnter(item.Price, item.Quantity, item.id)" @blur="inputEnter(item.Price, item.Quantity, item.id)" type="number" step="any"/>
                        </td>
                        <td>
                            <input class="table-input text-right" :class="tableInputTextColor" v-model="item.Quantity" @keyup="inputEnter(item.Price, item.Quantity, item.id)" @keyup.enter="inputEnter(item.Price, item.Quantity, item.id)" @blur="inputEnter(item.Price, item.Quantity, item.id)" type="number" step="any"/>
                        </td>
                        <td class="text-right">
                            <strong>{{ toMoney(parseFloat(item.TotalAmount)) }}</strong>
                            <button class="btn btn-sm" title="Remove" @click="removeItem(item.id)"><i class="fas fa-times-circle text-danger"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-">
                <p class="text-muted text-right">Total Amount Due</p>
                <h2 class="text-danger text-right">P <strong>{{ toMoney(totalAmountDue) }}</strong></h2>
            </div>
        </div>

        <!-- FORM -->
        <div class="col-lg-3 col-md-12">
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
                            <div class="mx-2">
                                <input type="number" class="form-control" ref="cashInput" placeholder="Cash amount..." autofocus v-model="cashAmount" @keyup="getTotalPayments()" @keydown.enter="handleEnterKey">
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
                            <div class="mx-2 row">
                                <div class="col-lg-7 col-md-12">
                                    <input title="Check number" type="text" class="form-control" placeholder="Check number..." autofocus v-model="checkNumber">
                                </div>
                                <div class="col-lg-5 col-md-12">
                                    <input title="Bank" type="text" class="form-control" placeholder="Bank..." autofocus v-model="checkBank">
                                </div>
                                <div class="col-lg-12 mt-1">
                                    <input title="Check amount" type="number" class="form-control" placeholder="Check amount..." autofocus v-model="checkAmount" @keyup="getTotalPayments()" @keydown.enter="handleEnterKey">
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
                            <div class="mx-2 row">
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
                            <tr>
                                <td class="text-muted v-align">
                                    TOTAL AMOUNT PAYABLE
                                    <br>
                                    <span class="text-muted text-sm">Change</span>
                                </td>
                                <td class="v-align text-right text-danger">
                                    <h4 class="no-pads">{{ toMoney(totalAmountDue) }}</h4>
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
            paymentType : 'Cash',
            cashAmount : '',
            checkNumber : '',
            checkBank : '',
            checkAmount : '',
            digitalNumber : '',
            digitalBank : '',
            digitalAmount : '',
            miscPayables : [],
            miscSelected : '',
            payableItems : [],
            totalAmountDue : 0.0,
            totalPayments : 0.0,
            paymentDetails : 'Miscellaneous Payments',
            orNumber : '',
            change : 0,
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
        inputEnter(price, qty, id) {
            var amount = parseFloat(price) * parseFloat(qty)

            this.payableItems = this.payableItems.map(obj => {
                if (obj.id === id) {
                    return { ...obj, Price : price, Quantity : qty, TotalAmount : amount }
                }
                return obj
            })

            this.validateTotal()
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
        getMiscPayables() {
            axios.get(`${ this.baseURL }/transactions/get-misc-payables`)
            .then(response => {
                this.miscPayables = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting miscellaneous payables!'
                })
            })
        },
        addPayable() {
            const selected = this.miscPayables.find(obj => obj.id === this.miscSelected)

            if (!this.isNull(selected)) {
                this.payableItems.push({
                    id : this.generateUniqueId(),
                    Payable : selected.Payable,
                    Price : selected.DefaultAmount,
                    Quantity : 1,
                    TotalAmount : selected.DefaultAmount
                })
            } else {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please select item before adding!'
                })
            }

            this.validateTotal()
        },
        removeItem(id) {
            this.payableItems = this.payableItems.filter(obj => obj.id !== id)

            this.toast.fire({
                icon : 'info',
                text : 'Item removed!'
            })
            this.validateTotal()
        },
        validateTotal() {
            this.totalAmountDue = 0
            for (let i=0; i<this.payableItems.length; i++) {
                this.totalAmountDue += parseFloat(this.payableItems[i].TotalAmount)
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
        getTotalPayments() {
            var cash = (this.cashAmount.length < 1 ? 0 : parseFloat(this.cashAmount))
            var check = (this.checkAmount.length < 1 ? 0 : parseFloat(this.checkAmount))
            var digital = (this.digitalAmount.length < 1 ? 0 : parseFloat(this.digitalAmount))
            
            // total payments
            this.totalPayments = cash + check + digital

            // change
            this.change = this.totalPayments - this.totalAmountDue
        },
        handleEnterKey(event) {
            event.preventDefault()
            if (event.key === 'Enter') {
                this.transact()
            }
        },
        transact() {
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
                    // filter if amount is lacking
                    if (this.change < 0) {
                        this.toast.fire({
                            icon : 'warning',
                            text : 'Insufficient amount!'
                        })
                    } else {
                        // begin transaction
                        Swal.fire({
                            title: "Confirm Transaction",
                            showCancelButton: true,
                            html: `
                                <p style='text-align: left;'>Miscellaneous payment summary:</p>
                                <ul>
                                    <li style='text-align: left;'>Amount Payable: <h2><strong>P ${ this.toMoney(this.totalAmountDue) }</strong></h2></li>
                                    <li style='text-align: left;'>Amount Paid: <strong>P ${ this.toMoney(this.totalPayments) }</strong></li>
                                    <li style='text-align: left;'>Change: <strong>P ${ this.toMoney(this.change) }</strong></li>
                                </ul>
                                <p style='text-align: left;'>Proceed payment transaction?</p>
                            `,
                            confirmButtonText: "Yes",
                            confirmButtonColor : '#3a9971'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.post(`${ this.baseURL }/transactions/transact-miscellaneous`, {
                                    _token : this.token,
                                    StudentId : this.studentId,
                                    cashAmount : this.cashAmount,
                                    checkNumber : this.checkNumber,
                                    checkBank : this.checkBank,
                                    checkAmount : this.checkAmount,
                                    digitalNumber : this.digitalNumber,
                                    digitalBank : this.digitalBank,
                                    digitalAmount : this.digitalAmount,
                                    totalPayables : this.totalPayables,
                                    totalPayments : this.totalAmountDue,
                                    ORNumber : this.orNumber,
                                    Details : this.paymentDetails,
                                    TransactionDetails : this.payableItems,
                                }) 
                                .then(response => {
                                    this.toast.fire({
                                        icon : 'success',
                                        text : 'Tuition successfully paid!'
                                    })
                                    window.location.href = this.baseURL + '/transactions/print-miscellaneous/' + response.data
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
        }
    },
    created() {
    },
    mounted() {
        this.getStudentDetails()
        this.nextOR()
        this.getMiscPayables()
    }
}

</script>