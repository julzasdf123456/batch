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
import EnrollmentFlexible from "./components/transactions/enrollment-flexible.vue"
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
import Notifier from "./components/sms/notifier.vue"
import DashboardIndex from "./components/dashboard/index.vue"
import AddNew from "./components/students/add-new.vue"
import AddNewToClass from "./components/students/add-new-to-class.vue"
import StudentsList from "./components/students/students-list.vue"
import OldOREntry from "./components/transactions/old-or-entry.vue"
import OtherPayments from "./components/transactions/other-payments.vue"
import History from "./components/sms/history.vue"
import PrintClassPayments from "./components/classes/print-class-payments.vue"
import StubConfig from "./components/classes/stub-config.vue"
import MergeTo from "./components/classes/merge-to.vue"
import LedgerManagement from "./components/transactions/ledger-management.vue"

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
app.component('enrollment-flexible', EnrollmentFlexible);
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
app.component('notifier', Notifier);
app.component('dashboard-index', DashboardIndex);
app.component('add-new', AddNew);
app.component('add-new-to-class', AddNewToClass);
app.component('students-list', StudentsList);
app.component('old-or-entry', OldOREntry);
app.component('other-payments', OtherPayments);
app.component('history', History);
app.component('print-class-payments', PrintClassPayments);
app.component('stub-config', StubConfig);
app.component('merge-to', MergeTo);
app.component('ledger-management', LedgerManagement);

app.component('my-classes', MyClasses);
app.component('view-class', ViewClass);
app.component('print-class-payment-details', PrintClassPaymentDetails);
app.component('my-advisory', MyAdvisory);
app.component('view-advisory', ViewAdvisory);

app.mount("#app");