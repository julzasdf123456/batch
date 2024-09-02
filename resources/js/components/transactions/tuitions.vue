<template>
    <div class="row">
        <!-- DETAILS -->
        <div class="col-lg-3 col-md-12">
            <!-- student info -->
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <div class="mb-3">
                        <div style="display: inline-block; vertical-align: middle;">
                            <img :src="imgPath + 'prof-img.png'" style="width: 46px; margin-right: 25px;" class="img-circle" alt="profile">
                        </div>
                        <div style="display: inline-block; height: inherit; vertical-align: middle;">
                            <h4 class="no-pads"><strong>{{ studentData.LastName + ', ' + studentData.FirstName + (isNull(studentData.MiddleName) ? '' : (' ' + studentData.MiddleName + ' ')) + (isNull(studentData.Suffix) ? '' : studentData.Suffix) }}</strong></h4>
                            <span class="text-muted text-sm">ID: <strong>{{ studentData.id }}</strong></span>
                            <a :href="baseURL + '/students/' + studentId" target="_blank" class="float-right"><i class="fas fa-share"></i></a>
                        </div>
                    </div>

                    <table class="table table-hover table-sm">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">Address</td>
                                <td class="v-align">{{ (isNull(studentData.Sitio) ? '' : studentData.Sitio) + ', ' + studentData.BarangaySpelled + ', ' + studentData.TownSpelled }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">Grade Level</td>
                                <td class="v-align">{{ isNull(studentData.Year) ? '-' : (studentData.Year + ' - ' + studentData.Section) }}</td>
                            </tr>
                            <tr v-if="studentData.Year==='Grade 11' | studentData.Year==='Grade 12'">
                                <td class="text-muted v-align">From</td>
                                <td class="v-align"><span class="badge" :class="studentData.FromSchool==='Private' ? 'bg-gray' : 'bg-warning'">{{ isNull(studentData.FromSchool) ? '-' : (studentData.FromSchool + ' School') }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">{{ studentData.Year==='Grade 11' | studentData.Year==='Grade 12' ? 'VMS' : 'ESC' }} Scholar</td>
                                <td class="v-align"><span class="badge" :class="studentData.ESCScholar==='No' ? 'bg-gray' : 'bg-success'">{{ isNull(studentData.ESCScholar) ? 'No' : studentData.ESCScholar }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button @click="addEsc(studentData.Year==='Grade 11' | studentData.Year==='Grade 12' ? 'VMS' : 'ESC')" class="btn btn-xs btn-default">Add {{ studentData.Year==='Grade 11' | studentData.Year==='Grade 12' ? 'VMS' : 'ESC' }}</button>
                    <button @click="removeEsc(studentData.Year==='Grade 11' | studentData.Year==='Grade 12' ? 'VMS' : 'ESC')" class="btn btn-xs btn-default ml-1">Remove {{ studentData.Year==='Grade 11' | studentData.Year==='Grade 12' ? 'VMS' : 'ESC' }}</button>
                    <button @click="transactionHistory()" title="View Transaction History" class="btn btn-sm btn-default float-right"><i class="fas fa-history"></i></button>
                </div>
            </div>

            <!-- tuition inclusions -->
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <span class="card-title text-muted">Breakdown</span>

                    <div class="card-tools">
                        <button class="btn btn-default btn-sm" @click="addPayableBreakdown()">Add</button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover">
                        <tbody>
                            <tr v-for="inc in tuitionInclusions">
                                <td>
                                    {{ inc.ItemName }}

                                    <i v-if="inc.NotDeductedMonthly === 'Yes' ? true : false" class="fas fa-exclamation-triangle ico-tab-left-mini text-warning" title="Not distributed monthly, should be paid once."></i>
                                </td>
                                <td class="text-right">{{ toMoney(parseFloat(inc.Amount)) }}</td>
                                <td style="width: 30px;" class="text-right v-align">
                                    <button v-if="inc.NotDeductedMonthly === 'Paid' ? false : true" @click="removeTuitionInclusion(inc.id)" title="Remove this item from tuition" class="btn btn-link-muted"><i class="fas fa-times"></i></button>
                                    <i v-if="inc.NotDeductedMonthly === 'Paid' ? true : false" class="fas fa-check-circle text-muted text-sm pr-3" title="Paid separately"></i>
                                </td> 
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- classes -->
            <div class="card shadow-none collapsed-card">
                <div class="card-header border-0">
                    <span class="text-muted">Subjects Taken</span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-hover table-sm">
                        <tbody>
                           <tr v-for="subject in subjects" :key="subject.id">
                                <td class="v-align">{{ subject.Subject }}</td>
                           </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PAYABLES -->
        <div class="col-lg-5 col-md-12">
            <!-- TUITION FEE SELECTION -->
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <p class="text-muted no-pads">Select Which Tuition Fee Pay</p>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <tbody>
                            <tr v-for="payable in payables" :key="payable.id" class="pointer">
                                <td class="v-align">
                                    <input type="radio" :id="payable.id" :value="payable.id" v-model="paymentFor" @change="getActivePayable(paymentFor)" class="custom-radio-sm pointer">
                                    <label :for="payable.id" class="custom-radio-label-sm pointer no-pads">{{ payable.PaymentFor }}</label>
                                </td>
                                <td class="v-align text-right"><strong>{{ toMoney(parseFloat(payable.AmountPayable)) }}</strong></td>
                                <td class="v-align text-right text-primary"><strong>{{ isNull(payable.AmountPaid) ? '-' : toMoney(parseFloat(payable.AmountPaid)) }}</strong></td>
                                <td class="v-align text-right text-danger"><strong>{{ toMoney(parseFloat(payable.Balance)) }}</strong></td>
                            </tr>
                            <tr>
                                <td class="v-align" colspan="3"><strong>TOTAL PAYABLES</strong></td>
                                <td class="v-align text-right text-danger"><h4><strong>{{ toMoney(getTotalPayables()) }}</strong></h4></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MISCELLANEOUS ADDITIONALS -->
            <div class="card shadow-none">
                <div class="card-header border-0">
                    <p class="text-muted no-pads">Add Miscellaneous Payables</p>
                    <div style="display: flex; gap: 10px;">
                        <select v-model="miscSelected" class="form-control form-control-sm" @change="addPayable">
                            <option v-for="misc in miscPayables" :value="misc.id">{{ misc.Payable }}</option>
                        </select>
                        <button @click="addPayable()" class="btn btn-primary btn-sm">Add</button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-sm table-bordered mt-3">
                        <thead>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            <tr v-for="item in payableItems" :key="item.id">
                                <td style="word-wrap:break-word;">
                                    <!-- {{ item.Payable }} -->
                                    <textarea :ref="'payable-name-' + item.id" class="table-input" :class="tableInputTextColor" v-model="item.Payable" @keyup="inputEnter(item.Payable, item.Price, item.Quantity, item.id)" @keyup.enter="inputEnter(item.Payable, item.Price, item.Quantity, item.id, 'enter')" @blur="inputEnter(item.Payable, item.Price, item.Quantity, item.id)" type="text"></textarea>
                                </td>
                                <td style="max-width: 100px;">
                                    <input :ref="'payable-' + item.id" class="table-input text-right" :class="tableInputTextColor" v-model="item.Price" @keyup="inputEnter(item.Payable, item.Price, item.Quantity, item.id)" @keyup.enter="inputEnter(item.Payable, item.Price, item.Quantity, item.id, 'enter')" @blur="inputEnter(item.Payable, item.Price, item.Quantity, item.id)" type="number" step="any"/>
                                </td>
                                <td style="max-width: 60px;">
                                    <input class="table-input text-right" :class="tableInputTextColor" v-model="item.Quantity" @keyup="inputEnter(item.Payable, item.Price, item.Quantity, item.id)" @keyup.enter="inputEnter(item.Payable, item.Price, item.Quantity, item.id, 'enter')" @blur="inputEnter(item.Payable, item.Price, item.Quantity, item.id)" type="number" step="any"/>
                                </td>
                                <td class="text-right" style="max-width: 180px;">
                                    <strong>{{ toMoney(parseFloat(item.TotalAmount)) }}</strong>
                                    <button class="btn btn-sm" title="Remove" @click="removeItem(item.id)"><i class="fas fa-times-circle text-danger"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">Total Miscellaneous</td>
                                <td class="text-right"><strong>{{ toMoney(totalMiscAmount) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <span class="text-muted text-sm"><strong>NOTE: </strong>These miscellaneous payments will not be added to the monthly payments of the students. If you wish to add an amount to the monthly payment, use the <strong>ADD</strong> feature in the `Breakdown` card instead.</span>
                </div>
            </div>

            <!-- payable breakdown -->
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <span class="text-muted">Tuition Monthly Payables</span>
                    <table class="table table-sm table-hover table-borderless">
                        <thead>
                            <th>Month</th>
                            <th class="text-right">Amount Paid</th>
                            <th class="text-right">Balance</th>
                        </thead>
                        <tbody>
                            <tr v-for="month in tuitionMonths" :key="month.id">
                                <td>
                                    <div class="input-group-radio-sm">
                                        <input @change="sumTotalTuitions()" type="checkbox" :id="month.id" :value="month" class="custom-radio-sm" v-model="selectedMonths">
                                        <label :for="month.id" class="custom-radio-label-sm">{{ moment(month.ForMonth).format('MMMM YYYY') }}</label>
                                    </div>
                                </td>
                                <td class="text-right">
                                    {{ isNull(month.AmountPaid) ? '-' : toMoney(parseFloat(month.AmountPaid)) }}
                                </td>
                                <td class="text-right">
                                    <strong class="text-danger">{{ toMoney(parseFloat(month.Balance)) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-muted">Total Selected</td>
                                <td class="text-right text-muted">{{ toMoney(totalSelectedTuitions) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <div class="col-lg-4 col-md-12">
            <!-- <div style="border-bottom: dotted 3px #aeaeae; margin-bottom: 18px; padding-bottom: 10px;">
                <span class="text-muted">Transact</span>
                <span class="float-right"><i class="fas fa-dollar-sign text-muted text-right"></i></span>
            </div> -->
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <div class="card shadow-none m-0">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-borderless table-hover">
                                <tbody>
                                    <tr>
                                        <td class="v-align"><strong>ORNumber</strong></td>
                                        <td class="v-align">
                                            <input type="number" class="form-control" placeholder="OR Number..." autofocus v-model="orNumber">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="v-align"><strong>Details</strong></td>
                                        <td class="v-align">
                                            <input type="text" class="form-control" placeholder="Payment details..." autofocus v-model="paymentDetails">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Cash -->
                    <div class="card shadow-none m-0">
                        <div class="card-header border-0">
                            <span class="card-title"><strong>Cash</strong></span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="ml-4">
                                <input type="number" class="form-control" ref="cashInput" placeholder="Cash amount..." autofocus v-model="cashAmount" @keyup="getTotalPayments" @keydown.enter="handleEnterKey">
                            </div>
                        </div>
                    </div>
                    <!-- Check -->
                    <div class="card shadow-none collapsed-card m-0">
                        <div class="card-header border-0">
                            <span class="card-title"><strong>Check</strong></span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="ml-4 row">
                                <div class="col-lg-7 col-md-12">
                                    <input title="Check number" type="text" class="form-control" placeholder="Check number..." autofocus v-model="checkNumber">
                                </div>
                                <div class="col-lg-5 col-md-12">
                                    <input title="Bank" type="text" class="form-control" placeholder="Bank..." autofocus v-model="checkBank">
                                </div>
                                <div class="col-lg-12 mt-1">
                                    <input title="Check amount" type="number" class="form-control" placeholder="Check amount..." autofocus v-model="checkAmount" @keyup="getTotalPayments" @keydown.enter="handleEnterKey">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Bank Transfers/Digital -->
                    <div class="card shadow-none collapsed-card m-0">
                        <div class="card-header border-0">
                            <span class="card-title"><strong>Bank Transfers/Digital Payments</strong></span>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="ml-4 row">
                                <div class="col-lg-7 col-md-12">
                                    <input title="Check number" type="text" class="form-control" placeholder="Transaction number..." autofocus v-model="digitalNumber">
                                </div>
                                <div class="col-lg-5 col-md-12">
                                    <input title="Bank/Digital Wallets/Source" type="text" class="form-control" placeholder="Bank/Digital Wallets/Source..." autofocus v-model="digitalBank">
                                </div>
                                <div class="col-lg-12 mt-1">
                                    <input title="Amount" type="number" class="form-control" placeholder="Amount..." autofocus v-model="digitalAmount" @keyup="getTotalPayments" @keydown.enter="handleEnterKey">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divider mt-3"></div>

                    <table class="table table-hover table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td class="text-muted v-align">MINIMUM PAYABLE</td>
                                <td class="v-align text-right text-danger">
                                    <h4 class="no-pads"><strong>{{ toMoney(totalMinAmountPayable) }}</strong></h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">AMOUNT FOR TUITION</td>
                                <td class="v-align text-right text-muted">{{ toMoney(tuitionPaymentAmount) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">TOTAL PAYMENT</td>
                                <td class="v-align text-right text-success"><h1 class="no-pads"><strong>{{ toMoney(totalPayments) }}</strong></h1></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary float-right" @click="transact">Transact Payment <i class="fas fa-check-circle ico-tab-mini-left"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="right-bottom" style="bottom: 0px !important;">
        <p id="msg-display" class="msg-display shadow" style="font-size: .8em; z-index: 99999999;"><i class="fas fa-check-circle ico-tab-mini text-success"></i>Amount tendered should not be less than the total amount payable!</p>
    </div>

    <!-- add breakdown -->
    <div ref="modalAddBreakdown" class="modal fade" id="modal-add-breakdown" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Add Subject Breakdown</span>
                </div>
                <div class="modal-body table-responsive">
                    <div class="form-group">
                        <label for="ItemPublic">Item to Include</label>
                        <input type="text" class="form-control" name="ItemPublic" id="ItemPublic" v-model="additionalPayableItem" style="width: 100%;" required placeholder="Tuition Fee, Books, Uniform, etc...">
                     </div>
      
                     <div class="form-group">
                        <label for="AmountPublic">Amount</label>
                        <input type="number" step="any" class="form-control" name="AmountPublic" v-model="additionalPayableAmount" id="AmountPublic" style="width: 100%;" required placeholder="0.0">
                    </div>
                    
                    <!-- <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" class="custom-control-input" id="distribute" v-model="additionalDistribute">
                        <label style="font-weight: normal;" class="custom-control-label" for="distribute" id="distributeLabel">Distribute Monthly</label>
                        <br>
                        <span class="text-muted text-sm">Turning this ON will split the amount for the next months, otherwise it will only be added in the current payable.</span>
                    </div>     -->
                    <span class="text-muted text-sm"><strong>NOTE that all items added using this will be deducted monthly automatically. If you wish to add a one-time payment miscellaneous, add it in the `Add Miscellaneous Payables` card instead.</strong></span>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-primary" @click="saveTuitionInclusion()"><i class="fas fa-check ico-tab-mini"></i>Proceed Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- payable history -->
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
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tuitions-tab" data-toggle="pill" href="#tuitions-content" role="tab" aria-controls="tuitions-content" aria-selected="false">Tuition Fee</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="transactions-tab" data-toggle="pill" href="#transactions-content" role="tab" aria-controls="transactions-content" aria-selected="false">All Transactions History</a>
                        </li>
                    </ul>

                    <!-- TAB BODY -->
                    <div class="tab-content" id="custom-tabs-three-tabContent">
                        <!-- TUITION FEES -->
                        <div class="tab-pane fade active show" id="tuitions-content" role="tabpanel" aria-labelledby="tuitions-tab">
                            <div class="mt-3">
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

                        <!-- ALL TRANSACTIONS -->
                        <div class="tab-pane fade" id="transactions-content" role="tabpanel" aria-labelledby="transactions-tab">
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
    name : 'Tuitions.tuitions',
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
            imgPath : axios.defaults.imgsPath,
            colorProfile : document.querySelector("meta[name='color-profile']").getAttribute('content'),
            userId : document.querySelector("meta[name='user-id']").getAttribute('content'),
            studentId : document.querySelector("meta[name='student-id']").getAttribute('content'),
            token : document.querySelector("meta[name='csrf-token']").getAttribute('content'),
            school : document.querySelector("meta[name='school']").getAttribute('content'),
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
            activePayable : {},
            paymentType : 'Cash',
            cashAmount : '',
            checkNumber : '',
            checkBank : '',
            checkAmount : '',
            digitalNumber : '',
            digitalBank : '',
            digitalAmount : '',
            totalPayables : 0.0,
            totalPayments : 0.0,
            change : 0.0,
            changeOfPeriod : 0.0,
            orNumber : null,
            paymentFor : '',
            paymentDetails : '',
            period : '',
            periodPayable : 0,
            paidAmount : 0,
            tuitionMonths : [],
            selectedMonths : [],
            totalSelectedTuitions : 0.0,
            tuitionInclusions : [],
            additionalPayableItem : '',
            additionalPayableAmount : 0.0,
            additionalDistribute : false,
            minAmountPayable : 0.0,
            tuitionPaymentAmount : 0.0,
            paymentFor : '',
            tuitionsBreakdown : [],
            isModalTuition : false,
            payableTransactionHistory : [],
            payableInclusions : [],
            detailedTransactions : [],
            // MISCELLANEOUS
            miscPayables : [],
            miscSelected : '',
            payableItems : [],
            totalMiscAmount : 0,
            totalMinAmountPayable : 0,
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

                // clean payables
                this.payables = this.payables.filter(obj => obj.Balance !== null && parseFloat(obj.Balance) > 0)

                // pre-select first payable
                if (this.payables.length > 0) {
                    this.paymentFor = this.payables[0].id
                    this.getActivePayable(this.payables[0].id)
                }
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
        },
        nextOR() {
            axios.get(`${ this.baseURL }/transactions/get-next-or`, {
                params : {
                    UserId : this.userId,
                }
            })
            .then(response => {
                this.orNumber = response.data
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting OR Number'
                })
            })
        },
        getTotalPayables() {
            var total = 0
            if (this.isNull(this.payables)) {
                return 0
            }

            for (let i=0; i<this.payables.length; i++) {
                var balance = this.isNull(this.payables[i].Balance) ? 0 : parseFloat(this.payables[i].Balance)
                total += balance
            }

            return total
        },
        focusCash() {
            this.$refs.cashInput.focus()
        },
        getActivePayable(id) {
            this.activePayable = this.payables.find(obj => obj.id === id)

            this.paymentDetails = this.activePayable.PaymentFor
            this.totalPayables = this.isNull(this.activePayable) ? 0 : parseFloat(this.activePayable.Balance)
            this.focusCash()

            // GET TUITIONS BREAKDOWN
            axios.get(`${ this.baseURL }/classes/get-tuitions-breakdown`, {
                params : {
                    PayableId : this.activePayable.id,
                }
            })
            .then(response => {
                this.tuitionMonths = response.data

                // this.periodPayable = this.isNull(this.activePayable) ? 0 : this.round((parseFloat(this.activePayable.AmountPayable)/10))
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting payable data!'
                })
            })

            // GET PAYABLE INCLUSIONS
            axios.get(`${ this.baseURL }/transactions/get-payable-inclusions`, {
                params : {
                    PayableId : this.activePayable.id,
                }
            })
            .then(response => {
                this.tuitionInclusions = response.data

                this.minAmountPayable = 0
                for (let i=0; i<this.tuitionInclusions.length; i++) {
                    if (this.tuitionInclusions[i].NotDeductedMonthly === 'Yes') {
                        this.minAmountPayable += parseFloat(this.tuitionInclusions[i].Amount)
                    } 
                }

                this.validateMinPayable()
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting payable inclusions!'
                })
            })
        }, 
        sumTotalTuitions() {
            this.totalSelectedTuitions = 0

            for(let i=0; i<this.selectedMonths.length; i++) {
                this.totalSelectedTuitions += parseFloat(this.selectedMonths[i].Balance)
            }
        },
        getTotalPayments() {
            var cash = (this.cashAmount.length < 1 ? 0 : parseFloat(this.cashAmount))
            var check = (this.checkAmount.length < 1 ? 0 : parseFloat(this.checkAmount))
            var digital = (this.digitalAmount.length < 1 ? 0 : parseFloat(this.digitalAmount))
            
            // total payments
            this.totalPayments = cash + check + digital

            if (this.totalPayments > this.getTotalPayables()) {
                this.totalPayments = this.getTotalPayables()
            }

            // change
            this.change = this.totalPayments - this.totalPayables
            this.changeOfPeriod = this.totalPayments - this.periodPayable
            this.changeOfPeriod = this.changeOfPeriod > 0 ? 0 : this.changeOfPeriod

            // amount for tuition
            if (this.totalPayments == 0) {
                this.tuitionPaymentAmount = 0
            } else {
                this.tuitionPaymentAmount = this.totalPayments - this.totalMinAmountPayable
            }

            // try preselect tuitions
            var monthPayable = parseFloat(this.activePayable.Balance) / this.tuitionMonths.length
            var indices = Math.ceil(this.tuitionPaymentAmount / monthPayable)
            console.log(this.activePayable.Balance)
            this.selectedMonths = []
            for (let i=0; i<indices; i++) {
                this.selectedMonths.push(this.tuitionMonths[i])
            }

            this.sumTotalTuitions()
        },
        handleEnterKey(event) {
            event.preventDefault()
            if (event.key === 'Enter') {
                this.transact()
            }
        },
        transact() {
            if (this.isNull(this.activePayable)) {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please select a payable to pay!'
                })
            } else {
                if (this.paymentDetails.length < 1) {
                    this.toast.fire({
                        icon : 'info',
                        text : 'Please provide details for the payment!'
                    })
                } else {
                    if (this.orNumber.length < 1) {
                        this.toast.fire({
                            icon : 'info',
                            text : 'Please provide an OR Number for this transaction!'
                        })
                    } else {
                        if (this.totalPayments < this.minAmountPayable) {
                            this.toast.fire({
                                icon : 'info',
                                text : 'Please make sure the Amount Tendered is greater than the Minimum Payable Amount!'
                            })
                        } else {
                            // get amount paid based on the total paid amount of customer
                            var amountPaid = 0
                            if (this.totalPayments > this.totalPayables) {
                                amountPaid = this.totalPayables
                                this.remainingBalance = 0
                            } else {
                                amountPaid = this.totalPayments
                                this.remainingBalance = this.totalPayables - this.tuitionPaymentAmount
                            }

                            // begin transaction
                            Swal.fire({
                                title: "Confirm Transaction",
                                showCancelButton: true,
                                html: `
                                    <p style='text-align: left;'>Tuition payment summary:</p>
                                    <ul>
                                        <li style='text-align: left;'>Amount Paid: <h4 class='text-primary'>P ${ this.toMoney(this.totalPayments) }</h4></li>
                                        <li style='text-align: left;'>Amount Payable: <strong>P ${ this.toMoney(this.totalPayables) }</strong></li>
                                        <li style='text-align: left;'>Account Balance: <strong>P ${ this.toMoney(this.remainingBalance) }</strong></li>
                                    </ul>
                                    <p class='text-sm text-muted text-left no-pads mt-2'>Input Amount Paid</p>
                                    <input type="number" id="numberInput" class="form-control form-control-lg" aria-label="Amount Paid...">
                                    <p class='text-left mb-3'>CHANGE : <strong class='text-danger' id="change"></strong></p>
                                    <p style='text-align: left;'>Proceed payment transaction?</p>
                                `,
                                confirmButtonText: "Yes",
                                confirmButtonColor : '#3a9971',
                                didOpen: () => {
                                    const numberInput = Swal.getPopup().querySelector('#numberInput')
                                    const outputParagraph = Swal.getPopup().querySelector('#change')

                                    numberInput.focus();

                                    numberInput.addEventListener('input', () => {
                                        let subtractedValue = Number(numberInput.value) - this.totalPayments
                                        outputParagraph.innerText = `${subtractedValue}`;
                                    });

                                    numberInput.addEventListener('keydown', (event) => {
                                        if (event.key === 'Enter') {
                                            let subtractedValue = Number(numberInput.value) - this.totalPayments

                                            if (subtractedValue < 0) {
                                                event.preventDefault()
                                                this.showSaveFader()
                                                alert('Amount tendered should not be less than the total amount payable!')
                                            } else {
                                                // Trigger the confirm button
                                                Swal.clickConfirm();
                                            }
                                        }
                                    })
                                },
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    axios.post(`${ this.baseURL }/transactions/transact-tuition`, {
                                        _token : this.token,
                                        StudentId : this.studentId,
                                        PayableId : this.activePayable.id,
                                        cashAmount : this.cashAmount,
                                        checkNumber : this.checkNumber,
                                        checkBank : this.checkBank,
                                        checkAmount : this.checkAmount,
                                        digitalNumber : this.digitalNumber,
                                        digitalBank : this.digitalBank,
                                        digitalAmount : this.digitalAmount,
                                        totalPayables : this.totalPayables,
                                        totalPayments : this.totalPayments,
                                        ORNumber : this.orNumber,
                                        Details : this.paymentDetails,
                                        Period : this.period,
                                        PaidAmount : amountPaid,
                                        Balance : this.remainingBalance,
                                        TuitionBreakdowns : this.selectedMonths,
                                        AmountForTuition : this.tuitionPaymentAmount,
                                        MinimumAmountPayable : this.totalMinAmountPayable,
                                        AdditionalMiscellaneousItems : this.payableItems,
                                    }) 
                                    .then(response => {
                                        this.toast.fire({
                                            icon : 'success',
                                            text : 'Tuition successfully paid!'
                                        })
                                        if (this.school === 'SVI') {
                                            window.location.href = this.baseURL + '/transactions/print-tuition-svi/' + response.data
                                        } else if (this.school === 'HCA') {
                                            window.location.href = this.baseURL + '/transactions/print-tuition/' + response.data
                                        }
                                        
                                    })
                                    .catch(error => {
                                        console.log(error.response)
                                        Swal.fire({
                                            icon : 'error',
                                            text : 'Error performing transaction'
                                        })
                                    })
                                }
                            })
                        }
                    }
                }
            } 
        },
        showSaveFader() {
            var message = document.getElementById('msg-display');

            // Show the message
            message.style.opacity = 1;

            // Wait for 3 seconds
            setTimeout(function() {
                // Fade out the message
                message.style.opacity = 0;
            }, 1500);
        },
        removeTuitionInclusion(id) {
            Swal.fire({
                title : 'Confirm Removal',
                text: "Removing this item from the tuition breakdown will alsow change the total tuition payable of the student. Proceed with caution.",
                showCancelButton: true,
                confirmButtonText: "Proceed Remove",
                confirmButtonColor : '#e03822'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`${ this.baseURL }/transactions/remove-payable-inclusion`, {
                        _token : this.token,
                        id : id,
                    })
                    .then(response => {
                        this.toast.fire({
                            icon : 'success',
                            text : 'Tuition inclusion removed!'
                        })
                        location.reload()
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error removing tuition inclusions!'
                        })
                    })
                }
            })
        },
        addPayableBreakdown() {
            if (this.isNull(this.activePayable)) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please select payable particulars first!'
                })
            } else {
                let modalElement = this.$refs.modalAddBreakdown
                $(modalElement).modal('show')
            }
        },
        saveTuitionInclusion() {
            if (this.isNull(this.additionalPayableItem)) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please fill in an item name!'
                })
            } else {
                axios.post(`${ this.baseURL }/transactions/add-payable-inclusion`, {
                    _token : this.token,
                    ItemName : this.additionalPayableItem,
                    Amount : this.additionalPayableAmount,
                    PayableId : this.activePayable.id,
                    NotDeductedMonthly : null,
                })
                .then(response => {
                    this.toast.fire({
                        icon : 'success',
                        text : 'Tuition inclusion added!'
                    })
                    location.reload()
                })
                .catch(error => {
                    console.log(error.response)
                    this.toast.fire({
                        icon : 'error',
                        text : 'Error adding tuition inclusions!'
                    })
                })
            }
        },
        addEsc(type) {
            if (this.isNull(this.activePayable)) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please select payable particulars first!'
                })
            } else {
                Swal.fire({
                    title: "Confirmation",
                    text : `Adding an ESC/VMS grant to this student will change his/her tuition payables. All payment's previously transacted will still be credited. Proceed with caution.`,
                    showCancelButton: true,
                    confirmButtonText: "Proceed",
                    confirmButtonColor : '#3a9971'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`${ this.baseURL }/student_scholarships/apply-scholarship-from-cashier`, {
                            _token : this.token,
                            PayableId : this.activePayable.id,
                            StudentId : this.studentId,
                            SchoolYear : this.activePayable.SchoolYear,
                            Type : type,
                        })
                        .then(response => {
                            this.toast.fire({
                                icon : 'success',
                                text : 'Student added ' + type + ' scholarship!'
                            })
                            location.reload()
                        })
                        .catch(error => {
                            console.log(error.response)
                            this.toast.fire({
                                icon : 'error',
                                text : 'Error adding scholarship!'
                            })
                        })
                    }
                })
            }
        },
        removeEsc(type) {
            if (this.isNull(this.activePayable)) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please select payable particulars first!'
                })
            } else {
                Swal.fire({
                    title: "Confirmation",
                    text : `Removing an ESC/VMS grant to this student will change his/her tuition payables. All payment's previously transacted will still be credited. Proceed with caution.`,
                    showCancelButton: true,
                    confirmButtonText: "Proceed",
                    confirmButtonColor : '#3a9971'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`${ this.baseURL }/student_scholarships/remove-scholarship-from-cashier`, {
                            _token : this.token,
                            PayableId : this.activePayable.id,
                            StudentId : this.studentId,
                            SchoolYear : this.activePayable.SchoolYear,
                            Type : type,
                        })
                        .then(response => {
                            this.toast.fire({
                                icon : 'success',
                                text : type + ' scholarship grant removed from student!'
                            })
                            location.reload()
                        })
                        .catch(error => {
                            console.log(error.response)
                            this.toast.fire({
                                icon : 'error',
                                text : 'Error removing scholarship!'
                            })
                        })
                    }
                })
            }
        },
        transactionHistory() {
            if (this.isNull(this.activePayable)) {
                this.toast.fire({
                    icon : 'warning',
                    text : 'Please select payable particulars first!'
                })
            } else {
                this.paymentFor = this.activePayable.PaymentFor
                this.isModalTuition = this.activePayable.Category === 'Tuition Fees' ? true : false
                this.getTransactionHistory(this.activePayable.id)
                this.getDetailedTransactions()

                let modalElement = this.$refs.modalTransactionHistory
                $(modalElement).modal('show')
            }
            
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
        // MISCELLANEOUS PAYMENTS
        getMiscPayables() {
            axios.get(`${ this.baseURL }/transactions/get-misc-payables`)
            .then(response => {
                this.miscPayables = response.data

                this.miscPayables = this.miscPayables.filter(obj => !obj.Payable.includes('Tuition Fee'))
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting miscellaneous payables!'
                })
            })
        },
        addPayable() {
            const selected = this.miscPayables.find(obj => obj.id === this.miscSelected)

            if (!this.isNull(selected)) {
                const idtmp = this.generateUniqueId()
                this.payableItems.push({
                    id : idtmp,
                    Payable : selected.Payable,
                    Price : selected.DefaultAmount,
                    Quantity : 1,
                    TotalAmount : isNaN(selected.DefaultAmount) | this.isNull(selected.DefaultAmount) ? 0 : selected.DefaultAmount
                })
                this.$nextTick(() => {
                    this.$refs['payable-' + idtmp][0].focus()
                })
            } else {
                this.toast.fire({
                    icon : 'info',
                    text : 'Please select item before adding!'
                })
            }

            this.validateTotal()
        },
        removeItem(id) {
            this.payableItems = this.payableItems.filter(obj => obj.id !== id)

            this.toast.fire({
                icon : 'info',
                text : 'Item removed!'
            })
            this.validateTotal()
        },
        validateTotal() {
            this.totalMiscAmount = 0
            for (let i=0; i<this.payableItems.length; i++) {
                this.totalMiscAmount += parseFloat(this.payableItems[i].TotalAmount)
            }

            this.validateMinPayable()
        },
        validateMinPayable() {
            this.totalMinAmountPayable = this.minAmountPayable + this.totalMiscAmount
        },
        inputEnter(payable, price, qty, id, key=null) {
            var amount = parseFloat(price) * parseFloat(qty)

            this.payableItems = this.payableItems.map(obj => {
                if (obj.id === id) {
                    return { ...obj, Payable : payable, Price : price, Quantity : qty, TotalAmount : (isNaN(amount) ? 0 : amount) }
                }
                return obj
            })

            if (!this.isNull(key) && key==='enter') {
                this.$refs.cashInput.focus()
            }

            this.validateTotal()
        },
    },
    created() {
    },
    mounted() {
        this.getStudentDetails()
        this.nextOR()
        this.getMiscPayables()
    }
}

</script>