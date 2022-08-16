@extends('layouts.app')
@section('content')
<div class="col-12 ptb-4">
     <div class="card">
         <div class="card-body">
          <div class="container-fluid">
            <div class="wrapper">
                <div class="inner-banner mb-5">

                        <div class="card-header bg-dark text-white"><h1 class="text-white"><----Speedaf API Documentation----></h1></div>
                       <p>Our Rate Api is a Rest API tool that lets you manage your logistic needs by provides you with realtime shipping rate for your parcel or cargo from one location to another accross nigeria</p>
                </div>


                <div class="col-12 mb-5">
                   <div class="card-header bg-dark text-white"> <h3 class="text-white">Introduction</h3></div>
                <div class="col-12">
                    <p> This Rate Api is based on a series of calculation involving the mapping of weight of package and package delivery zone. This Tariff Api provides a first logistic experience for your customers at a very affordable rate. </p>
                </div>
                </div>


                <div class="col-12 mb-5">
                 <div class="card-header bg-dark text-white"> <h3 class="text-white">Authentication</h3></div>
                    <div>
                        <p>Rate Api requires that all communication with the API is secured and any Requests made over without a proper API Key will fail.
                            The Authentication is done by providing an Authorization key (Token Bearer) on every request.
                          Rate APi
                         </p>
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
    "departure state": "lagos",
    "destination state": "abuja":
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




