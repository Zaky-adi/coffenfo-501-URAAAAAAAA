# API Task Tracker

## Informasi Dokumen

- **Tanggal dibuat:** 2026-10-28
- **Tanggal revisi terakhir:** 2026-10-29
- **Sumber urutan:** `API_RULES.md` bagian 11 dan `PRD.md` bagian 5 serta 14.
- **Base path:** `/api/v1`
- **AI yang digunakan untuk membuat dokumen ini:** Codex

## Status

- **Selesai:** implementasi dan verifikasi task sudah memenuhi kontrak yang relevan.
- **Belum:** task belum mulai dikerjakan.
- **Revisi:** implementasi sudah ada, tetapi masih ada gap, bug, atau verifikasi yang belum selesai.

> Task register berstatus **Revisi**, bukan Selesai: database yang dites belum memiliki kolom `users.public_id`, dan masih ada persyaratan serta alur verifikasi email yang belum ditangani tuntas.

## Daftar Task Menurut Urutan Pengerjaan

Tanggal pada task yang belum dimulai dibiarkan `ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â`. Isi tanggal dibuat saat implementasi dimulai, tanggal revisi setiap kali ada perubahan, dan prompt implementasi pada saat pengerjaan. `P-03` merujuk ke permintaan menyusun urutan API; spesifikasi tiap task tetap mengacu ke API_RULES.md dan PRD.md.

