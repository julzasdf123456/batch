<template>
    <!-- HEADER -->
    <section class="px-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-12">
                    <div style="display: flex; padding-bottom: 15px;">
                        <div style="width: 88px; display: inline;">
                            <img id="prof-img" style="width: 65px !important;" class="profile-user-img img-fluid img-circle" :src="imgPath + 'prof-img.png'" alt="User profile picture">
                        </div>
                        <div>
                            <span>
                                <p class="no-pads" style="font-size: 1.65em;"><strong>{{ teacherData.FullName }}</strong></p>
                                
                                <span class="text-muted">
                                    <i class="fas fa-paperclip ico-tab-mini"></i>{{ teacherData.Designation }} 
                                    <span class="ico-tab-mini ico-tab-left-mini">:</span> 
                                    <i class="fas fa-lightbulb ico-tab-mini"></i>{{ teacherData.SubjectExpertise }}
                                    <span class="badge" :class="!isNull(teacherData.Status) && teacherData.Status==='ACTIVE' ? 'bg-success' : 'bg-danger'">{{ isNull(teacherData.Status) ? 'ACTIVE' : teacherData.Status }}</span>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BODY -->
    <div class="row">
        <!-- School Years -->
        <div class="col-lg-3 col-md-6">
            <!-- SCHOOL YEARS -->
            <div v-for="(sy, index) in schoolYears" :key="sy.SchoolYear" class="card shadow-none" :class="index > 0 ? 'collapsed-card' : ''">
                <div class="card-header border-0">
                    <span class="card-title"><strong>{{ sy.SchoolYear }}</strong></span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas" :class="index > 0 ? 'fa-plus' : 'fa-minus'"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-borderless">
                        <tbody>
                            <tr v-for="subject in sy.SubjectClasses" :key="subject.id" class="pointer" :class="subject.Selected==='true' ? 'bg-semi-trans' : ''">
                                <td class="v-align" @click="getStudentsFromSubjectClass(subject)">
                                    {{ subject.Subject }}
                                    <br>
                                    <span class="text-muted text-sm">in {{ isNull(subject.Year) ? '-' : (subject.Year + ' - ' + subject.Section) }}</span>
                                </td>
                                <td class="v-align text-right" @click="getStudentsFromSubjectClass(subject)">
                                    <i :class="subject.Selected==='true' ? 'fas fa-angle-right' : ''"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Students -->
        <div class="col-lg-9 col-md-6">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-user-circle ico-tab"></i>{{ studentsCardTitle }}</span>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-bordered table-sm">
                        <thead>
                            <th></th>
                            <th class="text-muted">Student</th>
                            <th class="text-muted">1st Grading</th>
                            <th class="text-muted">2nd Grading</th>
                            <th class="text-muted">3rd Grading</th>
                            <th class="text-muted">4th Grading</th>
                            <th class="text-muted">Final Grade</th>
                        </thead>
                        <tbody>
                            <tr v-for="(student, index) in students" :key="student.StudentSubjectId">
                                <td class="v-align">{{ index+1 }}</td>
                                <td class="v-align">
                                    <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                </td>
                                <td class="v-align text-right">
                                    <input class="table-input text-right" :class="tableInputTextColor" v-model="student.FirstGradingGrade" @keyup.enter="inputEnter(student.FirstGradingGrade, student.id, 1)" @blur="inputEnter(student.FirstGradingGrade, student.id, 1)" type="number" step="any"/>
                                </td>
                                <td class="v-align text-right">
                                    <input class="table-input text-right" :class="tableInputTextColor" v-model="student.SecondGradingGrade" @keyup.enter="inputEnter(student.SecondGradingGrade, student.id, 2)" @blur="inputEnter(student.SecondGradingGrade, student.id, 2)" type="number" step="any"/>
                                </td>
                                <td class="v-align text-right">
                                    <input class="table-input text-right" :class="tableInputTextColor" v-model="student.ThirdGradingGrade" @keyup.enter="inputEnter(student.ThirdGradingGrade, student.id, 3)" @blur="inputEnter(student.ThirdGradingGrade, student.id, 3)" type="number" step="any"/>
                                </td>
                                <td class="v-align text-right">
                                    <input class="table-input text-right" :class="tableInputTextColor" v-model="student.FourthGradingGrade" @keyup.enter="inputEnter(student.FourthGradingGrade, student.id, 4)" @blur="inputEnter(student.FourthGradingGrade, student.id, 4)" type="number" step="any"/>
                                </td>
                                <td class="v-align text-right">
                                    <input class="table-input text-right" :class="tableInputTextColor" v-model="student.AverageGrade" @keyup.enter="inputEnter(student.AverageGrade, student.id, 0)" @blur="inputEnter(student.AverageGrade, student.id, 0)" type="number" step="any"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>
    
    <div class="right-bottom" style="bottom: 0px !important;">
        <p id="msg-display" class="msg-display shadow" style="font-size: .8em;"><i class="fas fa-check-circle ico-tab-mini text-success"></i>saved!</p>
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
    name : 'ViewTeacher.view-teacher',
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
            teacherId : document.querySelector("meta[name='teacher-id']").getAttribute('content'),
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
            teacherData : {},
            schoolYears : [],
            students : [],
            studentsCardTitle : '...'
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
        clearSelected() {
            for(let i=0; i<this.schoolYears.length; i++) {
                for(let j=0; j<this.schoolYears[i].SubjectClasses.length; j++) {
                    this.schoolYears[i].SubjectClasses[j].Selected = 'false'
                }
            }
        },
        getTeacherData() {
            axios.get(`${ this.baseURL }/teachers/get-teacher-data`, {
                params : {
                    id : this.teacherId,
                }
            })
            .then(response => {
                this.teacherData = response.data.teacher
                this.schoolYears = response.data.schoolYears
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting teacher data!'
                })
            })
        },
        getStudentsFromSubjectClass(subjectData) {
            this.clearSelected()
            subjectData.Selected = 'true'
            axios.get(`${ this.baseURL }/teachers/get-students-from-subject-class`, {
                params : {
                    ClassId : subjectData.ClassId,
                    TeacherId : this.teacherId,
                    SubjectId : subjectData.id
                }
            })
            .then(response => {
                this.students = response.data
                this.studentsCardTitle = 'Students in ' + subjectData.Subject + ', ' + (isNull(subjectData.Year) ? '-' : (subjectData.Year + ' - ' + subjectData.Section) )
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting students!'
                })
            })
        },
        showSaveFader() {
            var message = document.getElementById('msg-display');

            // Show the message
            message.style.opacity = 1;

            // Wait for 3 seconds
            setTimeout(function() {
                // Fade out the message
                message.style.opacity = 0;
            }, 1500);
        },
        getFinalGrade(id) {
            var student = this.students.find(obj => obj.id === id)
            console.log(student)

            var count = 0
            if (!this.isNull(student.FirstGradingGrade)) {
                count += 1
            }
            if (!this.isNull(student.SecondGradingGrade)) {
                count += 1
            }
            if (!this.isNull(student.ThirdGradingGrade)) {
                count += 1
            }
            if (!this.isNull(student.FourthGradingGrade)) {
                count += 1
            }

            return count
        },
        updateFinalGrade(id) {
            console.log(this.getFinalGrade(id))
            this.students = this.students.map(obj => {
                if (obj.id === id) {
                    return { ...obj, AverageGrade: this.getFinalGrade(id) };
                }
                return obj; 
            })
        },
        inputEnter(grade, id, gradePosition) {
            axios.post(`${ this.baseURL }/student_subjects/update-grade`, {
                _token : this.token,
                id : id,
                Grade : grade,
                FinalGrade : 0,
                GradePosition : gradePosition
            })
            .then(response => {
                this.showSaveFader()
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error updating grade!'
                })
            })
        }
    }, 
    created() {
    },
    mounted() {
        this.getTeacherData()
    }
}

</script>