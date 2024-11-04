<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/admin-styles.css')}}">
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
                @if (auth()->user()->role ==='SuperAdmin')

                <li><a href="{{route('admin-dashboard')}}">Dashboard</a></li>
                <li><a href="{{route('admin-view-users')}}">Users</a></li>
                <li><a href="{{route('view-create-quiz')}}">Add Quiz</a></li>
                <li><a href="{{route('admin-view-quizzes')}}">All Quizzes</a></li>
                <li><a href="#">Standing</a></li>
                <li><a href="#">Contacts</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="#">Settings</a></li>
                @elseif (auth()->user()->role ==='admin')
                <li><a href="{{route('admin-dashboard')}}">Dashboard</a></li>
                <li><a href="{{route('view-create-quiz')}}">Add Quiz</a></li>
                <li><a href="{{route('admin-view-quizzes')}}">Your Quizzes</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="{{route('home')}}">return website</a></li>
                @endif
            </ul>
        </nav>
    </aside>

    @yield('content')

    <script src="{{asset('js/admin.js')}}"></script>
    @yield('custom-js')
</div>
</body>
</html>
