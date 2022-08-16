@inject('ManifestStatus', '\App\Enums\ManifestStatus')
@extends('layouts.app')


@section('content')
<div class="card">
    <!-- Card header -->
    <div class="card-header border-0">
      <div class="row">
        <div class="col-6">
          <h3 class="mb-0">Manifests</h3>
        </div>
        <div class="col-6 text-right">
        </div>
      </div>
    </div>
    <!-- Light table -->
    <div class="table-responsive">
      <table class="table align-items-center table-flush table-striped" id="moderator-view-table">
        <thead class="thead-light">

          <tr>
            <th>Manifest Id</th>
            <th>Route</th>
            <th>Dispatched</th>
            <th>Status</th>
            <th>Total Parcels</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
            @foreach ($manifests as $manifest)
          <tr data-info="{{$manifest->id}}" data-destination-site="{{$manifest->destination_site_id}}">
            <td class="table-user">
          @if($manifest->status === \App\Enums\ManifestStatus::ACKNOWLEDGED)

          <i class='fas fa-lock-open text-sm text-green' data-toggle='tooltip' data-placement='left' title='Seal Number: {{$manifest->seal_number}}' data-container='body' data-animation='true'></i>

          @else
          <i class='fas fa-lock text-sm text-dark' data-toggle='tooltip' data-placement='left' title='Seal Number: {{$manifest->seal_number}}' data-container='body' data-animation='true'></i>

          @endif

              <b>{{$manifest->id}}</b> <br>
              @php
                  $now = \Carbon\Carbon::now();
                  $escalation_interval = config('custom.escalation_interval_in_hours');
              @endphp
            @if ($manifest->created_at->diffInHours($now, true)  > $escalation_interval && $manifest->status === 0)
            <div class="badge badge-warning">over due by {{abs($manifest->created_at->diffInHours($now, true)-$escalation_interval)}} hours</div>
                {{-- <div class="badge badge-info">{{$manifest->created_at->addHours(24) }} hours Remaining</div> --}}
             @endif

             @if ($manifest->created_at->diffInHours($now, true)  <= $escalation_interval && $manifest->status === 0)
             <div class="badge badge-default">Remaining  {{abs($manifest->created_at->diffInHours($now, true)-$escalation_interval)}} hours</div>
                 {{-- <div class="badge badge-info">{{$manifest->created_at->addHours(24) }} hours Remaining</div> --}}
              @endif
            </td>
            <td>

                <span style="color:coral"> From </span><span class="text-muted">{{$manifest->scan_site->name}} <span style="color:coral"> To </span> {{$manifest->next_site->name}}</span>
            </td>
            <td>
                <span class="">{{$manifest->created_at->diffForHumans()}}</span>
                 <div class="text-green">{{$manifest->created_at->format('Y-m-d , g:i A')}}</div>
          </td>
            <td>
              <a href="#!" class="font-weight-bold">
                @if($manifest->status === \App\Enums\ManifestStatus::IN_TRANSIT)
                @if($manifest->flagged === 1)

                <i class="fas fa-flag text-danger" ></i>

                @endif
                <span class="badge badge-light">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::IN_TRANSIT]}}<span>
                @elseif ($manifest->status === \App\Enums\ManifestStatus::ACKNOWLEDGED)
                <span class="badge badge-success">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::ACKNOWLEDGED]}}</span>
                @elseif($manifest->status === \App\Enums\ManifestStatus::CANCELLED)
                <span class="badge badge-primary">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::CANCELLED]}}</span>
                @elseif($manifest->status === \App\Enums\ManifestStatus::PARTIALLY_RECEIVED)
                <span class="badge badge-default">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::PARTIALLY_RECEIVED]}}</span>
                @else
                <span class="badge badge-light">Unknown</span>
                @endif
            </a>
            </td>
            <td>

                <button type="button" class="btn btn-sm btn-warning btn-tooltip" data-toggle="tooltip" data-placement="left" title="Numbers of parcels acknowledged" data-container="body" data-animation="true">{{$manifest->acknowledged_waybills()->count()}} </button>
                / <button type="button" class="btn btn-sm btn-dark btn-tooltip" data-toggle="tooltip" data-placement="right" title="Numbers of parcels dispatched" data-container="body" data-animation="true">{{ count($manifest->waybills) }} </button>
           </td>

            <td class="table-actions">
                <div class="dropdown">
                    <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-h"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <a class="dropdown-item change-group" href="manifest/{{$manifest->id}}/details">
                            <i class="fas fa-info-circle"></i>
                            <span class="nav-link-text">View Details</span>
                          </a>
                      <a class="dropdown-item change-group" href="#">
                        <i class="fas fa-check-circle text-warning"></i>
                        <span class="nav-link-text view-details">Waybills</span>
                      </a>
                      <a class="dropdown-item change-group" href="#">
                        <i class="fas fa-check-double text-success"></i>
                        <span class="nav-link-text view-destination-site">Destination Site</span>
                    </a>
                    <a class="dropdown-item change-group" href="#">
                        {{-- only intransit and overdue --}}
                        @if($manifest->status === \App\Enums\ManifestStatus::IN_TRANSIT && $manifest->flagged != 1)
                        <i class="fas fa-flag text-danger" ></i>
                        <span class="nav-link-text flag-destination-site">Flag Destination Site</span>
                   @endif
                    </a>
                    </div>
                  </div>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>




        	<!-- The Modal -->
            <div class="modal fade" id="modal-view-details">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                      <h4 class="modal-title" >Manifest ( <span id="modal-manifest-id"></span>)</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        {{-- <div class="error-space">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                <span class="alert-text"><strong>Default!</strong> This is a default alert—check it out!</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div> --}}

                        {{-- <div class="alert alert-info" role="alert">
                            <strong class="text-uppercase">Warning!</strong> Some Information here
                        </div> --}}

                        <div class="table-responsive"  style="max-height: 250px; min-height:250px" >
                            <table class="table-lighter table align-items-center table-flush table-striped">
                              <thead class="thead-light">
                                <tr>
                                  <th>S/N</th>
                                  <th>Waybill Number</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tbody id="addtobagbody">
                           </tbody>
                            </table>
                          </div>
                        {{-- <form id="addtobag-form">
                        <div class="form-group">
                            <label for="parcels">Parcels</label>
                            <textarea class="form-control" id="parcels" rows="3" required></textarea>
                          </div> --}}
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                      {{-- <button type="button" class="btn btn-primary"  id="continue">Continue</button>
                      <button type="submit"  id="continue" class="btn btn-primary" value="Continue"> --}}
                    </div>
                {{-- </form> --}}
                  </div>
                </div>
              </div>

              <div class="modal fade" id="modal-view-destination-site">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                      <h4 class="modal-title" >Destination Site ( <span id="modal-destination-site"></span>)</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        {{-- <div class="error-space">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                                <span class="alert-text"><strong>Default!</strong> This is a default alert—check it out!</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div> --}}

                        {{-- <div class="alert alert-info" role="alert">
                            <strong class="text-uppercase">Warning!</strong> Some Information here
                        </div> --}}

                        <div class="table-responsive"  style="max-height: 250px; min-height:250px" >
                            <table class="table-dark table align-items-center table-flush table-striped">
                              <thead class="thead-light">
                                <tr>
                                  <th>S/N</th>
                                  <th>Account ID</th>
                                  <th>Name</th>
                                  <th>Phone Number</th>


                                </tr>
                              </thead>
                              <tbody id="site-employees-table">
                           </tbody>
                            </table>
                          </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
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

