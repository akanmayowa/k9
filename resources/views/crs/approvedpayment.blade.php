@extends('layouts.app')
@section('content')
<div class="col-12 ptb-4">
     <div class="card">
         <div class="card">
        <div class="card-header alert alert-dark">
          <h3 class="mb-0 text-white">APPROVED PAYMENT</h3>
        </div>
        <div class="card-body">
         <div class="table responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                    <th class="text-white">TRANSACTION_ID</th>
                    <th class="text-white">SITE_ID</th>
                    <th class="text-white">TRANSACTION_TYPE</th>
                    <th class="text-white">TRANSACTION_DATE</th>
                    <th class="text-white">AMOUNT</th>
                    <th class="text-white">POSTED BY</th>
                    <th class="text-white">POSTED DATE</th>
                    <th class="text-white">PAYMENT PROOF</th>
                    <th class="text-white">DATE EFFECTIVE</th>
                    <th class="text-white">DESCRIPTION</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th></th>
                    <td></td>
                  </tr>
                </tbody>
              </table>
         </div>
        </div>
      </div>
</div>
</div>

@endsection

@push('scripts')
@endpush




