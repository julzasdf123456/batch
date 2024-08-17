@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2 col-md-12">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="text-muted text-sm"><strong>NOTE: </strong> These settings can only be changed in the <strong>.env</strong> configuration in the server for security purposes. For more information, contact IT administrator.</span>
            </div>

            <div class="card-body">
                {{-- SCHOLARSHIP_DEDUCTION --}}
                <div class="mt-2">
                    <h4 class="text-muted"># SCHOLARSHIP_DEDUCTION = <strong class="text-primary">{{ env('SCHOLARSHIP_DEDUCTION') }}</strong></h4>
                    <p class="pt-1">This configuration sets the deduction method used in the application of scholarship per student. There are 2 available options tabulated below:</p>
                    <div class="px-5">
                        <table class="table table-bordered">
                            <thead>
                                <th class="text-muted">Option</th>
                                <th class="text-muted">Description</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>ALL</strong></td>
                                    <td>All items/particulars/breakdowns in the tuition fee is subject for deduction.</td>
                                </tr>
                                <tr>
                                    <td><strong>TUITION_ONLY</strong></td>
                                    <td>A user can specify which item/particular is subject for deduction. By default, the item named "Tuition Fee" is pre-selected.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- STUDENT_IN_AM_THRESHOLD --}}
                <div class="mt-5">
                    <h4 class="text-muted"># STUDENT_IN_AM_THRESHOLD = <strong class="text-primary">{{ env('STUDENT_IN_AM_THRESHOLD') }} ({{ date('h:i A', strtotime(env('STUDENT_IN_AM_THRESHOLD'))) }})</strong></h4>
                    <p class="pt-1">This time configuration (PST) is the threshold at which the ID system caps the late morning time-ins. Any card swipes after/beyond this time 
                        is considered late by the system.
                    </p>
                </div>
                
                {{-- STUDENT_OUT_PM_THRESHOLD --}}
                <div class="mt-5">
                    <h4 class="text-muted"># STUDENT_OUT_PM_THRESHOLD = <strong class="text-primary">{{ env('STUDENT_OUT_PM_THRESHOLD') }} ({{ date('h:i A', strtotime(env('STUDENT_OUT_PM_THRESHOLD'))) }})</strong></h4>
                    <p class="pt-1">This time configuration (PST) is the threshold at which the ID system caps the late after time-outs. Any card swipes prior this time 
                        is considered undertimed/cutting classes.
                    </p>
                </div>

                {{-- TUITION_PROPAGATION_PRESET --}}
                <div class="mt-5">
                    <h4 class="text-muted"># TUITION_PROPAGATION_PRESET = <strong class="text-primary">{{ env('TUITION_PROPAGATION_PRESET') }}</strong></h4>
                    <p class="pt-1">This setting determines the total tuition amount payable of the students. This has 2 options:</p>
                    <div class="px-5">
                        <table class="table table-bordered">
                            <thead>
                                <th class="text-muted">Option</th>
                                <th class="text-muted">Description</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>FLEXIBLE_ENROLLMENT_FEE</strong></td>
                                    <td>Enrollment fee can be paid by any amount, and is also included in the breakdown of the Tuition Fee. If the 
                                        enrollment fee is paid with a larger amount, the tuition fee will be smaller and hence the monthly tuition payable is also smaller.</td>
                                </tr>
                                <tr>
                                    <td><strong>STATIC_ENROLLMENT_FEE</strong></td>
                                    <td>There's a specific enrollment fee item separate from the tuition fee. Tuition fee is not affected by this.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- SENIOR_HIGH_SEM_ENROLLMENT --}}
                <div class="mt-5">
                    <h4 class="text-muted"># SENIOR_HIGH_SEM_ENROLLMENT = <strong class="text-primary">{{ env('SENIOR_HIGH_SEM_ENROLLMENT') }}</strong></h4>
                    <p class="pt-1">This governs the semestral breaks of Senior High School enrollees. Options are the following:</p>
                    <div class="px-5">
                        <table class="table table-bordered">
                            <thead>
                                <th class="text-muted">Option</th>
                                <th class="text-muted">Description</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>CONTINUOS</strong></td>
                                    <td>SHS second semester don't need to be re-enrolled. Enrollment is only done during the start of school year.</td>
                                </tr>
                                <tr>
                                    <td><strong>BREAK</strong></td>
                                    <td>SHS second semester needs to undergo another enrollment right after the semestral break.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#page-title').html("<span class='text-muted'></span> <strong>General App Settings</strong>")
        })
    </script>
@endpush
