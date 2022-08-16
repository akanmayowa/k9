@inject('BagStatus', '\App\Enums\BagStatus')
@inject('BagType', '\App\Enums\BagType')
@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header  bg-dark border-0">
        <h3 class="mb-0"><span  style="color:white">Register Bags</span></h3>
    </div>
    <div class="card-body">
        <div class="row"> {{-- Start Row --}}
            <div class="col-sm-4">
                {!! Form::open(['route' => 'bags.registerBags', 'id' => 'bags_register', 'class' => 'needs-validation', 'novalidate' => true]) !!}
                <div class="form-group">
                  <label class="form-control-label" for="type">Type</label>
                    {{ Form::select('type', \App\Enums\BagType::TYPE_TEXT, null, [
                            'id' => 'type',
                            'class' => 'form-control',
                            'data-toggle'=>"select",
                            'placeholder' => 'Select Bag Type',
                            'required' => true
                    ]) }}
                    <div class="text-danger">
                        @error('type')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label" for="site_id"> Site</label>
                    {{ Form::select('site_id', $sites, null, [
                            'id' => 'site_id',
                            'class' => 'form-control site_id',
                            'data-toggle' => 'select',
                            'placeholder' => 'Select Site',
                            'required' => true,
                            ]) }}
                    <div class="text-danger">
                        @error('site_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="group-tag">Bag Numbers</label>
            <textarea class="form-control" id="bag_numbers" name="bag_numbers" rows="3"
                placeholder="For example
                45,343,342"></textarea>
                <div class="text-danger">
                    @error('bag_numbers')
                        {{ $message }}
                    @enderror
                </div>
        </div>

        <button type="submit" class="btn btn-primary" id="register_bags">Register</button>
    </div>
@endsection


  @push('scripts')
    @endpush