$("#continue").click(function (event)
 {
    $.ajax({
                url: `/manifest/virtual-sealnumber/new`,
                type: "GET",
                dataType: "json",
            })
                .done((virtualSealnumber) => {
                    console.log("Seal Number : ", virtualSealnumber);
                    $("#seal-number").fadeIn().val(virtualSealnumber);
                })
                .fail(function () {
                    console.log("! Error, Could not get virtual Seal Number");
                });


    $('#add-virtual-seal-number').modal('hide');

});
</script>
@endpush

{{-- [
    'copy', 'csv', 'excel', 'pdf', 'print'
] --}}

@push('scripts')
    <script>
        $('document').ready(function () {
            //Implement Sever Side Rendering soon
          let workingTable =  $('#moderator-view-table').DataTable({
        dom: 'Bfrtip',
        buttons:
        [
                    {
                        extend: 'copy',
                        exportOptions: {
                    columns: [ 0, 1, 3 ]
                }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                    columns: [ 0, 1, 3 ]
                }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                    columns: [ 0, 1, 3 ]
                }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                    columns: [ 0, 1, 3 ]
                }
                    }
                ]
    });
        });
    </script>
@endpush



@push('scripts')
<script>

//Datatables buttons


$("#moderator-view-table").on('click', ".flag-destination-site", function(event){
            event.preventDefault();

            let manifest_id = event.target.closest("tr").dataset.info;
            console.log(manifest_id);

            $.ajax({
                url: `/manifest/${manifest_id}/flag-overdue`,
                type: "POST",
				data: {test : "test", },
                dataType: "json",
            })
                .done((result) => {
					console.log(result);
                    if(result['success'] === true)
                    {
                    window.location.reload();
                    }
                })
                .fail(function () {
                    console.log("! Error, Could not retrieve manifest Information");
                });

        });

