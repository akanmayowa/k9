@extends('layouts.app')


@section('content')
<div class="card">
    <!-- Card header -->
    <div class="card-header border-0">
      <div class="row">
        <div class="col-6">
          <h3 class="mb-0"><span  style="color:coral">WAYBILLS</span></h3>
        </div>
        <div class="col-6 text-right">
          {{-- <a href="#" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Edit Manifest">
            <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
            <span class="btn-inner--text">Export</span>
          </a> --}}
        </div>
      </div>
    </div>
    <!-- Light table -->
    <div class="table-responsive">
        <table class="table align-items-center table-flush table-striped" id="waybills-view-table">
            <thead class="thead-light">

              <tr>
                <th>Waybill Number</th>
                <th>Status</th>
                <th>Departure Site</th>
                <th>Next Site</th>
                <th>Departure Date</th>
                <th>Route</th>
                <th>Manifest ID</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($waybills as $waybill)
              <tr>
                <td>
                    {{$waybill->id}}
                </td>
                    <td class="left strong">  @if($waybill->status === \App\Enums\ManifestStatus::IN_TRANSIT)
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
                    </td>

              </tr>
            @endforeach
            </tbody>
          </table>
    </div>
  </div>
@endsection




@push('scripts')
    <script>
        $('document').ready(function () {
            //Implement Sever Side Rendering soon
          let workingTable =  $('#waybills-view-table').DataTable({
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
