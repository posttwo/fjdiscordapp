@extends('layout.app')
@section('title', 'Home')
@section('content')
<div class="col-md-4">
    <div class="card card-user">
        <div class="image">
            <img src="{{Auth::user()->avatar}}" alt="..."/>
        </div>
        <div class="content">
            <div class="author">
                    <a href="#">
                <img class="avatar border-gray" src="{{Auth::user()->avatar}}" alt="..."/>

                    <h4 class="title">{{Auth::user()->nickname}}<br />
                        <small>{{Auth::user()->discord_id}}</small>
                    </h4>
                </a>
            </div>
            <p class="description text-center">
                {{Auth::user()->id}}
            </p>
            @if(Auth::user()->fjuser === null)
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#verificationStepOneModal">Connect FunnyJunk</button>
            @else
                FJ: {{Auth::user()->fjuser->username}}
            @endif
        </div>
    </div>
</div>
<div class="col-md-8" id="groupButtons">
    <div class="card text-center">
        <div class="header">
            <h4 class="title">You Just Joined</h4>
        </div>
        <div class="content">
            <div class="row">
                <h4 class="title">{{$role->name}}</h4>
                <img src="{{$role->icon}}" alt="{{$role->name}}" width="100px" height="100px"/><br />
                {{$role->description}}
            </div><br />
            <a href="{{route('home')}}"><button type="button" class="btn btn-default btn-lg btn-block">View Our Other Groups</button></a>
        </div>
    </div>
</div>
@if(Auth::user()->fjuser === null)
    @include('layout.models.verification')
@endif
@endsection()