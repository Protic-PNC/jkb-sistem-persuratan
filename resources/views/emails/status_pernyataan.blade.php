<h2>Halo {{ $pernyataan->nama_mhs }},</h2>

<p>Surat pernyataan magang Anda telah <strong>{{ $status == 'approved' ? 'disetujui' : 'ditolak' }}</strong>.</p>

@if($status == 'rejected')
    <p><strong>Alasan Penolakan:</strong></p>
    <blockquote>{{ $pernyataan->alasan }}</blockquote>
@endif

<p>Silakan login ke sistem untuk melihat detailnya.</p>
