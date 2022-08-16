@extends('layouts.app')


@section('content')
<div class="card">
    <!-- Card header -->
    <div class="card-header border-0">
      <div class="row">
        <div class="col-6">
          <h3 class="mb-0"><span  style="color:coral">DEPARTED WAYBILLS</span></h3>
        </div>
        <div class="col-6 text-right">
          {{-- <a href="#" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Edit Manifest">
            <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
            <span class="btn-inner--text">Export</span>
          </a> --}}
        </div>
      </div>
    </div>

    <div class="row">
        <div class="col-md-3 m-3">
            <div class="form-group">
                <label class="form-control-label" for="time">Departure DATE</label>
                {{ Form::text('start_date', null, [
                    'id' => 'start_date',
                    'class'=>'form-control datepicker',
                    'placeholder'=>'Select Start Date',
                 ]) }}

                <button type="button" class="btn btn-info">Search</button>
            </div>
        </div>
    </div>

    <!-- Light table -->
    <div class="table-responsive">
        <table class="table align-items-center table-flush table-striped" id="waybills-view-table">
            <thead class="thead-light">

              <tr>
                <th>Waybill Number</th>
                <th>scan site</th>
                <th>next site</th>
                <th>Scan Date</th>
                <th>Scanner</th>
                <th>Scan Type</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                @foreach ($waybills as $waybill)
              <tr>
                <td>
                    {{$waybill->BILL_CODE}}
                </td>
                <td>
                    {{$waybill->SCAN_SITE_NAME}}
                </td>
                <td>
                    {{$waybill->NEXT_SITE_NAME}}
                </td>
                <td>
                    {{$waybill->SCAN_DATE}}
                </td>
                <td>
                   {{$waybill->EMPLOYEE_NAME}}
                </td>
                <td>
                    @if($waybill->SCAN_TYPE_CODE == 02)
                    Departure Scan
                    @endif
                </td>
                <td class="table-actions">
                    <div class="dropdown">
                        <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item change-group">
                                <i class="fas fa-info-circle"></i>
                                <span class="nav-link-text track-waybill">Track</span>
                              </a>
                        </div>
                      </div>
                </td>
                    {{-- <td class="left strong">  @if($waybill->status === \App\Enums\ManifestStatus::IN_TRANSIT)
                        <span class="badge badge-default">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::IN_TRANSIT]}}<span>
                        @elseif ($waybill->status === \App\Enums\ManifestStatus::ACKNOWLEDGED)
                        <span class="badge badge-success">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::ACKNOWLEDGED]}}</span>
                        @else
                        <span class="badge badge-primary">{{\App\Enums\ManifestStatus::STATUS_TEXT[\App\Enums\ManifestStatus::CANCELLED]}}</span>
                        @endif
                    </td>
                    <td>
                        {{$waybill->manifest->created_at}}
                    </td>
                    <td>

                        <span style="color:coral"> From </span><span class="text-muted">{{$waybill->manifest->departure_site->name}} <span style="color:coral"> To </span> {{$waybill->manifest->destination_site->name}}</span>
                    </td>
                    <td>
                        {{$waybill->manifest->departure_site->name}}
                    </td>
                    <td>
                        {{$waybill->manifest->destination_site->name}}
                    </td>
                    <td>
                        {{$waybill->manifest->id}}
                    </td> --}}

              </tr>
            @endforeach
            </tbody>
          </table>
    </div>
  </div>


  <div class="modal fade" id="modal-waybill-track">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" >WAYBILL ( <span id="modal-waybill-id"></span>)</h4>
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
                      <th>DATE</th>
                      <th>SCAN</th>
                      <th>SCAN SITE</th>
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



{{--
@push('scripts')
    <script>
        $('document').ready(function () {
            //Implement Sever Side Rendering soon
          let workingTable =  $('#waybills-table').DataTable({
        dom: 'Bfrtip',
        "pageLength": 100,
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
@endpush --}}



@push('scripts')
    <script>
        $('document').ready(function () {


            //Implement Sever Side Rendering soon
          let workingTable =  $('#waybills-table').DataTable({

            processing: true,
                serverSide: true,
                // ajax: "{{route("waybills.k9_getDepartureScans")}}",

        dom: 'Bfrtip',
        "pageLength": 100,
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
$("#waybills-table").on('click', ".track-waybill", function(event){
    event.preventDefault();

    let site_id = event.target.closest("tr").dataset.destinationSite;
    console.log(site_id);
    // $.ajax({
    //     url: `/sites/${site_id}`,
    //     type: "GET",
    //     dataType: "json",
    // })
    //     .done((result) => {

    //          console.log(result);
    //          $("#modal-destination-site").text(result['data'][0].name);
    //          let site_supervisors = result['data'][0].users;
    //          let tempTd = "";

    //         //  console.log(site_supervisors);
    //         site_supervisors.forEach((employee, index) => {
    //             tempTd += `'<tr><td>${++index}</td><td>${employee.id}</td><td>${employee.first_name}</td><td>${employee.phone_number}</td></tr>`;

    //         });

    //         $("#site-employees-table").html(tempTd);

    //          $("#modal-view-destination-site").modal('show');

    //     })
    //     .fail(function () {
    //         console.log("! Error, Could not retrieve site Information");
    //     });


});

</script>
@endpush
