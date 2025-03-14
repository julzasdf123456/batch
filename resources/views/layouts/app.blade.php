<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="color-profile" content="{{ Auth::user()->ColorProfile }}">
    <meta name="employee-id" content="{{ Auth::user()->employee_id }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ URL::asset('css/source_sans_pro.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/all.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap4toggle.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/adminlte.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrapdatetimepicker.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/jqueryui.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/select2.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/select2.bootstrap4.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/fullcalendar.min.css'); }} ">

    <link rel="stylesheet" href="{{ URL::asset('css/sweetalert2.min.css'); }}">

    <link rel="stylesheet" href="{{ URL::asset('css/daterangepicker.min.css'); }}">
    
    <link rel="stylesheet" href="{{ URL::asset('css/style.css'); }}">

    <link rel="stylesheet" href="{{ URL::asset('css/bstreeview.css'); }}">

    @stack('third_party_stylesheets')

    @stack('page_css')
</head>
@php
    $userCache = Auth::user();
@endphp
<body class="hold-transition sidebar-mini layout-fixed {{ $userCache->ColorProfile }}">
<div class="wrapper">
    <!-- Main Header -->
    <nav class="main-header navbar navbar-expand {{ $userCache->ColorProfile != null ? 'navbar-dark' : 'navbar-light' }}">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item">
                <p class="title-text mt-2 p-0" id="page-title"></p>
            </li>  
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('students.index') }}" title="Student Search">
                    <i class="fas fa-search"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('schoolYears.index') }}" title="View Classes">
                    <i class="fas fa-bookmark"></i>
                </a>
            </li>

            <li class="nav-item dropdown" title="Cashiering Shortcuts">
                <a class="nav-link dropdown-toggle" href="#" id="file-menu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-coins"></i></a>
                <div class="dropdown-menu" aria-labelledby="file-menu">
                    <a href="{{ route('transactions.enrollment') }}" class="dropdown-item" title="Enrollment Payments">Enrollment</a>
                    <a href="{{ route('transactions.tuitions-search') }}" class="dropdown-item" title="School Tuition Payments">School Tuitions</a>
                    <a href="{{ route('transactions.miscellaneous-search') }}" class="dropdown-item" title="Miscellaneous Payments">Miscellaneous</a>
                    @if (env('APP_COMPANY_ABRV') === 'SVI')
                        <a href="{{ route('transactions.other-payments') }}" class="dropdown-item" title="Other Payments">Other Payments</a>
                    @endif
                    <a href="{{ route('transactions.my-dcr') }}" class="dropdown-item" title="My Daily Collection Report">My DCR</a>
                </div>
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ URL::asset('imgs/logo.png'); }}"
                        class="user-image img-circle" alt="User Image"> 
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header">
                        <img src="{{ URL::asset('imgs/logo.png'); }}"
                            class="img-circle"
                            alt="User Image"> 
                        <br>
                        <h4 style="margin-top: 10px;"> {{ Auth::check() ? Auth::user()->name : '' }} </h4>
                    </li>
                    <table class="table table-borderless table-hover table-sm">
                        @if (Auth::user()->TeacherId != null)
                            <tr class="no-pads">
                                <td class="no-pads">
                                    <a href="{{ route('users.my-account-index') }}" class="no-pads btn btn-link {{ $userCache->ColorProfile != null ? 'text-light' : 'text-dark' }}"><i class="fas fa-user-circle ico-tab"></i>My Profile</a>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                <button onclick="changePassword()" class="btn btn-link {{ $userCache->ColorProfile != null ? 'text-light' : 'text-dark' }}"><i class="fas fa-user-lock ico-tab-mini"></i>Change Password</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="custom-control custom-switch" style="margin-left: 10px; margin-top: 6px; margin-bottom: 6px;">
                                    <input type="checkbox" {{ $userCache->ColorProfile != null ? 'checked' : '' }} class="custom-control-input" id="color-switch">
                                    <label style="font-weight: normal;" class="custom-control-label" for="color-switch" id="color-switchLabel">Dark Mode</label>
                                </div>                                
                            </td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #e6e6e6;">
                                <a href="#" class="btn btn-link {{ $userCache->ColorProfile != null ? 'text-light' : 'text-dark' }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt ico-tab"></i>Sign out
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    </table>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Left side column. contains the logo and sidebar -->
@include('layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content">
            @yield('content')
        </section>
    </div>
</div>

<script src="{{ URL::asset('js/jquery.min.css'); }}"></script>

{{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
<script src="{{ URL::asset('js/jqueryui.css'); }}"></script>

{{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" 
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/popper.min.js'); }}"></script>

{{-- <script src="https://adminlte.io/themes/v3/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
<script src="{{ URL::asset('js/bootstrap.bundle.min.js'); }}"></script>
        
{{-- <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script> --}}
<script src="{{ URL::asset('js/bscustomfileinput.min.js'); }}"></script>


<!-- AdminLTE App -->
{{-- <script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js"></script> --}}
<script src="{{ URL::asset('js/adminlte.min.js'); }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"
        integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg=="
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/moment.js'); }}"></script>
<script src="{{ URL::asset('js/moment-timezone.js'); }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"
        integrity="sha512-GDey37RZAxFkpFeJorEUwNoIbkTwsyC736KNSYucu1WJWFK9qTdzYub8ATxktr6Dwke7nbFaioypzbDOQykoRg=="
        crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/datetimepicker.min.js'); }}"></script>

{{-- <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script> --}}
<script src="{{ URL::asset('js/bootstrap4toggle.min.js'); }}"></script>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js" integrity="sha512-J+763o/bd3r9iW+gFEqTaeyi+uAphmzkE/zU8FxY6iAvD3nQKXa+ZAWkBI9QS9QkYEKddQoiy0I5GDxKf/ORBA==" crossorigin="anonymous"></script> --}}
<script src="{{ URL::asset('js/bootstrapswitch.min.js'); }}"></script>

{{-- <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script> --}}
<script src="{{ URL::asset('js/lordicon.js'); }}"></script>

{{-- <script src="https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script> --}}
<script src="{{ URL::asset('js/chart.min.js'); }}"></script>

{{-- <script src="https://www.jqueryscript.net/demo/jQuery-Plugin-To-Connect-HTML-Elements-with-Lines-HTML-SVG-Connect/jquery.html-svg-connect.js"></script> --}}
<script src="{{ URL::asset('js/svgconnect.js'); }}"></script>

{{-- <script src="https://adminlte.io/themes/v3/plugins/select2/js/select2.full.min.js"></script> --}}
<script src="{{ URL::asset('js/select2min.js'); }}"></script>

{{-- CALENDAR --}}
{{-- <script src="https://adminlte.io/themes/v3/plugins/jquery-ui/jquery-ui.min.js"></script> --}}
{{-- <script src="https://adminlte.io/themes/v3/plugins/fullcalendar/main.js"></script> --}}
<script src="{{ URL::asset('js/jqueryuicalendar.min.js'); }}"></script>
<script src="{{ URL::asset('js/calendarfulljs.min.js'); }}"></script>

{{-- SWEETALERT2 --}}
<script src="{{ URL::asset('js/sweetalert2.all.min.js'); }}"></script>

{{-- INPUT MASK --}}
<script src="{{ URL::asset('js/inputmask.min.js'); }}"></script>
<script src="{{ URL::asset('js/daterangepicker.min.js'); }}"></script>
<script src="{{ URL::asset('js/bstreeview.js'); }}"></script>
<script>
    $('.select2').select2({
            theme: 'bootstrap4'
        })

    $(function () {
        bsCustomFileInput.init();
    });
    
    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

    /**
     * FOR TREEVIEW CHILD
     */
     var url = window.location;
    // for sidebar menu entirely but not cover treeview
    $('ul.nav-sidebar a').filter(function() {
        return this.href == url;
    }).addClass('active');
    // for treeview
    $('ul.nav-treeview a').filter(function() {
        return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');

    $(document).ready(function() {
        /**
         * TOWN CHANGE
         */
        // fetchBarangayFromTown($('#TownCurrent').val(), $('#Def_Brgy').text());
        // fetchBarangayFromTownPermanent($('#TownPermanent').val(), $('#Def_Brgy_Permanent').text());

        $('#TownCurrent').on('change', function() {
            fetchBarangayFromTown(this.value, $('#Def_Brgy').text());
        });

        $('#TownPermanent').on('change', function() {
            fetchBarangayFromTownPermanent(this.value, $('#Def_Brgy_Permanent').text());
        });

        /**
         * COLOR MODES CONTROLLER 
         **/
         $('#color-switch').on('change', function(e) {
            var col = ''
            if (e.target.checked) {
               col = 'dark-mode'
            } else {
               col = null
            }

            $.ajax({
                url : "{{ route('users.switch-color-modes') }}",
                type : "GET",
                data : {
                    id : "{{ Auth::id() }}",
                    Color : col,
                },
                success : function(res) {
                    location.reload()
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        text : 'Error changing color profile'
                    })
                }
            })
         })
    });

    /**
     * FUNCTIONS
     */
     function fetchBarangayFromTown(townId, prevValue) {
        $.ajax({
            url : "{{ url('/barangays/get-barangays-json') }}/" + townId,
            type: "GET",
            dataType : "json",
            success : function(data) {
                $('#BarangayCurrent option').remove();
                $.each(data, function(index, element) {
                    $('#BarangayCurrent').append("<option value='" + element + "' " + (element==prevValue ? "selected='selected'" : " ") + ">" + index + "</option>");
                });
            },
            error : function(error) {
                // alert(error);
                // console.log(error);
            }
        });
    }

    function fetchBarangayFromTownPermanent(townId, prevValue) {
        $.ajax({
            url : "{{ url('/barangays/get-barangays-json') }}/" + townId,
            type: "GET",
            dataType : "json",
            success : function(data) {
                $('#BarangayPermanent option').remove();
                $.each(data, function(index, element) {
                    $('#BarangayPermanent').append("<option value='" + element + "' " + (element==prevValue ? "selected='selected'" : " ") + ">" + index + "</option>");
                });
            },
            error : function(error) {
                // alert(error);
                // console.log(error);
            }
        });
    }


    /**
     * TOAST 
     **/
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    function serializeEmployeeName(fName, lName, mName, suffix) {
        if (jQuery.isEmptyObject(mName)) {
            return fName + " " + lName + " " + validateNulls(suffix)
        } else {
            return fName + " " + validateNulls(mName) + " " + lName + " " + validateNulls(suffix)
        }
    }

    function serializeEmployeeNameFormal(fName, lName, mName, suffix) {
        if (jQuery.isEmptyObject(mName)) {
            return lName + ", " + fName + " " + validateNulls(suffix)
        } else {
            return lName + ", " + fName + " " + validateNulls(mName) + " " + validateNulls(suffix)
        }
    }

    function serializeEmployeeNameFormalNoMiddle(fName, lName, suffix) {
        return lName + ", " + fName + " " + validateNulls(suffix)
    }

    function validateNulls(regex) {
        if (jQuery.isEmptyObject(regex)) {
            return ''
        } else {
            return regex
        }
    }

    function isNull(regex) {
        if (jQuery.isEmptyObject(regex)) {
            return true
        } else {
            return false
        }
    }

    function toMoney(value) {
        return Number(parseFloat(value).toFixed(2)).toLocaleString("en-US", { maximumFractionDigits: 2, minimumFractionDigits: 2 })
    }

    function round(value) {
        return Math.round((value + Number.EPSILON) * 100) / 100
    }

    function changePassword() {
        Swal.fire({
                title: 'Update Your Password',
                html:
                    `<p class='text-left'>Please make sure you write your password down in order for you to remember it the next time you log in.</p>
                    <input id="password" class="form-control" type="password" placeholder="Enter new password...">
                    <input id="password-confirm" class="form-control mt-2" type="password" placeholder="Confirm password...">`,
                confirmButtonText: 'Update Password',
                preConfirm: () => {
                    const pword = document.getElementById('password').value;
                    const pwordConfirm = document.getElementById('password-confirm').value;

                    if (!pword || !pwordConfirm) {
                        Swal.showValidationMessage('Both passwords are required.');
                        return false;
                    }

                    if (pword !== pwordConfirm) {
                        Swal.showValidationMessage('Passwords do not match.');
                        return false;
                    }

                    return fetch('{{ route("users.update-password") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            password: pword,
                            password_confirmation: pwordConfirm
                        })
                    })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(({ status, body }) => {
                        if (status !== 200) {
                            console.log('Server response:', body);
                            Swal.showValidationMessage(`Request failed: ${body.message || 'Unknown error'}`);
                            return false;
                        }
                        return body
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                        console.log(error.message)
                    })
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Success', 'Password updated successfully', 'success');
                }
            })
    }
    
</script>

@stack('third_party_scripts')

@stack('page_scripts')
</body>
</html>