$("#moderator-view-table").on('click', ".view-details", function(event){
            event.preventDefault();

            let manifest_id = event.target.closest("tr").dataset.info;
            $.ajax({
                url: `/manifest/${manifest_id}`,
                type: "GET",
                dataType: "json",
            })
                .done((result) => {

                    $("#modal-manifest-id").text(result['data'].id);
                    lines = result['data'].waybills;
                    console.log(lines);
                    let tempTd ="";
            lines.forEach((waybill, index) => {
               let status =  (waybill.status === 1) ? '<span class="badge badge-success">acknowledged</span>' : '<span class="badge badge-warning">pending</span>';
                tempTd += `'<tr data-info="${waybill.id}"><td>${++index}</td><td>${waybill.id}</td><td>${status}</td></tr>`;

            });

            $("#addtobagbody").html(tempTd);
            // $("#numberofparcels").text(lines.length);
            // $("#waybills").val(JSON.stringify(linesToSend));
            // $("#waybills").val(JSON.stringify(lines));
            // $('#modal-importparcels').modal('hide');
            //         console.log("File Contents: ", result);

                })
                .fail(function () {
                    console.log("! Error, Could not retrieve manifest Information");
                });

            $("#modal-view-details").modal('show');
        });




        $("#moderator-view-table").on('click', ".view-destination-site", function(event){
            event.preventDefault();

            let site_id = event.target.closest("tr").dataset.destinationSite;
            $.ajax({
                url: `/sites/${site_id}`,
                type: "GET",
                dataType: "json",
            })
                .done((result) => {

                     console.log(result);
                     $("#modal-destination-site").text(result['data'][0].name);
                     let site_supervisors = result['data'][0].users;
                     let tempTd = "";

                    //  console.log(site_supervisors);
                    site_supervisors.forEach((employee, index) => {
                        tempTd += `'<tr><td>${++index}</td><td>${employee.id}</td><td>${employee.first_name}</td><td>${employee.phone_number}</td></tr>`;

                    });

                    $("#site-employees-table").html(tempTd);

                     $("#modal-view-destination-site").modal('show');

                })
                .fail(function () {
                    console.log("! Error, Could not retrieve site Information");
                });


        });

</script>
@endpush
