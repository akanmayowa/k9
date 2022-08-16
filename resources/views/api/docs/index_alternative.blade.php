@extends('layouts.app')
@section('content')
<div class="col-12 ptb-4">
     <div class="card">
         <div class="card-body">
          <div class="container-fluid">
            <div class="wrapper">
                <div class="inner-banner mb-5">
                        <div class="card-header bg-dark text-white"><h1 class="text-white"><----Speedaf API Documentation----></h1></div>
                            <p> Our Rate Api is a Rest API service that allows you to manage your logistical demands by providing you with real-time shipping rates for your parcel or cargo from one point to another in Nigeria. </p>
                        </div>


                <div class="col-12 mb-5">
                   <div class="card-header bg-dark text-white"> <h3 class="text-white">Introduction</h3></div>
                <div class="col-12">
                    <p>This Rate API is based on a set of calculations including package weight and delivery zone mapping. This Tariff API offers your consumers a first-class logistic experience at a very affordable rate</p>
                </div>
                </div>


                <div class="col-12 mb-5">
                 <div class="card-header bg-dark text-white"> <h3 class="text-white">Authentication</h3></div>
                    <div>
                        <p>All connection with the API must be protected, and any requests performed without a valid API Key will be rejected. Every request is authenticated by giving an Authorization key (Token Bearer).</p>
                    </div>
                </div>

                <div class="col-12 mb-5">
                        <div class="card">
                          <div class="card-header bg-dark text-white"> <h3 class="text-white">Parameter</h3></div>
                          <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                  <thead class="thead-dark">
                                    <tr class="text-white">
                                      <th class="text-white">Param</th>
                                      <th class="text-white">Required ?</th>
                                      <th class="text-white">DataType</th>
                                      <th class="text-white">Desciption</th>
                                      <th class="text-white">Example</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>weight</td>
                                      <td>Required</td>
                                      <td>Float</td>
                                      <td>The Parcel's Weight is measured in kg</td>
                                      <td>1.5</td>
                                    </tr>
                                    <tr>
                                        <td>departure state</td>
                                        <td>Required</td>
                                        <td>String</td>
                                        <td>the location from which the shipment(parcel) departs</td>
                                        <td>lagos</td>
                                      </tr>
                                      <tr>
                                        <td>destination state</td>
                                        <td>Required</td>
                                        <td>String</td>
                                        <td>the location to which the shippment(parcel) is expected to arrive</td>
                                        <td>abuja</td>
                                      </tr>
                                  </tbody>
                                </table>
                              </div>
                          </div>
                        </div>
                  </div>

                  <div class="col-12 mb-5">
                    <div class="card">
                        <div class="card-header bg-dark"> <h3 class="text-white">Request Example</h3></div>
                           <div class="card-body bg-light">
<pre class="last literal-block" style="position: relative;">
{
    "weight": 1.5,
    "departure_state_name": "lagos",
    "destination state": "abuja"
}</pre>
                           </div>
                       </div>
                   </div>

                   <div class="col-12 mb-5">
                    <div class="card">
                        <div class="card-header bg-dark"> <h3 class="text-white">Response Example</h3></div>
                           <div class="card-body bg-light">
<pre class="last literal-block" style="position: relative;">
{
    "success": true,
    "shipping rate": 1000:
}</pre>
                           </div>
                       </div>
                   </div>



                   <div class="col-12 mb-5">
                    <div class="card">
                        <div class="card-header bg-dark"> <h3 class="text-white">Errors</h3></h3></div>
                        <div class="card-body">
                                <section class="table">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-dark">
                                        <tr>
                                            <th class="text-white">Status Code-Message</th>
                                            <th class="text-white">Description</th>
                                        </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                            <th><span>200 - OK</span></th>
                                            <td><span>Everything worked as expected.</span></td>
                                        </tr>

                                        <tr>
                                            <th><span>400 - Bad Request</span></th>
                                          <td><span>The request was unacceptable, often due to missing a required parameter.</span></td>
                                        </tr>


                                        <tr><th><span>404 - Not Found</span></th>
                                        <td><span>No valid API key provided or Wrong url or the request doesn’t exist.</span></td>
                                       </tr>


                                        <tr><th><span>500 - Server Error</span>
                                        </th>
                                        <td><span>we’ve got a problem on our side.</span></td>
                                       </tr>


                                       <tr><th><span>503 - Service Unavailable</span>
                                       </th>
                                       <td><span>Our API is down. Please try again.</span></td>
                                      </tr>

                          </tbody>
                        </table>
                        </section>
                        </div>
                       </div>
                   </div>




            </div>
       </div>
    </div>
</div>
</div>
@endsection


@push('scripts')

@endpush





<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <div class="sidenav-header d-flex align-items-center">
        <a class="">
          <img src="{{ asset('img/speedaf_logo.png') }}"  class="navbar-brand-img" >
        </a>
        <div class="ml-auto">
            <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
              <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
              </div>
            </div>
          </div>
      </div>
      <div class="navbar-inner">
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav" >
            <li class="nav-item">
              <a class="nav-link active" href="#overview"  role="button" aria-expanded="false" >
                <span class="nav-link-text">Overview</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#introduction"  role="button" aria-expanded="false" >
                <span class="nav-link-text">Introduction</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#authentication"  role="button" aria-expanded="false" >
                <span class="nav-link-text">Authentication</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#parameter"  role="button" aria-expanded="false" >
                <span class="nav-link-text">Parameter</span>
              </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#listofstate"  role="button" aria-expanded="false" >
                  <span class="nav-link-text">List of States</span>
                </a>
              </li>
            <li class="nav-item">
              <a class="nav-link" href="#getrate"  role="button" aria-expanded="false" >
                <span class="nav-link-text">Get Rate</span>
              </a>
            </li>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#errors"  role="button" aria-expanded="false" >
                <span class="nav-link-text">Errors</span>
              </a>
            </li>
          </ul>
          <hr class="my-3">
        </div>
      </div>
    </div>
  </nav>




    </head>
    <body>
