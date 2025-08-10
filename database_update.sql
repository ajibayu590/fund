-- Database Update Script for Fundraiser Role System
-- This script updates the existing database to support Fundraiser role and data isolation

-- Step 1: Update users table to support Fundraiser role
ALTER TABLE `users` 
MODIFY COLUMN `role` enum('admin','fundraiser','user') NOT NULL DEFAULT 'fundraiser';

-- Step 2: Create fundraiser_profiles table for Fundraiser-specific data
CREATE TABLE `fundraiser_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `hp` varchar(20) NOT NULL,
  `alamat` text,
  `foto_profil` varchar(255) DEFAULT NULL,
  `target_kunjungan` int(11) DEFAULT 8,
  `total_kunjungan_bulan` int(11) DEFAULT 0,
  `total_donasi_bulan` bigint(20) DEFAULT 0,
  `tanggal_bergabung` date DEFAULT NULL,
  `terakhir_aktif` datetime DEFAULT NULL,
  `status` enum('aktif','tidak_aktif','suspend') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `fundraiser_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Step 3: Update donatur table to link with fundraisers
ALTER TABLE `donatur` 
ADD COLUMN `fundraiser_id` int(11) DEFAULT NULL AFTER `id`,
ADD INDEX `fundraiser_id` (`fundraiser_id`),
ADD CONSTRAINT `donatur_ibfk_3` FOREIGN KEY (`fundraiser_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Step 4: Update kunjungan table to ensure proper linking
ALTER TABLE `kunjungan` 
ADD COLUMN `created_by` int(11) NOT NULL AFTER `fundraiser_id`,
ADD INDEX `created_by` (`created_by`),
ADD CONSTRAINT `kunjungan_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Step 5: Create fundraiser_dashboard_stats table for individual dashboard
CREATE TABLE `fundraiser_dashboard_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fundraiser_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kunjungan_hari_ini` int(11) DEFAULT 0,
  `donasi_berhasil` int(11) DEFAULT 0,
  `total_donasi` bigint(20) DEFAULT 0,
  `donatur_baru` int(11) DEFAULT 0,
  `target_tercapai` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_fundraiser_date` (`fundraiser_id`,`tanggal`),
  KEY `fundraiser_id` (`fundraiser_id`),
  CONSTRAINT `fundraiser_dashboard_stats_ibfk_1` FOREIGN KEY (`fundraiser_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Step 6: Create fundraiser_donatur_mapping for tracking relationships
CREATE TABLE `fundraiser_donatur_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fundraiser_id` int(11) NOT NULL,
  `donatur_id` int(11) NOT NULL,
  `tanggal_assign` date NOT NULL,
  `status` enum('aktif','tidak_aktif') DEFAULT 'aktif',
  `catatan` text,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_fundraiser_donatur` (`fundraiser_id`,`donatur_id`),
  KEY `fundraiser_id` (`fundraiser_id`),
  KEY `donatur_id` (`donatur_id`),
  CONSTRAINT `fundraiser_donatur_mapping_ibfk_1` FOREIGN KEY (`fundraiser_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fundraiser_donatur_mapping_ibfk_2` FOREIGN KEY (`donatur_id`) REFERENCES `donatur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Step 7: Insert sample data for Fundraisers (migrating from js/data.js)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Ahmad Rizki Pratama', 'ahmad.rizki@fundraising.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fundraiser'),
('Siti Nurhaliza Dewi', 'siti.nurhaliza@fundraising.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fundraiser'),
('Budi Santoso Wijaya', 'budi.santoso@fundraising.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fundraiser'),
('Dewi Sartika Putri', 'dewi.sartika@fundraising.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fundraiser'),
('Muhammad Fajar Sidiq', 'fajar.sidiq@fundraising.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fundraiser'),
('Rina Kartika Sari', 'rina.kartika@fundraising.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fundraiser');

-- Step 8: Insert fundraiser profiles
INSERT INTO `fundraiser_profiles` (`user_id`, `nama_lengkap`, `email`, `hp`, `alamat`, `target_kunjungan`, `total_kunjungan_bulan`, `total_donasi_bulan`, `tanggal_bergabung`, `terakhir_aktif`, `status`) VALUES
(3, 'Ahmad Rizki Pratama', 'ahmad.rizki@fundraising.com', '081234567890', 'Jl. Sudirman No. 123, Jakarta', 8, 156, 25000000, '2024-01-15', '2024-12-20 15:30:00', 'aktif'),
(4, 'Siti Nurhaliza Dewi', 'siti.nurhaliza@fundraising.com', '081234567891', 'Jl. Merdeka No. 45, Bandung', 8, 142, 22000000, '2024-02-01', '2024-12-20 14:45:00', 'aktif'),
(5, 'Budi Santoso Wijaya', 'budi.santoso@fundraising.com', '081234567892', 'Jl. Gatot Subroto No. 78, Surabaya', 8, 168, 28000000, '2024-01-10', '2024-12-20 16:00:00', 'aktif'),
(6, 'Dewi Sartika Putri', 'dewi.sartika@fundraising.com', '081234567893', 'Jl. Diponegoro No. 12, Yogyakarta', 8, 134, 19000000, '2024-03-01', '2024-12-20 13:20:00', 'aktif'),
(7, 'Muhammad Fajar Sidiq', 'fajar.sidiq@fundraising.com', '081234567894', 'Jl. Pemuda No. 67, Solo', 8, 172, 31000000, '2024-01-20', '2024-12-20 16:15:00', 'aktif'),
(8, 'Rina Kartika Sari', 'rina.kartika@fundraising.com', '081234567895', 'Jl. Veteran No. 34, Malang', 8, 98, 15000000, '2024-04-15', '2024-12-20 12:30:00', 'aktif');

-- Step 9: Insert donatur data with fundraiser assignments
INSERT INTO `donatur` (`fundraiser_id`, `nama`, `hp`, `email`, `alamat`, `kategori`, `total_donasi`, `terakhir_donasi`, `status`, `jumlah_kunjungan`, `rata_rata_donasi`, `first_donation`) VALUES
(3, 'Pak Joko Widodo Santoso', '081234567801', 'joko.widodo@email.com', 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220', 'individu', 2500000, '2024-12-20', 'aktif', 8, 312500, '2024-01-15'),
(3, 'Bu Sari Indah Permata', '081234567802', 'sari.indah@email.com', 'Jl. Merdeka No. 45, Bandung, Jawa Barat 40111', 'individu', 1800000, '2024-12-19', 'aktif', 6, 300000, '2024-02-10'),
(4, 'PT. Maju Bersama Sejahtera', '081234567803', 'info@majubersama.com', 'Jl. Gatot Subroto No. 78, Surabaya, Jawa Timur 60264', 'perusahaan', 15000000, '2024-12-20', 'aktif', 12, 1250000, '2024-01-05'),
(5, 'Pak Ahmad Dahlan Wijaya', '081234567804', 'ahmad.dahlan@email.com', 'Jl. Diponegoro No. 12, Yogyakarta, DIY 55213', 'individu', 3200000, '2024-12-18', 'aktif', 10, 320000, '2024-01-25'),
(6, 'Yayasan Peduli Sesama', '081234567805', 'info@pedulisesama.org', 'Jl. Pahlawan No. 89, Medan, Sumatera Utara 20112', 'organisasi', 8500000, '2024-12-17', 'aktif', 15, 566667, '2024-02-01'),
(7, 'Bu Ratna Sari Dewi', '081234567806', 'ratna.sari@email.com', 'Jl. Veteran No. 34, Malang, Jawa Timur 65145', 'individu', 1200000, '2024-12-16', 'aktif', 4, 300000, '2024-03-10'),
(8, 'CV. Berkah Mandiri', '081234567807', 'admin@berkahmandiri.co.id', 'Jl. Industri No. 56, Semarang, Jawa Tengah 50149', 'perusahaan', 5500000, '2024-12-15', 'aktif', 8, 687500, '2024-02-20'),
(3, 'Pak Bambang Sutrisno', '081234567808', 'bambang.sutrisno@email.com', 'Jl. Pemuda No. 67, Solo, Jawa Tengah 57126', 'individu', 2100000, '2024-12-14', 'aktif', 7, 300000, '2024-03-05');

-- Step 10: Insert kunjungan data with proper linking
INSERT INTO `kunjungan` (`fundraiser_id`, `donatur_id`, `alamat`, `lokasi`, `nominal`, `status`, `waktu`, `foto`, `catatan`, `follow_up_date`, `created_by`) VALUES
(3, 1, 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220', '-6.2088, 106.8456', 500000, 'berhasil', '2024-12-20 14:30:00', 'foto1.jpg', 'Donatur sangat antusias dengan program pendidikan anak yatim. Berkomitmen untuk donasi rutin setiap bulan.', NULL, 3),
(4, 2, 'Jl. Merdeka No. 45, Bandung, Jawa Barat 40111', '-6.9175, 107.6191', 0, 'follow-up', '2024-12-20 14:15:00', 'foto2.jpg', 'Perlu kunjungan ulang minggu depan, tertarik dengan program kesehatan. Minta proposal detail.', '2024-12-27', 4),
(5, 4, 'Jl. Diponegoro No. 12, Yogyakarta, DIY 55213', '-7.7956, 110.3695', 1000000, 'berhasil', '2024-12-20 13:45:00', 'foto3.jpg', 'Donasi untuk program pendidikan anak yatim. Donatur ingin laporan progress setiap 3 bulan.', NULL, 5),
(6, 3, 'Jl. Gatot Subroto No. 78, Surabaya, Jawa Timur 60264', '-7.2575, 112.7521', 2500000, 'berhasil', '2024-12-20 11:20:00', 'foto4.jpg', 'CSR perusahaan untuk program beasiswa. Akan ada kunjungan tim CSR bulan depan.', NULL, 6),
(7, 5, 'Jl. Pahlawan No. 89, Medan, Sumatera Utara 20112', '3.5952, 98.6722', 0, 'tidak-berhasil', '2024-12-20 10:15:00', 'foto5.jpg', 'Sedang fokus program internal. Mungkin bisa diajak kerjasama di tahun depan.', NULL, 7),
(3, 6, 'Jl. Veteran No. 34, Malang, Jawa Timur 65145', '-7.9666, 112.6326', 300000, 'berhasil', '2024-12-20 09:30:00', 'foto6.jpg', 'Donasi untuk program bantuan pangan. Tertarik untuk ikut volunteer.', NULL, 3),
(8, 7, 'Jl. Industri No. 56, Semarang, Jawa Tengah 50149', '-6.9667, 110.4167', 0, 'follow-up', '2024-12-19 16:45:00', 'foto7.jpg', 'Perlu diskusi dengan direksi dulu. Jadwal meeting Senin depan.', '2024-12-23', 8),
(4, 8, 'Jl. Pemuda No. 67, Solo, Jawa Tengah 57126', '-7.5667, 110.8167', 750000, 'berhasil', '2024-12-19 15:20:00', 'foto8.jpg', 'Donasi untuk program kesehatan. Minta update laporan setiap bulan.', NULL, 4);

-- Step 11: Insert fundraiser-donatur mappings
INSERT INTO `fundraiser_donatur_mapping` (`fundraiser_id`, `donatur_id`, `tanggal_assign`, `status`, `catatan`) VALUES
(3, 1, '2024-01-15', 'aktif', 'Donatur utama Ahmad Rizki'),
(3, 2, '2024-02-10', 'aktif', 'Donatur baru dari Bandung'),
(4, 3, '2024-01-05', 'aktif', 'CSR perusahaan besar'),
(5, 4, '2024-01-25', 'aktif', 'Donatur loyal Yogyakarta'),
(6, 5, '2024-02-01', 'aktif', 'Yayasan partner'),
(7, 6, '2024-03-10', 'aktif', 'Donatur baru Malang'),
(8, 7, '2024-02-20', 'aktif', 'Perusahaan lokal Semarang'),
(3, 8, '2024-03-05', 'aktif', 'Donatur Solo');

-- Step 12: Insert dashboard stats for fundraisers
INSERT INTO `fundraiser_dashboard_stats` (`fundraiser_id`, `tanggal`, `kunjungan_hari_ini`, `donasi_berhasil`, `total_donasi`, `donatur_baru`, `target_tercapai`) VALUES
(3, '2024-12-20', 7, 5, 2500000, 1, 87.50),
(4, '2024-12-20', 6, 4, 1800000, 0, 75.00),
(5, '2024-12-20', 8, 6, 2800000, 2, 100.00),
(6, '2024-12-20', 5, 3, 1900000, 0, 62.50),
(7, '2024-12-20', 9, 7, 3100000, 1, 112.50),
(8, '2024-12-20', 4, 2, 1500000, 0, 50.00);

COMMIT;
