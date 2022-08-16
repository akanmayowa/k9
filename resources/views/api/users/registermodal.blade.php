<!-- Create Article Modal -->
<div class="modal" id="RegisterApiUserModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Register Api User</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <form id="ApiUserRegisterForm"  class="form-horizontal" method="post" action="">
                    {{ csrf_field() }}
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                        <strong>Success!</strong>Api User was added successfully.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" >App Code</label>
                        <input type="text" class="form-control" required name="id" id="id">
                        <span class="text-danger">
                            <strong id="id-error"></strong>
                        </span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" >Company Name</label>
                        <input type="text" class="form-control" required  name="name" id="name" >
                        <span class="text-danger">
                            <strong id="name-error"></strong>
                        </span>
                      </div>
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label">Access Type</label>
                        <select type="text" class="form-control" required name="access_type" id="access_type">
                        <option disabled selected  class="form-control">Please Select Access Type</option>
                        <option value="test"  class="form-control">Test</option>
                        <option value="production"  class="form-control">Production</option>
                        </select>
                        <span class="text-danger">
                            <strong id="access_type-error"></strong>
                        </span>
                    </div>
                    </div>


                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label">Status</label>
                        <select type="text" class="form-control" required name="is_active" id="is_active">
                            <option disabled selected  class="form-control">Please Account Status</option>
                            <option value="deactivated"  class="form-control">Deactivated</option>
                            <option value="activated"  class="form-control">Activated</option>
                        </select>
                        <span class="text-danger">
                            <strong id="is_active-error"></strong>
                        </span>
                    </div>
                    </div>
                  </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning my-4" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success my-4"  id="SubmitRegisterApiUserForm" value="add">Register User</button>
            </div>
        </form>

        </div>
    </div>
</div>
</div>
