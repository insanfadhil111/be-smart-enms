<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Energi (kWh)</th>
            <th>Biaya (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>{{ \Carbon\Carbon::parse($row['date'])->format('d-m-Y') }}</td>
            <td>{{ number_format($row['total'], 2, ',', '.') }}</td>
            <td>{{ $row['bill'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
