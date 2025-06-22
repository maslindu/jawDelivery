<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Change Password</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/change-password.css') }}">
</head>
<body>
    <div class="container">
        <h1 class="form-title">Ubah Password</h1>
        
        <form action="#" method="POST">
            <div class="form-group">
                <label for="old-password" class="form-label">Masukkan Password Lama</label>
                <input 
                    type="password" 
                    id="old-password" 
                    name="old_password" 
                    class="form-input" 
                    placeholder="masukkan password lama"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="new-password" class="form-label">Password Baru</label>
                <input 
                    type="password" 
                    id="new-password" 
                    name="new_password" 
                    class="form-input" 
                    placeholder="minimal 8 karakter"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="confirm-password" class="form-label">Konfirmasi Password baru</label>
                <input 
                    type="password" 
                    id="confirm-password" 
                    name="confirm_password" 
                    class="form-input" 
                    placeholder="masukkan kembali password baru"
                    required
                >
            </div>
            
            <button type="submit" class="submit-btn">Ubah Password</button>
        </form>
    </div>

    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Password baru dan konfirmasi password tidak cocok!');
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('Password baru harus minimal 6 karakter!');
                return false;
            }
        });
    </script>
</body>
</html>