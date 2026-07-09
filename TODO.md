# TODO - Perbaikan Import Excel Barang

- [ ] Tambahkan logging & validasi detail pada `BarangController@import_ajax` untuk mengetahui kenapa baris tidak masuk.
- [ ] Ubah strategi insert dari `insertOrIgnore` menjadi insert yang lebih aman + tangkap duplikat/konflik.
- [ ] Pastikan response JSON selalu mengembalikan informasi jumlah baris diproses + jumlah tersimpan/diabaikan.
- [ ] Perbaiki frontend `import.blade.php` agar error validation tampil sesuai struktur Laravel (opsional bila masih perlu).
- [ ] Jalankan test manual: upload template `template_barang.xlsx` dan pastikan data masuk.

