<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <title>JawDelivery - Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-weight: bold;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: white;
            border: 2px solid rgb(0, 0, 0);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand {
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
        }

        .title {
            font-size: 36px;
            color: #000;
            letter-spacing: 1px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(0, 0, 0, 0.8);
            border-radius: 8px;
            font-size: 14px;
            background: white;
            transition: border-color 0.2s ease;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #fe4a49;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .submit-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .register-btn {
            background: #fe4a49;
            color: white;
            border: none;
            padding: 16px 0;
            border-radius: 50px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            max-width: 400px;
            transition: background-color 0.2s ease;
        }

        .register-btn:hover {
            background: #e63946;
        }

        .login-section {
            text-align: center;
        }

        .login-text {
            font-size: 14px;
            color: #333;
            margin-bottom: 4px;
        }

        .login-link {
            color: #fe4a49;
            text-decoration: none;
            font-size: 14px;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 24px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .title {
                font-size: 28px;
            }
        }
        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-box ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-box li {
            list-style-type: disc;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="header">
            <div class="brand">JawDelivery</div>
            <h1 class="title">REGISTER</h1>
        </div>
        @if ($errors->has('login_error'))
            <div class="error-box">
                {{ $errors->first('login_error') }}
            </div>
        @endif

        <form action="{{ route('api.auth.register') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="fullName">Nama lengkap</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telpon</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <div class="submit-section">
                <button type="submit" class="register-btn">REGISTER</button>
                
                <div class="login-section">
                    <div class="login-text">Already Have Account?</div>
                    <a href="/login" class="login-link">Login Now!</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>