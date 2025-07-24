<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | KnowledgeBase Forum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #1a2a6c);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        
        .login-header {
            background: linear-gradient(to right, #2c3e50, #4a6491);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
        }
        
        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .logo i {
            font-size: 2.5rem;
            margin-right: 15px;
            color: #3498db;
            background: white;
            border-radius: 50%;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .logo-text {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .logo-text span {
            color: #3498db;
        }
        
        .header-title {
            font-size: 1.2rem;
            font-weight: 400;
            margin-top: 10px;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .input-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }
        
        .input-group input {
            width: 100%;
            padding: 14px 20px 14px 50px;
            border: 2px solid #e1e5eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .input-group input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            bottom: 15px;
            color: #7f8c8d;
            font-size: 1.2rem;
        }
        
        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }
        
        .remember {
            display: flex;
            align-items: center;
        }
        
        .remember input {
            margin-right: 8px;
        }
        
        .forgot-password {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #2980b9;
            text-decoration: underline;
        }
        
        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #3498db, #2980b9);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        }
        
        .login-btn:hover {
            background: linear-gradient(to right, #2980b9, #2573a7);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.5);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
        }
        
        .divider span {
            flex: 1;
            height: 1px;
            background: #e1e5eb;
        }
        
        .divider p {
            padding: 0 15px;
            color: #7f8c8d;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .security-tips {
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-top: 30px;
            font-size: 0.9rem;
        }
        
        .security-tips h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        
        .security-tips ul {
            padding-left: 20px;
            color: #7f8c8d;
        }
        
        .security-tips li {
            margin-bottom: 8px;
        }
        
        .login-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            color: #7f8c8d;
            font-size: 0.85rem;
        }
        
        .login-footer a {
            color: #3498db;
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .error-item {
            color: #c33;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .error-item:last-child {
            margin-bottom: 0;
        }
        
        @media (max-width: 500px) {
            .login-container {
                border-radius: 15px;
            }
            
            .login-header {
                padding: 20px;
            }
            
            .logo {
                flex-direction: column;
                text-align: center;
            }
            
            .logo i {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .login-body {
                padding: 20px;
            }
            
            .options {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .forgot-password {
                margin-top: 10px;
            }
        }
        
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-brain"></i>
                <div class="logo-text">Knowledge<span>Base</span></div>
            </div>
            <h2 class="header-title">Admin Portal Login</h2>
        </div>
        
        <div class="login-body">
            <form id="loginForm" method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="input-group">
                    <label for="username">Admin ID</label>
                    <i class="fas fa-user-shield"></i>
                    <input type="text" id="username" name="username" placeholder="Enter your admin ID" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <div class="options">
                    <div class="remember">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="login-btn">Access Admin Dashboard</button>
                
                <div class="divider">
                    <span></span>
                    <p>Security Notice</p>
                    <span></span>
                </div>
                
                @if($errors->any())
                    <div class="error-message">
                        @foreach($errors->all() as $error)
                            <div class="error-item">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="error-message">
                        <div class="error-item">{{ session('error') }}</div>
                    </div>
                @endif
            </form>
            
            <div class="security-tips">
                <h3>Admin Security Best Practices:</h3>
                <ul>
                    <li>Never share your admin credentials with anyone</li>
                    <li>Always log out after your admin session</li>
                    <li>Use a strong, unique password for your admin account</li>
                    <li>Enable two-factor authentication if available</li>
                </ul>
            </div>
        </div>
        
        <div class="login-footer">
            <p>© 2023 KnowledgeBase Q&A Forum. Strictly for authorized personnel only.</p>
            <p>Contact <a href="mailto:security@knowledgebase.com">security@knowledgebase.com</a> for assistance</p>
        </div>
    </div>
</body>
</html>