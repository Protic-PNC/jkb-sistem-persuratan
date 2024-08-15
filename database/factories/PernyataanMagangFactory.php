<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PernyataanMagang>
 */
class PernyataanMagangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_ortu' => $this->faker->name,
            'alamat' => $this->faker->address,
            'no_telp' => $this->faker->phoneNumber, 
            'nama_mhs' => $this->faker->name, 
            'npm' => $this->faker->numerify('#########'),
            'jurusan' => $this->faker->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Teknik Sipil']),
            'perguruan_tinggi' => $this->faker->randomElement(['Universitas Indonesia', 'Institut Teknologi Bandung', 'Universitas Gadjah Mada']),
            'tglSurat' => $this->faker->date(),
        ];
    }
}
