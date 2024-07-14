<template>
    <div class="row px-3">
        <div class="col-lg-12">
            <span class="text-muted">Select School Year</span>
            <select v-model="sy" class="form-control form-control-sm" style="width: 150px;" @change="displayClasses()">
                <option v-for="sys in schoolYears" :value="sys.SchoolYear">{{ sys.SchoolYear }}</option>
            </select>
        </div>

        <!-- classes list -->
        <div class="col-lg-12 mt-4">
            <div class="row">
                <div class="col-lg-3 col-md-6" v-for="c in classes" :key="c.id">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <h4 class="no-pads">{{ c.Subject }}</h4>
                            <p class="no-pads text-muted">{{ c.Year + ' - ' + c.Section }}</p>

                            <a :href="baseURL + '/users/view-class/' + c.ClassId + '/' + syId + '/' + c.id" class="btn btn-link text-muted float-right" title="View Class"><i class="fas fa-eye"></i></a>
                        </div>
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
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
    name : 'MultiplePayrollDeductions.multiple-payroll-deductions',
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
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            tableInputTextColor : this.isNull(document.querySelector("meta[name='color-profile']").getAttribute('content')) ? 'text-dark' : 'text-white',
            teacherId : document.querySelector("meta[name='teacher-id']").getAttribute('content'),
            schoolYears : [],
            sy : '',
            syId : '',
            classes : []
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
        getTeacherData() {
            axios.get(`${ this.baseURL }/teachers/get-teacher-data`, {
                params : {
                    id : this.teacherId,
                }
            })
            .then(response => {
                this.schoolYears = response.data.schoolYears

                if (!isNull(this.schoolYears)) {
                    this.sy = this.schoolYears[0].SchoolYear
                    this.syId = this.schoolYears[0].id

                    this.displayClasses()
                }
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting teacher data!'
                })
            })
        },
        displayClasses() {
            let schoolYear = this.schoolYears.find(obj => obj.SchoolYear === this.sy)
            if (!isNull(schoolYear)) {
                this.classes = schoolYear.SubjectClasses
                console.log(this.classes)
            }
        }
    },
    created() {
        
    },
    mounted() {
        this.getTeacherData()
    }
}

</script>