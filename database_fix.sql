-- Perbaikan untuk bug login: Menambahkan kolom 'status' ke tabel 'users'
-- Jalankan query SQL ini pada database 'fundraising_db' Anda.

ALTER TABLE `users`
ADD COLUMN `status` ENUM('aktif', 'tidak_aktif') NOT NULL DEFAULT 'aktif' AFTER `role`;

-- Memperbarui status pengguna yang sudah ada menjadi 'aktif'
UPDATE `users` SET `status` = 'aktif';
