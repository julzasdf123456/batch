<template>
    <div class="row pt-1">
        <!-- config recipients -->
        <div class="col-lg-5">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title"><i class="fas fa-users ico-tab"></i>Configure Recipients</span>
                </div>
                <div class="card-body">
                    <span class="text-muted">Select Batch Recipients From:</span>
                    <div class="row pt-2">
                        <div class="col-lg-12 px-5" v-for="g in grades">
                            <input type="checkbox" :id="g.id" class="form-checkbox" v-model="selectedGrades" :value="g"/>
                            <label class="ico-tab-left-mini" style="font-weight: normal;" :for="g.id">{{ g.Year + '-' + g.Section + (isNull(g.Strand) ? '' : (' • ' + g.Strand)) + (isNull(g.Semester) ? '' : (' • ' + g.Semester)) }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- compose -->
        <div class="col-lg-7">
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title"><i class="fas fa-comments ico-tab"></i>Compose</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <textarea class="form-control" placeholder="Type your message here..." rows="6" v-model="message"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary float-right" @click="sendSMS()">Send SMS <i class="fas fa-paper-plane ico-tab-left-mini"></i></button>
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
            message : '',
            grades : [],
            selectedGrades : []
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
        getGrades() {
            axios.get(`${ this.baseURL }/sms_messages/get-grades`)
            .then(response => {
                this.grades = response.data
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting grades!'
                })
            })
        },
        sendSMS() {
            if (this.isNull(this.message)) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please provide a message!'
                })
            } else {
                if (this.selectedGrades.length < 1) {
                    this.toast.fire({
                        icon : 'warning',
                        text : 'Please select recipients from the class list!'
                    })
                } else {
                    axios.post(`${ this.baseURL }/sms_messages/send-sms`, {
                        _token : this.token,
                        Recipients : this.selectedGrades,
                        Message : this.message
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'SMS Composition are now queued for sending!'
                        })
                        window.location.href = this.baseURL
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error sending SMS messages!'
                        })
                    })
                }
            }
        }
    },
    created() {
        
    },
    mounted() {
        this.getGrades()
    }
}

</script>