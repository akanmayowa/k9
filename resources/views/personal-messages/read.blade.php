@extends('layouts.app')


@section('content')
<div class="card">
    <div class="card-header  bg-default">
        <div class="row">

              <h3 class="mb-0 text-white display-4">

                {{$personal_message->subject}} [

                    @if(!is_null($personal_message->created_at))
                                <span class="text-green">{{$personal_message->created_at->format('Y-m-d , g:i A')}}</span>
                                @endif

                ]

              </h3>

          {{-- <div class="col-6 text-right">
          </div> --}}
        </div>
      </div>
    <div class="card-body">
    {{$personal_message->message}}</div>
    <div class="card-footer">
 <a href="{{ url()->previous() }}" class="btn btn-default"  ><i class="fas fa-backward"> </i> Back</a>
    </div>
</div>
@endsection
