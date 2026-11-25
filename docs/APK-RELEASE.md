# Rilis APK Android

Halaman `/download` dan endpoint `/api/app-version` **mendeteksi otomatis** berkas APK
yang ada di penyimpanan, jadi tidak perlu mengubah kode setiap ada versi baru.

## Menaruh versi baru

1. Salin berkas APK ke folder:

   ```
   storage/app/public/mobile/
   ```

   Contoh nama berkas: `decor-v1.0.2.apk`

2. Nama berkas **wajib memuat versi** dengan format `vX.Y.Z`
   (mis. `decor-v1.0.2.apk`, `decor-1.0.2.apk`). Versi ini yang ditampilkan
   di halaman download dan direspons API. Kalau tidak ada versi di nama berkas,
   tanggal modifikasi berkas dipakai sebagai versi.

3. Selesai. Halaman `/download` dan `/api/app-version` otomatis memakai berkas
   dengan **waktu modifikasi terbaru**. Tidak perlu `view:clear` atau restart.

4. URL unduhannya:

   ```
   /storage/mobile/<nama-berkas>
   ```

   Ukuran berkas, tanggal rilis, dan SHA-256 dihitung otomatis
   (SHA-256 di-cache 7 hari per berkas).

## Penting: jangan lewat `git checkout`

Di server, folder `storage` adalah **symlink** ke penyimpanan persisten
(mis. `/srv/<tenant>/data/storage`). Berkas APK juga di-*ignore* oleh git.

Jangan menaruh APK lewat commit lalu `git checkout <path>` di bawah `storage/`,
karena Git akan mengganti symlink `storage` dengan folder biasa dan membuat
berkas aplikasi (gambar produk, sesi, cache) tidak lagi terbaca.

Cara yang benar di server:

- unggah langsung ke `storage/app/public/mobile/` lewat file manager panel
  atau SSH/SFTP, **atau**
- salin dari berkas yang sudah ada di server:

  ```
  cp /path/ke/decor-v1.0.2.apk storage/app/public/mobile/decor-v1.0.2.apk
  ```

Kalau `storage` sampai tertimpa folder biasa, pulihkan symlink-nya:

```
rm -rf storage
ln -s /srv/<tenant>/data/storage storage
```

## Menghapus versi lama

Halaman download hanya menampilkan versi terbaru (tidak ada daftar versi lama).
Berkas versi lama tetap bisa diakses lewat URL langsung, jadi hapus berkasnya
bila memang tidak ingin bisa diunduh:

```
rm storage/app/public/mobile/decor-v1.0.0.apk
```

## Verifikasi

```
curl -s https://<domain>/api/app-version
```

Respons berisi `version`, `url`, `size_bytes`, dan `sha256` rilis terbaru.
Bandingkan `sha256` dengan hasil `sha256sum` berkas asli untuk memastikan
unggahan tidak rusak.