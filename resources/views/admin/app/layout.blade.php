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
                <ul>
                    @if (auth()->user()->role === 'SuperAdmin')
                        <li><a href="{{ route('admin-dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('admin-view-users') }}">Users</a></li>

                <li><a href="{{route('admin-dashboard')}}">Dashboard</a></li>
                <li><a href="{{route('admin-view-users')}}">Users</a></li>
                <li><a href="{{route('view-create-quiz')}}">Add Quiz</a></li>
                <li><a href="{{route('admin-view-quizzes')}}">All Quizzes</a></li>
                <li><a href="#">Standing</a></li>
                <li><a href="{{route('admin-view-contacts')}}">Contacts</a></li>
                <li><a href="{{route('reports')}}">Reports</a></li>
                <li><a href="#">Settings</a></li>
                @elseif (auth()->user()->role ==='admin')
                <li><a href="{{route('admin-dashboard')}}">Dashboard</a></li>
                <li><a href="{{route('view-create-quiz')}}">Add Quiz</a></li>
                <li><a href="{{route('admin-view-quizzes')}}">Your Quizzes</a></li>
                <li><a href="{{route('reports')}}">Reports</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="{{route('home')}}">return website</a></li>
                @endif
            </ul>
        </nav>
    </aside>

                        <li><a href="{{ route('admin-view-quizzes') }}">All Quizzes</a></li>
                        <li><a href="#">Standing</a></li>
                        <li><a href="{{ route('admin-view-contacts') }}">Contacts</a></li>
                        <li><a href="#">Reports</a></li>
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

                        <li><a href="#">Reports</a></li>
                        {{-- <li><a href="#">Settings</a></li> --}}
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

            </nav>
        </aside>

        @yield('content')

        <script src="{{ asset('js/admin.js') }}"></script>
        @yield('custom-js')
    </div>
</body>

</html>
