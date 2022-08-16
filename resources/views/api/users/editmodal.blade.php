<div class="modal" id="EditApiUserModal" class="modal fade" role="dialog" aria-hidden="true">
<form id="ApiUserEditForm"  class="form-horizontal" method="post">
    {{ csrf_field() }}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Edit Api User</h4>
                <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                    <strong>Success!</strong>Api User was added successfully Updated.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="EditApiUserModalBody">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="SubmitEditApiUserForm">Update</button>
                <button type="button" class="btn btn-danger" id="modelClose" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</form>
</div>
