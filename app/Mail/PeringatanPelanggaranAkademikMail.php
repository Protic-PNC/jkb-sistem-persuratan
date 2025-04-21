<?php

namespace App\Mail;

use App\Models\PelanggaranAkademik;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PeringatanPelanggaranAkademikMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pelanggaranAkademik;

    public function __construct(PelanggaranAkademik $pelanggaranAkademik)
    {
        $this->pelanggaranAkademik = $pelanggaranAkademik;
    }

    public function build()
    {
        return $this->subject('Surat Peringatan karena Pelanggaran Peraturan Akademik')
                    ->view('emails.pelanggaran')
                    ->with([
                        'noSurat' => $this->pelanggaranAkademik->noSurat,
                        'nama_mhs' => $this->pelanggaranAkademik->nama_mhs,
                    ]);
    }
}
