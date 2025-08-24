<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPEN-DLH</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        /* Mengimpor font Poppins dari Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        /* Variabel warna agar mudah diubah jika perlu */
        :root {
            --primary-green: #4CAF50;
            --dark-green: #388E3C;
            --text-color: #333;
            --gray-icon: #aaa;
        }

        /* Reset dasar untuk semua elemen */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Mengatur body utama */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #66BB6A, #388E3C);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: var(--text-color);
        }

        /* Kotak form login putih di tengah */
        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Logo daun di dalam lingkaran */
        .logo {
            background-color: var(--primary-green);
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .login-container h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .login-container p {
            font-size: 0.9rem;
            margin-bottom: 30px;
            color: #666;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .input-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .input-group .icon {
            position: absolute;
            left: 15px;
            top: 60%;
            transform: translateY(-50%);
            color: var(--gray-icon);
        }

        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--primary-green);
        }
        
        .login-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: var(--dark-green);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .login-button:hover {
            background-color: #2E7D32;
        }

        .demo-info {
            margin-top: 25px;
            font-size: 0.8rem;
            color: #555;
        }
        
        .demo-info p {
            margin-bottom: 2px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="login-container">
         <img src="{{ asset('assets/Logoo_DLH.png') }}" alt="Logo DLH" style="width:150px;height:80px;object-fit:contain;">
        <h1>SIPEN-DLH</h1>
        <p>Sistem Informasi Pengarsipan Surat<br>Dinas Lingkungan Hidup</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf 

            <div class="input-group">
                <label for="username">Username</label>
                <i class="fa-solid fa-user icon"></i>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <i class="fa-solid fa-lock icon"></i>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="login-button">MASUK</button>
        </form>

        
    </div>

</body>
</html>