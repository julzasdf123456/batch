<template>
    <div class="row">
        <!-- student details -->
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
                            <tr title="Current Grade">
                                <td class="text-muted v-align"><i class="fas fa-book"></i></td>
                                <td class="v-align">{{ isNull(studentData.Year) ? '-' : (studentData.Year + ' - ' + studentData.Section) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>

        <!-- form -->
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title">Scholarship Form</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- school year selection -->
                        <div class="col-lg-12">
                            <p class="text-muted no-pads">Select School Year Tuition to Charge Scholarship</p>

                            <div class="card shadow-none mt-1" v-for="payable in availablePayables" :key="payable.id">
                                <div class="card-body py-1 px-4">
                                    <div class="input-group-radio-sm">
                                        <input type="radio" :id="payable.id" :value="payable.id" class="custom-radio-sm" v-model="selectedPayable" @change="onPayableSelection()">
                                        <label :for="payable.id" class="custom-radio-label-sm">{{ payable.SchoolYear }}</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <p class="text-muted no-pads text-sm text-center">Tuition Fee Payable</p>
                                            <p class="text-center no-pads">₱ {{ isNull(payable.AmountPayable) ? '-' : toMoney(parseFloat(payable.AmountPayable)) }}</p>
                                        </div>
                                        <div class="col-lg-4">
                                            <p class="text-muted no-pads text-sm text-center">Paid Amount</p>
                                            <p class="text-center no-pads">₱ {{ isNull(payable.AmountPaid) ? '-' : toMoney(parseFloat(payable.AmountPaid)) }}</p>
                                        </div>
                                        <div class="col-lg-4">
                                            <p class="text-muted no-pads text-sm text-center">Balance</p>
                                            <p class="text-center no-pads">₱ <strong>{{ isNull(payable.Balance) ? '-' : toMoney(parseFloat(payable.Balance)) }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- tuition payable selection, only visible if SCHOLARSHIP_DEDUCTION=TUITION_ONLY -->
                        <div class="col-lg-12 mt-2 mb-3" v-if="scholarshipOptions === 'TUITION_ONLY' ? true : false">
                            <div class="mx-4 px-4" style="border-left: 4px solid #a9a9a9;" v-if="tuitionInclusions.length > 0 ? true : false">
                                <p class="text-muted">Select Tuition Item to Deduct the Scholarship With</p>
                                <div class="input-group-radio-sm" v-for="ti in tuitionInclusions">
                                    <input type="radio" :id="ti.id" :value="ti.id" class="custom-radio-sm" v-model="selectedTuitionInclusionId" @change="onTuitionInclusionSelection()">
                                    <label :for="ti.id" class="custom-radio-label-sm">{{ ti.ItemName }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="divider my-2"></div>

                            <p class="text-muted no-pads">Amount Deductible:</p>
                            <h4 class="no-pads text-primary">P {{ isNull(amountDeductible) ? '-' : (toMoney(parseFloat(amountDeductible))) }}</h4>
                        </div>

                        <!-- scholarship form -->
                        <div class="col-lg-12">
                            <p class="text-muted no-pads">Select Scholaship Grant</p>
                            <select v-model="selectedGrant" class="form-control" @change="selectScholarship()">
                                <option v-for="grant in grants" :key="grant.id" :value="grant.id">{{ grant.Scholarship }}</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mt-3">
                            <p class="text-muted no-pads">Discount Percentage</p>
                            <input type="number" step="any" class="form-control" v-model="percentage" disabled>
                        </div>
                        
                        <div class="col-lg-6 mt-3">
                            <p class="text-muted no-pads">Discount Amount</p>
                            <input type="number" step="any" class="form-control" v-model="amount" style="font-weight: bold;">
                        </div>
                        
                        <div class="col-lg-12 mt-3">
                            <p class="text-muted no-pads">Deduction Configuration</p>
                            <div class="custom-control custom-switch" style="margin-left: 10px; margin-top: 6px; margin-bottom: 6px;">
                                <input type="checkbox" class="custom-control-input" id="deduct-to-tuition" v-model="deductToTuition">
                                <label style="font-weight: normal;" class="custom-control-label" for="deduct-to-tuition" id="deduct-to-tuitionLabel">{{ deductToTuition ? deductToTuitionOnLabel : deductToTuitionOffLabel }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button @click="saveScholarshipGrant()" class="btn btn-primary float-right">Apply Scholarship and Save <i class="fas fa-check-circle ico-tab-left-mini"></i></button>
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
    name : 'ScholarshipWizzard.scholarship-wizzard',
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
            from : document.querySelector("meta[name='from']").getAttribute('content'),
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            scholarshipOptions : document.querySelector("meta[name='scholarship-options']").getAttribute('content'),            
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
            availablePayables : [],
            tuitionInclusions : [],
            selectedTuitionInclusionId : '',
            selectedTuitionInclusion : {},
            selectedPayable : '',
            selectedPayableData : {},
            grants : [],
            selectedGrant : '',
            percentage : 0,
            amountDeductible : 0,
            amount : 0,
            selectedScholarship : {},
            deductToTuition : true,
            deductToTuitionOffLabel : "[OFF] The student/parent will still pay the tuition fees monthly, but will be refunded with the corresponding amount at the end of the school year or depending on the availabilitty of the scholarship amount. This is applicable for LGU scholars, etc.",
            deductToTuitionOnLabel : "[ON] The scholarship amount/percentage is deducted directly to the total tuition fee payable of the student, and is also distributed monthly."
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
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
        },
        getAvailableSYPayables() {
            axios.get(`${ this.baseURL }/student_scholarships/get-available-sy-payables`, {
                params : {
                    StudentId : this.studentId,
                }
            })
            .then(response => {
                this.availablePayables = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting available payables!'
                })
            })
        },
        getGrants() {
            axios.get(`${ this.baseURL }/student_scholarships/get-grants`)
            .then(response => {
                this.grants = response.data
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting scholarship grants!'
                })
            })
        },
        selectScholarship() {
            // select scholarship from dropdown
            this.selectedScholarship = this.grants.find(obj => obj.id === this.selectedGrant)

            // find selected school year payable
            this.selectedPayableData = this.availablePayables.find(obj => obj.id === this.selectedPayable)

            // validate if has amount
            if (!this.isNull(this.selectedScholarship.Amount)) {
                this.amount = parseFloat(this.selectedScholarship.Amount)
                this.percentage = 0
            } else {
                this.amount = 0

                // validate if has percentage
                if (!this.isNull(this.selectedScholarship.Percentage)) {
                    this.percentage = parseFloat(this.selectedScholarship.Percentage)

                    // calculate payable for discount
                    if(!this.isNull(this.amountDeductible)) {
                        this.amount = parseFloat(this.amountDeductible) * this.percentage
                    } else {
                        this.amount = 0
                    }
                } else {
                    this.percentage = 0
                    this.amount = 0
                }
            }
        },
        saveScholarshipGrant() {
            axios.post(`${ this.baseURL }/student_scholarships/apply-scholarship`, {
                _token : this.token,
                PayableId : this.selectedPayable,
                StudentId : this.studentId,
                SchoolYear : this.selectedPayableData.SchoolYear,
                ScholarshipId : this.selectedScholarship.id,
                Amount : this.amount,
                DeductMonthly : this.deductToTuition ? 'Yes' : 'No',
            })
            .then(response => {
                this.toast.fire({
                    icon : 'success',
                    text : 'Scholarship grant applied and saved!'
                })

                if (this.from === 'student-view') {
                    window.location.href = this.baseURL + '/students/' + this.studentId
                }
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error saving scholarship grant!'
                })
            })
        },
        onPayableSelection() {
            let selected = this.availablePayables.find(obj => obj.id === this.selectedPayable)

            if (this.scholarshipOptions === 'TUITION_ONLY') {
                if (!this.isNull(selected)) {
                    this.tuitionInclusions = selected.TuitionInclusions
                    console.log(this.tuitionInclusions)
                }
                this.amountDeductible = 0
            } else {
                this.amountDeductible = selected.Payable
            }
        },
        onTuitionInclusionSelection() {
            if (this.isNull(this.tuitionInclusions) && this.tuitionInclusions.length < 1) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'No tuition inclusion data found!'
                })
            } else {
                this.selectedTuitionInclusion = this.tuitionInclusions.find(obj => obj.id === this.selectedTuitionInclusionId)

                if (!this.isNull(this.selectedTuitionInclusion)) {
                    this.amountDeductible = this.selectedTuitionInclusion.Amount
                } else {
                    this.amountDeductible = 0
                }
            }
        }
    }, 
    created() {
    },
    mounted() {
        this.getStudentDetails()
        this.getAvailableSYPayables()
        this.getGrants()
    }
}

</script>