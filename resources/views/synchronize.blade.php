@extends('layouts.app')
<style>
    .buttonload {
      background-color: #04AA6D; /* Green background */
      border: none; /* Remove borders */
      color: white; /* White text */
      padding: 12px 24px; /* Some padding */
      font-size: 16px; /* Set a font-size */
    }


    .buttonload {
      background-color: #04AA6D; /* Green background */
      border: none; /* Remove borders */
      color: white; /* White text */
      padding: 12px 24px; /* Some padding */
      font-size: 16px; /* Set a font-size */
    }


    .fa {
      margin-left: -12px;
      margin-right: 8px;
    }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@section('content')
<div class="col-12 ptb-4">
     <div class="card">
         <div class="card-body">
          <div class="container-fluid">

            @if(Session::has('success'))
            <div class="alert {{ Session::get('succ', 'alert-primary') }} alert-dismissible fade show">
                {{ Session::get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if(Session::has('error'))
            <div class="alert {{ Session::get('error', 'alert-danger') }} alert-dismissible fade show">
                {{ Session::get('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="container-fluid p-2"><h3><strong>SYNCHRONIZE</strong></h3></div>


              @if (\Request::is('synchronize/index'))
              <a href="{{ route('synchronize.sites') }}" class="buttonload">
                <i class="fa fa-spinner fa-spin"></i>Click to Sync Sites Table
              </a>
            @endif


           @if (\Route::current()->getName() == 'synchronize.sites') {
            <a href="{{ route('synchronize.sites') }}" class="buttonload">
               Click
              </a>
            @endif

              <br /><br /><br />

              <a  href="{{ route('synchronize.employees') }}" class="buttonload">
                <i class="fa fa-circle-o-notch fa-spin"></i>Click to Sync employees Table
              </a>

              <br/><br/><br/>

              <button class="buttonload">
                <i class="fa fa-refresh fa-spin"></i>Loading
              </button>
       </div>
    </div>
</div>
</div>
@endsection


@push('scripts')

@endpush
