@extends('layouts.master')

@section('content')
<div class="container mt-3">

    <h2>Card RFID Page</h2>

    <form action="{{ route('cards.store') }}" class="row" id="form-card" method="post">
        @csrf
        <input type="hidden" name="_method" id="method" value="POST">

        <div class="form-group col-md-3">
            <label for="rfid">RFID</label>
            <input type="text" name="rfid" id="rfid" class="form-control" autofocus>

            @error('rfid')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group col-md-3">
            <label for="nopol">No Plat</label>
            <input type="text" name="nopol" id="nopol" class="form-control">

            @error('nopol')
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
                            <th>RFID</th>
                            <th>Nomor Plat</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cards as $card)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $card->rfid }}</td>
                            <td>{{ $card->nopol }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check-status" type="checkbox" role="switch" id="{{ $card->id }}" {{ $card->status == 1 ? 'checked' : '' }} data-route="{{ route('cards.change', $card->id) }}">
                                    <label class="form-check-label" for="{{ $card->id }}">{{ $card->status == 1 ? 'Active' : 'Nonactive' }}</label>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-edit" id="{{ $card->id }}" data-route="{{ route('cards.update', $card->id) }}">Edit</button>
                                <form action="{{ route('cards.destroy', $card->id) }}" method="post" class="d-inline">
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
            let route = "{{ route('cards.store') }}";

            $("#form-card").attr("action", route)
            $("#method").val("POST")
        });

        $(".table").on("click", ".btn-edit", function() {
            let id = $(this).attr('id');
            let route = $(this).attr("data-route");

            $("#form-card").attr("action", route)
            $("#method").val("PATCH")

            $.ajax({
                url: "/cards/" + id,
                method: "GET",
                success: function(response) {
                    let card = response.card;

                    $("#rfid").val(card.rfid)
                    $("#nopol").val(card.nopol)
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