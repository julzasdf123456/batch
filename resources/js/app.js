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
import Enroll from "./components/classes/enroll.vue"
import ExistingStudentEnroll from "./components/classes/existing-student-enroll.vue"

const app = createApp({
    
});

app.use(jQuery);

app.component('new-student', NewStudent);
app.component('enroll', Enroll);
app.component('existing-student-enroll', ExistingStudentEnroll);

app.mount("#app");