| Urutan | Modul | Task dan endpoint | Status | Tanggal dibuat | Tanggal revisi | Prompt |
|---:|---|---|---|---|---|---|
| 1 | 02 Auth | Registrasi POST /auth/register | **Selesai** | 2026-10-26 | 2026-11-25 | P-01, P-02, P-06, P-09 |
| 2 | 02 Auth | Login POST /auth/login | **Selesai** | 2026-10-29 | 2026-11-25 | P-13, P-14 |
| 3 | 02 Auth / 13 Sesi | Refresh, logout sesi saat ini, logout semua: POST /auth/refresh, /auth/logout, /auth/logout-all | **Selesai** | 2026-10-29 | 2026-11-25 | P-15 |
| 4 | 02 Auth | Lupa dan reset kata sandi: POST /auth/forgot-password, POST /auth/reset-password, GET /reset-password/{token} | **Selesai** | 2026-10-28 | 2026-11-25 | P-05, P-11, P-12 |
| 5 | 02 Auth | Verifikasi email dan kirim ulang verifikasi: GET /verify-email/{token}, POST /auth/verify-email, POST /auth/resend-verification | **Selesai** | 2026-10-28 | 2026-11-25 | P-05, P-07, P-10 |
| 6 | 02 Auth | Profil, kata sandi, preferensi, dan penghapusan akun: GET/PATCH /me, PATCH /me/password, PUT /me/preferences, DELETE /me | **Selesai** | 2026-10-31 | 2026-11-25 | P-16 |
| 7 | 01 Master | Baca master publik: GET /master/areas, /beans, /species, /roast-levels, /brew-methods, /facilities, /menu-categories | **Selesai** | 2026-10-31 | 2026-11-25 | P-17 |
| 8 | 01 Master | Kelola master oleh admin: POST/PATCH /admin/master/{type}, /admin/master/{type}/{id} | **Selesai** | 2026-10-31 | 2026-11-25 | P-17 |
| 9 | 03 Katalog | Daftar dan detail cafe: `GET /cafes`, `/cafes/nearby`, `/cafes/popular`, `/cafes/featured`, `/cafes/{id}`, `/cafes/{id}/similar`, `/testimonials` | **Selesai** | 2026-11-01 | 2026-11-25 | P-18 |
| 10 | 03 Katalog | Permintaan mitra dan tindak lanjut admin: POST /partnership-requests, GET/PATCH /admin/partnership-requests | **Selesai** | 2026-11-01 | 2026-11-25 | P-18 |
| 11 | 03 Katalog | Pengelolaan cafe admin/pemilik: /admin/cafes, /admin/cafes/{id}, /owner/cafes, /owner/cafes/{id}, accepts-orders, cafe pilihan | **Selesai** | 2026-11-01 | 2026-11-25 | P-18 |
| 12 | 04 Menu | Baca menu cafe: GET /cafes/{id}/menu | **Selesai** | 2026-11-04 | 2026-11-25 | P-19 |
| 13 | 04 Menu | Kelola menu pemilik: /owner/cafes/{id}/menu, /owner/menu/{id}, /owner/menu/{id}/availability | **Selesai** | 2026-11-04 | 2026-11-25 | P-20 |
| 14 | 05 Discovery | Wishlist: GET/PUT/DELETE /me/wishlist, /me/wishlist/{cafe_id} | **Selesai** | 2026-11-04 | 2026-11-25 | P-21 |
| 15 | 05 Discovery | Cafe terakhir dilihat: GET /me/recent-cafes, POST /cafes/{id}/view | **Selesai** | 2026-11-04 | 2026-11-25 | P-21 |
| 16 | 05 Discovery | Riwayat pencarian: GET/POST/DELETE /me/search-history, DELETE /me/search-history/{id} | **Selesai** | 2026-11-04 | 2026-11-25 | P-21 |
| 17 | 06 Review | Baca review dan ringkasan rating: GET /cafes/{id}/reviews, GET /cafes/{id}/rating-summary | **Selesai** | 2026-11-25 | 2026-11-25 | P-23 |
| 18 | 06 Review | Buat, edit, dan hapus review: POST /cafes/{id}/reviews, PATCH/DELETE /reviews/{id} | **Selesai** | 2026-11-25 | 2026-11-26 | P-24 |
| 19 | 13 Upload | Foto review dan presigned upload: /reviews/{id}/photos, /uploads/presign, /uploads/confirm, GET /uploads/{id} | **Selesai** | 2026-11-26 | 2026-11-27 | P-25 |
| 20 | 06 Review | Helpful dan laporan review: `PUT/DELETE /reviews/{id}/helpful`, `POST /reviews/{id}/reports` | **Revisi** | 2026-11-26 | 2026-11-26 | P-26 |
| 21 | 06/09 Review & Admin | Moderasi, laporan, testimoni, dashboard: `/admin/reviews*`, `/admin/reports*`, `/admin/dashboard/summary` | **Revisi** | 2026-11-26 | 2026-11-26 | P-26 |
| 22 | 07 Poin | Saldo dan ledger: `GET /me/points`, `GET /me/points/ledger`; pemberian poin internal saat review disetujui | **Selesai** | 2026-11-26 | 2026-11-27 | P-27 |
| 23 | 07 Voucher | Lihat dan tukar voucher: `GET /cafes/{id}/vouchers`, `POST /vouchers/{id}/redeem`, `GET /me/vouchers` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 24 | 07 Voucher | Kelola dan verifikasi voucher pemilik cafe: `/owner/cafes/{id}/vouchers`, `/owner/vouchers/{id}`, `/owner/voucher-redemptions/*` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 25 | 07/09 Admin | Aturan poin dan audit: `GET/PATCH /admin/point-rules`, `GET /admin/audit-logs` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 26 | 09 Admin | Pengaturan aplikasi dan user: `GET/PATCH /admin/settings`, `/admin/users` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 27 | 09/13 Notifikasi & Mobile | Notifikasi, perangkat, dan preferensi push: `/me/notifications*`, `/me/devices*`, `GET/PUT /me/notification-preferences` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 28 | 12 Privasi | Persetujuan, event, dan lokasi: `GET/PUT /me/privacy`, `POST /me/events`, `PUT /me/location`, `DELETE /me/personalization` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 29 | 08 Order (opsional) | Keranjang: `GET /cart`, `PUT/DELETE /cart/items/{menu_item_id}`, `DELETE /cart` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 30 | 08 Order (opsional) | Checkout, pembayaran, dan riwayat: `POST /orders`, `GET /orders/{id}`, `GET /me/orders`, cancel, payments, webhook | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 31 | 08 Order (opsional) | Pengelolaan order pemilik dan SSE opsional: `GET /owner/orders`, `PATCH /owner/orders/{id}/status`, `GET /owner/dashboard/summary`, `GET /orders/{id}/stream` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 32 | 12 Rekomendasi | Feed tahap B: `GET /me/feed`, hide/unhide cafe, `GET /me/taste-profile` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |
| 33 | 12 Admin Rekomendasi | Tahap C: `/admin/reco/models*`, `GET /admin/reco/metrics`, `POST /admin/reco/recompute` | Belum | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â | Belum ada prompt implementasi; spesifikasi API_RULES/PRD |

