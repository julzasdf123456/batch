import './bootstrap';

/**
 * DATE RANGE PICKER
 */
import jQuery from 'jquery';
import $ from 'jquery';
import 'bootstrap';
import moment from 'moment';
import daterangepicker from 'daterangepicker';

/**
 * VUE VITE
 */

import { createApp } from 'vue/dist/vue.esm-bundler.js';

import NewStudent from "./components/students/new-student.vue"
import SearchStudents from "./components/students/search-students.vue"
import ViewStudent from "./components/students/view-student.vue"
import EditStudent from "./components/students/edit-student.vue"
import Enroll from "./components/classes/enroll.vue"
import ExistingStudentEnroll from "./components/classes/existing-student-enroll.vue"
import ClassView from "./components/classes/class-view.vue"
import Transfer from "./components/classes/transfer.vue"
import EnrollmentTransactions from "./components/transactions/enrollment.vue"
import TuitionsSearch from "./components/transactions/tuitions-search.vue"
import Tuitions from "./components/transactions/tuitions.vue"
import Miscellaneous from "./components/transactions/miscellaneous.vue"
import MiscellaneousSearch from "./components/transactions/miscellaneous-search.vue"
import MyDCR from "./components/transactions/my-dcr.vue"
import AllDCR from "./components/transactions/all-dcr.vue"
import PrintMyDCR from "./components/transactions/print-my-dcr.vue"
import ViewTeacher from "./components/teachers/view-teacher.vue"
import ScholarshipWizzard from "./components/students/scholarship-wizzard.vue"
import ScanId from "./components/scanning/scan-id.vue"

import MyClasses from "./components/my-acount/my-classes.vue"
import ViewClass from "./components/my-acount/view-class.vue"
import PrintClassPaymentDetails from "./components/my-acount/print-class-payment-details.vue"
import MyAdvisory from "./components/my-acount/my-advisory.vue"
import ViewAdvisory from "./components/my-acount/view-advisory.vue"

const app = createApp({
    
});

app.use(jQuery);

app.component('new-student', NewStudent);
app.component('search-students', SearchStudents);
app.component('view-student', ViewStudent);
app.component('edit-student', EditStudent);
app.component('enroll', Enroll);
app.component('existing-student-enroll', ExistingStudentEnroll);
app.component('class-view', ClassView);
app.component('transfer', Transfer);
app.component('enrollment-transactions', EnrollmentTransactions);
app.component('tuitions-search', TuitionsSearch);
app.component('tuitions', Tuitions);
app.component('miscellaneous', Miscellaneous);
app.component('miscellaneous-search', MiscellaneousSearch);
app.component('my-dcr', MyDCR);
app.component('all-dcr', AllDCR);
app.component('print-my-dcr', PrintMyDCR);
app.component('view-teacher', ViewTeacher);
app.component('scholarship-wizzard', ScholarshipWizzard);
app.component('scan-id', ScanId);

app.component('my-classes', MyClasses);
app.component('view-class', ViewClass);
app.component('print-class-payment-details', PrintClassPaymentDetails);
app.component('my-advisory', MyAdvisory);
app.component('view-advisory', ViewAdvisory);

app.mount("#app");