@extends('layout.app')
@section('title', 'Moderator Tokens')
@section('content')
<div class="col-md-4">
    @include('layout.partials.usercard')
</div>
<div class="col-md-8">
    <div class="card">
        <div class="header">
            <h4 class="title">Token Repo</h4>
        </div>
        <div class="content">
            <div class="content table-responsive table-full-width">
            <table class="table table-hover">
                <thead>
                    <tr>
                    <th>Name</th>
                    <th>Creation</th>
                    <th>Client Name</th>
                    <th>Scope</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody id="moderatorTokenListing">
                    @foreach($tokens as $token)
                    <tr>
                        <td>{{$token->name}}</td>
                        <td>{{$token->created_at}}</td>
                        <td>{{$token->client->name}}</td>
                        <td><ul>
                        @foreach($token->scopes as $scope)
                            <li>{{$scopes[$scope]->description}}</li>
                        @endforeach
                        </ul></td>
                        <td><button type="button" class="btn btn-danger removeButton" data-id="{{$token->id}}">Revoke</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-success btn-lg btn-block" id="createPersonalAccessToken">Create Access Token</button>
        </div>
        </div>
    </div>
</div>
@endsection()