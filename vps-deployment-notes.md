# VPS Deployment Notes (Dummy Mode Reminder)

## Kondisi Dummy Saat Ini (Local Development)

Fitur dummy provider FASHN sedang aktif untuk menghindari pemakaian credit real:

- `FASHN_DUMMY_ENABLED=true`
- `FASHN_DUMMY_RESULT_URL=https://cdn.fashn.ai/22707ed1-d62f-480f-a7e3-7d56387cfc70/try_on_0.png`
- `TRYON_DUMMY_MODEL_IMAGE_URL=https://mangcoding.com/wp-content/uploads/2026/05/4af9fc7d-b518-42fe-9fce-0e0db27c5567.jpg`

Jika ini tetap aktif di VPS, sistem **tidak akan memanggil API FASHN real**.
Jika `TRYON_DUMMY_MODEL_IMAGE_URL` tetap aktif di VPS, input model image akan selalu override dari URL dummy (bukan upload user).

---

## Wajib Diubah Saat Setup VPS (Production/Pilot Real)

1. Nonaktifkan dummy:
   - `FASHN_DUMMY_ENABLED=false`
2. Opsional: kosongkan dummy URL:
   - `FASHN_DUMMY_RESULT_URL=`
3. Nonaktifkan dummy model image:
   - `TRYON_DUMMY_MODEL_IMAGE_URL=`
4. Pastikan env real provider terisi:
   - `FASHN_BASE_URL=https://api.fashn.ai/v1/run`
   - `FASHN_STATUS_URL_TEMPLATE=https://api.fashn.ai/v1/status/{job_id}`
   - `FASHN_MODEL=tryon-max`
5. Isi API key melalui menu `Settings` di dashboard seller (bukan dari `.env`).

Setelah ubah env, jalankan:

```bash
php artisan optimize:clear
php artisan config:cache
```

---

## Catatan Tambahan

- Mode quality sudah dikunci ke mode termurah (`standard`) dari backend.
- URL produk sudah support dua format:
  - slug (contoh: `/ceriakid/gamis-anak-perempuan-fadia`)
  - SKU (contoh: `/ceriakid/NA1`)
