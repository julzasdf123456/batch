@extends('layouts.app')

@section('content')
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Student Summary by Grade</h4>
                    </div>
                    <div class="col-sm-6">
                        <a class="btn btn-default float-right" href="{{ url()->previous() }}">

                            Back
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="content px-3">
            <div class="card shadow-none">
                <div class="card-header d-flex align-items-center">
                    <span class="card-title flex-grow-1">
                        <i class="fas fa-users mr-2"></i> Enrollment Summary by Grade Level
                    </span>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Grade Level</th>
                                <th>Number of Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
    $total = 0;
                            @endphp
                            @foreach ($summaries as $summary)
                                <tr>
                                    <td>{{ $summary['Year'] }}</td>
                                    <td>{{ $summary['Students'] }}</td>
                                </tr>
                                @php
        $total += $summary['Students'];
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <strong>Total Students:</strong> {{ $total }}
                </div>
            </div>
        </div>
@endsection