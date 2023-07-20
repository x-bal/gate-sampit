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
                            <th>Gate</th>
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

        function get() {
            $.ajax({
                url: "{{ route('gate.logs') }}",
                type: "GET",
                data: {
                    gate: "{{ $gate->id }}"
                },
                success: function(response) {
                    let logs = response.logs;
                    let total = response.count;
                    let storedLogs = JSON.parse(localStorage.getItem("gatelogs")) || {};

                    let hasUpdate = false;

                    $.each(logs, function(rfid, logArray) {
                        if (!storedLogs[rfid] || JSON.stringify(storedLogs[rfid]) !== JSON.stringify(logArray)) {
                            hasUpdate = true;
                            storedLogs[rfid] = logArray;
                        }
                    });

                    if (hasUpdate) {
                        localStorage.setItem("gatelogs", JSON.stringify(storedLogs));
                        window.location.reload();
                    }
                },
                error: function() {
                    console.log('Error fetching logs.');
                }
            });
        }

        let storedLogs = JSON.parse(localStorage.getItem("gatelogs")) || {};
        let no = 1;

        $.each(storedLogs, function(rfid, logArray) {
            $.each(logArray, function(index, data) {
                $("#body-logs").append(`<tr>
                <td>` + no++ + `</td>
                <td>` + data.waktu + `</td>
                <td>` + data.rfid + `</td>
                <td>` + data.gate.name + `</td>
                <td>` + data.nopol + `</td>
                <td>` + data.status + `</td>
            </tr>`);
            });
        });

        setInterval(function() {
            get();
            counter++;

            if (counter >= 1000) {
                window.location.reload();
            }
        }, 1500);
    });
</script>
@endpush