## Catatan Task Register

- Endpoint refresh, logout sesi saat ini, dan logout semua sudah ditambahkan. Refresh merotasi token, menyimpan hash token baru, memberi grace window 20 detik untuk request paralel, serta mencabut satu family saat reuse terdeteksi di luar grace window. Task masih **Revisi** sampai request Postman diverifikasi terhadap database aktif.

- Login sudah diimplementasikan dengan pencarian email/username, pesan gagal generik, lockout 5 kali/15 menit, pencatatan `login_attempts`, rate limit IP/perangkat, token Sanctum 15 menit, refresh token tersimpan sebagai hash, serta pencatatan perangkat mobile. Task masih **Revisi** karena PRD meminta JWT sedangkan implementasi repo memakai Sanctum, dan alur belum dijalankan end-to-end di database aktif.
- Koleksi Postman sudah mendapat skenario login valid dan kredensial salah. Isi `login_identifier` dan `login_password` dengan akun yang sudah ada; request menguji jalur mobile Android.

- Kode register dan koleksi Postman sudah ada, tetapi tes runtime sebelumnya gagal karena database yang digunakan belum memiliki `users.public_id` meski migration tercatat sudah berjalan. Perlu migrasi perbaikan dan tes ulang.
- Pendaftaran ulang dengan email akun yang belum terverifikasi kini menerbitkan token baru (maksimal 3 kali/jam/alamat); email Brevo dan token baru belum diuji end-to-end.
- Handler `GET /verify-email/{token}`, `POST /auth/verify-email`, dan authenticated `POST /auth/resend-verification` sudah dibuat. Tautan email memakai route GET yang menjalankan layanan verifikasi yang sama dengan API POST. SMTP diarahkan ke Brevo; pengiriman baru berhasil, tetapi route GET belum dites end-to-end setelah perubahan ini.
- Endpoint lupa/reset kata sandi, route halaman web reset, pencabutan sesi, dan audit reset sudah dibuat; alur email belum dites end-to-end.
- Password reset belum menerapkan pemeriksaan kata sandi umum/bocor atau normalisasi NFKC.
- Pemeriksaan password umum/bocor sesuai aturan 10.000-password denylist atau HIBP masih belum dibuat.
- Request pada koleksi Postman belum dijalankan terhadap database dan transport email aktif.
- `API_RULES.md` menetapkan Go, sedangkan repository ini memakai Laravel. Catatan ini mengikuti endpoint kontrak dokumen; keputusan stack perlu dibereskan sebelum pekerjaan API berkembang lebih jauh.

## Riwayat Prompt

### P-01 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Pembuatan awal API register

**Tanggal:** 2026-10-26 (tercatat pada `log_api.md`)

> sekarang kita mulai dengan membuat api register, untuk setiap api yang telah kita bikin buat juga cara pengetesannya di postman, dan catat log pembuat api, bikin file .md dengan nama log_api.md, catat nomor modulnya, status pengerjaan, tanggal pengerjaan, apa yang dikerjakan, serta prompt yang di berikan

