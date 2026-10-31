<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <title>Reset Kata Sandi Coffenfo</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5efeb; color: #3d2c24; margin: 0; padding: 24px; }
        main { box-sizing: border-box; max-width: 440px; margin: 8vh auto; padding: 32px; background: #fff; border-radius: 20px; }
        label { display: block; margin: 18px 0 6px; font-weight: 600; }
        input, button { box-sizing: border-box; width: 100%; padding: 12px; border-radius: 10px; }
        input { border: 1px solid #ccc; }
        button { margin-top: 22px; color: #fff; background: #3d2c24; border: 0; cursor: pointer; }
        #feedback { margin-top: 16px; }
    </style>
</head>
<body>
    <main>
        <h1>Atur kata sandi baru</h1>
        <p>Masukkan kata sandi baru untuk akun Coffenfo Anda. Tautan berlaku selama 30 menit.</p>
        <form id="reset-password-form">
            <label for="new_password">Kata sandi baru</label>
            <input id="new_password" name="new_password" type="password" minlength="8" maxlength="128" autocomplete="new-password" required>
            <label for="new_password_confirmation">Ulangi kata sandi baru</label>
            <input id="new_password_confirmation" name="new_password_confirmation" type="password" minlength="8" maxlength="128" autocomplete="new-password" required>
            <button type="submit">Simpan kata sandi</button>
        </form>
        <p id="feedback" role="status" aria-live="polite"></p>
    </main>
    <script>
        const form = document.getElementById('reset-password-form');
        const feedback = document.getElementById('feedback');
        const token = @json($token);

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const password = form.new_password.value;
            const confirmation = form.new_password_confirmation.value;
            if (password !== confirmation) {
                feedback.textContent = 'Konfirmasi kata sandi tidak cocok.';
                return;
            }

            feedback.textContent = 'Menyimpan kata sandi…';
            try {
                const response = await fetch(@json(url('/api/v1/auth/reset-password')), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ token, new_password: password })
                });
                const result = await response.json();
                feedback.textContent = response.ok
                    ? 'Kata sandi berhasil diubah. Silakan masuk dengan kata sandi baru.'
                    : (result.error?.message || 'Tautan reset tidak valid atau sudah kedaluwarsa.');
                if (response.ok) form.remove();
            } catch (_) {
                feedback.textContent = 'Tidak dapat terhubung ke server. Silakan coba lagi.';
            }
        });
    </script>
</body>
</html>
