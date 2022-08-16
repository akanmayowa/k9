
@inject('BagStatus', '\App\Enums\BagStatus')
@inject('BagType', '\App\Enums\BagType')
@extends('layouts.app')


@section('content')
<div class="card">
            <!-- Card header -->
            <div class="card-header bg-dark border-0">
                <div class="row">
                  <div class="col-6">
                    <h3 class="mb-0"><span  style="color:white">ONSITE Bags</span></h3>
                  </div>
                  <div class="col-6 text-right">
                    {{-- <a href="#" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Edit Manifest">
                      <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                      <span class="btn-inner--text">Export</span>
                    </a> --}}
                  </div>
                </div>
              </div>
    <!-- Card header -->
    {{-- <div class="card-header  bg-default">
      <div class="row">
        <div class="col-6">
            <h3 class="mb-0 text-white display-4">Manifests View</h3>
          </div>
        <div class="col-6 text-right">
        </div>
      </div>
    </div> --}}
    {{-- <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
          </div>
          <input class="flatpickr flatpickr-input form-control" type="text" placeholder="Select Date..">
        </div>
      </div>
    <div class="input-daterange datepicker row align-items-center">
        <div class="col">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                    </div>
                    <input class="form-control" placeholder="Start date" type="text" value="06/18/2020">
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                    </div>
                    <input class="form-control" placeholder="End date" type="text" value="06/22/2020">
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Light table -->

    <div class="card-body">
        <div class="row">            {{-- Start Row --}}

            <div class="col-sm-4">
                <div class="form-group">
                  <label class="form-control-label" for="status">Type</label>
                    {{ Form::select('status', \App\Enums\BagType::TYPE_TEXT, null, [
                            'id' => 'type',
                            'class' => 'form-control',
                            'data-toggle'=>"select",
                            'placeholder' => 'All Type',
                            'required' => true
                    ]) }}
                    <div class="text-danger">
                        @error('status')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-control-label" for="scan_site_id"> Location /Site </label>
                    @php
                        $sites_attributes = [
                                'id' => 'next_or_current_site_id',
                                'class' => 'form-control sacn_site',
                                'data-toggle'=>"select",
                                'placeholder' => 'All Sites',
                                'required' => true
                ];
                    @endphp
                        {{ Form::select('next_or_current_site_id', $sites, null, $sites_attributes ) }}

                    <div class="text-danger">
                        @error('site')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                  <label class="form-control-label" for="status">Status</label>
                    {{ Form::select('status', \App\Enums\BagStatus::STATUS_TEXT, null, [
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
          <div class="row">            {{-- Start Row --}}

            {{-- <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-control-label" for="start_date"> Start Date</label>
                        {{ Form::select('start_date', ['Test', 'Test'], null, [
                                'id' => 'start_date',
                                'class' => 'form-control next_site',
                                'data-toggle'=>"select",
                                'placeholder' => 'Select Start Date',
                                'required' => true
                        ]) }}

                    <div class="text-danger">
                        @error('start_date')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-sm-4">
                <div class="form-group">
					<label for="search-date">End Date</label>
					<input type="text" class="form-control" id="search-date" name="search-date" >
				</div>
            </div> --}}

            {{-- <div class="col-sm-4">
                <div class="form-group mt-4">
                  <button class="btn btn-default" id="search-departure-scans" >
                    <i class="fas fa-search text-warning"></i> SEARCH
                  </button>
                </div>
            </div> --}}

          </div> {{-- End Rows --}}
    </div>
    <div class="table-responsive">
      <table class="table align-items-center table-flush table-striped" id="bags-table">
        <thead class="thead-light">

          <tr>
            <th>S/N</th>
            <th>Code</th>
            <th>Status</th>
            <th>Current / Next Site</th>
            <th>Manifest / Transfer ID</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

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


</script>
@endpush

{{-- [
    'copy', 'csv', 'excel', 'pdf', 'print'
] --}}

@push('scripts')

    <script>


        function getStatus()
        {
            if( $('#status').val() == "")
            {
                return -1;
            }
            else
            {
                return  $('#status').val();
            }
        }


        function getType()
        {
            if( $('#type').val() == "")
            {
                return -1;
            }
            else
            {
                return  $('#type').val();
            }
        }

        $('document').ready(function () {


            //Implement Sever Side Rendering soon
          let workingTable =  $('#bags-table').DataTable(
            {

                processing: true,
                serverSide: true,
                dom: 'rtBp',
                buttons: [
                    'csv', 'print'
                ],
                //stateSave: true, research the consequence well
                pagingType: 'first_last_numbers',
                pageLength: 50,
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                ajax:
                {
                    url:  "{{ route('bags.index') }}",
                    data: function(d){
                        d.status = getStatus(), //$('input[name=start]').val()
                        d.next_or_current_site_id = $("#next_or_current_site_id").val()
                        d.type = getType()
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {data: "display_code", name:"display_code"},
                    {data: "display_status", name:"display_status"},
                    {data: "site.name", name:"site.name"},
                    {data: "current_manifest_or_transfer_display", name:"current_manifest_or_transfer_display"},
                    {data: "action", name:"action"}
                ]
            }

          );


        //   ------------------
        $('#status').change(function(){
            workingTable.draw();
            console.log('status', getStatus());
    });

    $('#next_or_current_site_id').change(function(){
            workingTable.draw();
    });

    $('#type').change(function(){
            workingTable.draw();
    });


        });
    </script>
@endpush
