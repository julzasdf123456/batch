<template>
    <div class="row">
        <!-- Subject Arrangements -->
        <div class="col-lg-6 offset-lg-3 col-md-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title text-muted">Arrange the Subjects According to Your Preference</span>
                </div>
                <div class="card-body p-0" style="width: 100% !important;">
                    <table class="table table-hover" style="width: 100% !important;">
                        <tbody style="width: 100% !important;">
                            <transition-group name="list" tag="tbody">
                                <tr  style="width: 100% !important;" v-for="(subject, index) in subjects" :key="subject.id">
                                    <td  style="width: 100% !important;" class="v-align">
                                        <span v-if="subject.ParentSubject" class="text-muted text-sm">{{ subject.ParentSubject }}</span>
                                        <br v-if="subject.ParentSubject">
                                        {{ subject.Subject }}
                                    </td>
                                    <td class="text-right v-align">
                                        <button @click="moveUp(index)" :disabled="index === 0" class="btn btn-default btn-sm" title="Move up"><i class="fas fa-arrow-up"></i></button>
                                        <button @click="moveDown(index)" :disabled="index === subjects.length - 1" class="btn btn-default btn-sm" title="Move down"><i class="fas fa-arrow-down"></i></button>
                                    </td>
                                </tr>
                            </transition-group>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <!-- <button @click="save()" class="btn btn-primary float-right"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button> -->
                </div>
            </div>
        </div>

        <!-- Parent subjects -->
        <div class="col-lg-6 offset-lg-3 col-md-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <p class="card-title text-muted">
                        Parent Subject Averaging
                        <br>
                        <p class="text-muted no-pads text-sm">Each subject component of the following subject parents will be averaged if checked.</p>
                    </p>
                    
                </div>
                <div class="card-body p-0" style="width: 100% !important;">
                    <table class="table table-hover" style="width: 100% !important;">
                        <tbody style="width: 100% !important;">
                            <tr v-for="parent in parentSubjects">
                                <td class="v-align" style="width: 30px;">
                                    <input type="checkbox" v-model="averagedParents" :value="parent" :checked="averagedParents.includes(parent)">
                                </td>
                                <td>{{ parent }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <!-- <button @click="save()" class="btn btn-primary float-right"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button> -->
                </div>
            </div>
        </div>
    </div>

    
    <div class="right-bottom">
        <button @click="save()" class="btn-floating shadow btn-primary">Save Stub Print Config <i class="fas fa-check-circle ico-tab-left-mini"></i></button>
    </div>
</template>

<!-- Add CSS animations for the transition -->
<style scoped>
    /* Transition for reordering items in the table */
    .list-move {
        transition: transform .5s ease;
    }

    /* Optional: Add other styles like background-color change during transition */
    .list-enter-active, .list-leave-active {
        transition: background-color .5s ease;
    }

    .list-enter-from, .list-leave-to {
        background-color: #ffeb3b;
    }
</style>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';

export default {
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
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            viewedIn : document.querySelector("meta[name='viewed-in']").getAttribute('content'),
            school : document.querySelector("meta[name='school']").getAttribute('content'),
            subjects : [],
            mainSubjects : [],
            parentSubjects : [],
            averagedParents : []
        }
    },
    methods : {
        isNull (value) {
            // Check for null or undefined
            if (value === null || value === undefined) {
                return true;
            }

            // Check for empty string
            if (typeof value === 'string' && value.trim() === '') {
                return true;
            }

            // Check for empty array
            if (Array.isArray(value) && value.length === 0) {
                return true;
            }

            // Check for empty object
            if (typeof value === 'object' && !Array.isArray(value) && Object.keys(value).length === 0) {
                return true;
            }

            // Check for NaN
            if (typeof value === 'number' && isNaN(value)) {
                return true;
            }

            // If none of the above, it's not null, empty, or undefined
            return false;
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
        getSubjects() {
            // GET SUBJECTS
            this.subjects = []
            this.mainSubjects = []
            axios.get(`${ this.baseURL }/users/get-subjects-from-class`, {
                params : {
                    ClassId : this.classId
                }
            })
            .then(response => {
                this.subjects = response.data

                this.mainSubjects = this.processedSubjects()
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting subjects!'
                })
            })
        },
        processedSubjects() {
            let headers = [];
            let subHeaders = []
            let groupedSubjects = {};

            // Separate subjects with null ParentSubject and group by ParentSubject
            this.subjects.forEach(subject => {
                if (subject.ParentSubject === null) {
                    headers.push({
                        id : subject.id,
                        Subject: subject.Subject,
                        TeacherId : subject.TeacherId,
                        FullName : subject.FullName,
                        rowspan: 2,
                        colspan: 1,
                        children : null,
                        hasMenu : true,
                    })
                } else {
                    if (!groupedSubjects[subject.ParentSubject]) {
                        groupedSubjects[subject.ParentSubject] = [];
                    }
                    groupedSubjects[subject.ParentSubject].push(subject.Subject)

                    subHeaders.push({
                        id : subject.id,
                        Subject: subject.Subject,
                        TeacherId : subject.TeacherId,
                        FullName : subject.FullName,
                        rowspan: 1,
                        colspan: 1,
                        children : null,
                        hasMenu : true,
                    })
                }
            });

            // Add grouped subjects with colspan
            Object.keys(groupedSubjects).forEach(parent => {
                headers.push({
                    Subject: parent,
                    rowspan: 1,
                    colspan: groupedSubjects[parent].length,
                    children: groupedSubjects[parent],
                    hasMenu : false,
                });
            });

            return { Headers : headers, SubHeaders : subHeaders }
        },
        moveUp(index) {
            if (index > 0) {
                const temp = this.subjects[index - 1];
                this.subjects[index - 1] = this.subjects[index];
                this.subjects[index] = temp;
            }
        },
        moveDown(index) {
            if (index < this.subjects.length - 1) {
                const temp = this.subjects[index + 1];
                this.subjects[index + 1] = this.subjects[index];
                this.subjects[index] = temp;
            }
        },
        save() {
            axios.post(`${ this.baseURL }/classes/save-grade-stub-config`, {
                _token : this.token,
                ClassId : this.classId,
                Subjects : this.subjects,
                AveragedSubjectParents : this.averagedParents
            })
            .then(response => {
                this.toast.fire({
                    icon : 'success',
                    text : 'Config saved!'
                })
                window.setTimeout(function(){
                    window.history.go(-1)
                }, 200);
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error saving config!'
                })
            })
        },
        getParentSubjects() {
            // GET SUBJECTS
            this.parentSubjects = []
            axios.get(`${ this.baseURL }/subjects/get-parent-subjects`)
            .then(response => {
                this.parentSubjects = response.data
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting parent subjects!'
                })
            })
        },
        getParentAveragingConfig() {
            // GET SUBJECTS
            this.averagedParents = []
            axios.get(`${ this.baseURL }/subjects/get-parent-avg-config`, {
                params : {
                    ClassId : this.classId,
                }
            })
            .then(response => {
                this.averagedParents = response.data

                console.log(this.averagedParents)
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting parent avergaing config!'
                })
            })
        },
    },
    created() {
        
    },
    mounted() {
        this.getSubjects()
        this.getParentSubjects()
        this.getParentAveragingConfig()
    }
}

</script>