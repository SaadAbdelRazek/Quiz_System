<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuizQuest</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    @yield('custom-css')
</head>
<body>
<!-- Header -->
<header>
    <div class="logo">
        <h1>QuizQuest</h1>
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
                    <a href="#" id="userDropdown" class="username">{{$user_data->name}}</a>
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li><a href="/dashboard">My Profile</a></li>
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
<footer>
    <p>&copy; 2024 QuizQuest. All rights reserved.</p>
</footer>

<!-- JavaScript -->
<script src="{{asset('js/script.js')}}"></script>
@yield('custom-js')
</body>
</html>
