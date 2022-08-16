<?php
    $user = Auth::user();
?>
@extends('layouts.app')

@section('content')
<div class="container">
<div class="row gutters">

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
<div class="card h-100">
	<div class="card-body">
        {!! Form::open(['route' => 'users.updateProfile', 'id' => 'user-edit-profile', 'class' => 'needs-validation', 'novalidate' => true]) !!}
        <input type="hidden" name="user_id" value="{{Auth::id()}}">
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<h6 class="mb-2 text-primary">Personal Details</h6>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="fullName">Full Name</label>
					<input type="text" class="form-control" id="name" name="name" value="{{$user->name}}" placeholder="Enter full name" disabled>
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="eMail">Site Name</label>
					<input type="email" class="form-control" id="site_name" value="{{$user->site->name}}" placeholder="Enter email ID" disabled>
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="phone">Primary Email</label>
					<input type="text" class="form-control" id="primary-email" name="email" value="{{$user->email}}">
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="website">Secondary Email</label>
					<input type="email" class="form-control" id="alternate-email" name="alternate_email" value="{{$user->alternate_email}}">
				</div>
			</div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
                    <label for="phone">Primary Phone Number @if (is_null($user->phone_number_verified_at))
                        <i class="fas fa-exclamation-triangle text-red"></i>
                        @endif</label>
					<input type="text" class="form-control" id="phone_number" name="phone_number" value="{{$user->phone_number}}" >
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="website">Secondary Phone Number</label>
					<input type="text" class="form-control" name="alternate_phone_number" id="alternate_phone_number" value="{{$user->alternate_phone_number}}">
				</div>
			</div>
		</div>

		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<h6 class="mt-3 mb-2 text-primary">Groups that you belong </h6>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="Street">Role</label>
                    @php
                    $roles = "";
                    foreach ($user->getRoleNames() as $role) {
                        $roles .= "[ " .$role ."], ";
                    }
					echo "<textarea type='name' class='form-control' id='Street' rows='3' readonly>$roles</textarea>";

                    @endphp
                    {{-- https://stackoverflow.com/questions/5235142/how-do-i-disable-the-resizable-property-of-a-textarea --}}
				</div>
			</div>


		</div>
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="text-right">
					{{-- <button type="button" id="submit" name="submit" class="btn btn-secondary">Cancel</button> --}}
					<button type="submit" id="submit" name="submit" class="btn btn-primary">Update</button>
				</div>
			</div>
		</div>
        {!! Form::close() !!}
	</div>
</div>
</div>
</div>
</div>
@endsection
