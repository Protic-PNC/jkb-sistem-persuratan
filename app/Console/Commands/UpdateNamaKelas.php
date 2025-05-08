<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateKelasCommand extends Command
{
    protected $signature = 'kelas:update';
    protected $description = 'Update nama kelas setiap Juli';

    public function handle()
    {
        $users = User::whereNotNull('kelas')->get();

        foreach ($users as $user) {
            $kelas = $user->kelas;

            if (str_starts_with($kelas, 'TI-')) {
                preg_match('/TI-(\d)([A-Z]+)/', $kelas, $matches);
                if ($matches && (int)$matches[1] < 3) {
                    $newKelas = 'TI-' . ((int)$matches[1] + 1) . $matches[2];
                    $user->kelas = $newKelas;
                    $user->save();
                }
            } else {
                preg_match('/^([A-Z]+)-(\d)([A-Z]+)/', $kelas, $matches);
                if ($matches && (int)$matches[2] < 4) {
                    $newKelas = $matches[1] . '-' . ((int)$matches[2] + 1) . $matches[3];
                    $user->kelas = $newKelas;
                    $user->save();
                }
            }
        }

        $this->info('Kelas berhasil diupdate');
    }
}
