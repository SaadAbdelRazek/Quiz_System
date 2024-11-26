@extends('admin.app.layout')
@section('custom-css')
    <link rel="stylesheet" href="{{asset('css/users.css')}}">
@endsection
<style>
    .copy-button {
            color: rgb(0, 0, 0);
            opacity: 80%;
            background-color: unset;
            width: fit-content;
            border: none;
            padding: 1px;
            cursor: pointer;
            font-size: 16px;
        }
        .copy-button:hover {
            opacity: 100%;
            background-color: unset
        }
</style>
@section('content')
    <div class="container">
        @if (auth()->user()->role == 'SuperAdmin')
        <h3 style="margin: 10px">All quizzes</h3>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Active</th>
                <th>Title</th>
                {{-- <th>Subject</th> --}}
                <th>Link</th>
                <th>actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($allQuizzes as $quiz)
                <tr>
                    <td>@if ($quiz->visibility == 'private')

                        <i title="make public"class="fas fa-lock"> </i>

                        @endif
                        {{$loop->iteration}}

                    </td>
                    @if ($quiz->is_published==1)
                    <td style="width: 10px; text-align:center"><i class="fas fa-solid fa-circle-check" style="color:green"></i></td>
                    @elseif ($quiz->is_published==0)
                    <td style="width: 10px; text-align:center"><i class="fa-solid fa-circle-xmark" style="color: red"></i></td>
                    @endif
                    <td>{{$quiz->title}}</td>
                    {{-- <td>
                        {{$quiz->subject}}
                    </td> --}}
                    <td style="max-width: 100px">
                        @if ($quiz->access_token)
                        <div style="display:flex; align-items: center;">
                            <button class="copy-button" title="Copy Link" onclick="copyLink()">
                                <i class="fas fa-copy"></i>
                            </button>
                            <a href="{{ route('quiz_password', $quiz->access_token) }}" id="quiz_link" style="">{{ $quiz->access_token }}</a>
                        </div>
                        <span id="copy-success" style="display: none; color: green; margin-left: 10px;">
                            <i class="fas fa-check-circle"></i> Copied!
                        </span>

                        <script>
                            function copyLink() {
                                // الحصول على رابط الكويز
                                var link = document.getElementById('quiz_link').href;

                                // نسخ الرابط إلى الحافظة
                                navigator.clipboard.writeText(link).then(function() {
                                    // إظهار رسالة "Copied!"
                                    var copySuccess = document.getElementById('copy-success');
                                    copySuccess.style.display = 'inline-block';

                                    // إخفاء الرسالة بعد ثانيتين
                                    setTimeout(function() {
                                        copySuccess.style.display = 'none';
                                    }, 2000);
                                }).catch(function(error) {
                                    console.error("Failed to copy link: ", error);
                                });
                            }
                        </script>


                        @else
                        --------
                        @endif
                    </td>


                    <td style="display: flex; justify-content:center">
                        <a id="action-td" style="padding:10px; border:none; background-color: unset;width:fit-content;color:rgb(0, 106, 255)" href="{{route('quizzes.edit',$quiz->id)}}" title="edit"><i class="fas fa-edit"></i></a>
                        <form action="{{route('quiz.destroy', $quiz->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button id="action-td" style="background-color: unset;width:fit-content;color:rgb(218, 34, 34)" title="delete" type="submit" onclick="return confirm('Are you sure you want to delete this quiz?');"><i class="fas fa-trash"></i></button>
                        </form>

                        <form action="{{route('update-quiz-activate',$quiz->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            @if ($quiz->is_published==1)
                            <button id="action-td" style="background-color: unset;width:fit-content;color:black" title="disable"><i class="fas fa-regular fa-eye-slash"></i></button>
                            @elseif ($quiz->is_published==0)
                            <button id="action-td" style="background-color: unset;width:fit-content;color:black" title="enable"><i class="fas fa-regular fa-eye"></i></button>
                            @endif
                        </form>

                        <a id="action-td" style="padding:10px; border:none; background-color: unset;width:fit-content;color:rgb(31, 93, 179)" href="{{route('admin-view-examinees',$quiz->id)}}" title="show examinees"><i class="fas fa-solid fa-users"></i></a>


                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{-- <a class="a-table" href="{{route('admin-view-examinees')}}">show all of examinees</a> --}}
        @endif

        <h3 style="margin: 10px">your Quizzes</h3>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Active</th>
                <th>Title</th>
                {{-- <th>Subject</th> --}}
                <th>Link</th>
                <th>actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($quizzes as $quiz)
                <tr>
                    <td>@if ($quiz->visibility == 'private')

                        <i title="make public"class="fas fa-lock"> </i>

                        @endif
                        {{$loop->iteration}}

                    </td>
                    @if ($quiz->is_published==1)
                    <td style="width: 10px; text-align:center"><i class="fas fa-solid fa-circle-check" style="color:green"></i></td>
                    @elseif ($quiz->is_published==0)
                    <td style="width: 10px; text-align:center"><i class="fa-solid fa-circle-xmark" style="color: red"></i></td>
                    @endif
                    <td>{{$quiz->title}}</td>
                    {{-- <td>
                        {{$quiz->subject}}
                    </td> --}}
                    <td style="max-width: 100px">
                        @if ($quiz->access_token)
                        <div style="display:flex; align-items: center;">
                            <button class="copy-button" title="Copy Link" onclick="copyLink()">
                                <i class="fas fa-copy"></i>
                            </button>
                            <a href="{{ route('quiz_password', $quiz->access_token) }}" id="quiz_link"  style="">{{ $quiz->access_token }}</a>
                        </div>
                        <span id="copy-success" style="display: none; color: green; margin-left: 10px;">
                            <i class="fas fa-check-circle"></i> Copied!
                        </span>

                        <script>
                            function copyLink() {
                                // الحصول على رابط الكويز
                                var link = document.getElementById('quiz_link').href;

                                // نسخ الرابط إلى الحافظة
                                navigator.clipboard.writeText(link).then(function() {
                                    // إظهار رسالة "Copied!"
                                    var copySuccess = document.getElementById('copy-success');
                                    copySuccess.style.display = 'inline-block';

                                    // إخفاء الرسالة بعد ثانيتين
                                    setTimeout(function() {
                                        copySuccess.style.display = 'none';
                                    }, 2000);
                                }).catch(function(error) {
                                    console.error("Failed to copy link: ", error);
                                });
                            }
                        </script>


                        @else
                        --------
                        @endif
                    </td>


                    <td style="display: flex; justify-content:center">
                        <a id="action-td" style=" border:none; background-color: unset;width:fit-content;color:rgb(0, 106, 255)" href="{{route('quizzes.edit',$quiz->id)}}" title="edit"><i class="fas fa-edit"></i></a>
                        <form action="{{route('quiz.destroy', $quiz->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button id="action-td" style="background-color: unset;width:fit-content;color:rgb(218, 34, 34)" title="delete" type="submit" onclick="return confirm('Are you sure you want to delete this quiz?');"><i class="fas fa-trash"></i></button>
                        </form>

                        <form action="{{route('update-quiz-activate',$quiz->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            @if ($quiz->is_published==1)
                            <button id="action-td" style="background-color: unset;width:fit-content;color:black" title="disable"><i class="fas fa-regular fa-eye-slash"></i></button>
                            @elseif ($quiz->is_published==0)
                            <button id="action-td" style="background-color: unset;width:fit-content;color:black" title="enable"><i class="fas fa-regular fa-eye"></i></button>
                            @endif
                        </form>

                        <a id="action-td" style=" border:none; background-color: unset;width:fit-content;color:rgb(31, 93, 179)" href="{{route('admin-view-examinees',$quiz->id)}}" title="show examinees"><i class="fas fa-solid fa-users"></i></a>


                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <a class="a-table" href="{{route('admin-view-examinees')}}">show all of examinees</a>
    </div>
@endsection
