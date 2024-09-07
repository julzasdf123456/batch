<template>
    <div class="row" @click="focusInput()">
        <div class="col-lg-4 content-cards px-5" @click="focusInput()">
            <!-- scanner -->
            <div class="card shadow-soft w-100" style="height: 80vh !important;" @click="focusInput()">
                <div class="card-body" @click="focusInput()">
                    <h3 class="mt-3 text-success"><i class="fas fa-qrcode ico-tab" @click="focusInput()"></i><strong>batch.ID</strong></h3>
                    <p class="no-pads text-muted text-sm" @click="focusInput()">Barcode and QR Scanning ID System</p>
                
                    <input ref="scanner" type="text" class="form-control form-control-lg mt-3" autofocus placeholder="QR/Barcode..." @keyup.enter="getStudentDetails" v-model="idNumber">

                    <button class="btn btn-danger mt-3" @click="trigger()"><i class="fas fa-shield-alt ico-tab-mini"></i>Trigger</button>
                </div>
                <div class="card-footer" @click="focusInput()">
                    <p class="text-muted text-right text-sm mt-4 mb-3" @click="focusInput()">batch.ID | All Rights Reserved @ Hashed.it</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 content-cards pr-5">
            <!-- results -->
            <div class="card shadow-none w-100 student-info-card" style="height: 80vh !important;" id="student-info" @click="focusInput()">
                <div class="card-body" @click="focusInput()">
                    <div class="row" @click="focusInput()">
                        <div class="col-lg-12" @click="focusInput()">
                            <div style="display: flex; padding: 20px 20px; align-items: center; justify-content: center; flex-direction: column; gap: 5px;" @click="focusInput()">
                                <h1 @click="focusInput()" class="py-3" :class="notifClass" v-html="notifHead"></h1>

                                <img @click="focusInput()" id="prof-img" style="width: 140px !important; height: 140px !important; object-fit: cover; text-align: center; line-height: 120px;" class="profile-user-img img-fluid img-circle" :src="imgPath + 'student-imgs/' + imgId" alt="No Picture">

                                <p @click="focusInput()" class="no-pads" style="font-size: 2.15em;"><strong>{{ studentData.LastName + ', ' + studentData.FirstName + (isNull(studentData.MiddleName) ? '' : (' ' + studentData.MiddleName + ' ')) + (isNull(studentData.Suffix) ? '' : studentData.Suffix) }}</strong></p>
                                <p @click="focusInput()" class="text-muted no-pads">
                                    <i class="fas fa-id-badge ico-tab"></i>LRN-{{ studentData.LRN }} 
                                </p>
                                <p @click="focusInput()" class="text-muted no-pads">
                                    <i class="fas fa-clock ico-tab"></i>{{ moment().format('dddd, MMMM DD, YYYY, hh:mm a') }} 
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.content-cards {
    margin-top: 5%;
}

.student-info-card {
    opacity: 0;
    transition: opacity .5s ease;
}

</style>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'ScanId.scan-id',
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
            token :  document.querySelector("meta[name='token']").getAttribute('content'),
            toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            }),
            studentData : {},
            idNumber : '',
            notifHead : '',
            notifClass : '',
            imgId : '',
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
            this.studentData = []
            axios.get(`${ this.baseURL }/barcode_attendances/punch-student`, {
                params : {
                    StudentId : this.idNumber,
                }
            })
            .then(response => {
                this.studentData = response.data.StudentDetails
                this.imgId = this.studentData.id + '.jpg'
                if (this.studentData != null) {
                    // save student data for sms sending
                    this.saveAttendanceInfo(this.studentData, response.data.LatestPunch)
                }
                
                this.idNumber = ''
            })
            .catch(error => {
                this.idNumber = ''
                this.focusInput()
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
        },
        showStudentInfo() {
            var message = document.getElementById('student-info');

            // Show the message
            message.style.opacity = 1;

            // Wait for 3 seconds
            setTimeout(function() {
                // Fade out the message
                message.style.opacity = 0;
            }, 2500);
        },
        saveAttendanceInfo(studentData, latestPunch) {
            axios.post(`${ this.baseURL }/barcodeAttendances`, {
                _token : this.token,
                id : this.generateUniqueId(),
                StudentId : studentData.id,
                PunchType : this.isNull(latestPunch) ? 'IN' : (latestPunch.PunchType === 'IN' ? 'OUT' : 'IN'),
                ContactNumber : studentData.ContactNumber,
            })
            .then(response => {
                this.notifHead = `<i class="fas fa-check ico-tab"></i>Student Marked!`
                this.notifClass = 'text-success'
                this.showStudentInfo()

                this.focusInput()
                this.idNumber = ''
            })
            .catch(error => {
                this.idNumber = ''
                console.log(error.response)

                if (error.response.status === 400 | error.response.status === '400') {
                    this.notifHead = `<i class="fas fa-exclamation-triangle ico-tab"></i>Student Already Marked!`
                    this.notifClass = 'text-danger'
                    this.showStudentInfo()
                } else {
                    this.toast.fire({
                        icon : 'error',
                        text : 'Error saving sms data!'
                    })
                }
                
                this.focusInput()
            })
        },
        focusInput() {
            this.$nextTick(function() {
                this.$refs.scanner.focus()
            })
        },
        trigger() {
            this.getStudentDetails()
        }
    }, 
    created() {
    },
    mounted() {
    
    }
}

</script>