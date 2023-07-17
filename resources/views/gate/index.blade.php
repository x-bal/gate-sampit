@extends('layouts.master')

@section('content')
<div class="container mt-3">

    <h2>Gates Page</h2>

    <form action="{{ route('gates.store') }}" class="row" id="form-gate" method="post">
        @csrf
        <input type="hidden" name="_method" id="method" value="POST">

        <div class="form-group col-md-3">
            <label for="id_gate">ID Gate</label>
            <input type="numeric" name="id_gate" id="id_gate" class="form-control" autofocus>

            @error('id_gate')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group col-md-3">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control">

            @error('name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group col-md-3 mt-3">
            <button type="submit" class="btn btn-primary mt-2">Save</button>
            <button type="reset" id="btn-reset" class="btn btn-secondary mt-2">Reset</button>
        </div>
    </form>

    <div class="row mt-5">
        <div class="col-md-12">

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Gate</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($gates as $gate)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $gate->id_gate }}</td>
                            <td>{{ $gate->name }}</td>
                            <td>
                                <a href="{{ route('gates.stream', $gate->id) }}" class="btn btn-info text-light">Show</a>
                                <button type="button" class="btn btn-success btn-edit" id="{{ $gate->id }}" data-route="{{ route('gates.update', $gate->id) }}">Edit</button>
                                <form action="{{ route('gates.destroy', $gate->id) }}" method="post" class="d-inline">
                                    @method("DELETE")
                                    @csrf

                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete data?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
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
        $("#btn-reset").on("click", function() {
            let route = "{{ route('gates.store') }}";

            $("#form-gate").attr("action", route)
            $("#method").val("POST")
        });

        $(".table").on("click", ".btn-edit", function() {
            let id = $(this).attr('id');
            let route = $(this).attr("data-route");

            $("#form-gate").attr("action", route)
            $("#method").val("PATCH")

            $.ajax({
                url: "/gates/" + id,
                method: "GET",
                success: function(response) {
                    let gate = response.gate;

                    $("#id_gate").val(gate.id_gate)
                    $("#name").val(gate.name)
                }
            })
        })

        $(".table").on("click", ".check-status", function() {
            let id = $(this).attr('id');
            let route = $(this).attr("data-route");
            let status = '';

            if ($(this).is(":checked")) {
                status = 1;
            } else {
                status = 0;
            }

            $.ajax({
                url: route,
                method: "GET",
                data: {
                    status: status
                },
                success: function(response) {
                    window.location.reload()
                }
            })
        })
    })
</script>
@endpush