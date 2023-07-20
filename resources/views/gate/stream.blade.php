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
                    let newTable = response.table;

                    // Get the existing table HTML
                    let existingTable = $("#body-logs").html();

                    // Compare the new table with the existing table
                    if (newTable !== existingTable) {
                        $("#body-logs").html(newTable);
                        localStorage.setItem("gatelogs", JSON.stringify(response.logs));
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

            if (counter >= 1000) {
                window.location.reload();
            }
        }, 1500);
    });
</script>
@endpush