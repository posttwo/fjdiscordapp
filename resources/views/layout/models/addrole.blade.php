<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel" data-backdrop="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addRoleModalLabel">Add a Role</h4>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.roles')}}" id="addRoleForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="text" class="form-control" name="name" placeholder="Role Name">
            <input type="text" class="form-control" name="description" placeholder="Description">
            <input type="text" class="form-control" name="discord_id" placeholder="Discord ID">
            <input type="text" class="form-control" name="icon" placeholder="Icon URL (https pls)">
            <input type="text" class="form-control" name="slug" placeholder="Slug">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="addRoleSubmitter" >Add</button>
      </div>
    </div>
  </div>
</div>