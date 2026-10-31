## API Development Log

### Module 02 — Profil & Preferensi Pengguna
- **Status:** Revisi (endpoint telah dibuat; verifikasi database/Postman dan alur avatar menunggu modul presigned upload)
- **Date:** 2026-10-31
- **Work done:**
  - Added authenticated `GET/PATCH /api/v1/me` for profile read/update, returning `public_id` and excluding internal numeric IDs and credentials.
  - Added `PATCH /api/v1/me/password`; verifies the current password, hashes the replacement, records `password_changed_at`, revokes access/refresh sessions and push tokens, and writes an audit event.
  - Added `PUT /api/v1/me/preferences` for bean and roast preferences, including null to clear either preference.
  - Added `DELETE /api/v1/me` with password confirmation, session/token revocation, personal-data anonymization, privacy consent withdrawal, and deletion of personalization data.
  - Added profile, password, preference, and account deletion Postman requests. Requests have not been run against an active database.
  - Avatar update accepts only a ready, unused avatar upload belonging to the authenticated user. Presigned upload API is not implemented yet, so this path awaits that module.
  - Repository uses Laravel/Sanctum while `API_RULES.md` specifies Go/JWT; this remains a contract/stack gap.
- **AI:** Codex
- **Prompt:** "kerjakan yang profil dan preferensi pengguna, jangan lupa catat log nya ya, nanti list apa yang baru dibuat"

### Module 02 – Auth & User
- **Status:** Completed (Register API implemented)
- **Date:** 2026-10-26
- **Work done:**
  - Added `RegisterRequest` with validation rules.
  - Created `RegisterController` handling user creation, related rows, email verification token generation, and JSON response.
  - Defined route `POST /api/auth/register` in `routes/api.php`.
  - Added Postman collection entry `Auth / Register` (see `Coffenfo.postman_collection.json`).
- **Prompt:** "sekarang kita mulai dengan membuat api register, untuk setiap api yang telah kita bikin buat juga cara pengetesannya di postman, dan catat log pembuat api, bikin file .md dengan nama log_api.md, catat nomor modulnya, status pengerjaan, tanggal pengerjaan, apa yang dikerjakan, serta prompt yang di berikan"

### Module 02 – Auth & User
- **Status:** Updated (Register API aligned with `API_RULES.md` and `PRD.md`; common-password screening still needs follow-up)
- **Date:** 2026-10-28
- **Work done:**
  - Normalized email and username; added optional username and consent validation, password length/name-email checks, unknown-field rejection, and the standard 422 response envelope.
  - Changed duplicate-email handling to return the same 202 response as a new registration and notify the existing address, preventing account-existence disclosure in the API response.
  - Created the user, coffee preferences, zero-balance points row, privacy settings, and hashed 24-hour verification token atomically.
  - Added verification and existing-account email notifications; set bcrypt's default cost to 12.
  - Moved the endpoint to `POST /api/v1/auth/register` and applied the API rule of 5 requests per hour per IP.
  - Removed the `log_api.md` ignore rule so this API log change is visible to Git.
  - Verified PHP syntax for changed files and confirmed the route with `php artisan route:list --path=v1/auth/register`.
  - Added `Coffenfo.postman_collection.json` with new-account, duplicate-email, and validation requests.
  - Note: the 10,000-password denylist/HIBP check remains outstanding; the repository also has no email-verification handler for the notification link yet. The Postman collection was syntax-checked but its requests were not run against a live database/mail server.
- **AI:** Codex
- **Prompt:** "baca API_RUPES.md dan PRD.md, coba liat api register yang dibikin sudah sesuai dengan ketentuan yang ada di file .md itu, kalo belum saya izin kan untuk mengubahnya setiap perubahan dicatat dilog_api.md ikuti format penulisan di log+api.md dan tulis saya menggunakan ai apa sebagai contoh saya menggunakan codex untuk menheksekusi prompt ini"

### Module 02 – Auth & User
- **Status:** Revisi (email verification and password recovery endpoints implemented; live database/email verification pending)
- **Date:** 2026-10-28
- **Work done:**
  - Added `POST /api/v1/auth/verify-email`; it validates and consumes the hashed, single-use 24-hour token and marks the user email verified.
  - Added `POST /api/v1/auth/forgot-password` with a generic 200 response for known and unknown addresses, per-email rate limiting, a hashed single-use 30-minute token, and reset email delivery.
  - Added `POST /api/v1/auth/reset-password`; it consumes the token once, updates the password, revokes all active refresh tokens, and sends a password-change notice.
  - Added an append-only `password.reset` audit entry within the reset transaction; tokens and passwords are not included in logs.
  - Added shared API validation response handling and Postman requests for verification, forgot password, and reset password.
  - Verified PHP syntax, Laravel route registration, and Postman collection JSON parsing. Requests were not run against the live database or mail transport.
  - Remaining at that point: password common/breached screening and NFKC normalization, plus end-to-end mail/database verification. `POST /auth/resend-verification` was added in the later entry below.
- **AI:** Codex
- **Prompt:** "oke ayo kita bikin verify email dan forgot password"

