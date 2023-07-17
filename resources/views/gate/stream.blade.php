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

        function get() {
            $.ajax({
                url: "/gate-logs?gate=" + "{{ $gate->id }}",
                type: "GET",
                method: "GET",
                success: function(response) {
                    console.log(response)
                    let logs = response.logs
                    let no = 1;
                    $.each(logs, function(i, data) {
                        $("#body-logs").append(`<tr>
                    <td>` + no++ + `</td>
                    <td>` + data.waktu + `</td>
                    <td>` + data.rfid + `</td>
                    <td>` + data.nopol + `</td>
                    <td>` + data.status + `</td>
                </tr>`);
                    })
                }
            })
        }

        setInterval(function() {
            $("#body-logs").empty()
            get();
            counter++

            if (counter == 1000) {
                window.location.reload()
            }
        }, 1500)
    })
</script>
@endpush