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
                            <tr>
                                <td colspan="2">
                                    <a title="View student" :href="baseURL + '/students/' + studentId" target="_blank"><i class="fas fa-share"></i></a>
                                    <button class="btn btn-xs btn-default float-right" @click="showHistory()">Transaction History</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- present tuition breakdown -->
            <div class="card shadow-none" v-if="tuitionHeaderCardShown">
                <div class="card-header">
                    <span class="text-muted text-sm">Active Tuition Fee</span>
                    <br>
                    <strong>{{ isNull(tuitionFeePayable) ? 'None' : tuitionFeePayable.PaymentFor }}</strong>

                    <br>

                    <button class="btn btn-xs btn-default" @click="showPayableHistory()">View</button>
                    <button class="btn btn-xs btn-success ml-1" @click="addPayableBreakdown()">Add</button>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-borderless">
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
                            <tr>
                                <td class="text-success text-sm" style="border-top: 1px solid #adadad;">Discount</td>
                                <td class="text-right text-sm" style="border-top: 1px solid #adadad;">{{ isNull(tuitionFeePayable) ? '-' : (isNull(tuitionFeePayable) ? '0' : ('-' + toMoney(parseFloat(tuitionFeePayable.DiscountAmount)))) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-success text-sm">Amnt. Paid</td>
                                <td class="text-right text-sm">{{ isNull(tuitionFeePayable) ? '-' : (isNull(tuitionFeePayable) ? '0' : ('-' + toMoney(parseFloat(tuitionFeePayable.AmountPaid)))) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-danger" style="border-top: 1px solid #adadad;">Balance</td>
                                <td class="text-right" style="border-top: 1px solid #adadad;"><strong>{{ isNull(tuitionFeePayable) ? '-' : (isNull(tuitionFeePayable) ? '0' : (toMoney(parseFloat(tuitionFeePayable.Balance)))) }}</strong></td>
                                <td></td>
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
        <div class="col-lg-6 col-md-12">
            <span class="text-muted">Select Miscellaneous Payables</span>
            <div style="display: flex; gap: 10px;">
                <select v-model="miscSelected" class="form-control" @change="addPayable">
                    <option v-for="misc in miscPayables" :value="misc.id">{{ misc.Payable }}</option>
                </select>
                <button @click="addPayable()" class="btn btn-primary">Add</button>
            </div>

            <table class="table table-hover table-bordered mt-3">
                <thead>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total Amount</th>
                </thead>
                <tbody>
                    <tr v-for="item in payableItems" :key="item.id">
                        <td>
                            {{ item.Payable }}
                        </td>
                        <td>
                            <input :ref="'payable-' + item.id" class="table-input text-right" :class="tableInputTextColor" v-model="item.Price" @keyup="inputEnter(item.Price, item.Quantity, item.id)" @keyup.enter="inputEnter(item.Price, item.Quantity, item.id, 'enter')" @blur="inputEnter(item.Price, item.Quantity, item.id)" type="number" step="any"/>
                        </td>
                        <td>
                            <input class="table-input text-right" :class="tableInputTextColor" v-model="item.Quantity" @keyup="inputEnter(item.Price, item.Quantity, item.id)" @keyup.enter="inputEnter(item.Price, item.Quantity, item.id, 'enter')" @blur="inputEnter(item.Price, item.Quantity, item.id)" type="number" step="any"/>
                        </td>
                        <td class="text-right">
                            <strong>{{ toMoney(parseFloat(item.TotalAmount)) }}</strong>
                            <button class="btn btn-sm" title="Remove" @click="removeItem(item.id)"><i class="fas fa-times-circle text-danger"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-">
                <p class="text-muted text-right">Total Amount Due</p>
                <h2 class="text-danger text-right">P <strong>{{ toMoney(totalAmountDue) }}</strong></h2>
            </div>
        </div>

        <!-- FORM -->
        <div class="col-lg-3 col-md-12">
            <div class="card shadow-none">
                <div class="card-body table-responsive">
                    <div class="card shadow-none m-0">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-sm table-borderless table-hover">
                                <tbody>
                                    
                                    <tr>
                                        <td class="v-align"><strong>OR Number</strong></td>
                                        <td class="v-align">
                                            <input type="number" class="form-control" placeholder="OR Number..." autofocus v-model="orNumber">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="v-align"><strong>OR Date</strong></td>
                                        <td class="v-align">
                                            <flat-pickr v-model="orDate" :config="pickerOptions" class="form-control" :readonly="false"></flat-pickr>
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
                            <div class="mx-2">
                                <input type="number" class="form-control" ref="cashInput" placeholder="Cash amount..." autofocus v-model="cashAmount" @keyup="getTotalPayments()" @keydown.enter="handleEnterKey">
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
                            <div class="mx-2 row">
                                <div class="col-lg-7 col-md-12">
                                    <input title="Check number" type="text" class="form-control" placeholder="Check number..." autofocus v-model="checkNumber">
                                </div>
                                <div class="col-lg-5 col-md-12">
                                    <input title="Bank" type="text" class="form-control" placeholder="Bank..." autofocus v-model="checkBank">
                                </div>
                                <div class="col-lg-12 mt-1">
                                    <input title="Check amount" type="number" class="form-control" placeholder="Check amount..." autofocus v-model="checkAmount" @keyup="getTotalPayments()" @keydown.enter="handleEnterKey">
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
                            <div class="mx-2 row">
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
                                <td class="text-muted v-align">TOTAL PAYMENT</td>
                                <td class="v-align text-right text-success"><h1 class="no-pads">{{ toMoney(totalPayments) }}</h1></td>
                            </tr>
                            <tr>
                                <td class="text-muted v-align">
                                    TOTAL AMOUNT PAYABLE
                                    <br>
                                    <span class="text-muted text-sm">Change</span>
                                </td>
                                <td class="v-align text-right text-danger">
                                    <h4 class="no-pads">{{ toMoney(totalAmountDue) }}</h4>
                                    <span class="text-info text-sm">{{ toMoney(change) }}</span>
                                </td>
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

    <div ref="modalShowHistory" class="modal fade" id="modal-selection-transfer" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <span>Transaction History</span>
                </div>
                <div class="modal-body table-responsive">
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
                <div class="modal-footer justify-content-between">
                    
                </div>
            </div>
        </div>
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
                                                <h1 class="text-primary">₱ {{ isNull(tuitionFeePayable.AmountPayable) ? '-' : toMoney(parseFloat(tuitionFeePayable.AmountPayable)) }}</h1>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <p class="text-muted text-sm no-pads">Payable <i class="fas fa-plus-circle"></i></p>
                                                        <h4 class="text-muted">₱ {{ isNull(tuitionFeePayable.Payable) ? '-' : toMoney(parseFloat(tuitionFeePayable.Payable)) }}</h4>
                                                    </div>
                                                    
                                                    <div class="col-lg-6">
                                                        <p class="text-muted text-sm no-pads">Discount <i class="fas fa-minus-circle"></i></p>
                                                        <h4 class="text-muted">₱ {{ isNull(tuitionFeePayable.DiscountAmount) ? '-' : toMoney(parseFloat(tuitionFeePayable.DiscountAmount)) }}</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="col-lg-6">
                                                <p class="text-muted text-right text-sm no-pads">Balance <i class="fas fa-dollar-sign"></i></p>
                                                <h1 class="text-right" :class="isNull(tuitionFeePayable.Balance) ? 'text-success' : (tuitionFeePayable.Balance <= 0 ? 'text-success' : 'text-danger')">₱ {{ isNull(tuitionFeePayable.Balance) ? '0.00' : toMoney(parseFloat(tuitionFeePayable.Balance)) }}</h1>

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <p class="text-muted text-right text-sm no-pads">Total Amount Paid <i class="fas fa-check-circle"></i></p>
                                                        <h4 class="text-muted text-right">₱ {{ isNull(tuitionFeePayable.AmountPaid) ? '-' : toMoney(parseFloat(tuitionFeePayable.AmountPaid)) }}</h4>
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
            paymentType : 'Cash',
            cashAmount : '',
            checkNumber : '',
            checkBank : '',
            checkAmount : '',
            digitalNumber : '',
            digitalBank : '',
            digitalAmount : '',
            miscPayables : [],
            miscSelected : '',
            payableItems : [],
            totalAmountDue : 0.0,
            totalPayments : 0.0,
            paymentDetails : 'Miscellaneous Payments',
            orNumber : '',
            change : 0,
            orDate : moment().format('YYYY-MM-DD'),
            allTransactions : [],
            // tuition fee embeded
            tuitionHeaderCardShown : false,
            tuitionFeePayable : {},
            tuitionInclusions : [],
            payableId : '',
            paymentFor : '',
            tuitionsBreakdown : [],
            payableTransactionHistory : [],
            payableInclusions : [],
            additionalPayableItem : '',
            additionalPayableAmount : 0.0,
            additionalDistribute : false,
            detailedTransactions : []
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
        inputEnter(price, qty, id, key=null) {
            var amount = parseFloat(price) * parseFloat(qty)

            this.payableItems = this.payableItems.map(obj => {
                if (obj.id === id) {
                    return { ...obj, Price : price, Quantity : qty, TotalAmount : amount }
                }
                return obj
            })

            if (!this.isNull(key) && key==='enter') {
                this.$refs.cashInput.focus()
            }

            this.validateTotal()
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
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting student data!'
                })
            })
        },
        getMiscPayables() {
            axios.get(`${ this.baseURL }/transactions/get-misc-payables`)
            .then(response => {
                this.miscPayables = response.data
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
                    TotalAmount : selected.DefaultAmount
                })
                this.$nextTick(() => {
                    this.$refs['payable-' + idtmp][0].focus()
                })

                // assess if tuition fee
                if (selected.Payable.includes('Tuition Fee')) {
                    this.getLatestTuitionFee()
                }
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
            this.checkIfPayableHasTuition()
        },
        validateTotal() {
            this.totalAmountDue = 0
            for (let i=0; i<this.payableItems.length; i++) {
                this.totalAmountDue += parseFloat(this.payableItems[i].TotalAmount)
            }
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
        getTotalPayments() {
            var cash = (this.cashAmount.length < 1 ? 0 : parseFloat(this.cashAmount))
            var check = (this.checkAmount.length < 1 ? 0 : parseFloat(this.checkAmount))
            var digital = (this.digitalAmount.length < 1 ? 0 : parseFloat(this.digitalAmount))
            
            // total payments
            this.totalPayments = cash + check + digital

            // change
            this.change = this.totalPayments - this.totalAmountDue
        },
        handleEnterKey(event) {
            event.preventDefault()
            if (event.key === 'Enter') {
                this.transact()
            }
        },
        transact() {
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
                    // filter if amount is lacking
                    if (this.change < 0) {
                        this.toast.fire({
                            icon : 'warning',
                            text : 'Insufficient amount!'
                        })
                    } else {
                        // begin transaction
                        Swal.fire({
                            title: "Confirm Transaction",
                            showCancelButton: true,
                            html: `
                                <p style='text-align: left;'>Miscellaneous payment summary:</p>
                                <ul>
                                    <li style='text-align: left;'>Amount Payable: <h2><strong>P ${ this.toMoney(this.totalAmountDue) }</strong></h2></li>
                                    <li style='text-align: left;'>Amount Paid: <strong>P ${ this.toMoney(this.totalPayments) }</strong></li>
                                    <li style='text-align: left;'>Change: <strong>P ${ this.toMoney(this.change) }</strong></li>
                                </ul>
                                <p style='text-align: left;'>Proceed payment transaction?</p>
                            `,
                            confirmButtonText: "Yes",
                            confirmButtonColor : '#3a9971'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.post(`${ this.baseURL }/transactions/transact-miscellaneous`, {
                                    _token : this.token,
                                    StudentId : this.studentId,
                                    cashAmount : this.cashAmount,
                                    checkNumber : this.checkNumber,
                                    checkBank : this.checkBank,
                                    checkAmount : this.checkAmount,
                                    digitalNumber : this.digitalNumber,
                                    digitalBank : this.digitalBank,
                                    digitalAmount : this.digitalAmount,
                                    totalPayables : this.totalPayables,
                                    totalPayments : this.totalAmountDue,
                                    ORNumber : this.orNumber,
                                    Details : this.paymentDetails,
                                    TransactionDetails : this.payableItems,
                                    ORDate : this.orDate,
                                    PayableId : this.payableId,
                                }) 
                                .then(response => {
                                    this.toast.fire({
                                        icon : 'success',
                                        text : 'Transction successful!'
                                    })
                                    if (this.school === 'SVI') {
                                        window.location.href = this.baseURL + '/transactions/print-miscellaneous-svi/' + response.data
                                    } else if (this.school === 'HCA') {
                                        window.location.href = this.baseURL + '/transactions/print-miscellaneous/' + response.data
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
        },
        showHistory() {
            this.getDetailedTransactions()

            let modalElement = this.$refs.modalShowHistory
            $(modalElement).modal('show')
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
        getLatestTuitionFee() {
            axios.get(`${ this.baseURL }/transactions/get-latest-tuition-fee`, {
                params : {
                    StudentId : this.studentId,
                }
            })
            .then(response => {
                this.tuitionFeePayable = response.data

                if (!this.isNull(this.tuitionFeePayable)) {
                    this.tuitionHeaderCardShown = true
                    this.payableId = this.tuitionFeePayable.id

                    // check if balance is already zero (0)
                    if (parseFloat(this.tuitionFeePayable.Balance) <= 0) {
                        Swal.fire({
                            icon : 'info',
                            title : 'Tuition Already Paid',
                            text : "This student no longer has a Tuition Balance for this school year. Proceeding will set his/her balance to negative. Proceed with caution."
                        })
                    }

                    // get payable inclusions
                    axios.get(`${ this.baseURL }/transactions/get-payable-inclusions`, {
                        params : {
                            PayableId : this.tuitionFeePayable.id,
                        }
                    })
                    .then(response => {
                        this.tuitionInclusions = response.data

                    })
                    .catch(error => {
                        console.log(error.response)
                        this.toast.fire({
                            icon : 'error',
                            text : 'Error getting payable inclusions!'
                        })
                    })
                } else {
                    this.tuitionHeaderCardShown = false

                    this.tuitionInclusions = []
                    this.tuitionFeePayable = {}
                    this.payableId = null

                    Swal.fire({
                        icon : 'warning',
                        title : 'Warning',
                        text : 'This student has no recorded Tuition Payables. Proceeding this transaction will not credit any tuition payable. Proceed with caution.'
                    })
                }
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting tuition fee data!'
                })
            })
        },
        checkIfPayableHasTuition() {
            this.tuitionHeaderCardShown = false
            this.tuitionInclusions = []
            this.tuitionFeePayable = {}

            if (!this.isNull(this.payableItems)) {
                for (let i=0; i<this.payableItems.length; i++) {
                    if (this.payableItems[i].Payable.includes('Tuition Fee')) {
                        this.tuitionHeaderCardShown = true
                        break
                    }
                }
            }
        },
        showPayableHistory() {
            this.paymentFor = this.tuitionFeePayable.PaymentFor
            this.isModalTuition = this.tuitionFeePayable.Category === 'Tuition Fees' ? true : false
            this.getTransactionHistory(this.tuitionFeePayable.id)
            this.getDetailedTransactions()

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
        addPayableBreakdown() {
            if (this.isNull(this.tuitionFeePayable)) {
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
                    PayableId : this.tuitionFeePayable.id,
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