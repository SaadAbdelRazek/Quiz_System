<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/admin-styles.css')}}">
    @yield('custom-css')
    <title>Admin Dashboard</title>
</head>
<body>
<div class="dashboard-container">
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <nav>
            <ul>
                <li><a href="{{route('admin-dashboard')}}">Dashboard</a></li>
                <li><a href="{{route('admin-view-users')}}">Users</a></li>
                <li><a href="{{route('view-create-quiz')}}">Quizzes</a></li>
                <li><a href="{{route('admin-view-quizzes')}}">All Quizzes</a></li>
                <li><a href="#">Standing</a></li>
                <li><a href="#">Contacts</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="#">Settings</a></li>
            </ul>
        </nav>
    </aside>

    @yield('content')

    <script src="{{asset('js/admin.js')}}"></script>
    @yield('custom-js')
</div>
</body>
</html>
