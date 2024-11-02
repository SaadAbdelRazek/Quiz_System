@extends('admin.app.layout')
@section('content')
    <div class="main-content">
        <div class="content">
            <section class="table">
                <h2>Recent Activities</h2>
                <table>
                    <thead>
                    <tr>
                        <th>Name (Click to contact)</th>
                        <th>Message</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contacts as $contact)
                        <tr>
                            <td><a href="mailto:{{$contact->email}}" class="admin-btn">{{$contact->name}}</a></td>
                            <td>{{$contact->message}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
