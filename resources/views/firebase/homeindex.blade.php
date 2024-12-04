<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabula - Event Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #3155FE;
            --background-gray: #F5F5F5;
            --text-color: #333333;
        }

        .navbar-custom {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-primary-custom {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
        }

        .btn-primary-custom:hover {
            background-color: #2844db;
            border-color: #2844db;
        }

        .btn-outline-custom {
            color: var(--text-color);
            border-color: var(--primary-blue);
        }

        .btn-outline-custom:hover {
            background-color: var(--primary-blue);
            color: white;
        }

        .main-content {
            background-color: var(--background-gray);
            min-height: calc(100vh - 70px);
            padding-top: 80px;
        }

        .stats-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .text-primary-custom {
            color: var(--primary-blue);
        }

        .home-logo {
            margin-right: 250px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <!-- Logo -->
        
                <img class="home-logo" src="{{ asset('tabulaLOGO.png') }}" alt="Tabula Logo" height="50">
            

            <!-- Auth Buttons -->
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="home-login-btn btn btn-outline-custom me-2">Sign In</a>
                <a href="{{ route('register') }}" class="home-signup-btn btn btn-primary-custom">Sign Up</a>
            </div>
        </div>
    </nav>

    

    <!-- Bootstrap JS and Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>

    <!-- Firebase Configuration -->
    <script src="https://www.gstatic.com/firebasejs/9.x.x/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.x.x/firebase-auth.js"></script>
    <script>
        // Your Firebase configuration
        const firebaseConfig = {
            // Add your Firebase config here
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
    </script>
</body>
</html>