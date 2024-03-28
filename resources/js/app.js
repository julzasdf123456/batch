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

const app = createApp({
    
});

app.use(jQuery);

app.component('new-student', NewStudent);

app.mount("#app");