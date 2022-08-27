@extends('web::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('web.name') !!}
    </p>
@endsection
