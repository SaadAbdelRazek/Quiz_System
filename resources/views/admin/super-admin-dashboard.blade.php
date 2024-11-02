@extends('admin.app.layout')
@section('content')
    <div class="main-content">
        <header class="header">
            <h1>Dashboard</h1>
            <div class="user-info">
                <span>Welcome, {{$user_data->name}}</span>
                <img src="https://via.placeholder.com/40" alt="User Avatar" class="avatar">
            </div>
        </header>
        <div class="content">
            <section class="stats">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p>{{$users}}</p>
                </div>
                <div class="stat-card">
                    <h3>Total Quizzes</h3>
                    <p>{{$quizzes}}</p>
                </div>
                <div class="stat-card">
                    <h3>Total Examinees</h3>
                    <p>{{$examinees}}</p>
                </div>
                <div class="stat-card">
                    <h3>Active Quizzes</h3>
                    <p>{{$activeQuizzes}}</p>
                </div>
            </section>
            <section class="table">
                <h2>Recent Activities</h2>
                <table>
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>Created Quiz</td>
                        <td>2024-10-20</td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>Updated Profile</td>
                        <td>2024-10-19</td>
                    </tr>
                    <tr>
                        <td>Tom Brown</td>
                        <td>Deleted Quiz</td>
                        <td>2024-10-18</td>
                    </tr>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
