@php
    use App\Models\IDGenerator;
@endphp

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>
                        {{ $classRepo->Year . ' - ' . $classRepo->Section }}
                    </h4>
                    @if ($classRepo->BaseTuitionFee != null)
                        <span><span class="text-muted">Tuition Fee: </span> <strong>{{ number_format($classRepo->BaseTuitionFee, 2) }}</strong> <span class="text-muted">(Fixed Tuition)</span></span>
                    @else
                        <span><span class="text-muted">Tuition Fee: </span> <strong>{{ $totalSubjectTuition != null ? number_format($totalSubjectTuition->Total, 2) : '0' }}</strong> <span class="text-muted">(Subject Based-Tuition)</span></span>
                    @endif
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right ico-tab-left-mini" href="{{ route('classesRepos.index') }}">Back</a>
                    
                    <a class="btn btn-primary float-right" href="{{ route('classesRepos.edit', [$classRepo->id]) }}">Update</a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            {{-- tuitions --}}
            <div class="col-lg-4">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title">Tuition Fee Inclusions</span>
                        <div class="card-tools">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-add-tuition-inclusion">Add</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-sm">
                            <thead>
                                <th>Item</th>
                                <th class="text-right">Amount</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($tuitionInclusions as $item)
                                    <tr>
                                        <td class="v-align">{{ $item->ItemName }}</td>
                                        <td class="text-right v-align">{{ number_format($item->Amount, 2) }}</td>
                                        <td class="text-right v-align">
                                            <button onclick="removeTuitionInclusion(`{{ $item->id }}`)" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>

            {{-- subjects --}}
            <div class="col-lg-8">
                <div class="card shadow-none">
                    <div class="card-header">
                        <span class="card-title text-muted">Subjects Offered in this Class/Grade</span>
        
                        <div class="card-tools">
                            <button class="btn btn-tools btn-primary" data-toggle="modal" data-target="#modal-add-subject">Add Subject</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <th class="text-muted">Subject</th>
                                <th class="text-muted text-right">Subject/Course Fees</th>
                            </thead>
                            <tbody>
                                @foreach ($subjectClasses as $item)
                                    <tr>
                                        <td class="v-align">{{ $item->Subject }}</td>
                                        <td class="v-align text-right">{{ number_format($item->CourseFee, 2) }}</td>
                                        <td class="text-right">
                                            <button onclick="remove(`{{ $item->SubjectClassId }}`)" class="btn btn-danger btn-sm"><i class="fas fa-trash ico-tab-mini"></i>Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        
    </div>

{{-- ADD SUBJECT --}}
<div class="modal fade" id="modal-add-subject" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Subject to this Class</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                   <label for="Subjects">Select Subject to Add</label>
                   <select class="custom-select select2"  name="Subjects" id="Subjects" style="width: 100%;" required>
                        <option value="">-- Select --</option>
                        @foreach ($subjects as $item)
                            <option value="{{ $item->id }}">{{ $item->Subject }}</option>
                        @endforeach
                    </select>
                </div>
 
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="addSubject()"><i class="fas fa-plus ico-tab-mini"></i>Add Subject</button>
            </div>
        </div>
    </div>
</div>

 {{-- ADD TUITION INCLUSIONS --}}
<div class="modal fade" id="modal-add-tuition-inclusion" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Tuition Inclusions to this Class</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                   <label for="Item">Item to Include</label>
                   <input type="text" class="form-control" name="Item" id="Item" style="width: 100%;" required placeholder="Tuition Fee, Books, Uniform, etc...">
                </div>
 
                <div class="form-group">
                    <label for="Amount">Amount</label>
                    <input type="number" step="any" class="form-control" name="Amount" id="Amount" style="width: 100%;" required placeholder="0.0">
                 </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="addTuitionInclusion()"><i class="fas fa-check ico-tab-mini"></i>Save</button>
            </div>
        </div>
    </div>
 </div>
@endsection

@push('page_scripts')
    <script>
        function addSubject() {
            var subject = $('#Subjects').val()

            if (isNull(subject)) {
                Toast.fire({
                    icon : 'info',
                    text : 'Please select subject!'
                })
            } else {
                $.ajax({
                    url : "{{ route('subjectClasses.store') }}",
                    type : "POST",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : "{{ IDGenerator::generateIDandRandString() }}",
                        SubjectId : subject,
                        ClassRepoId : "{{ $classRepo->id }}",
                        UserId : "{{ Auth::id() }}"
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'Subject added!'
                        })
                        location.reload()
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error adding subject!'
                        })
                    }
                })
            }
        }

        function remove(id) {
            Swal.fire({
                title: "Confirm Subject Removal",
                showCancelButton: true,
                text : 'Are you sure you want to remove this subject from this class?',
                confirmButtonText: "Yes",
                confirmButtonColor : '#e03822'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ url('/subjectClasses') }}/" + id,
                        type : "DELETE",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Subject removed!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error removing subject!'
                            })
                        }
                    })
                }
            })
        }

        function addTuitionInclusion() {
            var item = $('#Item').val()
            var amount = $('#Amount').val()

            if (isNull(item) | isNull(amount)) {
                Toast.fire({
                    icon : 'warning',
                    text : 'Kindly provide both item and amount to proceed!'
                })
            } else {
                $.ajax({
                    url : "{{ route('tuitionInclusions.store') }}",
                    type : "POST",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : "{{ IDGenerator::generateIDandRandString() }}",
                        ItemName : item,
                        Amount : amount,
                        ClassRepoId : "{{ $classRepo->id }}"
                    },
                    success : function(res) {
                        Toast.fire({
                            icon : 'success',
                            text : 'New tuition inclusion added!'
                        })
                        location.reload()
                    },
                    error : function(err) {
                        Toast.fire({
                            icon : 'error',
                            text : 'Error adding tuition inclusion!'
                        })
                    }
                })
            }
        }

        function removeTuitionInclusion(id) {
            Swal.fire({
                title: "Confirm Inclusion Removal",
                showCancelButton: true,
                text : 'Are you sure you want to remove this tuition inclusion from this class? This will also change the tuition fee.',
                confirmButtonText: "Yes",
                confirmButtonColor : '#e03822'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ url('/tuitionInclusions') }}/" + id,
                        type : "DELETE",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : id,
                        },
                        success : function(res) {
                            Toast.fire({
                                icon : 'success',
                                text : 'Tuition inclusion removed!'
                            })
                            location.reload()
                        },
                        error : function(err) {
                            Toast.fire({
                                icon : 'error',
                                text : 'Error removing tuition inclusion!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush
