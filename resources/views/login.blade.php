@extends('layout.app')
@section('title', 'Login')
@section('content')
<style>
.button-11zepb {
    -webkit-transition: all .12s ease-out;
    background-color: #7289da;
    border-radius: 3px;
    color: #fff;
    cursor: pointer;
    font-size: 15px;
    padding: 15px 25px;
    position: relative;
    text-transform: uppercase;
    top: 0;
    transition: all .12s ease-out;
}
</style>
<div class="col-md-12" id="groupButtons">
    <div class="card">
        <div class="header">
            <h4 class="title">Authenthication Required</h4>
        </div>
        <div class="content">
            <p>You're currently not logged in. Please authenthicate with Discord. The account in your web browser might be different than in the actual app, if that is the case please press cancel on the next screen to view instructions on how to logout from web browser Discord.</p>
            <a href="{{route('login.discord')}}" class="btn btn-primary btn-lg btn-block btn-fill btn-round" href="#" role="button">Login With Discord</a>
        </div>
    </div>
</div>
@endsection()