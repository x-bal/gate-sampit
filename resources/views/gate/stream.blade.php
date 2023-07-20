@extends('layouts.master')

@section('content')
<div class="container mt-3">

    <h2>Stream {{ $gate->name }} Page</h2>

    <div class="row mt-5">
        <div class="col-md-12">

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Waktu</th>
                            <th>Rfid</th>
                            <th>Nopol</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody id="body-logs">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@push('script')
<script>
    $(document).ready(function() {
        let counter = 0;
        // localStorage.removeItem("gatelogs");

        function get() {
            $.ajax({
                url: "{{ route('gate.logs') }}",
                type: "GET",
                data: {
                    gate: "{{ $gate->id }}"
                },
                success: function(response) {
                    let logs = response.logs;
                    let storedLogs = JSON.parse(localStorage.getItem("gatelogs")) || [];
                    let newLogs = [];

                    // Find new logs
                    for (let i = storedLogs.length; i < logs.length; i++) {
                        newLogs.push(logs[i]);
                    }

                    console.log(newLogs)

                    if (newLogs.length > 0) {
                        // Update stored logs
                        storedLogs = logs;
                        localStorage.setItem("gatelogs", JSON.stringify(storedLogs));

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

                        // Reload page after appending new logs
                        window.location.reload();
                    }
                },
                error: function() {
                    console.log('Error fetching logs.');
                }
            });
        }

        let storedLogs = JSON.parse(localStorage.getItem("gatelogs")) || [];
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
@endpush