@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/fontawesome.min.css" integrity="sha512-8Vtie9oRR62i7vkmVUISvuwOeipGv8Jd+Sur/ORKDD5JiLgTGeBSkI3ISOhc730VGvA5VVQPwKIKlmi+zMZ71w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" integrity="sha512-yFjZbTYRCJodnuyGlsKamNE/LlEaEAxSUDe5+u61mV8zzqJVFOH7TnULE2/PP/l5vKWpUNnF4VGVkXh3MjgLsg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="col-12 ptb-4">
     <div class="card">
         <div class="card">
        <div class="card-header alert alert-dark">
          <h3 class="mb-0 text-white">PAYMENT LIST</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                        <th class="text-white">PAYMENT_ID</th>
                        <th class="text-white">TRANSACTION_DATE</th>
                        <th class="text-white">DESCRIPTION</th>
                        <th class="text-white">STATUS</th>
                    </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
<button type="button" class="btn btn-danger btn-sm">X</button>
<button type="button" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i> print</button>
                      </td>
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
