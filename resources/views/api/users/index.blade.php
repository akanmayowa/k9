@extends('layouts.app')
@section('content')
<div class="row">
   <div class="col-12 ptb-4">
        <div class="card-header bg-dark text-white"> <span class="text-white">Api Users</span></div>
        <div class="card-body">
    <div class="d-flex justify-content-start mb-5">
    <button data-toggle="modal"  data-target="#RegisterApiUserModal" type="button" class="btn btn-sm btn-primary">Register Api user</button>
   </div>
            <div class="table-responsive">
                        <table class="table table-striped datatable">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>App Code</th>
                                <th>Company name</th>
                                <th>Api Token</th>
                                <th>Access type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
               </div>
          </div>
      </div>
</div>
@include('api.users.registermodal')
@include('api.users.editmodal')
@include('api.users.resetapikeymodal')
@endsection
@push('scripts')
{{-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
     <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
     <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script> --}}
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>   --}}
<script type="text/javascript">
    $(document).ready(function() {
       $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
       var dataTable = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            colReorder: true,
            fixedHeader: true,
            pageLength: 5,
            scrollX: true,
            "order": [[ 0, "desc" ]],
            ajax: '{{ route('api-users.getApiUser') }}',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,searchable: false,},
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'api_token', name: 'api_token'},
                {data: 'access_type', name: 'access_type'},
                {data: 'is_active', name: 'is_active'},
                {data: 'Actions', name: 'Actions',orderable:false,serachable:false},
            ]
        });

        // // Create  Ajax request.
        $("#SubmitRegisterApiUserForm").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
            $( '#id-error' ).html( "" );
            $( '#name-error' ).html( "" );
            $( '#access_type-error' ).html( "" );
            $( '#is_active-error' ).html( "" );
        var api_user_form_data = {
            id: jQuery('#id').val(),
            name: jQuery('#name').val(),
            access_type: jQuery('#access_type').val(),
            is_active: jQuery('#is_active').val(),
        };
        var state = jQuery('#SubmitRegisterApiUserForm').val();
        var type = "POST";
        $.ajax({
            type: type,
            url: "{{ route('api-users.store') }}",
            data: api_user_form_data,
            dataType: 'json',
            success: function (api_user_data) {
                if(api_user_data.errors)
                   {
                    if(api_user_data.errors.id)
                    {
                        $( '#id-error' ).html( api_user_data.errors.id[0] );
                    }
                    if(api_user_data.errors.name)
                    {
                        $( '#name-error' ).html( api_user_data.errors.name[0] );
                    }
                    if(api_user_data.errors.access_type)
                    {
                        $( '#access_type-error' ).html( api_user_data.errors.access_type[0] );
                    }
                    if(api_user_data.errors.is_active)
                    {
                        $( '#is_active-error' ).html( api_user_data.errors.is_active[0] );
                    }
                }
              else{
                dataTable.draw();
                jQuery('#ApiUserRegisterForm').trigger("reset");
                jQuery('#RegisterApiUserModal').modal('hide');
                // alert('Api User Registration successfully');
                Swal.fire({
                    type: 'success',
                    title: 'Operation Successful',
                    text: `Api User Registration successfully`,
                });
              }
            },
        });
    });
        //Get single Api User in EditApiUserModel
            $('#modelClose').click(function(e) {
            $('#EditApiUserModal').hide();
        });
        var id;
        $('body').on('click', '#getEditedApiUser', function(e) {
             e.preventDefault();
            id = $(this).data('id');
            $.ajax({
                url: "api-users-edit/"+id+"/edit",
                method: 'GET',
                 data: {
                     id: id,
                 },
                success: function(api_user_data) {
                    console.log(api_user_data);
                    $('#EditApiUserModalBody').html(api_user_data.html);
                    $('#EditApiUserModal').show();
                }
            });
        });
        // Update  Ajax request.
        $('#SubmitEditApiUserForm').click(function(e) {
            $( '#id-error' ).html( "" );
            $( '#name-error' ).html( "" );
            $( '#access_type-error' ).html( "" );
            $( '#status-error' ).html( "" );
            e.preventDefault();
            $.ajax({
                url: "/api-users-update/"+id,
                method: 'PUT',
                data: {
                    id: $('#editid').val(),
                    name: $('#editname').val(),
                    access_type: $('#editaccess_type').val(),
                    is_active: $('#editis_active').val(),
                },
                success: function(api_user_data) {
                console.log(api_user_data);
                if(api_user_data.errors)
                 {
                    if(api_user_data.errors.id)
                    {
                        $( '#id-error' ).html( api_user_data.errors.id[0] );
                    }
                    if(api_user_data.errors.name)
                    {
                        $( '#name-error' ).html( api_user_data.errors.name[0] );
                    }
                    if(api_user_data.errors.access_type)
                    {
                        $( '#access_type-error' ).html( api_user_data.errors.access_type[0] );
                    }
                    if(api_user_data.errors.is_active)
                    {
                        $( '#is_active-error' ).html( api_user_data.errors.is_active[0] );
                    }
                }
                else
                {
                    // $('.alert-danger').hide();
                        // $('.alert-success').show();
                    // setInterval(function(){
                    // $('.alert-success').hide();
                    dataTable.draw();
                    $('#SubmitEditApiUserForm').trigger("reset");
                    $('#EditApiUserModal').hide();
                    Swal.fire({
                    type: 'success',
                    title: 'Operation Successful',
                    text: `Api User was added successfully Updated`,
                    });
                        // location.reload();
                    // }, 2000);
                }
                }
            });
        });
        //Get single Api User in ResetApiKey
        $('.modelClose').on('click', function(){
            $('#ResetKeyApiUserModal').hide();
        });
        var id;
        $('body').on('click', '#getEditedResetKeyApiUser', function(e) {
             e.preventDefault();
            $('.alert-danger').html('');
            $('.alert-danger').hide();
            id = $(this).data('id');
            $.ajax({
                url: "api-users-key-edit/"+id+"/edit",
                method: 'GET',
                 data: {
                     id: id,
                 },
                success: function(api_user_data) {
                    console.log(api_user_data);
                    $('#ResetKeyApiUserForm').trigger("reset");
                    $('#ResetKeyApiUserModal').show();
                }
            });
        });
     // Update  Secret Key Ajax request.
     $('#SubmitResetKeyApiUserForm').click(function(e) {
        $( '#password-error' ).html( "" );
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
             });
            $.ajax({
                url: "/api-users-key-update/"+id,
                method: 'PUT',
                data: {
                    password: $('#password').val(),
                },
                success:function(api_user_data)
                   {
                            if(api_user_data.errors)
                            {
                               $( '#password-error').html( api_user_data.errors );
                            }
                            else
                            {
                            // $('.alert-success').show();
                            // setInterval(function(){
                            // $('.alert-success').hide();
                            dataTable.draw();
                            $('#ResetKeyApiUserForm').trigger("reset");
                            $('#ResetKeyApiUserModal').hide();
                            Swal.fire({
                            type: 'success',
                            title: 'Operation Successful',
                            text: `Reset Key for Api User`,
                            });
                            // location.reload();
                            // }, 2000);
                            }
                    }
                });
            });
        });
</script>
@endpush




