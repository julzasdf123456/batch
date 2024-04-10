@extends('layouts.app')

<meta name="class-id" content="{{ $class->id }}">

@section('content')

    <div class="content px3 row">
        {{-- STUDENTS --}}
        <div class="col-lg-9 col-md-12" id="app">
            <class-view></class-view>
        </div>
        @vite('resources/js/app.js')
        
        {{-- SUBJECTS --}}
        <div class="col-lg-3 col-md-12">
            <div class="card shadow-none">
                <div class="card-header">
                    <span class="card-title text-muted"><i class="fas fa-book ico-tab"></i>Subjects in this Class</span>
                </div>
                <div class="card-body table-responsive p-0">
                   <table class="table table-hover table-sm">
                        <tbody>
                            @foreach ($subjects as $item)
                                <tr>
                                    <td>{{ $item->Subject }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                   </table>
                </div>
                <div class="card-footer">
    
                </div>
            </div>
        </div>    
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-collapse')
            $('#page-title').html("<strong>{{ $class->Year . ' - ' . $class->Section }}</strong> <span class='text-muted'>{{ $class->SchoolYear }}</span>")
        })
    </script>
@endpush
