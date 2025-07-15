<template>
  <!-- HEADER -->
  <section class="px-4">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-lg-8">
          <div style="display: flex; padding-bottom: 15px">
            <div style="width: 88px; display: inline">
              <img
                @click="uploadPhoto()"
                id="prof-img"
                style="
                  width: 65px !important;
                  height: 65px !important;
                  cursor: pointer;
                  object-fit: cover;
                "
                title="Change profile photo"
                class="profile-user-img img-circle"
                :src="imagePreview"
                @error="handleImageError"
              />
              <input type="file" ref="fileInput" @change="onFileChange" class="gone" />
            </div>
            <div>
              <span>
                <p class="no-pads" style="font-size: 1.85em">
                  <strong>{{
                    studentData.LastName +
                    ", " +
                    studentData.FirstName +
                    (isNull(studentData.MiddleName)
                      ? ""
                      : " " + studentData.MiddleName + " ") +
                    (isNull(studentData.Suffix) ? "" : studentData.Suffix)
                  }}</strong>
                </p>

                <span class="text-muted">
                  <i class="fas fa-id-badge ico-tab-mini"></i>LRN-{{ studentData.LRN }} |
                  <i class="fas fa-lightbulb ico-tab-mini"></i
                  >{{
                    isNull(studentData.Year)
                      ? "-"
                      : studentData.Year + " - " + studentData.Section
                  }}
                  {{ isNull(studentData.Strand) ? "" : " • " + studentData.Strand }}
                  {{
                    isNull(studentData.Semester)
                      ? ""
                      : " • " + studentData.Semester + " Sem"
                  }}
                  <span
                    class="badge"
                    :class="isNull(studentData.Status) ? 'bg-success' : 'bg-danger'"
                    title="Status"
                    >{{
                      isNull(studentData.Status) ? "Studying" : studentData.Status
                    }}</span
                  >
                  <span
                    class="badge bg-success ico-tab-left-mini"
                    v-if="
                      !isNull(studentData.ESCScholar) && studentData.ESCScholar === 'Yes'
                        ? true
                        : false
                    "
                    >ESC Scholar/Grantee</span
                  >
                </span>
              </span>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="dropdown">
            <a
              class="btn btn-primary-skinny dropdown-toggle float-right {{ $colorProf != null ? 'text-white' : '' }}"
              href="#"
              role="button"
              data-toggle="dropdown"
              aria-expanded="false"
              style="margin-right: 15px"
            >
              Actions
            </a>

            <div class="dropdown-menu">
              <a
                class="dropdown-item"
                :href="baseURL + '/students/edit-student/' + studentId + '/student-view'"
                ><i class="fas fa-pen ico-tab"></i>Edit Details</a
              >
              <!-- <a class="dropdown-item" href="#"><i class="fas fa-calendar-alt ico-tab"></i>View Attendance</a> -->

              <div class="divider"></div>

              <a
                :href="baseURL + '/classes/transfer-to-another-class/' + studentId"
                class="dropdown-item"
                ><i class="fas fa-random ico-tab"></i>Transfer to Another
                Section/Strand</a
              >
              <button class="dropdown-item" @click="scholarshipWizzard()">
                <i class="fas fa-folder-plus ico-tab"></i>Add Scholarship Grant
              </button>

              <div class="divider"></div>

              <button class="dropdown-item text-danger" @click="deleteStudent()">
                <i class="fas fa-trash ico-tab"></i>Delete Student
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="content px-3 w-100">
    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div
              v-for="(grades, schoolYear) in studentClassesHistoryData"
              :key="schoolYear"
              class="mb-5"
            >
              <h4 class="text-primary fw-bold mb-3">{{ schoolYear }}</h4>

              <div
                v-for="(subjects, gradeLevel) in grades"
                :key="gradeLevel"
                class=" {{ $colorProf != null ? 'bl-dark' : 'bl-light' }}"
              >
                <p class="text-muted mt-3">
                  <i class="fas fa-dot-circle ico-tab-mini"></i>
                  Current/Latest Subjects Taken in this Class ({{ gradeLevel }} -
                  {{ subjects[0]?.Section ?? "-" }})
                </p>

                <div class="table-responsive">
                  <table class="table table-bordered align-middle text-center">
                    <thead class="table">
                      <tr>
                        <th scope="col" class="text-start">Subject</th>
                        <th scope="col">1st Grading</th>
                        <th scope="col">2nd Grading</th>
                        <th scope="col">3rd Grading</th>
                        <th scope="col">4th Grading</th>
                        <th scope="col">Final Average</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(subject, index) in subjects" :key="index">
                        <td class="text-left">
                          <strong>{{ subject.Subject }}</strong>
                          <br />
                          <small class="text-muted">{{
                            subject.TeacherName ?? ""
                          }}</small>
                        </td>
                        <td>{{ subject.FirstGradingGrade }}</td>
                        <td>{{ subject.SecondGradingGrade }}</td>
                        <td>{{ subject.ThirdGradingGrade }}</td>
                        <td>{{ subject.FourthGradingGrade }}</td>
                        <td class="fw-semibold">{{ subject.AverageGrade }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-4 {{ $colorProf != null ? 'bl-dark' : 'bl-light' }}">
        <div class="card shadow-none">
          <div class="card-body p-4"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import FlatPickr from "vue-flatpickr-component";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import "flatpickr/dist/flatpickr.css";
import jquery from "jquery";
import Swal from "sweetalert2";

import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";

export default {
  name: "ViewStudent.view-student",
  components: {
    FlatPickr,
    Swal,
    pagination: Bootstrap4Pagination,
    jquery,
    FullCalendar,
  },
  data() {
    return {
      moment: moment,
      baseURL: axios.defaults.baseURL,
      filePath: axios.defaults.filePath,
      imgPath: axios.defaults.imgsPath,
      colorProfile: document
        .querySelector("meta[name='color-profile']")
        .getAttribute("content"),
      userId: document.querySelector("meta[name='user-id']").getAttribute("content"),
      studentId: document
        .querySelector("meta[name='student-id']")
        .getAttribute("content"),
      tableInputTextColor: this.isNull(
        document.querySelector("meta[name='color-profile']").getAttribute("content")
      )
        ? "text-dark"
        : "text-white",
      toast: Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
      }),
      pickerOptions: {
        enableTime: false,
        dateFormat: "Y-m-d",
      },
      studentData: {},
      subjects: [],
      payables: [],

      activePayable: {},
      paymentFor: "",
      payableTransactionHistory: [],
      tuitionsBreakdown: [],
      isModalTuition: false,
      allTransactions: [],
      payableInclusions: [],
      calendarOptions: {
        plugins: [dayGridPlugin],
        initialView: "dayGridMonth",
        selectable: true,
        height: 650,
        width: 700,
        eventOrderStrict: false,
        themeSystem: "bootstrap",
        headerToolbar: {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth",
        },
        events: [],
      },
      attendanceData: [],
      scholarships: [],
      transactionDetails: [],
      detailedTransactions: [],
      selectedFile: null,
      imagePreview: null,
      updateLogs: [],
      studentClassesHistoryData: [],
    };
  },
  methods: {
    isNull(value) {
      // Check for null or undefined
      if (value === null || value === undefined) {
        return true;
      }

      // Check for empty string
      if (typeof value === "string" && value.trim() === "") {
        return true;
      }

      // Check for empty array
      if (Array.isArray(value) && value.length === 0) {
        return true;
      }

      // Check for empty object
      if (
        typeof value === "object" &&
        !Array.isArray(value) &&
        Object.keys(value).length === 0
      ) {
        return true;
      }

      // Check for NaN
      if (typeof value === "number" && isNaN(value)) {
        return true;
      }

      // If none of the above, it's not null, empty, or undefined
      return false;
    },
    validateNullStrings(string) {
      return this.isNull(string) ? "" : string;
    },
    toMoney(value) {
      if (this.isNumber(value)) {
        return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", {
          maximumFractionDigits: 2,
          minimumFractionDigits: 2,
        });
      } else {
        return "-";
      }
    },
    isNumber(value) {
      return typeof value === "number";
    },
    round(value) {
      return Math.round((value + Number.EPSILON) * 100) / 100;
    },
    generateRandomString(length) {
      const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      let result = "";

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
      return moment().valueOf();
    },
    getStudentDetails() {
      axios
        .get(`${this.baseURL}/students/get-student-details`, {
          params: {
            StudentId: this.studentId,
          },
        })
        .then((response) => {
          this.studentData = response.data.StudentDetails;
          this.subjects = response.data.Subjects;
          this.payables = response.data.TuitionPayables;
          this.scholarships = response.data.Scholarships;
          // concat other payables
          this.payables = this.payables.concat(response.data.OtherPayables);

          this.imagePreview = `${this.imgPath}student-imgs/${this.studentId}.jpg`;
        })
        .catch((error) => {
          console.log(error);
          this.toast.fire({
            icon: "error",
            text: "Error getting student data!",
          });
        });
    },
    getTotalBalance() {
      var total = 0;
      for (let i = 0; i < this.payables.length; i++) {
        var balance = this.isNull(this.payables[i])
          ? 0
          : this.isNull(this.payables[i].Balance)
          ? 0
          : this.payables[i].Balance.length < 1
          ? 0
          : parseFloat(this.payables[i].Balance);
        total += balance;
      }

      return total;
    },
    getActivePayable(id) {
      this.activePayable = this.payables.find((obj) => obj.id === id);
    },
    transactionHistory(id) {
      this.getActivePayable(id);
      this.paymentFor = this.activePayable.PaymentFor;
      this.isModalTuition = this.activePayable.Category === "Tuition Fees" ? true : false;
      this.getTransactionHistory(id);

      // init modal
      let modalElement = this.$refs.modalTransactionHistory;
      $(modalElement).modal("show");
    },
    getTransactionHistory(payableId) {
      axios
        .get(`${this.baseURL}/transactions/get-transactions-from-payable`, {
          params: {
            PayableId: payableId,
          },
        })
        .then((response) => {
          this.tuitionsBreakdown = response.data.TuitionLogs;
          this.payableTransactionHistory = response.data.Transactions;
          this.payableInclusions = response.data.PayableInclusions;
          this.updateLogs = response.data.UpdateLogs;
        })
        .catch((error) => {
          console.log(error);
          this.toast.fire({
            icon: "error",
            text: "Error getting transaction history data!",
          });
        });
    },

    scholarshipWizzard() {
      window.location.href =
        this.baseURL +
        "/student_scholarships/scholarship-wizzard/" +
        this.studentId +
        "/student-view";
    },
    getBarcodeAttendace() {
      axios
        .get(`${this.baseURL}/barcode_attendances/get-student-logs`, {
          params: {
            StudentId: this.studentId,
          },
        })
        .then((response) => {
          this.attendanceData = response.data;

          var event = [];
          for (let i = 0; i < this.attendanceData.length; i++) {
            event.push({
              title: this.attendanceData[i].PunchType,
              start: moment(this.attendanceData[i].created_at).format(
                "YYYY-MM-DD HH:mm:ss"
              ),
            });
          }

          this.calendarOptions.events = event;
        })
        .catch((error) => {
          console.log(error);
          this.toast.fire({
            icon: "error",
            text: "Error getting student data!",
          });
        });
    },
    removeScholarship(id) {
      Swal.fire({
        title: "Remove Scholarship?",
        showCancelButton: true,
        text: "You can always re-add a scholarship in the scholarship wizzard.",
        confirmButtonText: "Proceed Removal",
        confirmButtonColor: "#3a9971",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post(`${this.baseURL}/student_scholarships/remove-scholarship`, {
              _token: this.token,
              id: id,
            })
            .then((response) => {
              this.toast.fire({
                icon: "success",
                text: "Scholarship removed!",
              });
              this.scholarships = this.scholarships.filter((obj) => obj.id !== id);
              location.reload();
            })
            .catch((error) => {
              console.log(error.response);
              Swal.fire({
                icon: "error",
                text: error.response.data,
              });
            });
        }
      });
    },
    deleteStudent() {
      Swal.fire({
        title: "Remove Student?",
        showCancelButton: true,
        text:
          "Deleting this student will also delete all his data. Proceed with caution.",
        confirmButtonText: "Proceed Removal",
        confirmButtonColor: "#3a9971",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`${this.baseURL}/students/` + this.studentId, {
              params: {
                _token: this.token,
                id: this.studentId,
              },
            })
            .then((response) => {
              this.toast.fire({
                icon: "success",
                text: "Student removed!",
              });
              window.location.href = this.baseURL + "/students";
            })
            .catch((error) => {
              console.log(error.response);
              this.toast.fire({
                icon: "error",
                text: "Error getting all transaction history data!",
              });
            });
        }
      });
    },
    printTuitionLedger() {
      window.location.href = `${this.baseURL}/transactions/print-tuition-ledger/${this.studentId}/${this.activePayable.SchoolYear}`;
    },

    // upload photo
    uploadPhoto() {
      this.$refs.fileInput.click();
    },
    onFileChange(event) {
      this.selectedFile = event.target.files[0];

      // Generate a preview of the image
      const reader = new FileReader();
      reader.readAsDataURL(this.selectedFile);
      reader.onload = (e) => {
        this.imagePreview = e.target.result; // Update image preview
      };

      this.uploadImage();
    },
    async uploadImage() {
      if (!this.selectedFile) {
        alert("Please select a file");
        return;
      }

      const formData = new FormData();
      formData.append("image", this.selectedFile);
      formData.append("id", this.studentId);

      try {
        const response = await axios.post(
          `${this.baseURL}/students/upload-student-profile`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        );

        console.log("Upload successful:", response.data);
        this.toast.fire({
          icon: "success",
          text: "Profile picture uploaded and updated!",
        });
      } catch (error) {
        console.error("Error uploading the file:", error.response);
        this.toast.fire({
          icon: "error",
          text: "Error uploading profile picture!",
        });
      }
    },
    handleImageError(event) {
      this.imagePreview = `${this.imgPath}prof-img.png`; // Replace with a fallback image URL
    },
    getStudentClassesHistoryDetails() {
      axios
        .get(`${this.baseURL}/classes/get-student-history`, {
          params: {
            StudentId: this.studentId,
          },
        })
        .then((response) => {
          this.studentClassesHistoryData = response.data;
        })
        .catch((error) => {
          console.log(error.response);
          this.toast.fire({
            icon: "error",
            text: "Error getting classes history",
          });
        });
    },
  },
  created() {},
  mounted() {
    this.getStudentDetails();

    this.getBarcodeAttendace();
    this.getStudentClassesHistoryDetails();
  },
};
</script>
