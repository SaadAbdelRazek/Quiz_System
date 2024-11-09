<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('custom-meta')
    <title>QuizQuest</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @yield('custom-css')
</head>
<body>
<!-- Header -->
<header>
    <div class="logo">
        <a href="{{route('home')}}"><h1>QuizQuest</h1></a>
    </div>
    <nav>
        <div class="menu-toggle" id="menuToggle">
            <!-- The hamburger icon (3 bars) -->
            <div class="hamburger"></div>
            <div class="hamburger"></div>
            <div class="hamburger"></div>
        </div>
        <ul class="nav-list" id="navList">
            <li><a href="{{route('home')}}">Home</a></li>&nbsp;&nbsp;
            <li><a href="{{route('home')}}#about">About Us</a></li>&nbsp;&nbsp;
            <li><a href="{{route('home')}}#quizzes">Quizzes</a></li>&nbsp;&nbsp;
            <li><a href="{{route('home')}}#contact">Contact Us</a></li>&nbsp;&nbsp;
            @if(auth()->check())
                <div class="user-menu">
                    <span>Welcome,</span>
                    <a href="#" id="userDropdown" class="username">{{explode(' ',$user_data->name)[0]}}</a>
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li><a href="{{route('profile')}}">My Profile</a></li>
                        <li>
                            <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                                @csrf
                            </form>

                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                <li>
                    <a href="/login" class="btn login-btn">Login</a>
                    <a href="/register" class="btn signup-btn">Sign Up</a>
                </li>
            @endif
        </ul>
    </nav>
</header>
@yield('content')

<!-- Footer -->
<footer class="footer">
    <div class="footer-container">

        <!-- About Section -->
        <div class="footer-section about">
            <h3>About QuizQuest</h3>
            <p>QuizQuest is your ultimate destination for challenging quizzes and competitive knowledge tests, designed to enhance learning and fun.</p>
        </div>

        <!-- Quick Links Section -->
        <div class="footer-section quick-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#quizzes">Quizzes</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ul>
        </div>

        <!-- Contact Section -->
        <div class="footer-section contact-info">
            <h3>Contact Us</h3>
            <p><i class="fa-solid fa-envelope"></i> <a href="mailto:support@quizquest.com">support@quizquest.com</a></p>
            <p><i class="fa-solid fa-envelope"></i> <a href="mailto:hamadanaser791@gmail.com">hamadanaser791@gmail.com</a></p>
            <p><i class="fa-solid fa-phone"></i> <a href="tel:+201061632722">+201061632722</a></p>
            <p><i class="fa-solid fa-phone"></i><a href="tel:+201002384633">+201006874906</a></p>
        </div>

        <!-- Social Media Section -->
        <div class="footer-section social-media">
            <h3>Follow Us</h3>
            <div class="social-icons">

                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>

    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        <p>&copy; 2024 QuizQuest. All rights reserved. </p>
    </div>
</footer>

<style>
    /* Footer Styling */
.footer {
    background-color: #333;
    color: #ecf0f1;
    padding: 40px 0;
    text-align: center;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    max-width: 1200px;
    margin: auto;
}

.footer-section {
    flex: 1;
    min-width: 200px;
    margin: 20px;
}

.footer-section h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #3c95dd;
}

.footer-section p, .footer-section a {
    font-size: 14px;
    color: #bdc3c7;
    text-decoration: none;
}

.footer-section p {
    margin: 5px 0;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin: 8px 0;
}

.footer-section ul li a:hover {
    color: #3c95dd;
}

.social-icons a {
    margin: 0 8px;
    color: #ecf0f1;
    font-size: 20px;
    transition: color 0.3s;
}

.social-icons a:hover {
    color: #3c95dd;
}

.footer-bottom {
    border-top: 1px solid #34495e;
    padding-top: 15px;
    margin-top: 20px;
    font-size: 14px;
}

.footer-bottom a {
    color: #ecf0f1;
    text-decoration: none;
}

.footer-bottom a:hover {
    color: #3c95dd;
}

</style>

<!-- JavaScript -->
<script src="{{asset('js/script.js')}}"></script>
<script>
    function closeAlert() {
        var close = document.getElementById('error-message');
        if (close) {
            close.style.display = 'none';
        }
    }
</script>
@yield('custom-js')
</body>
</html>
