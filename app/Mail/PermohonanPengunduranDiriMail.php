<?php

namespace App\Mail;

use App\Models\PengunduranDiri;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PermohonanPengunduranDiriMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengunduranDiri;

    public function __construct(PengunduranDiri $pengunduranDiri)
    {
        $this->pengunduranDiri = $pengunduranDiri;
    }

    public function build()
    {
        return $this->subject('Surat Permohonan Pengunduran Diri')
                    ->view('emails.pengunduran')
                    ->with([
                        'noSurat' => $this->pengunduranDiri->noSurat,
                        'nama_mhs' => $this->pengunduranDiri->nama_mhs,
                    ]);
    }
}
