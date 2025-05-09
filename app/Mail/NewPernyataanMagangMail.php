<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\PernyataanMagang;
use Illuminate\Queue\SerializesModels;

class NewPernyataanMagangMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pernyataan;

    public function __construct(PernyataanMagang $pernyataan)
    {
        $this->pernyataan = $pernyataan;
    }

    public function build()
    {
        return $this->subject('Surat Pernyataan Magang Baru')
                    ->view('emails.new_pernyataan_magang')
                    ->with([
                        'pernyataan' => $this->pernyataan
                    ]);
    }
} 