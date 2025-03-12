<template>
    <!-- HEADER -->
    <section class="px-4">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-lg-8">
                    <div style="display: flex; padding-bottom: 15px;">
                        <div style="width: 88px; display: inline;">
                            <img @click="uploadPhoto()" id="prof-img" style="width: 65px !important; height: 65px !important; cursor: pointer; object-fit: cover;" title="Change profile photo" class="profile-user-img img-circle" :src="imagePreview" @error="handleImageError">
                            <input type="file" ref="fileInput" @change="onFileChange" class="gone" />
                        </div>
                        <div>
                            <span>
                                <p class="no-pads" style="font-size: 1.85em;"><strong>{{ studentData.LastName + ', ' + studentData.FirstName + (isNull(studentData.MiddleName) ? '' : (' ' + studentData.MiddleName + ' ')) + (isNull(studentData.Suffix) ? '' : studentData.Suffix) }}</strong></p>
                                
                                <span class="text-muted">
                                    <i class="fas fa-id-badge ico-tab-mini"></i>LRN-{{ studentData.LRN }} | 
                                    <i class="fas fa-lightbulb ico-tab-mini"></i>{{ isNull(studentData.Year) ? '-' : (studentData.Year + ' - ' + studentData.Section) }} {{ isNull(studentData.Strand) ? '' : (' • ' + studentData.Strand) }} {{ isNull(studentData.Semester) ? '' : (' • ' + studentData.Semester + ' Sem') }}
                                    <span class="badge" :class="isNull(studentData.Status) ? 'bg-success' : 'bg-danger'" title="Status">{{ isNull(studentData.Status) ? 'Studying' : studentData.Status }}</span>
                                    <span class="badge bg-success ico-tab-left-mini" v-if="!isNull(studentData.ESCScholar) && studentData.ESCScholar==='Yes' ? true : false">ESC Scholar/Grantee</span>
                                </span>
                            </span>
                        </div>
                    </div>
                    
                </div>

                <div class="col-lg-4">  
                    <div class="dropdown">
                        <a class="btn btn-primary-skinny dropdown-toggle float-right {{ $colorProf != null ? 'text-white' : '' }}" href="#" role="button" data-toggle="dropdown" aria-expanded="false" style="margin-right: 15px;">
                          Actions
                        </a>
                      
                        <div class="dropdown-menu">
                            <a class="dropdown-item" :href="baseURL + '/students/edit-student/' + studentId + '/student-view'"><i class="fas fa-pen ico-tab"></i>Edit Details</a>
                            <!-- <a class="dropdown-item" href="#"><i class="fas fa-calendar-alt ico-tab"></i>View Attendance</a> -->
                            
                            <div class="divider"></div>

                            <a :href="baseURL + '/classes/transfer-to-another-class/' + studentId" class="dropdown-item"><i class="fas fa-random ico-tab"></i>Transfer to Another Section/Strand</a>
                            <button class="dropdown-item" @click="scholarshipWizzard()"><i class="fas fa-folder-plus ico-tab"></i>Add Scholarship Grant</button>

                            <div class="divider"></div>

                            <button class="dropdown-item text-danger" @click="deleteStudent()"><i class="fas fa-trash ico-tab"></i>Delete Student</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BODY -->
    <div class="content px-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-none">
                    <div class="card-body p-0">
                        <div class="row">
                            <!-- BASIC DETAILS -->
                            <div class="col-lg-4" style="padding: 25px 35px 15px 35px;">
                                <h4><strong>Student Info</strong></h4>

                                <span class="badge bg-warning ico-tab-left-mini" title="From what school">From {{ studentData.FromSchool }} School</span>
                                
                                <table class="table table-sm table-borderless" style="margin-top: 18px;">
                                    <tbody>
                                        <tr title="Current Address">
                                            <td><i class="fas text-muted fa-map-marker-alt"></i></td>
                                            <td>{{ (isNull(studentData.Sitio) ? '' : studentData.Sitio) + ', ' + studentData.BarangaySpelled + ', ' + studentData.TownSpelled }} ({{ studentData.ZipCode }})</td>
                                        </tr>
                                        <tr title="Permanent Address">
                                            <td><i class="fas text-muted fa-home"></i></td>
                                            <td>{{ (isNull(studentData.PermanentSitio) ? '' : studentData.PermanentSitio) + ', ' + studentData.BarangaySpelledPermanent + ', ' + studentData.TownSpelledPermanent }} ({{ studentData.PermanentZipCode }})</td>
                                        </tr>
                                        <tr title="Birthday">
                                            <td><i class="fas text-muted fa-birthday-cake"></i></td>
                                            <td>{{ isNull(studentData.Birthdate) ? 'not recorded' : moment(studentData.Birthdate).format("MMMM DD, YYYY") }}</td>
                                        </tr>
                                        <tr title="Contact Numbers">
                                            <td><i class="fas text-muted fa-hashtag"></i></td>
                                            <td>{{ isNull(studentData.ContactNumber) ? '-' : studentData.ContactNumber }}</td>
                                        </tr>
                                        <tr title="Gender">
                                            <td><i class="fas text-muted fa-venus-mars"></i></td>
                                            <td>{{ studentData.Gender }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- TABS -->
                            <div class="col-lg-8 {{ $colorProf != null ? 'bl-dark' : 'bl-light' }}" style="padding-top: 15px; padding-bottom: 15px; padding-left: 25px; padding-right: 25px;">
                                <!-- TAB HEADS -->
                                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active   " id="account-tab" data-toggle="pill" href="#account-content" role="tab" aria-controls="account-content" aria-selected="false">Account</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="subjects-tab" data-toggle="pill" href="#subjects-content" role="tab" aria-controls="subjects-content" aria-selected="false">Subjects & Classes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="more-details-tab" data-toggle="pill" href="#more-details-content" role="tab" aria-controls="more-details-content" aria-selected="false">More Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="scholarship-tab" data-toggle="pill" href="#scholarship-content" role="tab" aria-controls="scholarship-content" aria-selected="false">Scholarship Grants</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="attendance-tab" data-toggle="pill" href="#attendance" role="tab" aria-controls="attendance" aria-selected="false">Attendance Logs</a>
                                    </li>
                                </ul>
                                <!-- TAB BODY -->
                                <div class="tab-content" id="custom-tabs-three-tabContent">

                                    <!-- 
                                        ====================================================================================================================================
                                        ACCOUNT 
                                        ====================================================================================================================================
                                    -->
                                    <div class="tab-pane fade active show" id="account-content" role="tabpanel" aria-labelledby="account-tab">
                                        <div class="p-2 table-responsive">
                                            <!-- Summary -->
                                            <div class="row mt-3">
                                                <div class="col-lg-6">
                                                    <h4 class="no-pads">Account Balance</h4>
                                                    <span class="text-muted">Unpaid tuition fees, enrollment fees, and other payments</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <p class="no-pads text-right" :class="getTotalBalance() > 0 ? 'text-danger' : 'text-success'" style="font-size: 2.8em;">₱ {{ toMoney(getTotalBalance()) }}</p>
                                                </div>
                                            </div>
                                            
                                            <!-- History -->
                                            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="payable-history-tab" data-toggle="pill" href="#payable-history-content" role="tab" aria-controls="payable-history-content" aria-selected="false">Payable History</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="transaction-history-tab" data-toggle="pill" href="#transaction-history-content" role="tab" aria-controls="transaction-history-content" aria-selected="false">Transaction History</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="detailed-transactions-tab" data-toggle="pill" href="#detailed-transactions-content" role="tab" aria-controls="detailed-transactions-content" aria-selected="false">Detailed Transactions</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                                <!-- payable history -->
                                                <div class="tab-pane fade active show" id="payable-history-content" role="tabpanel" aria-labelledby="payable-history-tab">
                                                    <table class="table table-sm table-hover">
                                                        <thead>
                                                            <th class="text-muted">Description</th>
                                                            <th class="text-muted text-center">Category</th>
                                                            <th class="text-muted text-right">Amount Payable</th>
                                                            <th class="text-muted text-right">Amount Paid</th>
                                                            <th class="text-muted text-right">Balance</th>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="pointer" v-for="payable in payables" :key="payable.id">
                                                                <td @click="transactionHistory(payable.id)" class="v-align">{{ payable.PaymentFor }}</td>
                                                                <td @click="transactionHistory(payable.id)" class="v-align text-center"><span class="badge bg-info">{{ payable.Category }}</span></td>
                                                                <td @click="transactionHistory(payable.id)" class="v-align text-right"><strong>{{ isNull(payable.AmountPayable) ? '0' : toMoney(parseFloat(payable.AmountPayable)) }}</strong></td>
                                                                <td @click="transactionHistory(payable.id)" class="v-align text-right">{{ isNull(payable.AmountPaid) ? '0' : toMoney(parseFloat(payable.AmountPaid)) }}</td>
                                                                <td @click="transactionHistory(payable.id)" class="v-align text-right" :class="parseFloat(payable.Balance) > 0 ? 'text-danger' : 'text-success'"><strong>{{ isNull(payable.Balance) ? '0' : toMoney(parseFloat(payable.Balance)) }}</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- transaction history -->
                                                <div class="tab-pane fade" id="transaction-history-content" role="tabpanel" aria-labelledby="transaction-history-tab">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm table-bordered">
                                                            <thead>
                                                                <th>Date</th>
                                                                <th>Transaction</th>
                                                                <th>OR Number</th>
                                                                <th>Payment Medium</th>
                                                                <th>Amount Paid</th>
                                                                <th>Cashier</th>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="item in allTransactions" :key="item.id" style="cursor: pointer;">
                                                                    <td @click="showTransactionDetails(item.id)">{{ moment(item.ORDate).format('MMM DD, YYYY') }}</td>
                                                                    <td @click="showTransactionDetails(item.id)">{{ item.PaymentFor }}</td>
                                                                    <td @click="showTransactionDetails(item.id)">{{ item.ORNumber }}</td>
                                                                    <td @click="showTransactionDetails(item.id)">{{ item.ModeOfPayment }}</td>
                                                                    <td @click="showTransactionDetails(item.id)" class='text-success text-right'><strong>{{ toMoney(parseFloat(item.TotalAmountPaid)) }}</strong></td>
                                                                    <td @click="showTransactionDetails(item.id)">{{ item.name }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <!-- detailed transaction history -->
                                                <div class="tab-pane fade" id="detailed-transactions-content" role="tabpanel" aria-labelledby="detailed-transactions-tab">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-sm table-bordered">
                                                            <thead>
                                                                <th>OR Number</th>
                                                                <th>Date</th>
                                                                <th>Particulars</th>
                                                                <th>Amount Paid</th>
                                                                <th>Cashier</th>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="item in detailedTransactions" :key="item.id">
                                                                    <td>{{ item.ORNumber }}</td>
                                                                    <td>{{ moment(item.ORDate).format('MMM DD, YYYY') }}</td>
                                                                    <td>{{ item.Particulars }}</td>
                                                                    <td class="text-right text-success "><strong>{{ toMoney(parseFloat(item.Amount)) }}</strong></td>
                                                                    <td>{{ item.name }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>

                                    <!-- 
                                        ====================================================================================================================================
                                        SUBJECTS 
                                        ====================================================================================================================================
                                    -->
                                    <div class="tab-pane fade" id="subjects-content" role="tabpanel" aria-labelledby="subjects-tab">
                                        <a :href="baseURL + '/classes/transfer-to-another-class/' + studentId" class="btn btn-primary float-right m-2">Transfer to Another Section/Strand</a>

                                        <div class="p-2 table-responsive">
                                            <p class="text-muted mt-3"><i class="fas fa-dot-circle ico-tab-mini"></i>Current/Latest Subjects Taken in this Class ({{ isNull(studentData.Year) ? '-' : (studentData.Year + ' - ' + studentData.Section) }})</p>
                                            <table class="table table-hover table-sm table-bordered">
                                                <thead>
                                                    <th></th>
                                                    <th class="text-muted">1st Grading</th>
                                                    <th class="text-muted">2nd Grading</th>
                                                    <th class="text-muted">3rd Grading</th>
                                                    <th class="text-muted">4th Grading</th>
                                                    <th class="text-muted">Final Grade</th>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="subject in subjects" :key="subject.StudentSubjectId">
                                                        <td class="v-align">
                                                            <strong>{{ subject.Subject }}</strong>
                                                            <br>
                                                            <span class="text-muted text-sm">{{ subject.TeacherName }}</span>
                                                        </td>
                                                        <td class="v-align text-right">{{ subject.FirstGradingGrade }}</td>
                                                        <td class="v-align text-right">{{ subject.SecondGradingGrade }}</td>
                                                        <td class="v-align text-right">{{ subject.ThirdGradingGrade }}</td>
                                                        <td class="v-align text-right">{{ subject.FourthGradingGrade }}</td>
                                                        <td class="v-align text-right">{{ subject.AverageGrade }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- 
                                        ====================================================================================================================================
                                        MORE DETAILS 
                                        ====================================================================================================================================
                                    -->
                                    <div class="tab-pane fade" id="more-details-content" role="tabpanel" aria-labelledby="more-details-tab">
                                        <div class="table-responsive p-2">
                                            <table class="table table-sm table-hover table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2" class="text-muted text-center"><i>Other Information</i></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Place of Birth</td>
                                                        <td class="v-align">{{ studentData.PlaceOfBirth }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Mother Tongue</td>
                                                        <td class="v-align">{{ studentData.MotherTounge }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">PSA Birth Cert. Number</td>
                                                        <td class="v-align">{{ studentData.PSABirthCertificateNumber }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Indigeneity</td>
                                                        <td class="v-align">{{ isNull(studentData.Indigenousity) ? '-' : studentData.Indigenousity }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">4Ps Beneficiary Number</td>
                                                        <td class="v-align">{{ isNull(studentData.Beneficiary4PsIDNumber) ? '-' : studentData.Beneficiary4PsIDNumber }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-muted text-center"><i>Parent/Guardian Information</i></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Father's Name</td>
                                                        <td class="v-align">{{ validateNullStrings(studentData.FatherFirstName) + ' ' + validateNullStrings(studentData.FatherMiddleName) + ' ' + validateNullStrings(studentData.FatherLastName) + ' ' + validateNullStrings(studentData.FatherSuffix) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Father's Contact Info</td>
                                                        <td class="v-align">{{ studentData.FatherContactNumber }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Mother's Name</td>
                                                        <td class="v-align">{{ validateNullStrings(studentData.MotherFirstName) + ' ' + validateNullStrings(studentData.MotherMiddleName) + ' ' + validateNullStrings(studentData.MotherLastName) + ' ' + validateNullStrings(studentData.MotherSuffix) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Mother's Contact Info</td>
                                                        <td class="v-align">{{ studentData.MotherContactNumber }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Guardian's Name</td>
                                                        <td class="v-align">{{ validateNullStrings(studentData.GuardianFirstName) + ' ' + validateNullStrings(studentData.GuardianMiddleName) + ' ' + validateNullStrings(studentData.GuardianLastName) + ' ' + validateNullStrings(studentData.GuardianSuffix) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Guardian's Contact Info</td>
                                                        <td class="v-align">{{ studentData.GuardianContactNumber }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-muted text-center"><i>Education History</i></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Elementary School Graduated</td>
                                                        <td class="v-align">{{ studentData.ElementarySchoolGraduated }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Elementary School Address</td>
                                                        <td class="v-align">{{ studentData.ElementarySchoolAddress }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Date Graduated</td>
                                                        <td class="v-align">{{ isNull(studentData.ElementaryDateGraduated) ? '' : moment(studentData.ElementaryDateGraduated).format("MMMM DD, YYYY") }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Junior High School Graduated</td>
                                                        <td class="v-align">{{ studentData.JHSSchoolGraduated }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Junior High School Address</td>
                                                        <td class="v-align">{{ studentData.JHSSchoolAddress }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted v-align">Date Graduated</td>
                                                        <td class="v-align">{{ isNull(studentData.JHSDateGraduated) ? '' : moment(studentData.JHSDateGraduated).format("MMMM DD, YYYY") }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <!-- 
                                        ====================================================================================================================================
                                        SCHOLARSHIP 
                                        ====================================================================================================================================
                                    -->
                                    <div class="tab-pane fade" id="scholarship-content" role="tabpanel" aria-labelledby="scholarship-tab">
                                        <button @click="scholarshipWizzard()" class="btn btn-primary float-right m-2">Add Scholarship Grant</button>

                                        <div class="table-responsive px-2 pt-2">
                                            <table class="table table-hover">
                                                <thead>
                                                    <th class="text-muted">School Year</th>
                                                    <th class="text-muted">Scholarship Grant</th>
                                                    <th class="text-muted text-right">Amount Granted</th>
                                                    <th class="text-muted">Credited<br>Monthly</th>
                                                    <th class="text-muted"></th>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="scholarship in scholarships" :key="scholarship.id">
                                                        <td class="v-align">{{ scholarship.SchoolYear }}</td>
                                                        <td class="v-align">{{ scholarship.Scholarship }}</td>
                                                        <td class="text-right v-align">{{ toMoney(parseFloat(scholarship.Amount)) }}</td>
                                                        <td class="v-align">{{ scholarship.DeductMonthly }}</td>
                                                        <td class="text-right v-align">
                                                            <button @click="removeScholarship(scholarship.id)" class="btn btn-sm btn-danger"><i class="fas fa-times ico-tab-mini"></i>Remove</button>
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
                                    <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
                                        <div style="min-height: 50vh;">
                                            <FullCalendar :options='calendarOptions' ref="calendar" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-bottom">
        <a :href="baseURL + '/students/scout/prev/' + studentId" class="btn-floating shadow btn-default" title="Previous student in this class"><i class="fas fa-backward"></i></a>
        <a :href="baseURL + '/students/scout/next/' + studentId" class="btn-floating shadow btn-default ml-2" title="Next student in this class"><i class="fas fa-forward"></i></a>
    </div>

    <!-- TRANSACTION HISTORY MODAL -->
    <div ref="modalTransactionHistory" class="modal fade" id="modal-transaction-history" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="max-width: 90% !important; margin-top: 30px;">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="no-pads">
                            {{ paymentFor }}
                            <!-- <div id="loader" class="spinner-border text-success" role="status">
                                <span class="sr-only">Loading...</span>
                            </div> -->
                        </h4>
                    </div>
                </div>
                <div class="modal-body table-responsive">
                    <!-- TUITION FEES -->
                    <div>
                        <div class="row">
                            <!-- Total -->
                            <div class="col-lg-12 mb-2">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <p class="text-muted text-sm no-pads">Net Amount Payable <i class="fas fa-eye"></i></p>
                                        <h1 class="text-primary">₱ {{ isNull(activePayable.AmountPayable) ? '-' : toMoney(parseFloat(activePayable.AmountPayable)) }}</h1>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p class="text-muted text-sm no-pads">Payable <i class="fas fa-plus-circle"></i></p>
                                                <h4 class="text-muted">₱ {{ isNull(activePayable.Payable) ? '-' : toMoney(parseFloat(activePayable.Payable)) }}</h4>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <p class="text-muted text-sm no-pads">Discount <i class="fas fa-minus-circle"></i></p>
                                                <h4 class="text-muted">₱ {{ isNull(activePayable.DiscountAmount) ? '-' : toMoney(parseFloat(activePayable.DiscountAmount)) }}</h4>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-lg-6">
                                        <p class="text-muted text-right text-sm no-pads">Balance <i class="fas fa-dollar-sign"></i></p>
                                        <h1 class="text-right" :class="isNull(activePayable.Balance) ? 'text-success' : (activePayable.Balance <= 0 ? 'text-success' : 'text-danger')">₱ {{ isNull(activePayable.Balance) ? '0.00' : toMoney(parseFloat(activePayable.Balance)) }}</h1>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <p class="text-muted text-right text-sm no-pads">Total Amount Paid <i class="fas fa-check-circle"></i></p>
                                                <h4 class="text-muted text-right">₱ {{ isNull(activePayable.AmountPaid) ? '-' : toMoney(parseFloat(activePayable.AmountPaid)) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- options -->
                            <div class="col-lg-12 pb-2">
                                <button @click="printTuitionLedger()" class="btn btn-default btn-xs"><i class="fas fa-print ico-tab-mini"></i>Print Tuition Ledger</button>
                                <!-- <button class="btn btn-default btn-xs ml-1"><i class="fas fa-print ico-tab-mini"></i>Print All Ledger</button> -->
                            </div>

                            <!-- Tuition/Payable Inclusions -->
                            <div class="col-lg-5 table-responsive">
                                <span class="text-muted">Payable Breakdown</span>
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <th class="text-muted">Item</th>
                                        <th class="text-muted text-right">Amount</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="inc in payableInclusions" :key="inc.id">
                                            <td class="v-align"><i class="fas fa-check text-success ico-tab-mini"></i>{{ inc.ItemName }}</td>
                                            <td class="v-align text-right">{{ toMoney(parseFloat(inc.Amount)) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Monthly Breakdown -->
                            <div class="col-lg-7 table-responsive" v-if="isModalTuition">
                                <span class="text-muted">Tuition Fee Monthly Balance Breakdown</span>
                                <table class="table table-hover table-sm table-bordered">
                                    <thead>
                                        <th class="text-muted">Month</th>
                                        <th class="text-muted text-right">Tuition Fee</th>
                                        <th class="text-muted text-right">Discount</th>
                                        <th class="text-muted text-right">Amount Payable</th>
                                        <th class="text-muted text-right">Amount Paid</th>
                                        <th class="text-muted text-right">Balance</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in tuitionsBreakdown" :key="item.id">
                                            <td>
                                                <i v-if="parseFloat(item.Balance) <= 0" class="fas fa-check-circle text-success ico-tab-mini"></i>
                                                <i v-if="parseFloat(item.Balance) > 0" class="fas fa-info-circle text-muted ico-tab-mini"></i>
                                                {{ moment(item.ForMonth).format('MMMM YYYY') }}
                                            </td>
                                            <td class='text-right'>{{ toMoney(parseFloat(item.Payable)) }}</td>
                                            <td class='text-right'>{{ isNull(item.Discount) ? '-' : toMoney(parseFloat(item.Discount)) }}</td>
                                            <td class='text-right'>{{ isNull(item.AmountPayable) ? '-' : toMoney(parseFloat(item.AmountPayable)) }}</td>
                                            <td class='text-right'>{{ isNull(item.AmountPaid) ? '-' : toMoney(parseFloat(item.AmountPaid)) }}</td>
                                            <td class='text-right' :class="parseFloat(item.Balance) > 0 ? 'text-danger' : 'text-success'"><strong>{{ toMoney(parseFloat(item.Balance)) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>                      
                                <br>
                            </div>
                        </div>  
                    </div>

                    <!-- TRANSACTION HISTORY LOGS -->
                     
                    <div class="table-responsive">
                        <span class="text-muted">Transaction Logs</span>
                        <table class="table table-hover table-sm table-bordered">
                            <thead>
                                <th class="text-muted">Payment For</th>
                                <th class="text-muted">Mode of Payment</th>
                                <th class="text-muted">Period</th>
                                <th class="text-muted">OR Number</th>
                                <th class="text-muted">OR Date</th>
                                <th class="text-muted">Cashier</th>
                                <th class="text-muted text-right">Cash Amount</th>
                                <th class="text-muted text-right">Check Amount</th>
                                <th class="text-muted text-right">Transfer Amount</th>
                                <th class="text-muted text-right">Total Amount Paid</th>
                            </thead>
                            <tbody>
                                <tr v-for="hist in payableTransactionHistory" :key="hist.id">
                                    <td class="v-align">{{ hist.PaymentFor }}</td>
                                    <td class="v-align">{{ hist.ModeOfPayment }}</td>
                                    <td class="v-align">{{ hist.Period }}</td>
                                    <td class="v-align">{{ hist.ORNumber }}</td>
                                    <td class="v-align">{{ hist.ORDate.length < 1 ? '-' : moment(hist.ORDate).format('MMM DD, YYY') }}</td>
                                    <td class="v-align">{{ hist.name }}</td>
                                    <td class="v-align text-right">{{ isNull(hist.CashAmount) ? '-' : toMoney(parseFloat(hist.CashAmount)) }}</td>
                                    <td class="v-align text-right">{{ isNull(hist.CheckAmount) ? '-' : toMoney(parseFloat(hist.CheckAmount)) }}</td>
                                    <td class="v-align text-right">{{ isNull(hist.DigitalPaymentAmount) ? '-' : toMoney(parseFloat(hist.DigitalPaymentAmount)) }}</td>
                                    <td class="v-align text-right"><strong>{{ isNull(hist.TotalAmountPaid) ? '-' : toMoney(parseFloat(hist.TotalAmountPaid)) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ALL TRANSACTIONS MODAL -->
    <div ref="modalAllTransactions" class="modal fade" id="modal-all-transactions" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="no-pads">
                            Transaction Details
                        </h4>
                    </div>
                </div>
                <div class="modal-body table-responsive">
                    <!-- items/particulars -->
                    <p class="text-muted no-pads">Particulars</p>
                    <table class="table table-sm table-hover">
                        <thead>
                            <th>Particulars</th>
                            <th class="text-right">Amount</th>
                        </thead>
                        <tbody>
                            <tr v-for="item in transactionDetails" :key="item.id">
                                <td class="v-align">{{ item.Particulars }}</td>
                                <td class="v-align text-right">{{ toMoney(parseFloat(item.Amount)) }}</td>
                            </tr>
                        </tbody>
                    </table>
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

import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'

export default {
    name : 'ViewStudent.view-student',
    components : {
        FlatPickr,
        Swal,
        'pagination' : Bootstrap4Pagination,
        jquery,
        FullCalendar,
    },
    data() {
        return {
            moment : moment,
            baseURL : axios.defaults.baseURL,
            filePath : axios.defaults.filePath,
            imgPath : axios.defaults.imgsPath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            studentId : document.querySelector("meta[name='student-id']").getAttribute('content'),
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
            studentData : {},
            subjects : [],
            payables : [],
            //payment history modal
            activePayable : {},
            paymentFor : '',
            payableTransactionHistory : [],
            tuitionsBreakdown : [],
            isModalTuition : false,
            allTransactions : [],
            payableInclusions : [],
            calendarOptions: {
                plugins: [dayGridPlugin],
                initialView: 'dayGridMonth',
                selectable: true,
                height: 650,
                width: 700,
                eventOrderStrict : false,
                themeSystem: 'bootstrap',
                headerToolbar: {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'dayGridMonth'
                },
                events: []
            },
            attendanceData : [],
            scholarships : [],
            transactionDetails : [],
            detailedTransactions : [],
            selectedFile: null,
            imagePreview : null,
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
        validateNullStrings(string) {
            return this.isNull(string) ? '' : string
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
        getStudentDetails() {
            axios.get(`${ this.baseURL }/students/get-student-details`, {
                params : {
                    StudentId : this.studentId,
                }
            })
            .then(response => {
                this.studentData = response.data.StudentDetails
                this.subjects = response.data.Subjects
                this.payables = response.data.TuitionPayables
                this.scholarships = response.data.Scholarships
                // concat other payables
                this.payables = this.payables.concat(response.data.OtherPayables)

                this.imagePreview = `${ this.imgPath }student-imgs/${ this.studentId }.jpg`
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
        },
        getTotalBalance() {
            var total = 0
            for (let i=0; i<this.payables.length; i++) {
                var balance = this.isNull(this.payables[i]) ? 0 : (this.isNull(this.payables[i].Balance) ? 0 : (this.payables[i].Balance.length < 1 ? 0 : parseFloat(this.payables[i].Balance)))
                total += balance
            }

            return total
        },
        getActivePayable(id) {
            this.activePayable = this.payables.find(obj => obj.id === id)
        },
        transactionHistory(id) {
            this.getActivePayable(id)
            this.paymentFor = this.activePayable.PaymentFor
            this.isModalTuition = this.activePayable.Category === 'Tuition Fees' ? true : false
            this.getTransactionHistory(id)

            // init modal
            let modalElement = this.$refs.modalTransactionHistory
            $(modalElement).modal('show')
        },
        getTransactionHistory(payableId) {
            axios.get(`${ this.baseURL }/transactions/get-transactions-from-payable`, {
                params : {
                    PayableId : payableId,
                }
            })
            .then(response => {
                this.tuitionsBreakdown = response.data.TuitionLogs
                this.payableTransactionHistory = response.data.Transactions
                this.payableInclusions = response.data.PayableInclusions
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting transaction history data!'
                })
            })
        },
        getAllTransactions() {
            axios.get(`${ this.baseURL }/transactions/get-transaction-history`, {
                params : {
                    StudentId : this.studentId,
                }
            })
            .then(response => {
                this.allTransactions = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting all transaction history data!'
                })
            })
        },
        scholarshipWizzard() {
            window.location.href = this.baseURL + '/student_scholarships/scholarship-wizzard/' + this.studentId + '/student-view'
        },
        getBarcodeAttendace() {
            axios.get(`${ this.baseURL }/barcode_attendances/get-student-logs`, {
                params : {
                    StudentId : this.studentId,
                }
            })
            .then(response => {
                this.attendanceData = response.data

                var event = []
                for(let i=0; i<this.attendanceData.length; i++) {
                    event.push({
                        title : this.attendanceData[i].PunchType,
                        start : moment(this.attendanceData[i].created_at).format("YYYY-MM-DD HH:mm:ss")
                    })
                }

                this.calendarOptions.events = event
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
        },
        removeScholarship(id) {
            Swal.fire({
                title: "Remove Scholarship?",
                showCancelButton: true,
                text : 'You can always re-add a scholarship in the scholarship wizzard.',
                confirmButtonText: "Proceed Removal",
                confirmButtonColor : '#3a9971'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ this.baseURL }/student_scholarships/remove-scholarship`, {
                        _token : this.token,
                        id : id,
                    }) 
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Scholarship removed!'
                        })
                        this.scholarships = this.scholarships.filter(obj => obj.id !== id)
                        location.reload()
                    })
                    .catch(error => {
                        console.log(error.response)
                        Swal.fire({
                            icon : 'error',
                            text : error.response.data
                        })
                    })
                }
            })
        },
        deleteStudent() {
            Swal.fire({
                title: "Remove Student?",
                showCancelButton: true,
                text : 'Deleting this student will also delete all his data. Proceed with caution.',
                confirmButtonText: "Proceed Removal",
                confirmButtonColor : '#3a9971'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`${ this.baseURL }/students/` + this.studentId, {
                        params : {
                            _token : this.token,
                            id : this.studentId,
                        }
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Student removed!'
                        })
                        window.location.href = this.baseURL + '/students'
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error getting all transaction history data!'
                        })
                    })
                }
            })
        },
        printTuitionLedger() {
            window.location.href = `${ this.baseURL }/transactions/print-tuition-ledger/${ this.studentId }/${ this.activePayable.SchoolYear }`
        },
        showTransactionDetails(id) {
            // get transaction details
            axios.get(`${ this.baseURL }/transactions/fetch-transaction-details`, {
                params : {
                    TransactionId : id
                }
            })
            .then(response => {
                this.transactionDetails = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting transaction details'
                })
            })

            // init modal
            let modalElement = this.$refs.modalAllTransactions
            $(modalElement).modal('show')
        },
        getDetailedTransactions() {
            axios.get(`${ this.baseURL }/transactions/fetched-detailed-transactions-per-student`, {
                params : {
                    StudentId : this.studentId
                }
            })
            .then(response => {
                this.detailedTransactions = response.data
            })
            .catch(error => {
                console.log(error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting detailed transactions'
                })
            })
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
            reader.onload = e => {
                this.imagePreview = e.target.result // Update image preview
            }

            this.uploadImage()
        },
        async uploadImage() {
            if (!this.selectedFile) {
                alert('Please select a file');
                return;
            }

            const formData = new FormData();
            formData.append('image', this.selectedFile)
            formData.append('id', this.studentId)

            try {
                const response = await axios.post(`${ this.baseURL }/students/upload-student-profile`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                console.log('Upload successful:', response.data)
                this.toast.fire({
                    icon : 'success',
                    text : 'Profile picture uploaded and updated!'
                })
            } catch (error) {
                console.error('Error uploading the file:', error.response)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error uploading profile picture!'
                })
            }
        },
        handleImageError(event) {
            this.imagePreview = `${ this.imgPath }prof-img.png`; // Replace with a fallback image URL
        },
    }, 
    created() {
    },
    mounted() {
        this.getStudentDetails()
        this.getAllTransactions()
        this.getDetailedTransactions()

        this.getBarcodeAttendace()
    }
}

</script>