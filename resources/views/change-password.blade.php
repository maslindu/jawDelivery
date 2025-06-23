<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Change Password</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background-color: white;
            border: 2px solid #333;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #333;
            border-radius: 25px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f8f8;
        }

        .form-input:focus {
            outline: none;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
        }

        .form-input::placeholder {
            color: #666;
            font-size: 14px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px 24px;
            background-color: #ffc107;
            border: 2px solid #333;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #ffb300;
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .form-title {
                font-size: 20px;
            }
        }
    </style>
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
                    placeholder="username"
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
                    placeholder="nama lengkap user"
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
                    placeholder="email@email.com"
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