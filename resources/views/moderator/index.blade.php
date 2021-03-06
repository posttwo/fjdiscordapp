@extends('layout.app')
@section('title', 'Moderator Home')
@section('content')
<div class="row">
    <div class="col-md-4">
        @include('layout.partials.usercard')
    </div>
    <div class="col-md-8">

        <div class="card">
            <div class="header">
                <h4 class="title">API Access Tokens</h4>
            </div>
            <div class="content">
                <p>You can now grab your own API Access Tokens!</p>
                <a href="{{route('moderator.tokens.index')}}"><button type="button" class="btn btn-default btn-danger">API Tokens</button></a>
            </div>
        </div>

        <div class="card">
            <div class="header">
                <h4 class="title">DJ Replacement</h4>
            </div>
            <div class="content">
                <p>You can now initiate DJ replacement votes! Its broken and I'm waiting for Hunman and his library to come out</p>
                <a href="{{route('moderator.dj.index', ['boardName' => 'party'])}}"><button type="button" class="btn btn-default">Party Board</button></a>
                <a href="{{route('moderator.dj.index', ['boardName' => 'refugees'])}}"><button type="button" class="btn btn-default">Refugee Board</button></a>
            </div>
        </div>

    </div>
</div>
@endsection()
