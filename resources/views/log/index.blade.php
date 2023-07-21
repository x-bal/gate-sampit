@extends('layouts.master')

@section('content')
<div class="container mt-3">
    <h2>Logs RFID Page</h2>

    <form action="" class="row mt-5" target="_blank">
        <div class="col-md-4">
            <div class="form-group ">
                <label for="from">From</label>
                <input type="date" name="from" id="from" class="form-control" value="{{ request('from') ?? '' }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group ">
                <label for="to">To</label>
                <input type="date" name="to" id="to" class="form-control" value="{{ request('from') ?? '' }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group mt-2">
                <button type="submit" class="btn btn-primary mt-3">Filter</button>
                @if(request('from') && request('to'))
                <a href="{{ route('export.logs') }}?from={{ request('from') }}&to={{ request('to') }}" class="btn btn-success mt-3">Export</a>
                @endif
            </div>
        </div>
    </form>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>RFID</th>
                            <th>Gate</th>
                            <th>Nomor Plat</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    @if(request('from') && request('to'))
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->waktu }}</td>
                            <td>{{ $log->rfid }}</td>
                            <td>{{ $log->gate->name }}</td>
                            <td>{{ $log->nopol }}</td>
                            <td>{{ $log->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <tbody id="body-logs">

                    </tbody>
                    @endif
                </table>
            </div>

            @if(request('from') && request('to'))
            {{ $logs->links() }}
            @endif
        </div>
    </div>
</div>
@stop

@push('script')
@if(!request('from') && !request('to'))
<script>
    $(document).ready(function() {
        let counter = 0;

        function get() {
            $.ajax({
                url: "{{ route('get.logs') }}",
                type: "GET",
                success: function(response) {
                    let newTable = response.table;

                    // Get the existing table HTML
                    let existingTable = $("#body-logs").html();

                    // Compare the new table with the existing table
                    if (newTable !== existingTable) {
                        $("#body-logs").html(newTable);
                        localStorage.setItem("logs", JSON.stringify(response.logs));
                    }
                },
                error: function() {
                    console.log('Error fetching logs.');
                }
            });
        }

        setInterval(function() {
            get();
            counter++;
            console.log(counter)

            if (counter >= 1000) {
                window.location.reload();
            }
        }, 1500);
    });
</script>
@endif
@endpush