<template>
    <div class="card shadow-none">
        <div class="card-header">
            <span class="card-title text-muted"><i class="fas fa-user-circle ico-tab"></i>Students Enrolled</span>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-sm">
                <thead>
                    <th class="text-muted" style="width: 40px;">#</th>
                    <th class="text-muted">Student</th>
                    <th class="text-muted">Address</th>
                    <th class="text-muted">Type</th>
                    <th class="text-muted"></th>
                </thead>
                <tbody>
                    <tr v-for="(student, index) in students" :key="student.id">
                        <td class="v-align text-muted">{{ index + 1 }}</td>
                        <td class="v-align">
                            <strong>{{ student.LastName + ', ' + student.FirstName +  (isNull(student.MiddleName) ? '' : (student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                            <span class="badge bg-danger ico-tab-left" v-if="student.Status==='Paid' ? false : true">{{ student.Status }}</span>
                        </td>
                        <td class="v-align">{{ (isNull(student.Sitio) ? '' : student.Sitio) + ', ' + student.BarangaySpelled + ', ' + student.TownSpelled }}</td>
                        <td class="v-align">{{ student.Type }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">

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
    name : 'ClassView.class-view',
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
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            classId : document.querySelector("meta[name='class-id']").getAttribute('content'),
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
            students : {},
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
        getStudents() {
            axios.get(`${ this.baseURL }/classes/get-students-from-class`, {
                params : {
                    ClassId : this.classId,
                }
            })
            .then(response => {
                this.students = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting students data!'
                })
            })
        }
    },
    created() {
    },
    mounted() {
        this.getStudents()
    }
}

</script>