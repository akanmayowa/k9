@extends('layouts.app')

@section('content')


    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Waybills</h3>
                <p class="text-sm mb-0">
                    Shipments
                </p>
            </div>
            <div class="table-responsive py-4">
                <table class="table table-flush" id="waybill-table">
                    <thead class="thead-light">
                        <tr>
                            {{-- <th>            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheckall">
                                <label class="custom-control-label" for="customCheckall"></label>
                            </div></th> --}}
                            <th>Waybill Number</th>
                            <th>Error Count</th>
                            <th></th>

                        </tr>



@foreach($check_result as $result)
    <tr >
        {{-- <td class="td-actions">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customCheck{{$loop->index}}">
                <label class="custom-control-label" for="customCheck{{$loop->index}}"></label>
            </div>
          </td> --}}
    @if($result->has_error === true)
    {{-- @foreach($result->errors as $error)
    <input type="hidden" id="error{{$result->waybill_number}}" value="{{$error}}" />
    @endforeach --}}
       <td style='color:red'>{{$result->waybill_number}}</td>
     @else
        <td style='color:green'>{{$result->waybill_number}}</td>
    @endif
   <td> <span class="badge badge-primary" style="font-size:18px; color:black">{{count($result->errors)}}</span></td>
  <td>
    <td class="text-right">
        <div class="dropdown">
          <a class="btn btn-lg btn-icon-only text-dark" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
            <a data-waybill={{$result->waybill_number}} data-error="{{json_encode($result->errors)}}" class="dropdown-item view-error" href="#">View Error Details</a>
            <a class="dropdown-item" href="#">Scan History</a>
          </div>
        </div>
      </td>
  </td>

     </tr>
@endforeach
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            </div>
        </div>
        </div>



  <!-- Modal -->
  <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-quotation" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-errorLabel">Errors Page For </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-errorBody">
          All errors here
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
$("#waybill-table").on('click', ".view-error", function(event){
            event.preventDefault();

            let currentRow = event.target;
            let currentWaybill = JSON.parse(
                currentRow.dataset.waybill
            );

            let errors = JSON.parse(
                currentRow.dataset.error
            );
            // alert("Hello");
            // console.log("view --\n", event.target.closest("tr"));
            console.log(currentWaybill, errors);
            $("#modal-errorLabel").text(currentWaybill);
            $("#modal-errorBody").html(errors);
            $("#modal-error").modal('show');
        });

</script>
@endpush

