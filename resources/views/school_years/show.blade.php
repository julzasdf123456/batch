@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>{{ $schoolYear->SchoolYear }}</h4>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('schoolYears.index') }}">
                                                    Back
                                            </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bookmark ico-tab"></i>Classes in {{ $schoolYear->SchoolYear }}</span>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <th>Grades/Classes - Section</th>
                        <th>Adviser</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($classes as $item)
                            <tr>
                                <td onclick="view(`{{ $item->id }}`)" class="v-align pointer">{{ $item->Year . ' - ' . $item->Section }}</td>
                                <td onclick="view(`{{ $item->id }}`)" class="v-align pointer">{{ $item->FullName }} <span class="text-muted">({{ $item->Designation }})</span></td>
                                <td class="text-right">
                                    <a class="btn btn-primary-skinny btn-sm" href="{{ route('classes.show', [$item->id]) }}">View <i class="fas fa-angle-right ico-tab-left-mini"></i></a>
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
@endsection

@push('page_scripts')
    <script>
        function view(id) {
            window.location.href = "{{ url('/classes') }}/" + id
        }
    </script>    
@endpush
