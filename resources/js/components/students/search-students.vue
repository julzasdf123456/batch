<template>
    <div class="row">
        <!-- SEARCH -->
        <div class="col-lg-8 offset-lg-2 col-md-12">
            <input type="text" placeholder="Search student name or ID..." :autofocus="true" class="form-control" v-model="search" @keyup="searchStudent">
        </div>
        <div class="col-lg-12 mt-3">
            <div class="table-responsive px-3">
                <table class="table table-hover table-sm">
                    <thead>
                        <th class="text-muted">Student</th>
                        <th class="text-muted">Address</th>
                        <th class="text-muted">Current Grade/Level</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                        <tr v-for="student in students.data" :key="student.id" style="cursor: pointer;">
                            <td @click="viewStudent(student.id)" class="v-align">
                                <div style="display: inline-block; vertical-align: middle;">
                                    <img :src="`${imgPath}student-imgs/${student.id}.jpg`" @error="handleError" style="width: 40px; height: 40px; object-fit: cover; margin-right: 25px;" class="img-circle" alt="">
                                </div>
                                <div style="display: inline-block; height: inherit; vertical-align: middle;">
                                    <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                    <br>
                                    <span class="text-muted text-sm">{{ student.id }}</span>
                                </div>
                            </td>
                            <td @click="viewStudent(student.id)" class="v-align">{{ (isNull(student.Sitio) ? '' : student.Sitio) + ', ' + student.BarangaySpelled + ', ' + student.TownSpelled }}</td>
                            <td @click="viewStudent(student.id)" class="v-align">{{ isNull(student.Year) ? '-' : (student.Year + ' - ' + student.Section) }}</td>
                            <td @click="viewStudent(student.id)" class="v-align">
                                <span class="badge bg-success" v-if="student.ESCScholar==='Yes' && (student.Year!=='Grade 11' && student.Year!=='Grade 12')">ESC</span>
                                <span class="badge bg-danger" v-if="student.ESCScholar==='Yes' && (student.Year==='Grade 11' | student.Year==='Grade 12')">VMS</span>
                            </td>
                            <td @click="viewStudent(student.id)" class="v-align">
                                <span class="badge bg-success" v-if="student.FromSchool==='Public' && (student.Year==='Grade 11' | student.Year==='Grade 12')">From Public School</span>
                            </td>
                            <td class="v-align text-right">
                                <div class="px-3" title="More Options" style="display: inline;">
                                    <a href="#" role="button" data-toggle="dropdown" aria-expanded="false" class="btn btn-sm btn-link-muted">
                                      <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" :href="baseURL + '/students/edit-student/' + student.id + '/student-view'">Edit Student</a>
                                    </div>
                                </div>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>

                <pagination :data="students" :limit="10" @pagination-change-page="searchStudent"></pagination>
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
    name : 'SearchStudents.search-students',
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
            search : '',
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
        viewStudent(studentId) {
            window.location.href = this.baseURL + '/students/' + studentId
        },
        searchStudent(page = 1) {
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
                console.log(error.response)
                // this.toast.fire({
                //     icon : 'error',
                //     text : 'Error searching application'
                // })
            })
        },
        handleError(event) {
            event.target.src = `${this.imgPath}prof-img.png`
        }
    },
    created() {
    },
    mounted() {
        this.searchStudent()
    }
}

</script>