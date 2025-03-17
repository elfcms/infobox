@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    @if (Session::has('infoboxresult'))
    <div class="alert alert-alternate">{{ Session::get('infoboxresult') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    888

@endsection
