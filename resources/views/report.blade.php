<table>
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Tanggal Reservasi</th>
            <th>Sesi</th>
            <th>Nama Customer</th>
            <th>Nama Mekanik</th>
            <th>Paket</th>
            <th>Tanggal Pengerjaan</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation->unique_number }}</td>
                <td>{{ $reservation->date }}</td>
                <td>{{ $reservation->session }}</td>
                <td>{{ $reservation->user->full_name }}</td>
                <td>{{ $reservation->assignment->worker->full_name }}</td>
                <td>{{ $reservation->package->name }}</td>
                <td>{{ $reservation->assignment->date }}</td>
                <td>{{ $reservation->assignment->start_time }}</td>
                <td>{{ $reservation->assignment->end_time }}</td>

            </tr>
        @endforeach
    </tbody>
</table>
