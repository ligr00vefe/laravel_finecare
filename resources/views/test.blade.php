@extends("layouts/admin_layout")

@section("title")
    간이세액표
@endsection


@section("content")


    간이세액표올리기

    <form action="/test/action" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <button>올리기</button>
    </form>

@endsection