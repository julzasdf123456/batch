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
import Enroll from "./components/classes/enroll.vue"
import ExistingStudentEnroll from "./components/classes/existing-student-enroll.vue"
import ClassView from "./components/classes/class-view.vue"
import EnrollmentTransactions from "./components/transactions/enrollment.vue"
import TuitionsSearch from "./components/transactions/tuitions-search.vue"
import Tuitions from "./components/transactions/tuitions.vue"
import Miscellaneous from "./components/transactions/miscellaneous.vue"
import MiscellaneousSearch from "./components/transactions/miscellaneous-search.vue"
import ViewTeacher from "./components/teachers/view-teacher.vue"
import ScholarshipWizzard from "./components/students/scholarship-wizzard.vue"

const app = createApp({
    
});

app.use(jQuery);

app.component('new-student', NewStudent);
app.component('search-students', SearchStudents);
app.component('view-student', ViewStudent);
app.component('enroll', Enroll);
app.component('existing-student-enroll', ExistingStudentEnroll);
app.component('class-view', ClassView);
app.component('enrollment-transactions', EnrollmentTransactions);
app.component('tuitions-search', TuitionsSearch);
app.component('tuitions', Tuitions);
app.component('miscellaneous', Miscellaneous);
app.component('miscellaneous-search', MiscellaneousSearch);
app.component('view-teacher', ViewTeacher);
app.component('scholarship-wizzard', ScholarshipWizzard);

app.mount("#app");