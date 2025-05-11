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
    public $isMahasiswa;
    public $opsi;

    public function __construct(PelanggaranAkademik $pelanggaran, $status, $opsi = [])
    {
        $this->pelanggaran = $pelanggaran;
        $this->status = $status;
        $this->opsi = $opsi;
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
        } elseif ($this->status == 'reminder_approval') {
            $subject = 'Pengingat Persetujuan Surat Pelanggaran Akademik';
            $view = 'emails.reminder_approval_pelanggaran';
        } elseif ($this->status == 'rejected_update') {
            $subject = 'Tambahan Alasan Tanda Tangan Surat Pelanggaran Akademik';
            $view = 'emails.alasan_pelanggaran';
        } elseif ($this->status == 'data_changed') {
            $subject = 'Perubahan Data Pelanggaran Akademik';
            $view = 'emails.data_changed_pelanggaran';
        }

        return $this->subject($subject)
            ->view($view)
            ->with([
                'pelanggaran' => $this->pelanggaran,
                'isMahasiswa' => $this->isMahasiswa ?? false,
                'opsi' => $this->opsi ?? [],
            ]);
    }
}
