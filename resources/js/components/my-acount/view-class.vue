<template>
    <div class="row px-4">
        <div class="col-lg-12">
            <h4><i class="fas fa-book ico-tab text-muted"></i>{{ classDetails.Subject }}</h4>
            <p class="no-pads text-muted">{{ classDetails.Year + ' - ' + classDetails.Section }} | {{ syDetails.SchoolYear }}</p>
        </div>

        <!-- students in class -->
        <div class="col-lg-12 mt-3">
            <div class="card shadow-none">
                <div class="card-body">
                    <div >
                        <!-- TAB HEADS -->
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="students-list-tab" data-toggle="pill" href="#students-list-content" role="tab" aria-controls="students-list-content" aria-selected="false">Grading</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="payments-tab" data-toggle="pill" href="#payments-content" role="tab" aria-controls="payments-content" aria-selected="false">Payments</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <!-- 
                                ====================================================================================================================================
                                GRADING 
                                ====================================================================================================================================
                            -->
                            <div class="tab-pane fade active show" id="students-list-content" role="tabpanel" aria-labelledby="students-list-tab">
                                <div class="table-responsive mt-2">
                                    <div class="custom-control custom-switch mt-2 mb-2 float-right" style="margin-left: 10px; margin-top: 6px; margin-bottom: 6px;">
                                        <input type="checkbox" class="custom-control-input" id="visibility" v-model="visibilityToggle" @change="toggleGradeVisibility()" title="Turning this ON enables the parents and students to view the grades">
                                        <label style="font-weight: normal;" class="custom-control-label" for="visibility" id="visibilityLabel" title="Turning this ON enables the parents and students to view the grades">
                                            Toggle Visibility 
                                            <span class="text-muted">({{ visibilityToggle ? 'VISIBLE' : 'HIDDEN' }})</span>
                                        </label>
                                    </div>

                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <th></th>
                                            <th class="text-muted">Student</th>
                                            <th class="text-muted">1st Grading</th>
                                            <th class="text-muted">2nd Grading</th>
                                            <th class="text-muted">3rd Grading</th>
                                            <th class="text-muted">4th Grading</th>
                                            <th class="text-muted">Final Grade</th>
                                            <th style="width: 20px;"></th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="8" class="text-muted">Male Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in male" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.StudentId">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.Status==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Male${index}-1`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.FirstGradingGrade" @keyup.enter="inputEnter(student.FirstGradingGrade, student.id, 1, 'Male', 'enter', index)" @blur="inputEnter(student.FirstGradingGrade, student.id, 1, 'Male')" type="number" step="any"/>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Male${index}-2`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.SecondGradingGrade" @keyup.enter="inputEnter(student.SecondGradingGrade, student.id, 2, 'Male', 'enter', index)" @blur="inputEnter(student.SecondGradingGrade, student.id, 2, 'Male')" type="number" step="any"/>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Male${index}-3`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.ThirdGradingGrade" @keyup.enter="inputEnter(student.ThirdGradingGrade, student.id, 3, 'Male', 'enter', index)" @blur="inputEnter(student.ThirdGradingGrade, student.id, 3, 'Male')" type="number" step="any"/>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Male${index}-4`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.FourthGradingGrade" @keyup.enter="inputEnter(student.FourthGradingGrade, student.id, 4, 'Male', 'enter', index)" @blur="inputEnter(student.FourthGradingGrade, student.id, 4, 'Male')" type="number" step="any"/>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Male${index}-0`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.AverageGrade" @keyup.enter="inputEnter(student.AverageGrade, student.id, 0, 'Male', 'enter', index)" @blur="inputEnter(student.AverageGrade, student.id, 0, 'Male')" type="number" step="any"/>
                                                </td>
                                                <td class="text-right v-align">
                                                    <i class="fas fa-eye text-sm" :class="isNull(student.Visibility) ? 'text-muted' : 'text-success'"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="8" class="text-muted">Female Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in female" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.StudentId">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.Status==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Female${index}-1`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.FirstGradingGrade" @keyup.enter="inputEnter(student.FirstGradingGrade, student.id, 1, 'Female', 'enter', index)" @blur="inputEnter(student.FirstGradingGrade, student.id, 1, 'Female')" type="number" step="any"/>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Female${index}-2`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.SecondGradingGrade" @keyup.enter="inputEnter(student.SecondGradingGrade, student.id, 2, 'Female', 'enter', index)" @blur="inputEnter(student.SecondGradingGrade, student.id, 2, 'Female')" type="number" step="any"/>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Female${index}-3`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.ThirdGradingGrade" @keyup.enter="inputEnter(student.ThirdGradingGrade, student.id, 3, 'Female', 'enter', index)" @blur="inputEnter(student.ThirdGradingGrade, student.id, 3, 'Female')" type="number" step="any"/>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Female${index}-4`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.FourthGradingGrade" @keyup.enter="inputEnter(student.FourthGradingGrade, student.id, 4, 'Female', 'enter', index)" @blur="inputEnter(student.FourthGradingGrade, student.id, 4, 'Female')" type="number" step="any"/>
                                                </td>
                                                <td class="v-align text-right">
                                                    <input :ref="el => { if (el) inputRefs[`Female${index}-0`] = el }" class="table-input text-right" :class="tableInputTextColor" v-model="student.AverageGrade" @keyup.enter="inputEnter(student.AverageGrade, student.id, 0, 'Female', 'enter', index)" @blur="inputEnter(student.AverageGrade, student.id, 0, 'Female')" type="number" step="any"/>
                                                </td>
                                                <td class="text-right v-align">
                                                    <i class="fas fa-eye text-sm" :class="isNull(student.Visibility) ? 'text-muted' : 'text-success'"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 
                                ====================================================================================================================================
                                PAYMENTS 
                                ====================================================================================================================================
                            -->
                            <div class="tab-pane fade" id="payments-content" role="tabpanel" aria-labelledby="payments-tab">
                                <div class="mt-2">
                                    <a :href="baseURL + '/teachers/print-class-payment-details/' + classId + '/' + syId + '/' + subjectId" class="btn btn-link btn-link-muted" title="Print"><i class="fas fa-print"></i></a>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <th></th>
                                            <th class="text-center">Students</th>
                                            <th class="text-center">Tuition<br>Payable</th>
                                            <th class="text-center" v-for="pm in paymentMonths">{{ moment(pm.ForMonth).format('MMM YYYY') }}</th>
                                            <th class="text-center">Remaining<br>Balance</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td :colspan="(4 + (paymentMonths.length))" class="text-muted">Male Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in male" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <span><i class="ico-tab-mini text-xs fas" :class="student.FromSchool==='Private' ? 'fa-user-lock text-primary' : 'fa-user-check text-warning'" :title="student.FromSchool==='Private' ? 'From Private School' : 'From Public School'"></i></span>
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.StudentId">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.Status==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="text-right v-align text-primary">{{ isNull(student.PayableData) ? '-' : toMoney(parseFloat(student.PayableData.AmountPayable)) }}</td>
                                                <td class="text-right v-align" v-for="pmd in paymentMonths" v-html="getPaymentData(pmd.ForMonth, student.StudentId)"></td>
                                                <td class="text-right v-align text-danger">{{ isNull(student.PayableData) ? '-' : toMoney(parseFloat(student.PayableData.Balance)) }}</td>
                                            </tr>
                                            <tr>
                                                <td :colspan="(4 + (paymentMonths.length))" class="text-muted">Female Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in female" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <span><i class="ico-tab-mini text-xs fas" :class="student.FromSchool==='Private' ? 'fa-user-lock text-primary' : 'fa-user-check text-warning'" :title="student.FromSchool==='Private' ? 'From Private School' : 'From Public School'"></i></span>
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.StudentId">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.Status==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="text-right v-align text-primary">{{ isNull(student.PayableData) ? '-' : toMoney(parseFloat(student.PayableData.AmountPayable)) }}</td>
                                                <td class="text-right v-align" v-for="pmd in paymentMonths" v-html="getPaymentData(pmd.ForMonth, student.StudentId)"></td>
                                                <td class="text-right v-align text-danger">{{ isNull(student.PayableData) ? '-' : toMoney(parseFloat(student.PayableData.Balance)) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';

export default {
    name : 'ViewClass.view-class',
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
            classId : document.querySelector("meta[name='class-id']").getAttribute('content'),
            teacherId : document.querySelector("meta[name='teacher-id']").getAttribute('content'),
            syId : document.querySelector("meta[name='school-year-id']").getAttribute('content'),
            subjectId : document.querySelector("meta[name='subject-id']").getAttribute('content'),
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            classDetails : {},
            syDetails : {},
            male : {},
            female : {},
            visibilityToggle : false,
            paymentMonths : [],
            paymentData : [],
            payablesProfile : [],
            inputRefs : ref({}),
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
        getClassData() {
            axios.get(`${ this.baseURL }/teachers/get-class-details`, {
                params : {
                    ClassId : this.classId,
                    TeacherId : this.teacherId,
                    SubjectId : this.subjectId,
                    SchoolYearId : this.syId,
                }
            })
            .then(response => {
                this.classDetails = response.data.Class
                this.male = response.data.MaleStudents
                this.female = response.data.FemaleStudents
                this.syDetails = response.data.SchoolYear
                
                this.getClassPaymentDetails()

                if (!this.isNull(this.male)) {
                    this.visibilityToggle = this.isNull(this.male[0].Visibility) ? false : true
                }

                if (!this.isNull(this.female)) {
                    this.visibilityToggle = this.isNull(this.female[0].Visibility) ? false : true
                }
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting data!'
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
        inputEnter(grade, id, gradePosition, gender, key = '', index = -999) {
            // auto compute final grades
            var subjectData = {}
            if (gender === 'Male') {
                subjectData = this.male.find(obj => obj.id === id)
            } else {
                subjectData = this.female.find(obj => obj.id === id)
            }

            var finalGrade = null
            if (!this.isNull(subjectData)) {
                var grades = []

                // first grading
                if (subjectData.FirstGradingGrade) {
                    grades.push(subjectData.FirstGradingGrade + '')
                }

                // second grading
                if (subjectData.SecondGradingGrade) {
                    grades.push(subjectData.SecondGradingGrade + '')
                }
                
                // third grading
                if (subjectData.ThirdGradingGrade) {
                    grades.push(subjectData.ThirdGradingGrade + '')
                }
                
                // fourth grading
                if (subjectData.FourthGradingGrade) {
                    grades.push(subjectData.FourthGradingGrade + '')
                }

                const len = grades.length
                if (len > 0) {
                    var sum = 0
                    for (let i=0; i<len; i++) {
                        sum += parseFloat(grades[i])
                    }

                    finalGrade = this.round(sum / len)
                } else {
                    finalGrade = 0
                }
            } else {
                finalGrade = grade
            }

            // update live array data
            if (gender === 'Male') {
                this.male = this.male.map(item => {
                    if (item.id === id) {
                        return { ...item, AverageGrade : finalGrade }; 
                    }
                    return item;
                })
            } else {
                this.female = this.female.map(item => {
                    if (item.id === id) {
                        return { ...item, AverageGrade : finalGrade }; 
                    }
                    return item;
                })
            }

            if (key === 'enter') {
                this.focusNextRow(gender, index, gradePosition)
            }

            axios.post(`${ this.baseURL }/student_subjects/update-grade`, {
                _token : this.token,
                id : id,
                Grade : grade,
                FinalGrade : finalGrade,
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
        },
        toggleGradeVisibility() {
            if (this.visibilityToggle) {
                this.visibilityToggle = true
            } else {
                this.visibilityToggle = false
            }
            
            axios.post(`${ this.baseURL }/teachers/update-grade-visibility`, {
                _token : this.token,
                ClassId : this.classId,
                TeacherId : this.teacherId,
                SubjectId : this.subjectId,
                Visibility : this.visibilityToggle ? 'Yes' : null,
            })
            .then(response => {
                this.male = this.male.map(obj => {
                    return { ...obj, Visibility: this.visibilityToggle ? 'Yes' : null };
                })

                this.female = this.female.map(obj => {
                    return { ...obj, Visibility: this.visibilityToggle ? 'Yes' : null };
                })

                this.toast.fire({
                    icon : 'success',
                    text : 'Grades made visible to students!'
                })
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error updating grade visibility!'
                })
            })
        },
        getClassPaymentDetails() {
            axios.get(`${ this.baseURL }/teachers/get-class-payment-details`, {
                params : {
                    ClassId : this.classId,
                    SchoolYear : this.syDetails.SchoolYear,
                }
            })
            .then(response => {
                this.paymentMonths = response.data.Months
                this.paymentData = response.data.PaymentData
                this.payablesProfile = response.data.PayableProfile

                // add payables profile to male and female array
                console.log(response.data)
                if (!this.isNull(this.male)) {
                    for(let i=0; i<this.male.length; i++) {
                        let dataFound = this.payablesProfile.find(obj => obj.StudentId === this.male[i].StudentId)

                        if (!this.isNull(dataFound)) {
                            this.male[i].PayableData = dataFound
                        } else {
                            this.male[i].PayableData = []
                        }
                    }
                }

                if (!this.isNull(this.female)) {
                    for(let i=0; i<this.female.length; i++) {
                        let dataFound = this.payablesProfile.find(obj => obj.StudentId === this.female[i].StudentId)

                        if (!this.isNull(dataFound)) {
                            this.female[i].PayableData = dataFound
                        } else {
                            this.female[i].PayableData = []
                        }
                    }
                }
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting payment data!'
                })
            })
        },
        getPaymentData(month, studentId) {
            let dataFound = this.paymentData.find(obj => obj.ForMonth === month && obj.StudentId === studentId)

            if (this.isNull(dataFound)) {
                return `<span class="text-sm"><i class="fas fa-exclamation-circle text-gray"></i></span>`
            } else {
                var bal = (this.isNull(dataFound.Balance) ? 0 : parseFloat(dataFound.Balance))

                if (bal > 0) {
                    if (this.isNull(dataFound.AmountPaid)) {
                        return `<span class="text-sm" title='Unpaid'><i class="fas fa-exclamation-circle text-gray ico-tab-mini"></i> Unpaid</span>` +
                                `<br><span class='text-sm text-muted'>Bal: ` + bal + `</span>`
                    } else {
                        return `<span class="text-sm ico-tab-mini" title='Partially paid'><i class="fas fa-check text-warning"></i></span>` + 
                            `<strong>` + (this.isNull(dataFound) ? '-' : (this.isNull(dataFound.AmountPaid) ? '-' : this.toMoney(parseFloat(dataFound.AmountPaid)))) + `</strong>` +
                            `<br><span class='text-sm text-muted'>Bal: ` + bal + `</span>`
                    }
                } else {
                    return `<span class="text-sm ico-tab-mini" title='Fully paid'><i class="fas fa-check-circle text-success"></i></span>` + 
                        `<strong>` + (this.isNull(dataFound) ? '-' : (this.isNull(dataFound.AmountPaid) ? '-' : this.toMoney(parseFloat(dataFound.AmountPaid)))) + `</strong>` +
                        `<br><span class='text-sm text-muted'>Bal: ` + bal + `</span>`
                }
            }
        },
        focusNextRow(gender, index, cellIndex) {
            const nextRowIndex = index + 1;

            const nextInputKey = `${gender + '' + nextRowIndex}-${cellIndex}`;

            const nextInput =  this.inputRefs[nextInputKey]
            if (nextInput) {
                nextInput.focus();
                setTimeout(() => {
                    nextInput.select();
                }, 0);
            }
        },
    },
    created() {
        
    },
    mounted() {
        this.getClassData()
    }
}

</script>