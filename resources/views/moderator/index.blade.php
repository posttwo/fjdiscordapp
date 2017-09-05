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
                <h4 class="title">Good Morning Mods</h4>
            </div>
            <div class="content">
                <p>You can now grab your own meme tokens!</p>
                <a href="{{route('moderator.tokens.index')}}"><button type="button" class="btn btn-default">Meme Tokens</button></a>
            </div>
        </div>
        <div class="card">
            <div class="header">
                <h4 class="title">DJ Replacement</h4>
            </div>
            <div class="content">
                <p>You can now initiate DJ replacement votes!</p>
                <a href="{{route('moderator.dj.index', ['boardName' => 'party'])}}"><button type="button" class="btn btn-default">Party Board</button></a>
                <a href="{{route('moderator.dj.index', ['boardName' => 'america'])}}"><button type="button" class="btn btn-default">America Board</button></a>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-md-12">
    If ya can't see them on the page click here: <a href="https://docs.google.com/document/d/1VEPFLwT-JpigMxR6KDkJKM6R3ABhBw1fJrQnIpDAypk/">Mod Manual</a> | <a href="
https://docs.google.com/document/d/19B4ra2Q5KKfWXWRYcKmiH6JC0vmwN_asZhj3jx3bN3o/edit">Noob Guide</a>
</div>
</div>
<div class="row visible-lg-block">
    <div class="col-md-6">
        <div class="card">
            <div class="header"><h4 class="title">Noob Guide</h4></div>
            <div class="content">
                <iframe width="100%" height="800px" src="https://docs.google.com/document/d/e/2PACX-1vQmqCcl_mnLtBSVkhPoBcB0BQdyxkLX7JA4BfPU5IvaSOCwRjLviftGEmdb_oJ8qd2ZobCHqZglDgRw/pub?embedded=true"></iframe>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="header"><h4 class="title">Mod Manual</h4></div>
            <div class="content">
                <iframe width="100%" height="800px" src="https://docs.google.com/document/d/e/2PACX-1vSdqt4mIPPlp4r3OGlXTf_VUXlCTQxrfd04cRGt5v9K0nGzTiX38CuErQfP53bZsMRU6IkweGZuQRwG/pub?embedded=true"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection()