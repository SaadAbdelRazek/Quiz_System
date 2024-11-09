<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/admin-styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @yield('custom-css')
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <div class="hamburger-menu" id="hamburgerMenu" onclick="toggleSidebar()">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>

                <ul id="sidebarMenu" class="sidebar-menu">
                    @if (auth()->user()->role === 'SuperAdmin')
                        <li><a href="{{ route('admin-dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin-view-users') }}">Users</a></li>

                        <!-- Add Quiz Dropdown -->
                        <li class="sidebar-dropdown" id="quizDropdown">
                            <a href="#" onclick="toggleSidebarDropdown(event)">Quizzes <i class="fas fa-solid fa-caret-down" style="color: white; margin-left:50px"></i></a>
                            <ul class="sidebar-submenu">
                                <li><a href="{{ route('view-create-quiz') }}">Create New Quiz</a></li>
                                <li><a href="{{ route('admin-view-quizzes') }}">Manage All Quizzes</a></li>
                            </ul>
                        </li>

                        <li><a href="#">Standing</a></li>
                        <li><a href="{{ route('admin-view-contacts') }}">Contacts</a></li>
                        <li><a href="{{route('reports')}}">Reports</a></li>
                        {{-- <li><a href="#">Settings</a></li> --}}
                    @elseif (auth()->user()->role === 'admin')
                        <li><a href="{{ route('admin-dashboard') }}">Dashboard</a></li>

                        <!-- Add Quiz Dropdown -->
                        <li class="sidebar-dropdown" id="quizDropdown">
                            <a href="#" onclick="toggleSidebarDropdown(event)">Quizzes <i class="fas fa-solid fa-caret-down" style="color: white; margin-left:50px"></i></a>
                            <ul class="sidebar-submenu">
                                <li><a href="{{ route('view-create-quiz') }}">Create New Quiz</a></li>
                                <li><a href="{{ route('admin-view-quizzes') }}">Your Quizzes</a></li>
                            </ul>
                        </li>

                        <li><a href="{{route('reports')}}">Reports</a></li>
                    @endif
                    <li><a href="{{ route('home') }}">Return to Website</a></li>
                </ul>
                <style>
                    /* تنسيق القائمة الجانبية */


                    ul li {
                        padding: 0 0 0 10px;
                        margin: 0;

                    }

                    /* تنسيق القائمة المنسدلة الفرعية */
                    .sidebar-dropdown {
                        position: relative;
                    }

                    .sidebar-dropdown a {
                        display: block;
                        text-decoration: none;
                        color: #333;


                    }

                    .sidebar-submenu {
                        display: none;
                        list-style-type: none;
                        padding-left: 15px;
                        /* مسافة لإظهار القائمة الفرعية داخل القائمة الرئيسية */
                        margin-top: 5px;
                        border-radius: 5px;
                        background-color: #333
                    }

                    .sidebar-submenu li a {
                        color: #555;
                    }

                    /* تنسيق فعال عند عرض القائمة */
                    .sidebar-dropdown.active .sidebar-submenu {
                        display: block;
                    }

                    .hamburger-menu {
                        display: none;
                        flex-direction: column;
                        cursor: pointer;
                        width: 30px;
                        height: 25px;
                        justify-content: space-between;
                    }

                    .hamburger-menu .bar {
                        background-color: white;
                        height: 4px;
                        width: 100%;
                        border-radius: 5px;
                    }

                    /* Hide the menu by default on mobile */
                    .sidebar-menu {
                        display: block;
                        list-style: none;
                        margin: 0;
                        padding: 0;
                    }

                    .sidebar-menu li {
                        padding: 10px;
                    }

                    .sidebar-menu a {
                        text-decoration: none;
                        color: white;
                        font-size: 16px;
                    }

                    /* Media Queries */
                    @media (max-width: 768px) {
                        /* Show the hamburger icon only on mobile */
                        .hamburger-menu {
                            display: flex;
                        }

                        /* Hide the sidebar menu by default on mobile */
                        .sidebar-menu {
                            display: none;
                            background-color: #333;
                            width: 100%;
                            position: absolute;
                            top: 0;
                            left: 0;
                            z-index: 9999;
                            padding-top: 60px;
                        }

                        .sidebar-menu.show {
                            display: block;
                        }

                        .sidebar-menu li {
                            text-align: center;
                        }

                        .sidebar-menu a:hover {
                            background-color: #444;
                        }
                    }
                </style>

                <script>
                    // Toggle dropdown and save state in localStorage
                    function toggleSidebarDropdown(event) {
                        event.preventDefault();
                        const dropdown = event.target.parentElement;
                        dropdown.classList.toggle('active');

                        // Save state in localStorage
                        const isActive = dropdown.classList.contains('active');
                        localStorage.setItem('quizDropdownState', isActive ? 'active' : 'inactive');
                    }

                    // Restore state on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        const dropdown = document.getElementById('quizDropdown');
                        const savedState = localStorage.getItem('quizDropdownState');

                        if (savedState === 'active') {
                            dropdown.classList.add('active');
                        }
                    });
                </script>
                <script>
                    // Function to toggle the sidebar menu visibility
                    function toggleSidebar() {
                        const menu = document.getElementById('sidebarMenu');
                        const hamburger = document.getElementById('hamburgerMenu');

                        // Toggle the 'show' class to show or hide the sidebar menu
                        menu.classList.toggle('show');

                        // Toggle the hamburger icon (optional)
                        hamburger.classList.toggle('active');
                    }

                    // Function to toggle sidebar dropdown visibility
                    function toggleSidebarDropdown(event) {
                        event.preventDefault();
                        const dropdown = event.target.parentElement;
                        dropdown.classList.toggle('active');
                    }

                    // Close sidebar if click is outside of it
                    document.addEventListener('click', function(event) {
                        const menu = document.getElementById('sidebarMenu');
                        const hamburger = document.getElementById('hamburgerMenu');

                        // Close menu if clicked outside of sidebar or hamburger icon
                        if (!menu.contains(event.target) && !hamburger.contains(event.target)) {
                            menu.classList.remove('show');
                        }
                    });
                </script>

            </nav>
        </aside>

        @yield('content')

        <script src="{{ asset('js/admin.js') }}"></script>
        @yield('custom-js')
    </div>
</body>

</html>
