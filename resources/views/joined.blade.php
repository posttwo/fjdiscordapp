@extends('layout.app')
@section('title', 'Home')
@section('content')
<div class="col-md-4">
    @include('layout.partials.usercard')
</div>
<div class="col-md-8" id="groupButtons">
    <div class="card text-center">
        <div class="header">
            <h4 class="title">
            @if($check)
            You Just Joined
            @else
            <span class="danger">Failed To Join</span>
            @endif
            </h4>
        </div>
        <div class="content">
            <div class="row">
                <h4 class="title">{{$role->name}}</h4>
                <img src="{{$role->icon}}" alt="{{$role->name}}" width="100px" height="100px"/><br />
                {{$role->description}}<hr />
                @if(!$check)
                    <h4>Reason for not being able to join</h4>
                    @foreach($role->restrictions as $restriction)
                         @cannot($restriction->permission)
                              <br />{{$restriction->restriction->description}}
                        @endcannot
                    @endforeach
                @endif

                <br />If there's no other reason, it may be because you're already in the group!
            </div><br />
            <a href="{{route('home')}}"><button type="button" class="btn btn-default btn-lg btn-block">View Our Other Groups</button></a>
        </div>
    </div>
</div>
@if(Auth::user()->fjuser === null)
    @include('layout.models.verification')
@endif
@endsection()