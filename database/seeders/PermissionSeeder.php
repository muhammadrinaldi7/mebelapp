<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar permission yang ingin dibuat
        $permissions = [
            // Permissions untuk Produk
            'tambah-produk',
            'edit-produk',
            'delete-produk',

            // Permissions untuk Merek
            'tambah-merek',
            'edit-merek',
            'delete-merek',

            // Permissions untuk Kategori
            'tambah-kategori',
            'edit-kategori',
            'delete-kategori',

            //Laporan
            'lihat-laporan-transaksi',
            'lihat-laporan-stok',
            'lihat-laporan-mutasi',
            'lihat-laporan-laba',
            'lihat-laporan-filter',
            'export-laporan',
            'lihat-laporan',
            'kelola-pengeluaran',

            // Transaksi
            'edit-barang-masuk',
            'edit-barang-keluar',
            'hapus-barang-masuk',
            'hapus-barang-keluar',
        ];

        // Looping dan simpan ke database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web' // Opsional: sesuaikan dengan guard Anda
            ]);
        }

        $this->command->info('Permissions berhasil dibuat!');
    }
}
