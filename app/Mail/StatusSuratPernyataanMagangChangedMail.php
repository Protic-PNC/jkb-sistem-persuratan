<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\PernyataanMagang;
use Illuminate\Queue\SerializesModels;

class StatusSuratPernyataanMagangChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pernyataan;
    public $status;

    public function __construct(PernyataanMagang $pernyataan, $status)
    {
        $this->pernyataan = $pernyataan;
        $this->status = $status;
    }

    public function build()
    {

        return $this->subject('Status Surat Pernyataan Magang Anda')
                    ->view('emails.status_pernyataan')
                    ->with([
                        'noSurat' => $this->pernyataan->noSurat,
                        'nama_mhs' => $this->pernyataan->nama_mhs
                    ]);
    }
}
