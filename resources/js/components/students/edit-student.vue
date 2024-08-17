<template>
    <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title text-muted"><i class="fas fa-info-circle ico-tab"></i>Fill-in All Non-optional Fields</span>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover table-borderless">
                        <tbody>
                            <tr>
                                <td class="v-align">Learner Ref. No. (LRN)</td>
                                <td>
                                    <input class="form-control" placeholder="LRN..." :autofocus="true" v-model="student.LRN">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">First Name</td>
                                <td>
                                    <input class="form-control" placeholder="First name..." :autofocus="true" v-model="student.FirstName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Middle Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Middle name..." :autofocus="true" v-model="student.MiddleName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Last Name</td>
                                <td>
                                    <input class="form-control" placeholder="Last name..." :autofocus="true" v-model="student.LastName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Suffix <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <select class="form-control" v-model="student.Suffix">
                                        <option value="">-</option>
                                        <option value="SR">SR</option>
                                        <option value="JR">JR</option>
                                        <option value="I">I</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                        <option value="IV">IV</option>
                                        <option value="V">V</option>
                                        <option value="VI">VI</option>
                                        <option value="VII">VII</option>
                                        <option value="VII">VII</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Birth Date <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <flat-pickr v-model="student.Birthdate" :config="pickerOptions" class="form-control" :readonly="false"></flat-pickr>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Gender</td>
                                <td>
                                    <select class="form-control" v-model="student.Gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Contact Number <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Separate by comma if many..." :autofocus="true" v-model="student.ContactNumber">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">PSA Number <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="PSA Birth Certificate Number..." :autofocus="true" v-model="student.PSABirthCertificateNumber">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Place of Birth <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Place of birth..." :autofocus="true" v-model="student.PlaceOfBirth">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Mother Tounge <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <select class="form-control" v-model="student.MotherTounge">
                                        <option value="">-- None --</option>
                                        <option v-for="mtl in motherToungeList" :value="mtl">{{ mtl }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Indigenous Community <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <select class="form-control" v-model="student.Indigenousity">
                                        <option value="">-- None --</option>
                                        <option v-for="ind in indigenousList" :value="ind">{{ ind }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">4Ps ID Number <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="4Ps Household ID (if applicable)..." :autofocus="true" v-model="student.Beneficiary4PsIDNumber">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">From [] School?<span class="text-muted"></span></td>
                                <td>
                                    <div class="input-group-radio-sm">
                                        <input type="radio" id="Public" value="Public" v-model="student.FromSchool" class="custom-radio-sm">
                                        <label for="Public" class="custom-radio-label-sm">Public School</label>
                                        
                                        <input type="radio" id="Private" value="Private" v-model="student.FromSchool" class="custom-radio-sm">
                                        <label for="Private" class="custom-radio-label-sm">Private School</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">ESC Scholar/Grantee<span class="text-muted"></span></td>
                                <td>
                                    <div class="input-group-radio-sm">
                                        <input type="radio" id="Yes" value="Yes" v-model="escScholar" class="custom-radio-sm">
                                        <label for="Yes" class="custom-radio-label-sm">Yes</label>
                                        
                                        <input type="radio" id="No" value="No" v-model="escScholar" class="custom-radio-sm">
                                        <label for="No" class="custom-radio-label-sm">No</label>
                                    </div>
                                </td>
                            </tr>
                            <!-- ADDRESS -->
                            <tr>
                                <td colspan="2" class="tbl-divider"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="text-muted"><i>Current Address</i></span></td>
                            </tr>
                            <tr>
                                <td class="v-align">Current Sitio/Purok/Street</td>
                                <td>
                                    <input class="form-control" placeholder="Sitio/purok/street..." :autofocus="true" v-model="student.Sitio">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Current Town</td>
                                <td>
                                    <select class="form-control" v-model="student.Town" @change="getBarangays(student.Town)">
                                        <option v-for="town in towns" :value="town.id">{{ town.Town }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Current Barangay</td>
                                <td>
                                    <select class="form-control" v-model="student.Barangay">
                                        <option v-for="brgy in barangays" :value="brgy.id">{{ brgy.Barangay }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Current Zip Code</td>
                                <td>
                                    <input class="form-control" placeholder="Zip Code..." :autofocus="true" v-model="student.ZipCode">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-muted"><i>Permanent Address</i></span>
                                </td>
                                <td>
                                    <input type="checkbox" v-model="sameWithCurrentAddress" id="same-with-current" @change="isSameWithCurrentAddress">
                                    <label for="same-with-current" class="ico-tab-left">Same with Current Address</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Permanent Sitio/Purok/Street</td>
                                <td>
                                    <input class="form-control" placeholder="Sitio/purok/street..." :autofocus="true" v-model="student.PermanentSitio">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Permanent Town</td>
                                <td>
                                    <select class="form-control" v-model="student.PermanentTown" @change="getBarangaysPermanent(student.PermanentTown)">
                                        <option v-for="town in towns" :value="town.id">{{ town.Town }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Permanent Barangay</td>
                                <td>
                                    <select class="form-control" v-model="student.PermanentBarangay">
                                        <option v-for="brgy in permanentBarangays" :value="brgy.id">{{ brgy.Barangay }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Permanent Zip Code</td>
                                <td>
                                    <input class="form-control" placeholder="Zip Code..." :autofocus="true" v-model="student.PermanentZipCode">
                                </td>
                            </tr>
                            <!-- PARENT -->
                            <tr>
                                <td colspan="2" class="tbl-divider"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><span class="text-muted"><i>Parent's/Guardian's Information</i></span></td>
                            </tr>
                            <tr>
                                <td class="v-align">Father's First Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Father's First Name..." :autofocus="true" v-model="student.FatherFirstName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Father's Middle Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Father's Middle Name..." :autofocus="true" v-model="student.FatherMiddleName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Father's Last Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Father's Last Name..." :autofocus="true" v-model="student.FatherLastName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Father's Contact Number <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Father's Contact Number..." :autofocus="true" v-model="student.FatherContactNumber">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Mother's First Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Mother's First Name..." :autofocus="true" v-model="student.MotherFirstName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Mother's Middle Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Mother's Middle Name..." :autofocus="true" v-model="student.MotherMiddleName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Mother's Last Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Mother's Last Name..." :autofocus="true" v-model="student.MotherLastName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Mother's Contact Number <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Mother's Contact Number..." :autofocus="true" v-model="student.MotherContactNumber">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Guardian's First Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Guardian's First Name..." :autofocus="true" v-model="student.GuardianFirstName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Guardian's Middle Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Guardian's Middle Name..." :autofocus="true" v-model="student.GuardianMiddleName">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Guardian's Last Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Guardian's Last Name..." :autofocus="true" v-model="student.GuardianLastName">
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="v-align">Guardian's Contact Number <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Guardian's Contact Number..." :autofocus="true" v-model="student.GuardianContactNumber">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button @click="saveStudent()" class="btn btn-primary float-right">Save <i class="fas fa-check-circle ico-tab-left-mini"></i></button>
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
    name : 'EditStudent.edit-student',
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
            studentId : document.querySelector("meta[name='student-id']").getAttribute('content'),
            from : document.querySelector("meta[name='from']").getAttribute('content'),
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
            towns : [],
            barangays : [],
            firstname : '',
            middlename : '',
            lastname : '',
            suffix : '',
            birthdate : '',
            gender : '',
            sitio : '',
            town : '',
            barangay : '',
            zipCode : '',
            contactNumbers : '',
            lrn : '',
            placeOfBirth : '',
            psaNumber : '',
            indigenousity : '',
            indigenousList : [
                'ATA MANOBO', 'BAGOBO', 'BANWAON', "B'LAAN", "BUKIDNON", "DIBABAWON", "HIGAONON", "MAMANWA", "MANDAYA", "MANGGUWANGAN", "MANOBO", "MANSAKA", "MATIGSALOG", "SUBANEN", "TAGAKAOLO", "T'BOLI", "TEDUARY", "UBO"
            ],
            motherTounge : 'CEBUANO',
            motherToungeList : [
                "AKLANON", "BIKOL", "CEBUANO", "CHABACANO", "CHINESE", "ENGLISH", "FRENCH", "GERMAN", "HILIGAYNON", "ILOKO", "IVATAN", "JAPANENSE", "KAPAMPANGAN", "KINARAY-A", "KOREAN", "MAGUINDANAOAN", "MARANAO", "PANGASINENSE", "RUSSIAN", "SAMBAL", "SPANISH", "SURIGAONON", "TAGALOG", "TAUSOG", "WARAY", "YAKAN", "YBANAG", "OTHER",
            ],
            beneficiary4PsID : '',
            permanentSitio : '',
            permanentTown : '',
            permanentBarangay : '',
            permanentBarangays : [],
            permanentZipCode : '',
            fatherFirstName : '',
            fatherMiddleName : '',
            fatherLastName : '',
            motherFirstName : '',
            motherMiddleName : '',
            motherLastName : '',
            guardianFirstName : '',
            guardianMiddleName : '',
            guardianLastName : '',
            fatherContact : '',
            motherContact : '',
            guardianContact : '',
            sameWithCurrentAddress : false,
            fromSchool : 'Public', // Public, Private
            student : {},
            escScholar : 'No',
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
        getTowns() {
            axios.get(`${ this.baseURL }/towns/get-towns`) // IF PORT 80 DIRECT FROM APACHE
            .then(response => {
                this.towns = response.data

                this.getBarangays(this.student.Town)
                this.getBarangaysPermanent(this.student.PermanentTown)
            })
            .catch(error => {
                console.log(error)
            })
        },
        setAllCaps() {
            const forceKeyPressUppercase = (e) => {
                let el = e.target;
                let charInput = e.keyCode;
                if((charInput >= 97) && (charInput <= 122)) { // lowercase
                if(!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
                    let newChar = charInput - 32;
                    let start = el.selectionStart;
                    let end = el.selectionEnd;
                    el.value = el.value.substring(0, start) + String.fromCharCode(newChar) + el.value.substring(end);
                    el.setSelectionRange(start+1, start+1);
                    e.preventDefault();
                }
                }
            };

            document.querySelectorAll(".all-caps").forEach(function(current) {
                current.addEventListener("keypress", forceKeyPressUppercase);
            })
        },
        getBarangays(town) {
            axios.get(`${ this.baseURL }/barangays/get-barangays`, {
                params : {
                    TownId : town,
                }
            }) // IF PORT 80 DIRECT FROM APACHE
            .then(response => {
                this.barangays = response.data
            })
            .catch(error => {
                console.log(error)
            })
        },
        getBarangaysPermanent(town) {
            axios.get(`${ this.baseURL }/barangays/get-barangays`, {
                params : {
                    TownId : town,
                }
            }) // IF PORT 80 DIRECT FROM APACHE
            .then(response => {
                this.permanentBarangays = response.data

                if (this.sameWithCurrentAddress) {
                    this.student.PermanentBarangay = this.student.Barangay
                }
            })
            .catch(error => {
                console.log(error.response)
            })
        },
        saveStudent() {
            if (this.isNull(this.student.LRN) | this.isNull(this.student.FirstName) | this.isNull(this.student.LastName)/* |  this.isNull(this.student.Town) | this.isNull(this.student.Barangay + '') | this.isNull(this.student.Gender) | this.isNull(this.student.Sitio) */) {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please supply all non-optional fields!'
                })
            } else {
                axios.post(`${ this.baseURL }/students/update-student`, {
                    _token : this.token,
                    id : this.studentId,
                    FirstName : this.student.FirstName,
                    MiddleName : this.student.MiddleName,
                    LastName : this.student.LastName,
                    Suffix : this.student.Suffix,
                    Sitio : this.student.Sitio,
                    Town : this.student.Town,
                    Barangay : this.student.Barangay + '',
                    Birthdate : this.student.Birthdate,
                    Gender : this.student.Gender,
                    ContactNumber : this.student.ContactNumber,
                    LRN : this.student.LRN,
                    PSABirthCertificateNumber : this.student.PSABirthCertificateNumber,
                    PlaceOfBirth : this.student.PlaceOfBirth,
                    MotherTounge : this.student.MotherTounge,
                    Indigenousity : this.student.Indigenousity,
                    Beneficiary4PsIDNumber : this.student.Beneficiary4PsIDNumber,
                    ZipCode : this.student.ZipCode,
                    PermanentTown : this.student.PermanentTown,
                    PermanentBarangay : this.student.PermanentBarangay + '',
                    PermanentSitio : this.student.PermanentSitio,
                    PermanentZipCode : this.student.PermanentZipCode,
                    FatherFirstName : this.student.FatherFirstName,
                    FatherMiddleName : this.student.FatherMiddleName,
                    FatherLastName : this.student.FatherLastName,
                    FatherContactNumber : this.student.FatherContactNumber,
                    MotherFirstName : this.student.MotherFirstName,
                    MotherMiddleName : this.student.MotherMiddleName,
                    MotherLastName : this.student.MotherLastName,
                    MotherContactNumber : this.student.MotherContactNumber,
                    GuardianFirstName : this.student.GuardianFirstName,
                    GuardianMiddleName : this.student.GuardianMiddleName,
                    GuardianLastName : this.student.GuardianLastName,
                    GuardianContactNumber : this.student.GuardianContactNumber,
                    FromSchool : this.student.FromSchool,
                    ESCScholar : this.escScholar,
                }) // IF PORT 80 DIRECT FROM APACHE
                .then(response => {
                    this.toast.fire({
                        icon : 'success',
                        text : 'Student data updated!!'
                    })
                    if (this.from === 'student-view') {
                        window.location.href = this.baseURL + '/students/' + this.studentId
                    } else if (this.from === 'class-view') {
                        window.history.go(-1)
                    }
                    
                })
                .catch(error => {
                    console.log(error.response)
                    Swal.fire({
                        icon : 'error',
                        text : 'Error saving student'
                    })
                })
            }            
        },
        isSameWithCurrentAddress() {
            if (this.sameWithCurrentAddress) {
                this.student.PermanentTown = this.student.Town
                this.student.PermanentSitio = this.student.Sitio
                this.student.PermanentZipCode = this.student.ZipCode
                this.getBarangaysPermanent(this.student.PermanentTown)
            } else {
                this.student.PermanentTown = ''
                this.student.PermanentSitio = ''
                this.student.PermanentZipCode = ''
                this.permanentBarangays = []
                this.student.PermanentBarangay = ''
            }
        },
        getStudentData() {
            axios.get(`${ this.baseURL }/students/get-student`, {
                params : {
                    id : this.studentId,
                }
            }) 
            .then(response => {
                this.student = response.data

                if (!this.isNull(this.student.ESCScholar) && this.student.ESCScholar === 'Yes') {
                    this.escScholar = 'Yes'
                } else {
                    this.escScholar = 'No'
                }

                this.getTowns()
            })
            .catch(error => {
                console.log(error)
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.getStudentData()

        // all caps set
        this.setAllCaps()
    }
}

</script>