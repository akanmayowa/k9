@extends('layouts.app')
@section('content')

<div class="card">
    <!-- Card header -->
    <div class="card-header border-0">
        <div class="row">            {{-- Start Row --}}
            {{-- <div class="col-sm-4">
                <div class="form-group">
					<label for="search-date">Date</label>
					<input type="text" class="form-control" id="search-date" name="search-date" >
				</div>
            </div> --}}
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-control-label" for="scan_site_id"> State </label>
                        {{ Form::select('scan_site_id', ['Lagos', 'Edo'], null, [
                                'id' => 'scan_site_id',
                                'class' => 'form-control sacn_site',
                                'data-toggle'=>"select",
                                'placeholder' => 'Every States',
                                'required' => true
                        ]) }}

                    <div class="text-danger">
                        @error('scan_site_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-control-label" for="next_site_id"> Type </label>
                    {{ Form::select('next_site_id', ['Franchise', 'Direct'], null, [
                            'id' => 'next_site_id',
                            'class' => 'form-control next_site',
                            'data-toggle'=>"select",
                            'placeholder' => 'Every Type',
                            'required' => true
                    ]) }}
                    <div class="text-danger">
                        @error('next_site_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                  <label class="form-control-label" for="status">Test</label>
                    {{ Form::select('status',['Real Sites', 'Test Sites'], null, [
                            'id' => 'status',
                            'class' => 'form-control',
                            'data-toggle'=>"select",
                            'placeholder' => 'Both Real and Test Sites',
                            'required' => true
                    ]) }}
                    <div class="text-danger">
                        @error('status')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

          </div> {{-- End Rows --}}
      <div class="row">
      </div>
    </div>
    <!-- Light table -->
    <div class="table-responsive">
      <table class="table table-lighter align-items-center table-flush table-striped" id="sites-datatable">
        <thead class="thead-light text-white">
          <tr>
            <th>S/N</th>
              <th>Name</th>
              <th>ID</th>
            <th>Franschise ?</th>
            <th>type</th>
            <th>Parent Site</th>
            <th>Test ?</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  @include('sites.editmodal')
  <!-- Modal -->
  <div class="modal fade" id="modal-user-group" tabindex="-1" role="dialog" aria-labelledby="modal-usergroup" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-errorLabel"><span id="username"></span>'s Group</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-errorBody">
            {{-- <div class="row" id="roles"> --}}
                <ul class="list-group list-group-hover" id="roles">
                  </ul>
        {{-- </div> --}}
      </div>
      <div class="modal-footer">
        <input type="hidden" id="user_id_field" value="">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-roles">Save changes</button>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal-details" tabindex="-1" role="dialog" aria-labelledby="modal-details" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-errorLabel">User Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-errorBody">
          View User Details
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal-reset" tabindex="-1" role="dialog" aria-labelledby="modal-changegroup" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-errorLabel">CHANGE GROUP </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-errorBody">
          Change the group for this employee
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$(document).on({
    ajaxStart: function() {
        console.log("Loading.............");
        $("#search-departure-scans").hide();
        $("#ajax-loading-indicator").show();
    },
    ajaxStop: function() {
        console.log("Done............");
        $("#search-departure-scans").show();
        $("#ajax-loading-indicator").hide();
    }
});
</script>
@endpush
@push('scripts')
        <script>
        $('document').ready(function () {
    let workingTable = $('#sites-datatable').DataTable(
        {
                    processing: true,
                    serverSide: true,
                    pageLength: 50,
                    ajax: {
                        url: "{{ route('sites.getSites') }}",
                     },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: "name",
                            name: "name"
                        },
                        {
                            data: "id",
                            name: 'id'
                        },
                        {
                            data: "is_a_franchise",
                            name: 'id'
                        },
                        {
                            data: "site_type_id",
                            name: 'site_type_id'
                        },
                        {
                            data: "parent_site.name",
                            name: 'parent_site.name'
                        },
                        {
                            data: "is_a_test_site",
                            name: 'is_a_test_site'
                        },
                        {
                            data: "action",
                            name : "action",
                            orderable: false,
                            searchable: false
                        }
                    ]
                }
              );
         });


        $('body').on('click', '.editSite', function () {
            let edit_id = $(this).data('id');
            $.get(
                "{{ url('sites') }}" +'/' + edit_id +'/edit', function (data){
                $('#editSiteModelHeading').html("Edit Site");
                $('#submitData').val("edit-site");
                $('#editSiteModal').modal('show');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#is_a_franchise').val(data.is_a_franchise);
                $('#site_type').val(data.site_type);
                $('#state_id').val(data.state_id);
                $('#state_name').val(data.state.name);
                $("#state").val(data.state.name).trigger("chosen:updated");
                $("#site_type").val(data.site_type).trigger("chosen:updated");
                $('#address').val(data.address);
            })
        });
                $('#submitData').click(function (e) {
                    $( '#name-error' ).html(" ");
                    $( '#is_a_franchise-error' ).html(" ");
                    $( '#state_id-error' ).html(" ");
                    $( '#address-error' ).html(" ");
                e.preventDefault();
                $(this).html('Update Site');
                $.ajax({
                data: $('#editSiteForm').serialize(),
                url: "{{ route('sites.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data)
                {
                    if(data.errors)
                     {
                        if(data.errors.name)
                        {
                            $('#name-error').html(data.errors.name[0]);
                        }
                        if(data.errors.is_a_franchise)
                        {
                            $('#is_a_franchise-error').html(data.errors.is_a_franchise[0]);
                        }
                        if(data.errors.state_id)
                        {
                            $('#state_id-error').html(data.errors.state_id[0]);
                        }
                        if(data.errors.address)
                        {
                            $('#address-error').html(data.errors.address[0]);
                        }
                     }
                     else
                     {
                        $('#sites-datatable').DataTable().draw();
                        $('#editSiteForm').trigger("reset");
                        $('#editSiteModal').modal('hide');
                        Swal.fire({
                            type: 'success',
                            title: 'Operation Successful',
                            text: `Site has been updated`,
                            });
                     }
                },
                error: function (data)
                {
                    console.log('Error:', data);
                }
            });
            });
    </script>

