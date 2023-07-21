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
</table>