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
                    let logs = response.logs;
                    let storedLogs = JSON.parse(localStorage.getItem("logs")) || [];
                    let newLogs = [];

                    // Find new logs
                    for (let i = storedLogs.length; i < logs.length; i++) {
                        newLogs.push(logs[i]);
                    }

                    if (newLogs.length > 0) {
                        // Update stored logs
                        storedLogs = logs;
                        localStorage.setItem("logs", JSON.stringify(storedLogs));

                        let no = parseInt($("#body-logs tr:last td:first").text()) || 0;
                        $.each(newLogs, function(i, data) {
                            $("#body-logs").append(`<tr>
                        <td>` + (++no) + `</td>
                        <td>` + data.waktu + `</td>
                        <td>` + data.rfid + `</td>
                        <td>` + data.gate.name + `</td>
                        <td>` + data.nopol + `</td>
                        <td>` + data.status + `</td>
                    </tr>`);
                        });

                    }
                    console.log(newLogs)
                },
                error: function() {
                    console.log('Error fetching logs.');
                }
            });
        }

        let storedLogs = JSON.parse(localStorage.getItem("logs")) || [];
        let no = 1;

        $.each(storedLogs, function(i, data) {
            $("#body-logs").append(`<tr>
                <td>` + no++ + `</td>
                <td>` + data.waktu + `</td>
                <td>` + data.rfid + `</td>
                <td>` + data.gate.name + `</td>
                <td>` + data.nopol + `</td>
                <td>` + data.status + `</td>
            </tr>`);
        });

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