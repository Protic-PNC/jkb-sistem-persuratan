<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\PelanggaranAkademik;
use Illuminate\Queue\SerializesModels;

class StatusPelanggaranAkademikChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pelanggaran;
    public $status;

    public function __construct(PelanggaranAkademik $pelanggaran, $status)
    {
        $this->pelanggaran = $pelanggaran;
        $this->status = $status;
    }

    public function build()
{
    $subject = '';
    $view = '';

    if ($this->status == 'approved' || $this->status == 'rejected') {
        $subject = 'Status Pelanggaran Akademik Anda';
        $view = 'emails.status_pelanggaran';
    } elseif ($this->status == 'reminder_ttd_pelanggaran') {
        $subject = 'Pengingat Tanda Tangan Surat Pelanggaran Akademik';
        $view = 'emails.reminder_ttd_pelanggaran';
    }

    return $this->subject($subject)
                ->view($view)
                ->with([
                    'pelanggaran' => $this->pelanggaran,
                ]);
}

}
