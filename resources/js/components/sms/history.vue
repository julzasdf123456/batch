
<template>
    <div class="row">
        <!-- active -->
        <div class="col-lg-12">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title text-muted">Actively Sending</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-borderless table-sm table-hover">
                        <tbody>
                            <tr v-for="(active, i) in activeData">
                                <td>
                                    <span class="text-muted text-sm">SMS Batch # {{ i+1 }}</span>
                                    <p class="ellipsize-max no-pads"><strong>{{ active.Message }}</strong></p>
                                    <div class="progress mt-2" style="height: 3px;">
                                        <div class="progress-bar bg-success" role="progressbar" :style="getPercentStyle(parseFloat(active.TotalSent), parseFloat(active.TotalRecipients))" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="text-muted text-xs">{{ active.TotalSent + "/" + active.TotalRecipients }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>

        <!-- brigade history -->
        <div class="col-lg-6">
            <div class="card shadow-none" style="height: 60vh;">
                <div class="card-header border-0">
                    <span class="text-muted">Text Brigade History</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <tbody>
                            <tr v-for="msgs in batchSmsData">
                                <td class="pointer">
                                    <p class="ellipsize-max no-pads">{{ msgs.Message }}</p>
                                    <span class="text-muted text-sm">{{ msgs.TotalRecipients }} recipients</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer"></div>
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
                batchSmsData : [],
                activeData : []
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
            batchSms() {
                axios.get(`${ this.baseURL }/sms_messages/get-batch-sms-history`)
                .then(response => {
                    this.batchSmsData = response.data
                })
                .catch(error => {
                    console.log(error.response)
                    this.toast.fire({
                        icon : 'error',
                        text : 'Error getting batch SMS data!'
                    })
                })
            },
            activeSending() {
                axios.get(`${ this.baseURL }/sms_messages/get-active-batch-sms`)
                .then(response => {
                    this.activeData = response.data
                })
                .catch(error => {
                    console.log(error.response)
                    this.toast.fire({
                        icon : 'error',
                        text : 'Error getting active data!'
                    })
                })
            },
            getPercentStyle(top, bottom) {
                if (top > 0 && bottom > 0) {
                    return "width: " + ((top / bottom) * 100) + "%"
                } else {
                    return "width: " + 0 + "%"
                }
            },
        },
        created() {
        },
        mounted() {
            this.batchSms()
            
            setInterval(() => {
                this.activeSending()
            }, 4000)
        }
    }
    
</script>