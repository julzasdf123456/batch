<template>
    <div class="row px-4">
        <div class="col-lg-12">
            <h4><i class="fas fa-graduation-cap ico-tab text-muted"></i><span class="text-muted">{{ advisory.Year }} - </span>{{ advisory.Section }}</h4>
            <span class="text-muted">{{ syDetails.SchoolYear }}</span>
            <span class="text-muted" v-if="isNull(advisory.Strand) ? false : true">{{ isNull(advisory.Strand) ? '' : (' • ' + advisory.Strand) }}</span>
            <span class="text-muted" v-if="isNull(advisory.Semester) ? false : true">{{ isNull(advisory.Semester) ? '' : (' • ' + advisory.Semester + ' Sem') }}</span>
            
            <select v-model="classSelect" class="form-control form-control-sm float-right" style="width: 150px;" @change="goToClass()">
                <option v-for="c in classesInSy" :value="c.id">{{ c.Year + '-' + c.Section + (!isNull(c.Strand) ? (' ' + c.Strand) : '') + (!isNull(c.Semester) ? (' (' + c.Semester + ' Sem)') : '') }}</option>
            </select>
            <div class="dropdown mr-1 float-right" title="More Options" v-if="userId === '1' ? true : false">
                <a href="#" role="button" data-toggle="dropdown" aria-expanded="false" class="btn btn-default btn-sm">
                  Administrative Options
                </a>
                <div class="dropdown-menu">
                    <button class="dropdown-item" @click="revalidateSubjects()" title="Populates Subjects per student">Revalidate Subjects</button>
                    <button  class="dropdown-item" @click="revalidatePayments()" title="Populates PayableInclusions and TuitionsBreakdown tables">Revalidate Payments</button>
                </div>
            </div>
            

            <div id="loader" class="spinner-border text-success float-right" v-if="loaderVisibility" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <!-- students in class -->
        <div class="col-lg-12 mt-3">
            <div class="card shadow-none">
                <div class="card-body">
                    <div>
                        <!-- TAB HEADS -->
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="students-list-tab" data-toggle="pill" href="#students-list-content" role="tab" aria-controls="students-list-content" aria-selected="false">Active Students List</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="attendance-tab" data-toggle="pill" href="#attendance-content" role="tab" aria-controls="attendance-content" aria-selected="false">Attendance</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="grades-tab" data-toggle="pill" href="#grades-content" role="tab" aria-controls="grades-content" aria-selected="false">Grades</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="payments-tab" data-toggle="pill" href="#payments-content" role="tab" aria-controls="payments-content" aria-selected="false">Payments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="inactive-tab" data-toggle="pill" href="#inactive-content" role="tab" aria-controls="inactive-content" aria-selected="false">Inactive Students</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="custom-tabs-three-tabContent">
                            <!-- 
                                ====================================================================================================================================
                                STUDENTS LIST 
                                ====================================================================================================================================
                            -->
                            <div class="tab-pane fade active show" id="students-list-content" role="tabpanel" aria-labelledby="students-list-tab">
                                <div class="mt-2">
                                    <a :href="baseURL + '/students/print-students/' + classId" class="btn btn-link btn-link-muted" title="Print"><i class="fas fa-print"></i></a>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <th style="width: 35px;"></th>
                                            <th class="text-muted">Last Name</th>
                                            <th class="text-muted">First Name</th>
                                            <th class="text-muted">Middle Name</th>
                                            <th class="text-muted">LRN</th>
                                            <th class="text-muted">Address</th>
                                            <th class="text-muted">Birth Date</th>
                                            <th class="text-muted">Contact Numbers</th>
                                            <th style="width: 120px;"></th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="9" class="text-muted"><i class="fas fa-venus ico-tab-mini"></i>Male Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in male" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <span><i class="ico-tab-mini text-xs fas" :class="student.FromSchool==='Private' ? 'fa-user-lock text-primary' : 'fa-user-check text-warning'" :title="student.FromSchool==='Private' ? 'From Private School' : 'From Public School'"></i></span>
                                                    <span><i class="ico-tab-mini text-xs fas" :class="student.ESCScholar==='Yes' ? 'fa-check-circle text-primary' : 'fa-check-circle text-gray'" :title="student.ESCScholar==='Yes' ? 'ESC Scholar' : 'Non-ESC Scholar'"></i></span>
                                                    <strong>{{ student.LastName }}</strong>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.EnrollmentStatus==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="v-align">
                                                    <strong>{{ student.FirstName + (isNull(student.Suffix) ? '' : (' ' + student.Suffix)) }}</strong>
                                                </td>
                                                <td class="v-align">
                                                    <strong>{{ student.MiddleName }}</strong>
                                                </td>
                                                <td class="v-align">{{ student.LRN }}</td>
                                                <td class="v-align">{{ (isNull(student.Sitio) ? '' : student.Sitio) + ', ' + student.BarangaySpelled + ', ' + student.TownSpelled }}</td>
                                                <td class="v-align">{{ isNull(student.Birthdate) ? '-' : moment(student.Birthdate).format('MMM DD, YYYY') }}</td>
                                                <td class="v-align">{{ isNull(student.ContactNumber) ? '-' : student.ContactNumber }}</td>
                                                <td class="text-right" style="overflow: visible;">
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.id"><i class="fas fa-eye"></i></a>

                                                    <div class="px-3" title="More Options" style="display: inline;">
                                                        <a href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                                          <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu">
                                                            <span class="text-muted text-sm px-4">Tag as: </span>
                                                            <button @click="updateStatus(student.id, `Transferred to Another School`, `Tag this student as TRANSFERRED TO ANOTHER SCHOOL? You can always change this anytime.`)" class="dropdown-item"><i class="fas fa-share ico-tab"></i>Transferred to Another School</button>
                                                            <button @click="updateStatus(student.id, `Withdrawn`, `Tag this student as WITHDRAWN? You can always change this anytime.`)" class="dropdown-item"><i class="fas fa-sign-out-alt ico-tab"></i>Withdrawn</button>
                                                            <button @click="updateStatus(student.id, `Dropped Out`, `Tag this student as DROPPED OUT? You can always change this anytime.`)" class="dropdown-item"><i class="fas fa-times-circle ico-tab"></i>Dropped Out</button>

                                                            <div class="divider"></div>

                                                            <a target="_blank" class="dropdown-item" :href="baseURL + '/students/edit-student/' + student.id"><i class="fas fa-pen ico-tab"></i>Edit Student Details</a>
                                                            <a class="dropdown-item" :href="baseURL + '/classes/transfer-to-another-class/' + student.id"><i class="fas fa-random ico-tab"></i>Transfer to Another Class</a>
                                                            <button @click="markEsc(student.id, 'Yes')" v-if="student.ESCScholar === 'No' ? true : false" class="dropdown-item"><i class="fas fa-check-circle ico-tab"></i>Mark ESC Scholar</button>
                                                            <button @click="markEsc(student.id, 'No')" v-if="student.ESCScholar === 'Yes' ? true : false" class="dropdown-item"><i class="far fa-check-circle ico-tab"></i>Mark Non-ESC Scholar</button>

                                                            <div class="divider"></div>

                                                            <button @click="removeFromClass(student.StudentClassId)" title="Remove from this class" class='dropdown-item text-danger'><i class="fas fa-trash ico-tab"></i>Remove/Unenroll</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="9" class="text-muted"><i class="fas fa-mars ico-tab-mini"></i>Female Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in female" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <span><i class="ico-tab-mini text-xs fas" :class="student.FromSchool==='Private' ? 'fa-user-lock text-primary' : 'fa-user-check text-warning'" :title="student.FromSchool==='Private' ? 'From Private School' : 'From Public School'"></i></span>
                                                    <span><i class="ico-tab-mini text-xs fas" :class="student.ESCScholar==='Yes' ? 'fa-check-circle text-primary' : 'fa-check-circle text-gray'" :title="student.ESCScholar==='Yes' ? 'ESC Scholar' : 'Non-ESC Scholar'"></i></span>
                                                    <strong>{{ student.LastName }}</strong>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.EnrollmentStatus==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="v-align">
                                                    <strong>{{ student.FirstName + (isNull(student.Suffix) ? '' : (' ' + student.Suffix)) }}</strong>
                                                </td>
                                                <td class="v-align">
                                                    <strong>{{ student.MiddleName }}</strong>
                                                </td>
                                                <td class="v-align">{{ student.LRN }}</td>
                                                <td class="v-align">{{ (isNull(student.Sitio) ? '' : student.Sitio) + ', ' + student.BarangaySpelled + ', ' + student.TownSpelled }}</td>
                                                <td class="v-align">{{ isNull(student.Birthdate) ? '-' : moment(student.Birthdate).format('MMM DD, YYYY') }}</td>
                                                <td class="v-align">{{ isNull(student.ContactNumber) ? '-' : student.ContactNumber }}</td>
                                                <td class="text-right" title="View Student">
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.id"><i class="fas fa-eye"></i></a>
                                                    
                                                    <div class="px-3" title="More Options" style="display: inline;">
                                                        <a href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                                          <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu">
                                                            <span class="text-muted text-sm px-2">Tag as: </span>
                                                            <button @click="updateStatus(student.id, `Transferred to Another School`, `Tag this student as TRANSFERRED TO ANOTHER SCHOOL? You can always change this anytime.`)" class="dropdown-item"><i class="fas fa-share ico-tab"></i>Transferred to Another School</button>
                                                            <button @click="updateStatus(student.id, `Withdrawn`, `Tag this student as WITHDRAWN? You can always change this anytime.`)" class="dropdown-item"><i class="fas fa-sign-out-alt ico-tab"></i>Withdrawn</button>
                                                            <button @click="updateStatus(student.id, `Dropped Out`, `Tag this student as DROPPED OUT? You can always change this anytime.`)" class="dropdown-item"><i class="fas fa-times-circle ico-tab"></i>Dropped Out</button>

                                                            <div class="divider"></div>

                                                            <a target="_blank" class="dropdown-item" :href="baseURL + '/students/edit-student/' + student.id"><i class="fas fa-pen ico-tab"></i>Edit Student Details</a>
                                                            <a class="dropdown-item" :href="baseURL + '/classes/transfer-to-another-class/' + student.id"><i class="fas fa-random ico-tab"></i>Transfer to Another Class</a>

                                                            <div class="divider"></div>

                                                            <button @click="removeFromClass(student.StudentClassId)" title="Remove from this class" class='dropdown-item text-danger'><i class="fas fa-trash ico-tab"></i>Remove/Unenroll</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- 
                                ====================================================================================================================================
                                ATTENDANCE 
                                ====================================================================================================================================
                            -->
                            <div class="tab-pane fade" id="attendance-content" role="tabpanel" aria-labelledby="attendance-tab">
                                <div class="table-responsive mt-2">
                                    <!-- action -->
                                    <div class="form-group float-right ml-2">
                                        <span class="text-muted">Actions</span>
                                        <br>
                                        <button class="btn btn-default btn-sm" @click="getAllAttendanceData()">View <i class="fas fa-check-circle ico-tab-left-mini"></i></button>

                                        <button class="btn btn-primary btn-sm ico-tab-left" @click="downloadSF2()">Download SF2 <i class="fas fa-file-excel ico-tab-left-mini"></i></button>
                                    </div>

                                    <!-- year -->
                                    <div class="form-group float-right ml-2" style="width: 130px;">
                                        <span class="text-muted">Year</span>
                                        <input type="number" class="form-control form-control-sm" v-model="attendanceYear">
                                    </div>
                                    <!-- months -->
                                    <div class="form-group float-right" style="width: 150px;">
                                        <span class="text-muted">Month</span>
                                        <select v-model="attendanceMonth" class="form-control form-control-sm" @change="getAllAttendanceData()">
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>

                                    <table class="table table-hover table-sm table-bordered">
                                        <thead>
                                            <th style="width: 28px;"</th>
                                            <th class="text-center">Students</th>
                                            <th class="text-center" v-for="d in daysInAMonth">{{ d }}</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td :colspan="(4 + (daysInAMonth.length))" class="text-muted"><i class="fas fa-venus ico-tab-mini"></i>Male Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in male" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.id">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.EnrollmentStatus==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="v-align text-center" v-for="d in daysInAMonth" v-html="fetchDailyAttendance(student.id, `${attendanceYear}-${attendanceMonth}-${d}`)"></td>
                                            </tr>
                                            <tr>
                                                <td :colspan="(4 + (daysInAMonth.length))" class="text-muted"><i class="fas fa-mars ico-tab-mini"></i>Female Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in female" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.id">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.EnrollmentStatus==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="v-align text-center" v-for="d in daysInAMonth" v-html="fetchDailyAttendance(student.id, `${attendanceYear}-${attendanceMonth}-${d}`)"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 
                                ====================================================================================================================================
                                GRADES 
                                ====================================================================================================================================
                            -->
                            <div class="tab-pane fade" id="grades-content" role="tabpanel" aria-labelledby="grades-tab">
                                <div class="table-responsive mt-2">
                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <th></th>
                                            <th class="text-center">Students</th>
                                            <th class="text-center" v-for="sb in subjects">{{ sb.Subject }}</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td :colspan="(4 + (paymentMonths.length))" class="text-muted"><i class="fas fa-venus ico-tab-mini"></i>Male Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in male" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.id">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.EnrollmentStatus==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="v-align text-right" v-for="sb in subjects" v-html="getFinalGrade(student.id, sb.id)"></td>
                                            </tr>
                                            <tr>
                                                <td :colspan="(4 + (paymentMonths.length))" class="text-muted"><i class="fas fa-mars ico-tab-mini"></i>Female Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in female" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.id">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.EnrollmentStatus==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="v-align text-right" v-for="sb in subjects" v-html="getFinalGrade(student.id, sb.id)"></td>
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
                                                <td :colspan="(4 + (paymentMonths.length))" class="text-muted"><i class="fas fa-venus ico-tab-mini"></i>Male Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in male" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <span><i class="ico-tab-mini text-xs fas" :class="student.FromSchool==='Private' ? 'fa-user-lock text-primary' : 'fa-user-check text-warning'" :title="student.FromSchool==='Private' ? 'From Private School' : 'From Public School'"></i></span>

                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.id">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.EnrollmentStatus==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="text-right v-align text-primary">{{ isNull(student.PayableData) && isNull(student.PayableData.AmountPayable) ? '-' : toMoney(student.PayableData.AmountPayable) }}</td>
                                                <td class="text-right v-align" v-for="pmd in paymentMonths" v-html="getPaymentData(pmd.ForMonth, student.id)"></td>
                                                <td class="text-right v-align text-danger">{{ isNull(student.PayableData.Balance) ? '-' : toMoney(parseFloat(student.PayableData.Balance)) }}</td>
                                            </tr>
                                            <tr>
                                                <td :colspan="(4 + (paymentMonths.length))" class="text-muted"><i class="fas fa-mars ico-tab-mini"></i>Female Students</td>
                                            </tr>
                                            <tr v-for="(student, index) in female" :key="student.StudentSubjectId">
                                                <td class="v-align">{{ index+1 }}</td>
                                                <td class="v-align">
                                                    <span><i class="ico-tab-mini text-xs fas" :class="student.FromSchool==='Private' ? 'fa-user-lock text-primary' : 'fa-user-check text-warning'" :title="student.FromSchool==='Private' ? 'From Private School' : 'From Public School'"></i></span>
                                                    
                                                    <a target="_blank" :href="baseURL + '/students/guest-view/' + student.id">
                                                        <strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong>
                                                    </a>
                                                    <span title="Enrollment payment not yet paid" class="badge bg-warning ico-tab-left-mini" v-if="student.EnrollmentStatus==='Pending Enrollment Payment' ? true : false">Pending</span>
                                                </td>
                                                <td class="text-right v-align text-primary">{{ isNull(student.PayableData) && isNull(student.PayableData.AmountPayable) ? '-' : toMoney(student.PayableData.AmountPayable) }}</td>
                                                <td class="text-right v-align" v-for="pmd in paymentMonths" v-html="getPaymentData(pmd.ForMonth, student.id)"></td>
                                                <td class="text-right v-align text-danger">{{ isNull(student.PayableData.Balance) ? '-' : toMoney(parseFloat(student.PayableData.Balance)) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- 
                                ====================================================================================================================================
                                INACTIVE 
                                ====================================================================================================================================
                            -->
                            <div class="tab-pane fade" id="inactive-content" role="tabpanel" aria-labelledby="inactive-tab">
                                <div class="mt-2">
                                    <a :href="baseURL + '/students/print-inactive-students/' + classId" class="btn btn-link btn-link-muted" title="Print"><i class="fas fa-print"></i></a>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <th></th>
                                            <th>Student Name</th>
                                            <th>LRN</th>
                                            <th>Address</th>
                                            <th>Gender</th>
                                            <th>Status</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(student, index) in inactive">
                                                <td>{{ index+1 }}</td>
                                                <td class="v-align"><a :href="baseURL + '/students/guest-view/' + student.id" target="_blank"><strong>{{ student.LastName + ', ' + student.FirstName + (isNull(student.MiddleName) ? '' : (' ' + student.MiddleName + ' ')) + (isNull(student.Suffix) ? '' : student.Suffix) }}</strong></a></td>
                                                <td class="v-align">{{ student.LRN }}</td>
                                                <td class="v-align">{{ (isNull(student.Sitio) ? '' : student.Sitio) + ', ' + student.BarangaySpelled + ', ' + student.TownSpelled }}</td>
                                                <td class="v-align">{{ student.Gender }}</td>
                                                <td class="v-align">{{ student.Status }}</td>
                                                <td class="text-right">
                                                    <button title="Revert to Active" @click="updateStatus(student.id, null, `Re-active this student? You can always change this anytime.`)" class="btn btn-link-muted" :href="baseURL + '/students/edit-student/' + studentId"><i class="fas fa-sync-alt"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

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
            teacherId : document.querySelector("meta[name='teacher-id']").getAttribute('content'),
            syId : document.querySelector("meta[name='school-year-id']").getAttribute('content'),
            classId : document.querySelector("meta[name='class-id']").getAttribute('content'),
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            amInThreshold : document.querySelector("meta[name='am-in-threshold']").getAttribute('content'),
            pmOutThreshold : document.querySelector("meta[name='pm-out-threshold']").getAttribute('content'),
            male : [],
            female : [],
            advisory : {},
            syDetails : {},
            paymentMonths : [],
            paymentData : [],
            payablesProfile : [],
            subjects : [],
            subjectData : [],
            attendanceMonth : moment().format('MM'),
            attendanceYear : moment().format('YYYY'),
            daysInAMonth : [],
            barcodeAttendances : [],
            loaderVisibility : true,
            inactive : [],
            classesInSy : [],
            classSelect : ''
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
        getAdvisoryData() {
            axios.get(`${ this.baseURL }/users/get-advisory-details`, {
                params : {
                    TeacherId : this.teacherId,
                    SchoolYearId : this.syId,
                    ClassId : this.classId
                }
            })
            .then(response => {
                this.advisory = response.data.Class
                this.syDetails = response.data.SchoolYear
                this.male = response.data.Male
                this.female = response.data.Female
                this.inactive = response.data.Inactive

                this.getClassPaymentDetails()
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting data!'
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
                if (!this.isNull(this.male)) {
                    for(let i=0; i<this.male.length; i++) {
                        let dataFound = this.payablesProfile.find(obj => obj.StudentId === this.male[i].id)

                        if (!this.isNull(dataFound)) {
                            this.male[i].PayableData = dataFound
                        } else {
                            this.male[i].PayableData = []
                        }
                    }
                }

                if (!this.isNull(this.female)) {
                    for(let i=0; i<this.female.length; i++) {
                        let dataFound = this.payablesProfile.find(obj => obj.StudentId === this.female[i].id)

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
        getSubjects() {
            // GET SUBJECTS
            axios.get(`${ this.baseURL }/users/get-subjects-from-class`, {
                params : {
                    ClassId : this.classId
                }
            })
            .then(response => {
                this.subjects = response.data

                // GET SUBJECT GRADES AND DATA
                this.getSubjectData()
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting subjects!'
                })
            })
        },
        getSubjectData() {
            axios.get(`${ this.baseURL }/users/get-student-subjects-data-from-class`, {
                params : {
                    ClassId : this.classId
                }
            })
            .then(response => {
                this.subjectData = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting subject data!'
                })
            })
        },
        getFinalGrade(studentId, subjectId) {
            let gradeData = this.subjectData.find(obj => obj.StudentId === studentId && obj.SubjectId === subjectId)

            if (!this.isNull(gradeData)) {
                return this.isNull(gradeData.AverageGrade) ? '-' : (parseFloat(gradeData.AverageGrade) > 0 ? ('<strong>' + gradeData.AverageGrade + '</strong>') : '-')
            } else {
                return '-'
            }
        },
        getDaysInMonth() {
            const days = moment(this.attendanceYear + '-' + this.attendanceMonth).daysInMonth()

            this.daysInAMonth = []

            for (let i=0; i<days; i++) {
                this.daysInAMonth.push((i+1))
            }
        },
        getBarcodeAttendances() {
            axios.get(`${ this.baseURL }/barcode_attendances/get-barcode-attendance-per-class`, {
                params : {
                    ClassId : this.classId
                }
            })
            .then(response => {
                this.barcodeAttendances = response.data

                this.loaderVisibility = false
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting barcode attendance!'
                })
            })
        },
        getAllAttendanceData() {
            this.getDaysInMonth()
            this.getBarcodeAttendances()
        },
        fetchDailyAttendance(studentId, date) {
            var att = this.barcodeAttendances.filter(obj => moment(obj.created_at).format('YYYY-MM-D') === date && obj.StudentId === studentId)

            var timeIn = null
            var timeOut = null

            // fetch am first
            const inThreshold = moment(date + ' ' + this.amInThreshold).format('YYYY-MM-DD HH:mm')

            for (let i=0; i<att.length; i++) {
                const obj = att[i]

                var inTime = obj.created_at

                if (!this.isNull(inTime)) {
                    var xTime = moment(inTime).format('YYYY-MM-DD HH:mm')
   
                    if (moment(xTime).isBefore(moment(inThreshold))) {
                        if (this.isNull(timeIn)) {
                            timeIn = xTime
                        }
                    }
                }
            }

            // fetch pm out
            const outThreshold = moment(date + ' ' + this.pmOutThreshold).format('YYYY-MM-DD HH:mm')

            for (let i=0; i<att.length; i++) {
                const obj = att[i]

                var outTime = obj.created_at

                if (!this.isNull(outTime)) {
                    var xTime = moment(outTime).format('YYYY-MM-DD HH:mm')
   
                    if (moment(xTime).isAfter(moment(outThreshold))) {
                        if (this.isNull(timeOut)) {
                            timeOut = xTime
                        }
                    }
                }
            }

            // validate time ins and outs
            var returnData = ""
            if (!this.isNull(timeIn)) {
                returnData += `<span class='text-success' title='Morning In: ${ moment(timeIn).format('hh:mm A') }'><strong>✓</strong></span> | `
            } else {
                returnData += `<span class='text-danger'>○</span> | `;
            }

            if (!this.isNull(timeOut)) {
                returnData += `<span class='text-success' title='Afternoon Out: ${ moment(timeOut).format('hh:mm A') }'><strong>✓</strong></span>`
            } else {
                returnData += `<span class='text-danger'>○</span>`;
            }

            return returnData
        },
        downloadSF2() {
            if (this.advisory.Year === 'Grade 11' || this.advisory.Year === 'Grade 12') {
                window.location.href = this.baseURL + '/barcode_attendances/download-sf2-senior/' + this.classId + '/' + this.attendanceMonth + '/' + this.attendanceYear
            } else {
                window.location.href = this.baseURL + '/barcode_attendances/download-sf2-junior/' + this.classId + '/' + this.attendanceMonth + '/' + this.attendanceYear
            }
        },
        revalidatePayments() {
            axios.get(`${ this.baseURL }/transactions/repopulate-payables`, {
                params : {
                    ClassId : this.classId
                }
            })
            .then(response => {
                this.toast.fire({
                    icon : 'success',
                    text : 'Payables repopulated!'
                })
                location.reload()
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error repopulating payables!'
                })
            })
        },
        revalidateSubjects() {
            axios.post(`${ this.baseURL }/classes/revalidate-subjects`, {
                _token : this.token,
                ClassId : this.classId
            })
            .then(response => {
                this.toast.fire({
                    icon : 'success',
                    text : 'Subjects repopulated!'
                })
                location.reload()
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error repopulating subjects!\n' + error.response
                })
            })
        },
        updateStatus(id, status, message) {
            Swal.fire({
                title: "Update Status",
                showCancelButton: true,
                text : message,
                confirmButtonText: "Proceed",
                confirmButtonColor : '#3a9971'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ this.baseURL }/students/update-status`, {
                        _token : this.token,
                        id : id,
                        Status : status
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Student status updated!'
                        })
                        location.reload()
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error updating student status!'
                        })
                    })
                }
            })            
        },
        removeFromClass(studentClassId) {
            Swal.fire({
                title: "Confirm Removal",
                text : 'Removing this student from this class does not delete the student. If you wish to delete the student, you may go to the student account page. Proceed with caution.',
                showCancelButton: true,
                confirmButtonText: "Proceed Removal",
                confirmButtonColor : '#e03822'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`${ this.baseURL }/studentClasses/` + studentClassId, {
                        _token : this.token,
                        id : studentClassId,
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Student removed!'
                        })
                        location.reload()
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error removing student!'
                        })
                    })
                }
            })
        },
        getClassesInSY() {
            axios.get(`${ this.baseURL }/school_years/get-classes-in-sy`, {
                params : {
                    SchoolYearId : this.syId
                }
            })
            .then(response => {
                this.classesInSy = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting classes inside school year!'
                })
            })
        },
        goToClass() {
            window.location.href = `${ this.baseURL }/classes/view-class/${ this.teacherId }/${ this.syId }/${ this.classSelect }`
        },
        markEsc(studentId, isEscScholar) {
            axios.post(`${ this.baseURL }/students/mark-esc`, {
                _token : this.token,
                id : studentId,
                ESCScholar : isEscScholar
            })
            .then(response => {
                this.toast.fire({
                    icon : 'success',
                    text : 'Student marked ' + isEscScholar + ' for ESC Scholarship!'
                })
                location.reload()
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error tagging ESC Scholar!'
                })
            })
        }
    },
    created() {
        
    },
    mounted() {
        this.classSelect = this.classId

        this.getAdvisoryData()
        this.getSubjects()
        
        // attendance
        this.getAllAttendanceData()

        this.getClassesInSY()
    }
}

</script>