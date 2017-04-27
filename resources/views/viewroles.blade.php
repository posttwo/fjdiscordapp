@extends('layout.app')
@section('title', 'Role Managment')
@section('content')

<div class="col-md-12">
	<div class="card">
		<div class="header">
			<h4 class="title">List of Roles</h4>
		</div>
		<div class="content table-responsive table-full-width">
			<table class="table table-hover table-striped">
				<thead>
					<tr><th>ID</th>
					<th>Name</th>
					<th>Description</th>
					<th>Discord ID</th>
					<th>Icon</th>
					<th>Slug</th>
					<th>Restrictions</th>
					<th></th>
				</tr></thead>
				<tbody>
				@foreach($roles as $role)
					<tr>
						<td>{{$role->id}}</td>
						<td>{{$role->name}}</td>
						<td>{{$role->description}}</td>
						<td>{{$role->discord_id}}</td>
						<td><img src="{{$role->icon}}" width="50px"/></td>
						<td>{{$role->slug}}</td>
						<td>@foreach($role->restrictions as $restriction)
							{{$restriction->permission}}
							@endforeach
						</td>
						<td><button type="button" class="btn btn-danger btn-xs restrictRoleButton" data-id="{{$role->id}}">Restrict</button></td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@include('layout.models.addrole')
<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#addRoleModal">Add Role</button>
@endsection