@extends('layout.app')
@section('title', 'CAH Cards')
@section('content')
<table class="table table-striped">
    <thead>
        <tr>
            <th>Color</th>
            <th>Text</th>
        </tr>
    </thead>
    <tbody>
    @foreach($cards as $card)
        <tr>
            <td>{{$card->color}}</td>
            <td>{{$card->text}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection

@section('meta')
<meta property="og:title" content="FunnyJunk Discord - CAH Card Proposals">
<meta property="og:description" content="Propose Your Stupid Cards Here!">
<meta property="og:image" content="https://i.imgur.com/gPgcmuc.png">
<meta property="og:url" content="https://fjme.me/list/cah">
@endsection