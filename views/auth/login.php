<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication - Tạp Hóa Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* CSS bổ sung riêng cho phần chọn quyền (Permission Tags) */
        .permission-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .perm-checkbox {
            display: none; /* Ẩn checkbox mặc định */
        }

        .perm-label {
            padding: 10px 15px;
            border: 1px solid #e5e5e5;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
            text-transform: uppercase;
            font-family: 'Oswald', sans-serif;
            flex-grow: 1;
            text-align: center;
        }

        /* Hiệu ứng khi chọn (Style Nike Selected Size) */
        .perm-checkbox:checked + .perm-label {
            background-color: #000;
            color: #fff;
            border-color: #000;
        }

        .hidden { display: none; }
        .error-msg { color: red; margin-bottom: 15px; font-size: 0.9rem; }
        .success-msg { color: green; margin-bottom: 15px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand-section">
            <div class="brand-content">
                <h1>QUẢN LÝ<br>TẠP HÓA</h1>
                <p>ALL IN ONE PLACE.</p>
                <div class="hero-image">
                    <<img src="https://i.pinimg.com/736x/6b/89/5d/6b895d6b1fd3276a93669956982dc2bb.jpg" alt="Nike Art">
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-wrapper">
                
                <div class="tabs">
                    <button class="tab-btn active" onclick="switchTab('login')">Đăng nhập</button>
                    <button class="tab-btn" onclick="switchTab('register')">Đăng kí</button>
                </div>

                <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
                <?php if(isset($success)) echo "<p class='success-msg'>$success</p>"; ?>

                <form id="login-form" action="index.php?action=login_submit" method="POST" class="auth-form">
    <h2>MEMBER LOGIN</h2>
    <div class="input-group">
        <input type="text" name="email" placeholder="Email" required> 
    </div>
    <div class="input-group">
        <input type="password" name="password" placeholder="Mật khẩu" required>
    </div>
    <button type="submit" class="btn-black">ĐĂNG NHẬP</button>
</form>

                <form id="register-form" action="index.php?action=register_submit" method="POST" class="auth-form hidden">
                    <h2>BECOME A MEMBER</h2>
                    <div class="input-group">
                        <input type="text" name="username" placeholder="Tên hiển thị" required>
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" placeholder="Mật khẩu" required>
                    </div>

                    <p style="margin-bottom: 10px; font-weight: bold; font-family: 'Oswald'">CHỌN CHỨC NĂNG:</p>
                    <div class="permission-grid">
                        <input type="checkbox" id="uc1" name="permissions[]" value="UC1" class="perm-checkbox">
                        <label for="uc1" class="perm-label">Quản lý Hàng hóa</label>

                        <input type="checkbox" id="uc2" name="permissions[]" value="UC2" class="perm-checkbox">
                        <label for="uc2" class="perm-label">Nhập hàng & NCC</label>

                        <input type="checkbox" id="uc3" name="permissions[]" value="UC3" class="perm-checkbox">
                        <label for="uc3" class="perm-label">Thu ngân</label>

                        <input type="checkbox" id="uc4" name="permissions[]" value="UC4" class="perm-checkbox">
                        <label for="uc4" class="perm-label">Khách hàng</label>

                        
                    </div>

                    <button type="submit" class="btn-black">TẠO TÀI KHOẢN</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Xử lý active tab style
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(t => t.classList.remove('active'));
            
            // Xử lý hiển thị form
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            if (tab === 'login') {
                tabs[0].classList.add('active');
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
            } else {
                tabs[1].classList.add('active');
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>