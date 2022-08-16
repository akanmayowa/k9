<div class="modal" id="ResetKeyApiUserModal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">

        @include('includes.messages')
        <form class="form-horizontal" method="post" id="ResetKeyApiUserForm" >
            {{ csrf_field() }}
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reset Key for Api User</h4>
                <button type="button" class="close modelClose" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
                    <strong>Success!</strong>Api User Key Updated.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div>
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                            <span  class="form-control-label"><strong>Please Enter Your Password to Validate this Operation</strong></span>
                        </div>
                        </div>
                </div>
                <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-control-label"> Password</label>
                            <input type="password" required class="form-control" id="password" name="password">
                            <span class="text-danger">
                                <strong id="password-error"></strong>
                            </span>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="SubmitResetKeyApiUserForm">Reset Key</button>
                <button type="button" class="btn btn-danger modelClose" data-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
    </div>
</div>
