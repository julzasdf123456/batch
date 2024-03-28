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
                                <td class="v-align">First Name</td>
                                <td>
                                    <input class="form-control" placeholder="First name..." :autofocus="true" v-model="firstname">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Middle Name <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Middle name..." :autofocus="true" v-model="middlename">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Last Name</td>
                                <td>
                                    <input class="form-control" placeholder="Last name..." :autofocus="true" v-model="lastname">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Suffix <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <select class="form-control" v-model="suffix">
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
                                <td class="v-align">Sitio/Purok/Street</td>
                                <td>
                                    <input class="form-control" placeholder="Sitio/purok/street..." :autofocus="true" v-model="sitio">
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Town</td>
                                <td>
                                    <select class="form-control" v-model="town" @change="getBarangays(town)">
                                        <option v-for="town in towns" :value="town.id">{{ town.Town }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Barangay</td>
                                <td>
                                    <select class="form-control" v-model="barangay">
                                        <option v-for="brgy in barangays" :value="brgy.id">{{ brgy.Barangay }}</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Birth Date <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <flat-pickr v-model="birthdate" :config="pickerOptions" class="form-control" :readonly="false"></flat-pickr>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Gender</td>
                                <td>
                                    <select class="form-control" v-model="gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-align">Contact Number <span class="text-muted">(Optional)</span></td>
                                <td>
                                    <input class="form-control" placeholder="Separate by comma if many..." :autofocus="true" v-model="contactNumbers">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button @click="saveStudent()" class="btn btn-primary float-right">Next <i class="fas fa-arrow-right ico-tab-left-mini"></i></button>
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
    name : 'PersonalInfo.personal-info',
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
            contactNumbers : ''
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
        saveStudent() {
            if (this.isNull(this.firstname) | this.isNull(this.lastname) | this.isNull(this.town) | this.isNull(this.barangay + '') | this.isNull(this.gender) | this.isNull(this.sitio)) {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please supply all non-optional fields!'
                })
            } else {
                axios.post(`${ this.baseURL }/students/save-student`, {
                    _token : this.token,
                    id : this.generateId(),
                    FirstName : this.firstname,
                    MiddleName : this.middlename,
                    LastName : this.lastname,
                    Suffix : this.suffix,
                    Sitio : this.sitio,
                    Town : this.town,
                    Barangay : this.barangay + '',
                    Birthdate : this.birthdate,
                    Gender : this.gender,
                    ContactNumber : this.contactNumbers,
                }) // IF PORT 80 DIRECT FROM APACHE
                .then(response => {
                    this.toast.fire({
                        icon : 'success',
                        text : 'Student saved!'
                    })
                })
                .catch(error => {
                    console.log(error.response)
                    Swal.fire({
                        icon : 'error',
                        text : 'Error saving student'
                    })
                })
            }            
        }
    },
    created() {
        
    },
    mounted() {
        this.getTowns()

        // all caps set
        this.setAllCaps()
    }
}

</script>