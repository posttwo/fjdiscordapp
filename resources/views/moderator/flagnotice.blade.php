@extends('layout.app')
@section('title', 'Flag Notices')
@section('content')
<div class="row">
    <div class="col-md-4">
        @include('layout.partials.usercard')
    </div>
    <div class="col-md-8">
        {{$notices->links()}}
        @foreach($notices as $notice)
        <div class="col-md-12">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        ID {{$notice->id}} @ {{$notice->created_at}}
                    </div>
                    <div class="panel-body">
                        <p>Created by 
                        <a href="https://funnyjunk.com/u/{{$notice->poster->fjuser->username}}">/u/{{$notice->poster->fjuser->username}}</a></p>
                        <pre>{{$notice->context}} : {{$notice->value}}</pre>
                        {{$notice->text}}
                    </div>
                    <div class="panel-footer">Please use FunnyJunk Exec Tools to delete this notice.</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection()
