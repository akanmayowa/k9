@inject('TransferStatus', '\App\Enums\TransferStatus')
@extends('layouts.app')


@section('content')
<div class="card">
        <!-- Card header -->
        <div class="card-header bg-dark border-0">
            <div class="row">
              <div class="col-6">
                <h3 class="mb-0"><span  style="color:white">OUTGOING TRANSFERS</span></h3>
              </div>
              <div class="col-6 text-right">
              </div>
            </div>
          </div>
    <div class="card-body">
        <div class="row">            {{-- Start Row --}}
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="form-control-label" for="destination_site"> TO </label>
                    @php
                        $to_attributes = [
                                'id' => 'destination_site',
                                'class' => 'form-control destination_site',
                                'data-toggle'=>"select",
                                'placeholder' => 'All Sites',
                                'required' => true
                ];
                    @endphp
                        {{ Form::select('destination_site', $to_sites, null, $to_attributes ) }}

                    <div class="text-danger">
                        @error('destination_site')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

          </div> {{-- End Rows --}}
          <div class="row">            {{-- Start Row --}}
          </div> {{-- End Rows --}}
    </div>
    <div class="table-responsive">
      <table class="table align-items-center table-flush table-striped" id="moderator-view-table">
        <thead class="thead-light">

          <tr>
            <th>S/N</th>
            <th>Transfer ID</th>
            <th>TO</th>
            <th>No of Bags</th>
            <th>Dispatched</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
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


        $('document').ready(function () {
          let workingTable =  $('#moderator-view-table').DataTable(
            {

                processing: true,
                serverSide: true,
                dom: 'rtBp',
                buttons: [
                    'csv', 'print'
                ],
                //stateSave: true, research the consequence well
                pagingType: 'first_last_numbers',
                pageLength: 30,
                lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
                ajax:
                {
                    url:  "{{ route('transfers.outgoing') }}",
                    data: function(d){
                        d.destination_site = $("#destination_site").val()
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "id", name : "id"
                    },
                    { data: "destination_site.name", name: 'destination_site.name' },
                    {data: "dispatched_bags_count", name:"dispatched_bags_count"},
                    {data: "dispatched", name:"dispatched"},
                    {data: "action", name:"action"},
                ]
            }

          );


    $('#destination_site').change(function(){
            workingTable.draw();
    });
});
    </script>
@endpush
