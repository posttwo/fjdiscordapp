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
                Lorem Ipsum
            </p>
            @if(Auth::user()->fjuser === null)
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#verificationStepOneModal">Connect FunnyJunk</button>
            @else
                FJ: {{Auth::user()->fjuser->username}}
            @endif
        </div>
    </div>
</div>
<div class="col-md-8">
    <div class="card">
        <div class="header">
            <h4 class="title">Click to Join a Group!</h4>
        </div>
        <div class="content">
            <div class="row">
                @foreach($roles as $role)
                    <button type="button" class="btn btn-default btn-sm joinGroupButton" data-name="{{$role->name}}">
                        {{$role->name}}
                        <img src="{{$role->icon}}" alt="{{$role->name}}" width="50px" height="50px"/>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
@if(Auth::user()->fjuser === null)
    @include('layout.models.verification')
@endif
@endsection()