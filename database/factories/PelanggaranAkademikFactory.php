<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PelanggaranAkademik>
 */
class PelanggaranAkademikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_mhs' => $this->faker->name(),
            'nama_pelapor' => $this->faker->name(),
            'nama_dosen_wali' => $this->faker->name(),
            'nama_ketua_jurusan' => $this->faker->name(),
            'npm' => $this->faker->regexify('[0-9]{8}'),
            'semester' => $this->faker->numberBetween(1, 8),
            'kelas' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'peringatan' => $this->faker->randomElement(['lisan', 'tertulis']),
            'hari' => $this->faker->dayOfWeek(),
            'tglSurat' => $this->faker->date(),
            'pasal' => $this->faker->word(),
            'isi_pasal' => $this->faker->sentence(),
            'jumlah_peringatan' => $this->faker->numberBetween(1, 3),
            // 'ttd_mahasiswa' => 'signatures/' . $this->faker->image(storage_path('app/public/signatures'), 640, 480, null, false),
            // 'ttd_pelapor' => 'signatures/' . $this->faker->image(storage_path('app/public/signatures'), 640, 480, null, false),
            // 'ttd_dosen_wali' => 'signatures/' . $this->faker->image(storage_path('app/public/signatures'), 640, 480, null, false),
            // 'ttd_ketua_jurusan' => 'signatures/' . $this->faker->image(storage_path('app/public/signatures'), 640, 480, null, false)
            
        ];
    }
}
