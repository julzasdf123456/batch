<template>
    <p class="no-pads text-center">{{ company }}</p>
    <h3 class="no-pads text-center">Daily Collection Report</h3>

    <span class="no-pads text-left">Cashier: {{ teacher }}</span>
    <p class="no-pads" style="display: inline; float: right;">Collection Date: {{ moment(collectionDate).format('MMMM DD, YYYY') }}</p>

    <table class="print-table-bordered" style="width: 100%; margin-top: 15px;">
        <thead>
            <th></th>
            <th>OR Number</th>
            <th>Student</th>
            <th>Payment For</th>
            <th>Time</th>
            <th>Mode of<br>Payment</th>
            <th class="text-right">Amount Paid</th>
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
            </tr>
            <tr>
                <td class="v-align" colspan="6"><strong>TOTAL PAYMENTS</strong></td>
                <td class="v-align text-right"><strong>{{ toMoney(summaryTotal) }}</strong></td>
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
            teacher : document.querySelector("meta[name='teacher']").getAttribute('content'),
            collectionDate : document.querySelector("meta[name='date']").getAttribute('content'),
            company : document.querySelector("meta[name='company']").getAttribute('content'),
            summary : [],
            summaryTotal : 0,
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
            const dcr = axios.get(`${ this.baseURL }/transactions/fetch-payments`, {
                params : {
                    Date : this.collectionDate,
                }
            })

            const [response1] = await Promise.all([dcr])

            this.summary = response1.data
            
            // sum total summary
            this.summaryTotal = this.summary.reduce((sum, item) => sum + parseFloat(item.TotalAmountPaid), 0)
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