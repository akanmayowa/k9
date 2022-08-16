@extends('layouts.app')


@section('content')
<div class="card">
    <!-- Card header -->
    <div class="card-header border-0">
      <div class="row">
        <div class="col-6">
          <h3 class="mb-0"><span  style="color:coral">Notifications (<span class="text-success display-2"> {{  $notifications->count()}} </span>)</span></h3>
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
        <table class="table align-items-center table-flush table-striped">
            <thead class="thead-light">

              <tr>
                <th>S/N</th>
                <th>Date Logged</th>
                <th>Content</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $notification)
              <tr>
                    <td>{{$loop->index + 1}}</td>
                    <td>{{ $notification->created_at }}</td>
                    <td>{{$notification->content}}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
    </div>
  </div>
@endsection


@push('scripts')
<script>

setInterval(function() {
    window.location.reload();
  }, 60000);

</script>
@endpush


