<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="text-muted">Junior High Enrollees Trend This Year in Contrast to Last Year</span>
                </div>
                <div class="card-body">
                    <Line :data="juniorEnrolleeLineData" :options="lineOptions" />
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
import { Line } from 'vue-chartjs'
import { Chart as ChartJS,
        CategoryScale,
        LinearScale,
        PointElement,
        LineElement,
        Title,
        Tooltip,
        Legend } from 'chart.js'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
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
            lineOptions : {
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
                // console.log(response.data.Current.Data)
                // this.data.datasets[0].data = response.data.Current.Data
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
        }
    },
    created() {
        
    },
    mounted() {
        this.getJuniorEnrolleesTrend()
    }
}

</script>