<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JawDelivery - Login</title>
    <link href="https://fonts.cdnfonts.com/css/plus-jakarta-sans" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-weight: bold;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 32px;
            background-color: white;
            border: 2px solid black;
            border-radius: 24px;
        }

        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand h1 {
            font-size: 14px;
            margin-bottom: 24px;
        }

        .brand h2 {
            font-size: 32px;
            margin: 0;
        }

        /* Error Alert Styling */
        .alert {
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 12px;
            border: 2px solid;
        }

        .alert-danger {
            background-color: #fee;
            border-color: #fe4a49;
            color: #fe4a49;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert li {
            margin-bottom: 4px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert li:last-child {
            margin-bottom: 0;
        }

        /* When there's no list, just text */
        .alert:not(:has(ul)) {
            font-size: 14px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            box-sizing: border-box;
            border: 2px solid black;
            border-radius: 12px;
            font-size: 16px;
        }

        .forgot-password {
            text-align: right;
            margin-top: 8px;
        }

        .forgot-password a {
            color: #fe4a49;
            text-decoration: none;
            font-size: 14px;
        }

        .login-button {
            width: 100%;
            height: 48px;
            background-color: #fe4a49;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 8px;
        }

        .login-button:hover {
            opacity: 0.9;
        }

        .register {
            margin-top: 32px;
            text-align: center;
        }

        .register p {
            margin: 0 0 4px 0;
            font-size: 16px;
        }

        .register a {
            color: #fe4a49;
            text-decoration: none;
            font-size: 16px;
        }

        .register a:hover {
            text-decoration: underline;
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
    <div class="login-container">
        
        <div class="brand">
            <h1>JawDelivery</h1>
            <h2>LOGIN</h2>
        </div>
    
        @if ($errors->has('login_error'))
            <div class="error-box">
                {{ $errors->first('login_error') }}
            </div>
        @endif
    

        <form method="post" action="{{ route('api.auth.login') }}">
            @csrf <!-- This was missing! -->
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required value="{{ old('username') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <div class="forgot-password">
                    <a href="">Forgot Password?</a>
                </div>
            </div>


            <button type="submit" class="login-button">LOGIN</button>
        </form>

        <div class="register">
            <p>Don't Have an Account?</p>
            <a href="">Register Now!</a>
        </div>
    </div>
</body>
</html>