### Module 02 – Auth & User
- **Status:** Revisi (register validation fatal error fixed)
- **Date:** 2026-10-28
- **Work done:**
  - Removed the `RegisterRequest::withValidator()` override whose `Validator` type resolved to the wrong namespace after extraction of shared API validation handling. Unknown-field rejection now comes from `ApiFormRequest`.
  - Checked PHP syntax and class loading for `RegisterRequest`.
- **AI:** Codex
- **Prompt:** "{\"success\": false, \"error\": {\"code\": \"VALIDATION_ERROR\", \"message\": \"Data tidak valid.\", \"details\": [{\"field\": \"token\", \"message\": \"The token field is required.\"}]}, \"request_id\": \"50d851d5-ade4-4dd3-86a3-8ef52a8682d5\"} bagaimana melakukan pengetesan untuk verify email nya dipostman"
- **Follow-up prompt:** "{\"message\": \"Could not check compatibility between App\\\\Http\\\\Requests\\\\RegisterRequest::withValidator(App\\\\Http\\\\Requests\\\\Validator $validator): void and App\\\\Http\\\\Requests\\\\ApiFormRequest::withValidator(Illuminate\\\\Contracts\\\\Validation\\\\Validator $validator): void, because class App\\\\Http\\\\Requests\\\\Validator is not available\"} ini kenapa"

### Module 02 – Auth & User
- **Status:** Revisi (resend-verification endpoint added; real email delivery awaits an active SMTP service)
- **Date:** 2026-10-28
- **Work done:**
  - Added authenticated `POST /api/v1/auth/resend-verification`; it rotates the unverified account's email token, stores only its SHA-256 hash, and sends a new 24-hour verification link.
  - Added per-user resend rate limiting and a matching Postman request that requires a valid `access_token`.
  - Checked local configuration and recent logs: `MAIL_MAILER=smtp`, `MAIL_HOST=mailpit`, port `1025`; both `mailpit:1025` and `127.0.0.1:1025` are unreachable and Laravel logged a mail transport exception.
  - No SMTP credentials were added to source files or changed in `.env`.
- **AI:** Codex
- **Prompt:** "kalo langsung bikinkan yang bisa kirim link verify email ke email langsung bagaimana, kita buat langsung"

### Module 02 – Auth & User
- **Status:** Revisi (Brevo SMTP transport configured; account credentials and verified sender are still required)
- **Date:** 2026-10-28
- **Work done:**
  - Updated local `.env` to use Brevo SMTP host `smtp-relay.brevo.com`, port `587`, and TLS encryption.
  - Set explicit placeholders for the Brevo SMTP login, SMTP key, and verified sender address; no secrets were added to code or this log.
  - Cleared Laravel's configuration cache and verified the active mailer/host/port/encryption settings.
  - Real delivery remains unverified until the user enters Brevo account values and a verified sender. Reference: https://help.brevo.com/hc/en-us/articles/7924908994450-Send-transactional-emails-using-Brevo-SMTP
- **AI:** Codex
- **Prompt:** "untuk smtpnya saya mau gunakan brevo, konfigurasikan sekarang"

### Module 02 – Auth & User
- **Status:** Revisi (Brevo selected; mail transport configuration restored and unverified registrations can request a fresh token through register)
- **Date:** 2026-10-28
- **Work done:**
  - Diagnosed that the active Laravel mailer had been changed to `log` with Mailpit host/port, so it was not sending to an inbox.
  - Restored Brevo SMTP host, port 587, TLS, and Coffenfo sender name while preserving the user's SMTP login/key/sender values.
  - Changed duplicate registration for an existing unverified account to issue and mail a fresh 24-hour token, with a 3-per-hour email rate limit; API response remains generic.
  - Cleared Laravel config cache and confirmed the active mailer configuration. No verification email was sent as part of this check.
- **AI:** Codex
- **Prompt:** "saya sudah setting dienv tetap belum masuk link verifynya"

### Module 02 – Auth & User
- **Status:** Revisi (GET verification link route added; browser flow pending end-to-end check)
- **Date:** 2026-10-28
- **Work done:**
  - Added `GET /verify-email/{token}` to handle the link opened from verification emails and render a success/expired page.
  - Extracted token consumption into `EmailVerificationService`, shared by the web link and `POST /api/v1/auth/verify-email`.
  - Updated the notification to generate a named Laravel route URL from the active request host instead of concatenating `APP_URL`.
  - Set `Referrer-Policy: no-referrer` and `Cache-Control: no-store` on the link response; the raw token is not displayed in the page.
  - Verified PHP syntax, route registration, and Blade view compilation.
- **AI:** Codex
- **Prompt:** "sudah bisa, tapi klik urlnya not found"