@endpush
@push('scripts')
<script>
$("#sites-datatable").on('click', ".change-group", function(event){
            event.preventDefault();
            console.log(this);
            $("#modal-changegroup").modal('show');
        });

        //RESET USER'S PASSWORD
        $("#sites-datatable").on('click', ".reset-password", function(event){
            event.preventDefault();
            console.log("Reset password");
            console.log(this);
            // $("#modal-changegroup").modal('show');
        });

        $("#sites-datatable").on('click', ".deactivate-user", function(event){
            event.preventDefault();
            console.log("DEACTIVATE USER");
            console.log(this);
            // $("#modal-changegroup").modal('show');
        });

        $("#sites-datatable").on('click', ".restore-user", function(event){
            event.preventDefault();
            console.log("RESTORE USER");
            console.log(this);
            // $("#modal-changegroup").modal('show');
        });

        $("#sites-datatable").on('click', ".send-pm", function(event){
            event.preventDefault();
            console.log("Send Personal Message");
            console.log(this);
            $("#myModal").modal('show');
        });

        $("#sites-datatable").on('click', ".user-group", function(event){
            event.preventDefault();

            let currentRow = event.target;
            // console.log(currentRow);
            let user_roles = JSON.parse(
                currentRow.dataset.roles
            );

            let username =  JSON.parse(
                currentRow.dataset.username
            );

            let userId =  JSON.parse(
                currentRow.dataset.user
            );

            console.log(currentRow.dataset.user);
            $("#user_id_field").val(userId);
            $('#username').html(username);

                $.ajax({
                url: `/roles`,
                type: "GET",
                // data : {
                //     scan_site_id : scan_site_id,
                // },
                dataType: "json",
            })
                .done((result) => {

                    let temp = "";
                    if(result.success == true)
                    {
                        console.log(result.data);

                    result.data.forEach(function (role) {
                        // console.log(role);
                        if(user_roles.find(x => x === role.name))
                        {
                            temp += `
                            <label> <li class="list-group-item border-0">
                      <input  class="form-check-input me-1 user-role" type="checkbox" value="${role.id}" aria-label="..."  checked>
                      ${role.name}
                    </li></label>`;
                        }
                        else
                        {
                            temp += `<label><li class="list-group-item border-0">
                      <input class="form-check-input me-1 user-role" type="checkbox" value="${role.id}" aria-label="...">
                      ${role.name}
                    </li></label>`;
                        }

                    });

                    // // console.log(options);
                    $("#roles").html(temp);
                    }
                    else {
                                Swal.fire({
                                type: 'info',
                                title: 'Oops!',
                                text: 'Could not retrieve data from server',
                                // footer: '<a href>Why do I have this issue?</a>'
                                });

                    }

                })
                .fail(function () {
                    // console.log("! Error, Could not connect to k9 server");
                    Swal.fire({
                                type: 'error',
                                title: 'Server Error',
                                text: 'Could not connect to server',
                                });
                });


            $("#modal-user-group").modal('show');
        });
        let userRoles = [];
        $("#save-roles").on('click', function(event){
            event.preventDefault();
            console.log($('#user_id_field').val());
            user = $('#user_id_field').val();
            userRoles = [];
            $('input[type="checkbox"]:checked').each(function() {
                userRoles.push(parseInt($(this).val()));
            });
            if(userRoles.length == 0)
            {
                console.log("Nothing to sync"); // strip them of their roles or give him employee number
            }
            else
            {
                console.log("Kindly Sync ", userRoles);

                $.ajax({
                    url: `/roles`,
                    type: "POST",
                    data: {roles : userRoles, user_id : user},
                    dataType: "json",
                })
                    .done((result) => {
                        // console.log(result);
                        if(result['success'] === true)
                        {
                            $("#modal-user-group").modal('hide');
                            Swal.fire({
                                        type: 'success',
                                        title: 'Operation successful',
                                        text: `${result['message']}`,
                                        });

                            window.location.reload();
                        }
                    })
                    .fail(function () {
                        console.log("! Error, Could Update user roles");
                    });
            }
        });
        $("#view-user-roles").on('click', function(event){
            event.preventDefault();
            console.log("View user Roles");
        });



</script>
@endpush
