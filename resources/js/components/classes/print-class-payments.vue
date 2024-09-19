<template>
    <h3 class="no-pads text-center">{{ classDetails.Subject }}</h3>
    <p class="no-pads text-center">{{ classDetails.Year + ' - ' + classDetails.Section }} | {{ syDetails.SchoolYear }}</p>

    <table class="print-table-bordered" style="width: 100%;">
        <thead>
            <th></th>
            <th class="text-center">Students</th>
            <th class="text-center">Tuition<br>Payable</th>
            <th class="text-center" v-for="pm in paymentMonths">{{ moment(pm.ForMonth).format('MMM YYYY') }}</th>
            <th class="text-center">Remaining<br>Balance</th>
        </thead>
        <tbody>
            <tr>
                <td :colspan="(4 + (paymentMonths.length))" class="text-muted text-center">Male Students</td>
            </tr>
            <tr v-for="(student, index) in male" :key="student.StudentSubjectId">
                <td class="v-align">{{ index+1 }}</td>
                <td class="v-align">
                    <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.Status==='Pending Enrollment Payment' ? true : false">Pending</span>
                </td>
                <td class="text-right v-align text-primary">{{ isNull(student.PayableData) ? '-' : toMoney(parseFloat(student.PayableData.AmountPayable)) }}</td>
                <td class="text-right v-align" v-for="pmd in paymentMonths" v-html="getPaymentData(pmd.ForMonth, student.id)"></td>
                <td class="text-right v-align text-danger"><strong>{{ isNull(student.PayableData) ? '-' : toMoney(parseFloat(student.PayableData.Balance)) }}</strong></td>
            </tr>
            <tr>
                <td :colspan="(4 + (paymentMonths.length))" class="text-muted text-center">Female Students</td>
            </tr>
            <tr v-for="(student, index) in female" :key="student.StudentSubjectId">
                <td class="v-align">{{ index+1 }}</td>
                <td class="v-align">
                    <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.Status==='Pending Enrollment Payment' ? true : false">Pending</span>
                </td>
                <td class="text-right v-align text-primary">{{ isNull(student.PayableData) ? '-' : toMoney(parseFloat(student.PayableData.AmountPayable)) }}</td>
                <td class="text-right v-align" v-for="pmd in paymentMonths" v-html="getPaymentData(pmd.ForMonth, student.id)"></td>
                <td class="text-right v-align text-danger"><strong>{{ isNull(student.PayableData) ? '-' : toMoney(parseFloat(student.PayableData.Balance)) }}</strong></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    components : {
        FlatPickr,
        Swal,
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
            classId : document.querySelector("meta[name='class-id']").getAttribute('content'),
            teacherId : document.querySelector("meta[name='teacher-id']").getAttribute('content'),
            syId : document.querySelector("meta[name='school-year-id']").getAttribute('content'),
            classDetails : {},
            syDetails : {},
            male : {},
            female : {},
            visibilityToggle : false,
            paymentMonths : [],
            paymentData : [],
            payablesProfile : [],
        }
    },
    methods : {
        isNull (item) {
            if (jquery.isEmptyObject(item)) {
                return true;
            } else {
                return false;
            }
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
        async fetchAllData() {
            const classRequest = axios.get(`${ this.baseURL }/users/get-advisory-details`, {
                    params : {
                        TeacherId : this.teacherId,
                        SchoolYearId : this.syId,
                        ClassId : this.classId
                    }
                })

            const [response1] = await Promise.all([classRequest])

            this.classDetails = response1.data.Class
            this.syDetails = response1.data.SchoolYear
            this.male = response1.data.Male
            this.female = response1.data.Female

            if (!this.isNull(this.male)) {
                this.visibilityToggle = this.isNull(this.male[0].Visibility) ? false : true
            }

            if (!this.isNull(this.female)) {
                this.visibilityToggle = this.isNull(this.female[0].Visibility) ? false : true
            }
            
            const paymentRequest = axios.get(`${ this.baseURL }/teachers/get-class-payment-details`, {
                    params : {
                        ClassId : this.classId,
                        SchoolYear : this.syDetails.SchoolYear,
                    }
                })

            const [response2] = await Promise.all([paymentRequest])

            this.paymentMonths = response2.data.Months
            this.paymentData = response2.data.PaymentData
            this.payablesProfile = response2.data.PayableProfile

            // add payables profile to male and female array
            console.log(this.payablesProfile)
            if (!this.isNull(this.male)) {
                for(let i=0; i<this.male.length; i++) {
                    let dataFound = this.payablesProfile.find(obj => obj.StudentId === this.male[i].id)

                    if (!this.isNull(dataFound)) {
                        this.male[i].PayableData = dataFound
                    } else {
                        this.male[i].PayableData = []
                    }
                }
            }

            if (!this.isNull(this.female)) {
                for(let i=0; i<this.female.length; i++) {
                    let dataFound = this.payablesProfile.find(obj => obj.StudentId === this.female[i].id)

                    if (!this.isNull(dataFound)) {
                        this.female[i].PayableData = dataFound
                    } else {
                        this.female[i].PayableData = []
                    }
                }
            }
        },
        getPaymentData(month, studentId) {
            let dataFound = this.paymentData.find(obj => obj.ForMonth === month && obj.StudentId === studentId)

            if (this.isNull(dataFound)) {
                return `<span class="text-sm"><i class="fas fa-exclamation-circle text-gray"></i></span>`
            } else {
                var bal = (this.isNull(dataFound.Balance) ? 0 : parseFloat(dataFound.Balance))

                if (bal > 0) {
                    if (this.isNull(dataFound.AmountPaid)) {
                         /*`<span class="text-sm" title='Unpaid'><i class="fas fa-exclamation-circle text-gray ico-tab-mini"></i> Unpaid</span><br>` + */
                        return        `<span class='text-sm text-muted'>Bal: ` + bal + `</span>`
                    } else {
                         /* `<strong class='text-success'>Partial</strong><br>` + */
                        return    `<span class='text-sm text-muted'>Bal: ` + bal + `</span>`
                    }
                } else {
                    return `<strong class='text-success'>✔</strong>`
                }
            }
        },
        async getAndPrint() {
            await this.fetchAllData()

            /**
             *  INITIALIZE PRINT
             */
            window.print();

            window.setTimeout(function(){
                window.history.go(-1)
            }, 200);
        }
    },
    created() {
        
    },
    async mounted() {
        await this.getAndPrint()
    },
}

</script>