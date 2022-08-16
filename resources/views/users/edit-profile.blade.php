
@extends('layouts.app')

@section('content')
<div class="container">
<div class="row gutters">
{{-- <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12"> --}}
{{-- <div class="card h-100">
	<div class="card-body">
		<div class="account-settings">
			<div class="user-profile">
				<div class="user-avatar">
					<img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Maxwell Admin">
				</div>
				<h5 class="user-name">Yuki Hayashi</h5>
				<h6 class="user-email">yuki@Maxwell.com</h6>
			</div>
			<div class="about">
				<h5>About</h5>
				<p>I'm Yuki. Full Stack Designer I enjoy creating user-centric, delightful and human experiences.</p>
			</div>
		</div>
	</div>
</div> --}}
{{-- </div> --}}
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
<div class="card h-100">
	<div class="card-body">
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
					<input type="text" class="form-control" id="primary-email" name="primary_email" value="{{$user->email}}" disabled>
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="website">Secondary Email</label>
					<input type="email" class="form-control" id="alternate-email" value="{{$user->alternate_email}}">
				</div>
			</div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="phone">Primary Phone Number</label>
					<input type="text" class="form-control" id="phone_number" value="{{$user->phone_number}}" disabled>
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="website">Secondary Phone Number</label>
					<input type="email" class="form-control" id="email-alternate" value="{{$user->alternate_phone_number}}">
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
					<button type="button" id="submit" name="submit" class="btn btn-secondary">Cancel</button>
					<button type="button" id="submit" name="submit" class="btn btn-primary">Update</button>
				</div>
			</div>
		</div>

	</div>
</div>
</div>
</div>
</div>
@endsection
