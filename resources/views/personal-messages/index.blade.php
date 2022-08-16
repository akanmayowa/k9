@extends('layouts.app')


@section('content')
<div class="card">
    <!-- Card header -->
    <div class="card-header border-0">
      <div class="row">
    <div class="col-md-6">
          <h3 class="mb-0"><span  style="color:coral">All Nofications </span></h3>
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
                <th>Date Received</th>
                <th>Content</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                @foreach ($personal_messages as $personal_message)
              <tr>
                    <td>{{$loop->index + 1}}</td>
                    {{-- this check is temporal oo --}}
                    <td>
                        @if(!is_null($personal_message->created_at))
                        <span class="">{{$personal_message->created_at->diffForHumans()}}</span>
                        <div class="text-green">{{$personal_message->created_at->format('Y-m-d , g:i A')}}</div>
                        @endif
                        </td>
                    <td>
                        {{ \Illuminate\Support\Str::limit($personal_message->message, 50, $end='...') }}
                        {{-- {{$personal_message->message}} --}}
                    </td>
                    <td>
                        @if($personal_message->read != 1)
                        <a href="{{route("personal-messages.read", $personal_message->id)}}" class="dropdown-item text-center text-primary font-weight-bold py-3">Read</a>
                        @else
                        <a href="{{route("personal-messages.read", $personal_message->id)}}" class="dropdown-item text-center text-primary font-weight-bold py-3">View</a>
                        @endif
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

setInterval(function() {
    window.location.reload();
  }, 60000);

</script>
@endpush


