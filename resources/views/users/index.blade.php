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
                    <label class="form-control-label" for="scan_site_id"> Site </label>
                        {{ Form::select('scan_site_id', $sites, null, [
                                'id' => 'scan_site_id',
                                'class' => 'form-control sacn_site',
                                'data-toggle'=>"select",
                                'placeholder' => 'All Sites',
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
                    <label class="form-control-label" for="next_site_id"> Department </label>
                    {{ Form::select('next_site_id', ["IT", "Quality Control"], null, [
                            'id' => 'next_site_id',
                            'class' => 'form-control next_site',
                            'data-toggle'=>"select",
                            'placeholder' => 'All Sites',
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
                  <label class="form-control-label" for="status">Status</label>
                    {{ Form::select('status', \App\Enums\ManifestStatus::STATUS_TEXT, null, [
                            'id' => 'status',
                            'class' => 'form-control',
                            'data-toggle'=>"select",
                            'placeholder' => 'All Status',
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
      <table class="table table-lighter align-items-center table-flush table-striped" id="employees-datatable">
        <thead class="thead-light text-white">
          <tr>
            <th>S/N</th>
              <th>Name</th>
              <th>Site</th>
            <th>K9 Account ID</th>
            <th>Group</th>
            <th>Telephone</th>
            <th>Email</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>

    <!-- Button trigger modal -->
      <a class="btn btn-primary" data-toggle="modal" data-target="#userRoleModal">
        Launch demo modal
      </a>


  @include('users.editmodal')


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
            //Implement Sever Side Rendering soon


        //    $('#employees-datatable').DataTable();


    let workingTable = $('#employees-datatable').DataTable(
        {
                     processing: true,
                    serverSide: true,
                    pageLength: 50,
                    ajax: {
                        url: "{{ route('users.getUsers') }}",
                        data: function(d) {
                        d.status = $('#status').val(), //$('input[name=start]').val()
                        d.site_id = $("#site_id").val()

                        }
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
                        }, {
                            data: "site.name",
                            name: 'site.name'
                        },
                        {
                            data: "id",
                            name: 'id'
                        },
                        {
                            data: "id",
                            name: 'id'
                        },
                        //goup needs to be in the middle here
                        {
                            data: "phone_number",
                            name: 'phone_number'
                        }
                        , {
                            data: "email",
                            name: 'email'
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
    </script>
@endpush


@push('scripts')
<script>
$("#employees-datatable").on('click', ".change-group", function(event){
            event.preventDefault();
            console.log(this);
            $("#modal-changegroup").modal('show');
        });

        //RESET USER'S PASSWORD
        $("#employees-datatable").on('click', ".reset-password", function(event){
            event.preventDefault();
            console.log("Reset password");
            console.log(this);
            // $("#modal-changegroup").modal('show');
        });


        $("#employees-datatable").on('click', ".deactivate-user", function(event){
            event.preventDefault();
            console.log("DEACTIVATE USER");
            console.log(this);
            // $("#modal-changegroup").modal('show');
        });

        $("#employees-datatable").on('click', ".restore-user", function(event){
            event.preventDefault();
            console.log("RESTORE USER");
            console.log(this);
            // $("#modal-changegroup").modal('show');
        });


        $("#employees-datatable").on('click', ".send-pm", function(event){
            event.preventDefault();
            console.log("Send Personal Message");
            console.log(this);
            $("#myModal").modal('show');
        });

        $("#employees-datatable").on('click', ".user-group", function(event){
            event.preventDefault();
            let currentRow = $( event.target );
            let user_roles = [];
            let userId =  currentRow.dataset.user;
            $("#user_id_field").val(userId);
            $('#username').html(userId);





            $.ajax({
                url: `/users/${userId}/roles`,
                type: "GET",
                dataType: "json",
            })
                .done((result) => {

                    let temp = "";
                    if(result.success == true)
                    {
                        console.log(result);
						user_roles = result.data.user_roles;

                    result.data.all_roles.forEach(function (role) {
                        if(user_roles.find(x => x === role.name))
                        {
                           temp +=  `<div class="col-4">
                               <div class="form-check mb-2 mr-sm-2">
                                    <label class="form-check-label">
                                        <input class="form-check-input user-role" type="checkbox" value="${role.id}" checked> ${role.name}
                                    </label>
                                </div>
                            </div>`;
                        }
                        else
                        {
                            temp +=  `<div class="col-4">
                               <div class="form-check mb-2 mr-sm-2">
                                    <label class="form-check-label">
                                        <input class="form-check-input user-role" type="checkbox" value="${role.id}" > ${role.name}
                                    </label>
                                </div>
                            </div>`;
                        }
                    });
                    $("#roles").html(temp);

                         $("#modal-user-group").modal('show');

                    }
                    else {
                                Swal.fire({
                                type: 'info',
                                title: 'Oops!',
                                text: 'Could not retrieve roles from server',
                                });

                    }

                })
                .fail(function () {
                    Swal.fire({
                                type: 'error',
                                title: 'Server Error',
                                text: 'Could not connect to server, so no roles have been retrived',
                                });
                });

return false;
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

            //j
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


        function getData(url, data, result) {
    $.ajax({
        method: "GET",
        url: url,
        data: { data: data },
        success: function (result) {
            console.log(result.data)

        }
    });
}
</script>
@endpush
