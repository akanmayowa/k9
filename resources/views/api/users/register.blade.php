@extends('layouts.app')
@section('content')
<div class="row">
   <div class="col-12 ptb-4">
        <div class="card-header bg-dark text-white"> <span class="text-white">Register</span></div>
        @include('includes.messages')
        <div class="card-body"><div class="card-body">
            <form action="{{ route('api-users.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label class="form-control-label">App Code</label>
                    <input type="number" class="form-control"  name="id" placeholder="12345678">
                    </div>


                <div class="form-group">
                <label class="form-control-label">Company Name</label>
                <input type="text" class="form-control"  name="name" placeholder="Speedaf Express Logistic Nig">
                </div>

                    {{-- <div class="form-group">
                    <label class="form-control-label">Access Key</label>
                    <input type="number" class="form-control" >
                    </div> --}}

                    <div class="form-group">
                        <label class="form-control-label" >Access Type</label>
                        <select class="form-control" name="access_type" >
                        <option disabled selected>Please Select Access level</option>
                        <option value="0">TEST</option>
                        <option value="1">PRODUCTION</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" >Account Status</label>
                        <select class="form-control" name="status" >
                        <option disabled selected>Please Select Account status</option>
                        <option value="0">Activated</option>
                        <option value="1">DeActivated</option>
                        </select>
                    </div>


                <div class="form-group text-center">
                    <button type="cancel" class="btn btn-warning my-4">Cancel</button>
                    <button type="submit" class="btn btn-success my-4">Create Acoount</button>
                </div>


            </form>
          </div>

          </div>
      </div>
</div>
@endsection


@push('scripts')

@endpush




