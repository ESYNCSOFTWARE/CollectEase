<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collexa</title>
    <style>
        body {
            
            background: #f5f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            width: 400px;
            margin: 80px auto;
                background: #0f172a;  
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px 24px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 24px;
            color: #333;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #fffefeff;
            font-size: 15px;
        }
        .form-group input {
            width: 95%;
            padding: 10px 12px;
            border: 1px solid #dcdde1;
            border-radius: 4px;
            font-size: 15px;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            border-color: #4078c0;
            outline: none;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background: #4078c0;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .login-btn:hover {
            background: #305a8d;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2><img alt="Logo" style="max-width: 270px;height:90px" src="assets/media/logos/Collexa.png" /></h2>
        <form method="POST" action="{{route('login')}}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>
