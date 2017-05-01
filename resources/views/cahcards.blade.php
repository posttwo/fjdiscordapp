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