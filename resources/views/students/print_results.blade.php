@php
    use App\Models\Students;
    use App\Models\Users;
@endphp
<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }
    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        margin: 0;
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .90em;
    }
    @media print {
        @page {
            
        }

        header {
            display: none;
        }

        .divider {
            width: 100%;
            margin: 10px auto;
            height: 1px;
            background-color: #dedede;
        }

        .left-indent {
            margin-left: 30px;
        }

        p {
            padding: 0px !important;
            margin: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

    }  
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 3px;
        background-color: #dedede;
      -webkit-print-color-adjust: exact;
    } 

    p {
        padding: 0px !important;
        margin: 0px;
        font-size: 1.2em;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .half {
        display: inline-table; 
        width: 49%;
    }

    .thirty {
        display: inline-table; 
        width: 30%;
    }

    .seventy {
        display: inline-table; 
        width: 69%;
    }

    .watermark {
        position: fixed;
        left: 15%;
        top: 60px;
        width: 65%;
        opacity: 0.16;
        z-index: -99;
        color: white;
        user-select: none;
    }

    .border {
        position: fixed;
        width: 100%;
        z-index: 1;
        color: white;
        left: 0;
        top: 0;
    }

    .pms {
      color: black;
      background: rgb(243, 231, 57);
      padding: 30px;
      font-size: 2em;
      -webkit-print-color-adjust: exact;
    }

    .bg-bill {
      background-color: #607D8B;
      -webkit-print-color-adjust: exact;
    }

    .text-white {
      color: white;
      -webkit-print-color-adjust: exact;
    }

    .text-muted {
      color: #898989;
      -webkit-print-color-adjust: exact;
    }

    .no-pad {
      margin: 0px; 
      padding: 0px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        font-size: .85em;
        border: 1px solid #a8a8a8;
        padding: 5px 8px 5px 8px;
    }

    .header-data {
        padding: 85px 30px 10px 50px;
    }

    .body-data {
        padding: 60px 30px 0px 30px;
    }

</style>

<div id="print-area" class="content">

    <div>
        <p class="text-center"><strong>{{ env('APP_COMPANY') }}</strong></p>
        <p class="text-center">{{ env('APP_LOCATION') }}</p>

        <p class="text-center" style="margin-top: 10px;"><strong>STUDENTS LIST {{ $class != null && $class != 'All' ? (strtoupper(' for ' . $class->Year . ' ' . $class->Section)) : '(ALL STUDENTS)' }}</strong></p>
        @if ($class != null && $class != 'All')
            @if ($class->Strand != null)
                <p class="text-center text-muted">{{ $class->Strand }} {{ $class->Semester }} Semester</p>
            @endif
        @endif
    </div>

    {{-- male --}}
    <div style="margin-top: 16px;">
        <table class="table">
            <thead>
                <th></th>
                <th>LRN</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Suffix</th>
                <th>Gender</th>
                <th>Sitio/Purok</th>
                <th>Barangay</th>
                <th>Town</th>
                <th>Birth Date</th>
                <th>Contact Numbers</th>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($students as $item)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ strtoupper($item->LRN) }}</td>
                        <td>{{ strtoupper($item->LastName) }}</td>
                        <td>{{ strtoupper($item->FirstName) }}</td>
                        <td>{{ strtoupper($item->MiddleName) }}</td>
                        <td>{{ strtoupper($item->Suffix) }}</td>
                        <td>{{ strtoupper($item->Gender) }}</td>
                        <td>{{ strtoupper($item->Sitio) }}</td>
                        <td>{{ strtoupper($item->BarangaySpelled) }}</td>
                        <td>{{ strtoupper($item->TownSpelled) }}</td>
                        <td>{{ $item->Birthdate != null ? date('M d, Y', strtotime($item->Birthdate)) : '-' }}</td>
                        <td>{{ strtoupper($item->ContactNumber) }}</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.history.go(-1)
    }, 1800);
</script>