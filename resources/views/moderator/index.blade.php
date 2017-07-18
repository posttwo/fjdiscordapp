@extends('layout.app')
@section('title', 'Moderator Home')
@section('content')
<div class="col-md-4">
    @include('layout.partials.usercard')
</div>
<div class="col-md-8">
    <div class="card">
        <div class="header">
            <h4 class="title">Good Morning Mods</h4>
        </div>
        <div class="content">
            <p>You can now grab your own meme tokens!</p>
            <a href="{{route('moderator.tokens.index')}}"><button type="button" class="btn btn-default">Meme Tokens</button></a>
        </div>
    </div>
</div>
@endsection()