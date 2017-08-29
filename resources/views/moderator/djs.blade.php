@extends('layout.app')
@section('title', 'DJ Control')
@section('content')
<div class="col-md-4">
    @include('layout.partials.usercard')
</div>
<div class="col-md-8">
    <div class="card">
        <div class="header">
            <h4 class="title">Board: {{$board->name}}</h4>
        </div>
        <div class="content">
            <p>Click To Begin Replacement Vote</p>
            @foreach($board->dj as $index => $dj)
                <a href="{{route('moderator.dj.replace', ['boardName' => $board->name, 'djNumber' => $index])}}"><p>{{$index}} : {{$dj}}</p></a>
            @endforeach
        </div>
    </div>
</div>
@endsection()