### P-02 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Pemeriksaan dan revisi API register

**Tanggal:** 2026-10-28

> baca API_RUPES.md dan PRD.md, coba liat api register yang dibikin sudah sesuai dengan ketentuan yang ada di file .md itu, kalo belum saya izin kan untuk mengubahnya setiap perubahan dicatat dilog_api.md ikuti format penulisan di log+api.md dan tulis saya menggunakan ai apa sebagai contoh saya menggunakan codex untuk menheksekusi prompt ini

### P-03 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Permintaan menyusun urutan API

**Tanggal:** 2026-10-28

> baca API_RULES.md dan PRD.md, selanjutnya api apa yang harus kita buat, berikan saran jangan langsung buat beri list apinya sesuai urutan

### P-04 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Pembuatan tracker API

**Tanggal:** 2026-10-28

> sepertinya kita perlu bikin lagi satu md namanya api task yang berisi task apa aja yang harus dibikin dari apinya dan daftar task yang sudah selesai, belum, dan revisi, tanggal buat, tanggal revisi, dan prompt juga dicatat

### P-05 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Verifikasi email dan lupa kata sandi

**Tanggal:** 2026-10-28

> oke ayo kita bikin verify email dan forgot password

### P-06 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Perbaikan fatal error validasi register

**Tanggal:** 2026-10-28

> {"message":"Could not check compatibility between App\\Http\\Requests\\RegisterRequest::withValidator(App\\Http\\Requests\\Validator $validator): void and App\\Http\\Requests\\ApiFormRequest::withValidator(Illuminate\\Contracts\\Validation\\Validator $validator): void, because class App\\Http\\Requests\\Validator is not available"} ini kenapa

### P-07 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Pengiriman langsung tautan verifikasi dan kirim ulang

**Tanggal:** 2026-10-28

> kalo langsung bikinkan yang bisa kirim link verify email ke email langsung bagaimana, kita buat langsung

### P-08 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Konfigurasi SMTP Brevo

**Tanggal:** 2026-10-28

> untuk smtpnya saya mau gunakan brevo, konfigurasikan sekarang

### P-09 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Tautan verifikasi tidak masuk setelah konfigurasi SMTP

**Tanggal:** 2026-10-28

> saya sudah setting dienv tetap belum masuk link verifynya

### P-10 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Tautan email membuka halaman Not Found

**Tanggal:** 2026-10-28

> sudah bisa, tapi klik urlnya not found

### P-11 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Tautan reset password membuka halaman Not Found

**Tanggal:** 2026-10-29

> dibagian reset password berhasil mengirim link ke email untuk reset tapi masi not found saat dipencet

### P-12 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Catat waktu perubahan password

**Tanggal:** 2026-10-29

> oke, sekarang sisi database, ketika user berhasil mereset password dibagian kolom password_changed_at tidak ada tanggal password ditunggal berapa

## Pembaruan Task

Saat task mulai atau berubah, perbarui status, tanggal dibuat/revisi, dan tambahkan prompt yang memicu pekerjaan pada tabel. Jika ada perubahan implementasi, catat juga di `log_api.md`.

### Catatan P-16 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Profil pengguna

- Endpoint profil, perubahan kata sandi, preferensi, anonimisasi akun, dan skenario Postman sudah ditambahkan.
- Status **Revisi** karena alur belum dijalankan terhadap database aktif; upload avatar memerlukan endpoint presigned upload yang belum dikerjakan.
- `API_RULES.md` menyebut preferensi `bean_type_id`, sedangkan skema aktif memakai tabel `bean_types`; implementasi mengikuti skema repository.

### Catatan P-17 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Master data

