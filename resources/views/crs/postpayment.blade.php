@extends('layouts.app')
@section('content')
<div class="col-12 ptb-4">
     <div class="card">
         <div class="card">
        <div class="card-header alert alert-dark">
          <h3 class="mb-0 text-white">PROOF OF PAYMENT</h3>
        </div>
        <div class="card-body">
          <form action="" method="post" enctype="multipart/form">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label">PAYMENT TYPE</label>
                  <select class="form-control"  type="text" name="">
                   <option>--SELECT HERE--</option>
                   <option>PAYMENT FOR COD</option>
                   <option>PICKUP FEE</option>
                   <option>UNSPECIFIED</option>
                </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label class="form-control-label"> AMOUNT PAID</label>
                  <input class="form-control"  type="number" value="">
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label class="form-control-label">MEANS OF PAYMNET</label>
                  <select class="form-control"  type="text">
                      <option disabled="disabled" value="" readonly="readonly">--SELECT HERE--</option>
                      <option>CASH</option>
                      <option>BANK PAYMENT</option>
                      <option>ONLINE TRANSFER</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row align-items-center">
                <div class="col">
                  <div class="form-group">
                    <label class="form-control-label">SOURCE BANK</label>
                    <input class="form-control" placeholder="Start date" type="text" value="06/18/2018">
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label class="form-control-label">DESTINATION BANK</label>
                    <input class="form-control" placeholder="End date" type="text" value="06/22/2018">
                  </div>
                </div>
              </div>
              <div class="row  align-items-center">
                <div class="col">
                  <div class="form-group">
                    <label class="form-control-label">ENTER RELEVANT DETAILS</label>
                    <textarea  rows="5" class="form-control" type="text" ></textarea>
                  </div>
                </div>
              </div>
              <div class="row align-items-center">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">PAYMENT PROOF<small class="text-muted text-red">  ( please upload proof os payment )</small></label>
                    <input class="form-control"  type="file">
                  </div>
                </div>

              </div>
          </form>
        </div>
      </div>


</div>
</div>

@endsection

@push('scripts')
@endpush




{{-- payment for cod , pickup fee , unspecified
means of payment Bank Payment  | Online Transfer
Amount Paid
Source bank
Destination Bank
Enter any relevat detials
uploda Proof of Payment --}}




