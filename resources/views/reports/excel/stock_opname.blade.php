<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Referensi</th>
            <th>Tanggal</th>
            <th>Admin</th>
            <th>Keterangan</th>
            <th>Total Barang Diperiksa</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($opnames as $index => $opname)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $opname->reference_code }}</td>
            <td>{{ $opname->opname_date->format('d/m/Y') }}</td>
            <td>{{ $opname->user->name }}</td>
            <td>{{ $opname->notes }}</td>
            <td>{{ $opname->details->count() }}</td>
            <td>{{ $opname->status == 'completed' ? 'Selesai' : 'Draft' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
