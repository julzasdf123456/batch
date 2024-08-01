<template>
    <div class="row">
        <!-- junior high enrollees trend -->
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="text-muted">Junior High Enrollees Trend This Year in Contrast to Last Year</span>
                </div>
                <div class="card-body">
                    <Line :data="juniorEnrolleeLineData" :options="lineOptions" />
                </div>
                <div class="card-footer">
                    <div class="row">
                        <!-- previous year -->
                        <div class="col-lg-6">
                            <p class="no-pads text-muted text-sm text-center">Previous Total</p>
                            <p class="text-center no-pads">{{ isNull(juniorEnrolleePrevious) ? '-' : juniorEnrolleePrevious.SchoolYear }}</p>
                            <h1 class="text-center no-pads"><strong>{{ isNull(juniorEnrolleePrevious) ? '0' : toMoneyInteger(sumUpArray(juniorEnrolleePrevious.Data)) }}</strong></h1>
                        </div>
                        
                        <!-- current year -->
                        <div class="col-lg-6">
                            <p class="no-pads text-muted text-sm text-center">Current Total</p>
                            <p class="text-center no-pads">{{ juniorEnrolleeCurrent.SchoolYear }}</p>
                            <h1 class="text-center no-pads"><strong>{{ toMoneyInteger(sumUpArray(juniorEnrolleeCurrent.Data)) }}</strong></h1>
                            <p class="text-muted text-sm text-center" v-html="getPercentage(isNull(juniorEnrolleePrevious) ? 0 : sumUpArray(juniorEnrolleePrevious.Data), sumUpArray(juniorEnrolleeCurrent.Data))"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- senior high enrollees trend -->
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="text-muted">Senior High Enrollees Trend This Year in Contrast to Last Year</span>
                </div>
                <div class="card-body">
                    <Bar :data="seniorEnrolleeLineData" :options="barOptions" />
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
import { Line, Bar } from 'vue-chartjs'
import { Chart as ChartJS,
        CategoryScale,
        LinearScale,
        PointElement,
        LineElement,
        BarElement,
        Title,
        Tooltip,
        Legend } from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend
)

export default {
    name : 'DashboardIndex.dashboard-index',
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination,
        jquery,
        Line,
        Bar,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
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
            juniorEnrolleeLineData : {
                labels: [],
                datasets: [
                    {
                        label: '',
                        borderColor : '#00968b',
                        backgroundColor : '#afafaf',
                        data: []
                    }
                ]
            },
            juniorEnrolleePrevious : {},
            juniorEnrolleeCurrent : {},
            seniorEnrolleeLineData : {
                labels: [],
                datasets: [
                    {
                        label: '',
                        borderColor : '#00968b',
                        backgroundColor : '#afafaf',
                        data: []
                    }
                ]
            },
            lineOptions : {
                responsive: true,
                maintainAspectRatio: false
            },
            barOptions : {
                responsive: true,
                maintainAspectRatio: false
            }
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
        toMoneyInteger(value) {
            if (this.isNumber(value)) {
                return Number(parseFloat(value)).toLocaleString("en-US", { maximumFractionDigits: 0, minimumFractionDigits: 0 })
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
        getJuniorEnrolleesTrend() {
            axios.get(`${ this.baseURL }/home/get-junior-enrolless-trend`)
            .then(response => {
                this.juniorEnrolleePrevious = response.data.Previous
                this.juniorEnrolleeCurrent = response.data.Current
        
                this.juniorEnrolleeLineData = {
                    labels : response.data.Labels,
                    datasets : [
                        {
                            label : response.data.Current.SchoolYear,
                            data : response.data.Current.Data,
                            borderColor : this.colorProfile === 'dark-mode' ? '#46c79a' : '#349e77',
                            backgroundColor : '#ffffff',
                        },
                        {
                            label : response.data.Previous.SchoolYear,
                            data : response.data.Previous.Data,
                            borderColor : this.colorProfile === 'dark-mode' ? '#678077' : '#b1c4bd',
                            backgroundColor : '#ffffff',
                        }
                    ]
                }
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting junior enrollees trend!'
                })
            })
        },
        getSeniorEnrolleesTrend() {
            axios.get(`${ this.baseURL }/home/get-senior-enrolless-trend`)
            .then(response => {
                // this.data.datasets[0].data = response.data.Current.Data
                this.seniorEnrolleeLineData = {
                    labels : response.data.Labels,
                    datasets : [
                        {
                            label : response.data.Current.SchoolYear + ' 1st Sem',
                            data : response.data.Current.DataFirstSem,
                            backgroundColor : this.colorProfile === 'dark-mode' ? '#46c79a' : '#349e77',
                        },
                        {
                            label : response.data.Previous.SchoolYear + ' 1st Sem',
                            data : response.data.Previous.DataFirstSem,
                            backgroundColor : this.colorProfile === 'dark-mode' ? 'rgba(70, 199, 154, .2)' : 'rgba(69, 209, 160, .2)',
                            borderColor : this.colorProfile === 'dark-mode' ? '#2e8063' : '#349e77',
                            borderWidth: 2,
                        },
                        {
                            label : response.data.Current.SchoolYear + ' 2nd Sem',
                            data : response.data.Current.DataSecondSem,
                            backgroundColor : this.colorProfile === 'dark-mode' ? '#d63a73' : '#ba295e',
                        },
                        {
                            label : response.data.Previous.SchoolYear + ' 2nd Sem',
                            data : response.data.Previous.DataSecondSem,
                            backgroundColor : this.colorProfile === 'dark-mode' ? 'rgba(214, 58, 115, .2)' : 'rgba(237, 40, 112, .2)',
                            borderColor : this.colorProfile === 'dark-mode' ? '#d63a73' : '#ed2870',
                            borderWidth: 2,
                        }
                    ]
                }
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting junior enrollees trend!'
                })
            })
        },
        sumUpArray(arr) {
            if (this.isNull(arr)) {
                return 0
            } else {
                var sum = 0

                for (let i=0; i<arr.length; i++) {
                    sum += arr[i]
                }

                return sum
            }
            
        },
        getPercentage(prev, current) {
            if (prev == 0) {
                return `<span class='text-success'>${ 100 }% <i class='fas fa-caret-up'></i></span>`
            } else if (current == 0) {
                return `<span class='text-danger'>${ 0 }% <i class='fas fa-caret-down'></i></span>`
            } else {
                if (current == prev) {
                    return ''
                } else if (current > prev) {
                    var dif = current - prev

                    return `<span class='text-success'>${ round((dif / prev) * 100, 2) }% <i class='fas fa-caret-up'></i></span>`
                } else {
                    var dif = prev - current

                    return `<span class='text-danger'>${ round((dif / current) * 100, 2) }% <i class='fas fa-caret-down'></i></span>`
                }
            }
        }
    },
    created() {
        
    },
    mounted() {
        this.getJuniorEnrolleesTrend()
        this.getSeniorEnrolleesTrend()
    }
}

</script>