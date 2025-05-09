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
        $subject = '';
        if ($this->status == 'approved') {
            $subject = 'Surat Pernyataan Magang Anda Telah Disetujui';
        } elseif ($this->status == 'rejected') {
            $subject = 'Surat Pernyataan Magang Anda Ditolak';
        } elseif ($this->status == 'reminder') {
            $subject = 'Pengingat: Surat Pernyataan Magang Anda Memerlukan Perhatian';
        }

        return $this->subject($subject)
                    ->view('emails.status_pernyataan')
                    ->with([
                        'pernyataan' => $this->pernyataan,
                        'status' => $this->status
                    ]);
    }
}
