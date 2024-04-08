<template>
    <section class="content-header">
        <div class="container-fluid">
            <form class="row mb-2" @submit.prevent="getSearch()">
                <div class="col-lg-6 offset-lg-2 col-md-12">
                    <input type="text" v-model="search" @keyup="getSearch()" class="form-control" placeholder="Search student name or ID..." name="params" autofocus>
                </div>
                <div class="col-lg-3 col-md-12">
                    <button @click="getSearch()" class="btn btn-primary">Search <i class="fas fa-search ico-tab-left-mini"></i></button>
                </div>
            </form>
        </div>
    </section>

    <div class="content table-responsive px-3">
        <table class="table table-hover table-sm">
            <thead>
                <th>Student ID</th>
                <th>Student</th>
                <th>Address</th>
                <th>Latest Grade Level</th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="student in students.data" :key="student.id" style="cursor: pointer;">
                    <td @click="enroll(student.id)" class="v-align">{{ student.id }}</td>
                    <td @click="enroll(student.id)" class="v-align"><strong>{{ student.FirstName + ' ' + (isNull(student.MiddleName) ? '' : student.MiddleName) + ' ' + student.LastName + ' ' + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong></td>
                    <td @click="enroll(student.id)" class="v-align">{{ (isNull(student.Sitio) ? '' : student.Sitio) + ', ' + student.BarangaySpelled + ', ' + student.TownSpelled }}</td>
                    <td @click="enroll(student.id)" class="v-align">{{ isNull(student.CurrentGradeLevel) ? '-' : student.CurrentGradeLevel }}</td>
                    <td class="v-align text-right">
                        <a class="btn btn-primary" :href="baseURL + '/classes/enroll/' + student.id">Enroll <i class="fas fa-arrow-right ico-tab-left-mini"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <pagination :data="students" :limit="10" @pagination-change-page="getSearch"></pagination>
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
    name : 'ExistingStudentEnroll.existing-student-enroll',
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
            token : document.querySelector("meta[name='token']").getAttribute('content'),
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
            search : '',
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
        getSearch(page = 1) {
            axios.get(`${ this.baseURL }/students/search-students-paginated`, {
                params : {
                    page : page,
                    SearchParams : this.search,
                }
            })
            .then(response => {
                this.students = response.data
            })
            .catch(error => {
                console.log(error)
                // this.toast.fire({
                //     icon : 'error',
                //     text : 'Error searching application'
                // })
            })
        },
        enroll(id) {
            window.location.href = this.baseURL + '/classes/enroll/' + id
        }
    },
    created() {
    },
    mounted() {
       this.getSearch()
    }
}

</script>