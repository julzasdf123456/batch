@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>{{ $schoolYear->SchoolYear }}</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right" href="{{ route('schoolYears.index') }}">
                        Back
                    </a>

                    @if (Auth::id() == 1)
                        <button class="btn btn-danger float-right ico-tab-mini" data-toggle="modal"
                            data-target="#modal-merge">Merge to Existing/Other SY</button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card shadow-none">
            <div class="card-header d-flex align-items-center">
                <span class="card-title flex-grow-1"><i class="fas fa-bookmark mr-2"></i>Classes in
                    {{ $schoolYear->SchoolYear }}</span>
                <a href="{{ route('schoolYears.view-summary', ['school_year_id' => $schoolYear->id]) }}"
                    class="btn btn-secondary ml-2">Summary of Classes <i class="fas fa-arrow-right"></i> </a>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <th>Grades/Classes - Section</th>
                        <th>Track</th>
                        <th>Strand</th>
                        <th>Semester</th>
                        <th>Adviser</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($classes as $item)
                            <tr>
                                <td onclick="view(`{{ $item->Adviser }}`, `{{ $schoolYear->id }}`, `{{ $item->id }}`)"
                                    class="v-align pointer">{{ $item->Year . ' - ' . $item->Section }}</td>
                                <td onclick="view(`{{ $item->Adviser }}`, `{{ $schoolYear->id }}`, `{{ $item->id }}`)"
                                    class="v-align pointer">{{ $item->Track }}</td>
                                <td onclick="view(`{{ $item->Adviser }}`, `{{ $schoolYear->id }}`, `{{ $item->id }}`)"
                                    class="v-align pointer">{{ $item->Strand }}</td>
                                <td onclick="view(`{{ $item->Adviser }}`, `{{ $schoolYear->id }}`, `{{ $item->id }}`)"
                                    class="v-align pointer">{{ $item->Semester != null ? $item->Semester . ' Sem' : '' }}</td>
                                <td onclick="view(`{{ $item->Adviser }}`, `{{ $schoolYear->id }}`, `{{ $item->id }}`)"
                                    class="v-align pointer">{{ $item->FullName }} <span
                                        class="text-muted">({{ $item->Designation }})</span></td>
                                <td class="text-right">
                                    <a class="btn btn-primary-skinny btn-sm"
                                        href="{{ route('classes.view-class', [$item->Adviser, $schoolYear->id, $item->id]) }}">View
                                        <i class="fas fa-angle-right ico-tab-left-mini"></i></a>
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

    {{-- MERGE MODAL--}}
    <div class="modal fade" id="modal-merge" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Merge to Another School Year</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="SY">Select School Year to Merge</label>
                        <select class="custom-select select2" name="SY" id="SY" style="width: 100%;" required>
                            <option value="">-- Select --</option>
                            @foreach ($sys as $item)
                                <option value="{{ $item->id }}">{{ $item->SchoolYear }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="merge()"><i
                            class="fas fa-plus ico-tab-mini"></i>Confirm Merge</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function () {

        })

        function merge() {
            let newSy = $('#SY').val()
            Swal.fire({
                title: "Confirm Merge",
                showCancelButton: true,
                text: `Merging this School Year to the selected one will transfer all the students grading and tuition information. This will not affect the student's details. Proceed will caution.`,
                confirmButtonText: "Yes",
                confirmButtonColor: '#e03822'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/school_years/merge-to-sy') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            SchoolYearId: "{{ $schoolYear->id }}",
                            NewSchoolYearId: newSy,
                        },
                        success: function (res) {
                            Toast.fire({
                                icon: 'success',
                                text: 'School Years Merged!'
                            })
                            window.location.href = "{{ url('/schoolYears') }}"
                        },
                        error: function (err) {
                            console.log(err)
                            Toast.fire({
                                icon: 'error',
                                text: 'Error removing merging school years!'
                            })
                        }
                    })
                }
            })
        }
    </script>
@endpush