### Module 02 – Auth & User
- **Status:** Revisi (halaman tautan reset password ditambahkan; perlu dicoba dari email pada server lokal)
- **Date:** 2026-10-29
- **Work done:**
  - Menambahkan route web `GET /reset-password/{token}` dan halaman form untuk memasukkan serta mengonfirmasi kata sandi baru.
  - Form mengirim token ke `POST /api/v1/auth/reset-password`; token tetap single-use dan masa berlaku 30 menit ditangani endpoint yang sudah ada.
  - Mengubah email reset agar memakai named route Laravel, konsisten dengan host aplikasi yang melayani permintaan.
  - Menambahkan `Referrer-Policy: no-referrer` dan `Cache-Control: no-store` pada halaman reset agar token URL tidak diteruskan atau disimpan cache.
  - Memeriksa sintaks PHP, daftar route, dan kompilasi Blade; belum melakukan reset password end-to-end melalui email.
- **AI:** Codex
- **Prompt:** "dibagian reset password berhasil mengirim link ke email untuk reset tapi masi not found saat dipencet"

### Module 02 – Auth & User
- **Status:** Revisi (reset password kini mencatat waktu perubahan pada `users.password_changed_at`; perlu verifikasi melalui reset aktual)
- **Date:** 2026-10-29
- **Work done:**
  - Saat reset password berhasil, menyimpan waktu saat ini ke `users.password_changed_at` bersama hash password baru dalam transaksi yang sama.
  - Menambahkan cast `password_changed_at` ke `datetime` pada model `User`.
  - Memastikan migration `2026_10_26_000005_create_auth_user_support_tables` sudah mendefinisikan kolom nullable tersebut; tidak perlu migration tambahan.
  - Memeriksa sintaks PHP; belum menjalankan reset terhadap database aktif.
- **AI:** Codex
- **Prompt:** "oke, sekarang sisi database, ketika user berhasil mereset password dibagian kolom password_changed_at tidak ada tanggal password ditunggal berapa"


### Module 02 – Auth & User
- **Status:** Revisi (login endpoint implemented; database/Postman end-to-end verification and JWT contract alignment remain open)
- **Date:** 2026-10-29
- **Work done:**
  - Added `POST /api/v1/auth/login` with case-insensitive email/username lookup, generic invalid-credential responses, dummy password hashing for unknown identifiers, and validation that rejects unknown request fields.
  - Added failed-login tracking, configurable five-attempt/15-minute account lockout, lockout audit entry, IP/device rate limits, and login-attempt records containing only a hashed identifier.
  - Issued 15-minute Sanctum bearer access tokens and 14-day random refresh tokens stored as SHA-256 hashes; web receives the refresh token in a Secure, HttpOnly, SameSite=Strict cookie, while Android/iOS receives it in the JSON body.
  - Recorded mobile device details and created an in-app notification for a newly observed mobile device.
  - Updated password reset to revoke Sanctum access tokens as well as refresh-token rows, so the newly added login sessions are revoked after reset.
  - Added Postman requests for successful Android login and generic invalid credentials. They are documented but were not run against a live database.
  - Did not run automated tests. PRD/API_RULES specify JWT; this repository currently uses Sanctum and has no JWT package, so the task remains Revisi pending a stack/contract decision and end-to-end verification.
- **AI:** Codex
- **Prompt:** "kita bikin endpoint login dulu"
### Module 02 – Auth & User
- **Status:** Revisi (perbaikan bug pemanggilan method login; verifikasi runtime database masih perlu dilakukan)
- **Date:** 2026-10-29
- **Work done:**
  - Fixed the successful-login branch to call the existing `recordAttempt()` method instead of the removed `insertAttempt()` method.
  - Confirmed no remaining `insertAttempt` references in `LoginController` and checked PHP syntax.
  - Postman/database runtime verification remains outstanding.
- **AI:** Codex
- **Prompt:** "{\"message\": \"Method App\\\\Http\\\\Controllers\\\\Auth\\\\LoginController::insertAttempt does not exist.\"} ini error karna apa?"
### Module 02 / 13 – Auth, Refresh & Sesi
- **Status:** Revisi (refresh, logout current session, and logout-all implemented; live database/Postman verification pending)
- **Date:** 2026-10-29
- **Work done:**
  - Added `POST /api/v1/auth/refresh` for web refresh-token cookies and Android/iOS JSON refresh tokens. Successful refresh issues a new 15-minute Sanctum access token and a new random 14-day refresh token; only its SHA-256 hash is stored.
  - Rotated refresh-token rows in a transaction with `FOR UPDATE`, linked replacements using `replaced_by_id`, honored the 20-second grace window for parallel refresh requests, and revoked a token family plus its access tokens after reuse outside the grace window. Reuse is recorded in append-only `audit_logs`.
  - Added authenticated `POST /api/v1/auth/logout` to revoke the current token family, access tokens, device push token, and web refresh cookie; added `POST /api/v1/auth/logout-all` to revoke all user sessions and push tokens.
  - Added Postman requests for refresh, current-session logout, and logout-all, including instructions to log in again before running logout-all after current-session logout.
  - Automated tests and live Postman requests were not run. Existing PRD specifies JWT while this repository issues Sanctum tokens; the task remains Revisi pending that contract decision and runtime verification.
- **AI:** Codex
- **Prompt:** "oke sekarang kita bikin refresh dan logoutnya"
