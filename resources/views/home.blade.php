@extends('layout.app')
@section('title', 'Home')
@section('content')
<div class="col-md-4">
    @include('layout.partials.usercard')
</div>
<div class="col-md-8" id="groupButtons">
    <div class="card">
        <div class="header">
            <h4 class="title">Click to Join a Group!</h4>
        </div>
        <div class="content">
            <div class="row joinableGroups">
                @foreach($rolesUserDoesntHave as $role)
                    <button type="button" class="btn btn-default btn-sm joinGroupButton"
                    @foreach($role->restrictions as $restriction)
                        @cannot($restriction->permission)
                              disabled data-toggle="tooltip" title="{{$restriction->restriction->description}}"
                            @break
                        @endcannot
                    @endforeach
                    data-name="{{$role->slug}}">
                        {{$role->name}}
                        <img src="{{$role->icon}}" alt="{{$role->name}}" width="50px" height="50px"/>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card">
        <div class="header">
            <h4 class="title">Click to Leave a Group!</h4>
        </div>
        <div class="content">
            <div class="row joinedGroups">
                @foreach($rolesUserHas as $role)
                    <button type="button" class="btn btn-default btn-sm leaveGroupButton" data-name="{{$role->slug}}">
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