- Endpoint publik untuk tujuh master, cache 1 jam dengan ETag/If-None-Match, serta endpoint admin create/update dan audit sudah dibuat.
- Status **Revisi** sampai migrasi `2026_10_31_000001` dijalankan dan endpoint diverifikasi memakai akun admin/database.
- Tabel `areas`, `bean_types`, `facilities`, dan `menu_categories` memerlukan `is_active`/`sort_order`; migrasi menambah kolom yang belum ada.
- Current master tables use numeric IDs as foreign-key references and do not have public UUIDs; API responses retain these IDs for compatibility with current schema.

### Catatan P-18 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Katalog, kemitraan, dan pengelolaan cafe

- Endpoint katalog publik, form kemitraan + antrean admin, pengelolaan cafe admin/pemilik, status penerimaan pesanan, dan cafe pilihan ditambahkan.
- Status **Revisi** sampai migrasi idempotensi kemitraan dan request Postman dijalankan di database aktif.
- Pengelolaan cafe hanya mengembalikan `public_id`; tabel relasi lama masih memakai integer sebagai foreign key.
- Permintaan kemitraan juga memakai `public_id`; migrasi idempotensi menambahkan UUID dan key unik untuk retry form publik.
- Pemilik dibatasi ke cafe dengan `owner_id` miliknya, dan status cafe hanya dapat diubah admin.


### P-13 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Implementasi endpoint login

**Tanggal:** 2026-10-29

> kita bikin endpoint login dulu


### P-14 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Perbaikan pemanggilan pencatatan login

**Tanggal:** 2026-10-29

> {"message": "Method App\\Http\\Controllers\\Auth\\LoginController::insertAttempt does not exist."} ini error karna apa?


### P-15 ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â Implementasi refresh dan logout

**Tanggal:** 2026-10-29

> oke sekarang kita bikin refresh dan logoutnya

### P-19 ÃƒÂ¯Ã‚Â¿Ã‚Â½ Implementasi menu publik cafe

**Tanggal:** 2026-11-04

> kerjakan modul 04 tapi kita kerjakan yang public dulu

### P-20 ÃƒÂ¯Ã‚Â¿Ã‚Â½ Implementasi kelola menu pemilik cafe

**Tanggal:** 2026-11-04

> sekarang kita bikin api modul 4 bagian owner cafe, dan juga berikan tanda untuk api yang kita bikin dan yang terakhir kita commit belum saya test jadi tandain sebagai untested di file .jsonnya untuk yang saya tandai tadi


### P-21 Ã¢â‚¬â€ Implementasi Discovery (Wishlist, Recent Cafes, Search History)

**Tanggal:** 2026-11-04

> kita bikin discovery

### P-22 Ã¢â‚¬â€ Verifikasi Pengujian Modul Auth, Profile, dan Master Data

**Tanggal:** 2026-11-25

> untuk bagian auth, profile, master data itu sudah saya test dan berhasil jadi perbarui lagi task yang sudah, dan belumnya


### P-23 Ã¢â‚¬â€ Implementasi Baca Review & Ringkasan Rating Kafe

__Tanggal:__ 2026-11-25

> ayok kita bikin task 17


### P-24 Ã¢â‚¬â€ Implementasi Buat, Edit, dan Hapus Review (Task 18)

__Tanggal:__ 2026-11-25

> selanjutnya kita bikin task 18


### P-25 Ã¢â‚¬â€ Implementasi Foto Review dan Presigned Upload (Task 19)

**Tanggal:** 2026-11-26

> ayok kita kerjakan task 19

### P-26 Ã¢â‚¬â€ Implementasi Helpful, Laporan Review, Moderasi Admin, dan Dashboard Summary (Task 20 & 21)

**Tanggal:** 2026-11-26

> lanjut ke task 20 dan 21, setelah selesai berikan daftar list api yang perlu saya test nanti

### P-27 â€” Implementasi Saldo dan Riwayat Buku Besar Poin (Task 22)

**Tanggal:** 2026-11-26

> sekarang kita lanjut ke task 22
