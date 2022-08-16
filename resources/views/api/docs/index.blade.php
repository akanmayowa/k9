
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Api documentation </title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

  <style>
      th {
    background-color: black;
    color: white;
}
  </style>
    <style>
        .sidenav {
          height: 100%;
          width: 250px;
          position: fixed;
          z-index: 1;
          top: 0;
          left: 0;
          background-color: white;
          overflow-x: hidden;
          padding-top: 20px;
        }
        .sidenav a {
          padding: 6px 8px 6px 16px;
          font-size: 18px;
          display: block;
          padding-left: 60px;
        }


        .sidenav a:hover {
        color: orange;
        }

        .sidenav a:active {
        color: orange;
        }

        .sidenav .active {
        color: orange;
        }

    </style>
  </head>
<body>
    <div class="sidenav">
        <span class="">
            <img src="{{ asset('img/speedaf_logo.png') }}"  class="navbar-brand-img">
          </span>


<ul class="nav flex-column">
<li class="nav-item">  <a class="nav-links active pt-5" href="#overview">Overview</a>  </li>
<li class="nav-item"> <a class="nav-links" href="#introduction">Introduction</a>  </li>
<li class="nav-item"><a class="nav-links" href="#authentication">Authentication</a>  </li>
<li class="nav-item"> <a class="nav-links" href="#parameter">Parameter</a>  </li>
<li class="nav-item"> <a class="nav-links" href="#listofstate">List of States</a>  </li>
<li class="nav-item"> <a class="nav-links" href="#getrate">Get Rate</a>  </li>
<li class="nav-item"> <a class="nav-links" href="#errors">Errors</a>  </li>
</ul>

        </div>



  <div style="margin-left: 250px;padding: 0px 10px;" class="main-content" id="panel">
   {{-- <nav class="navbar navbar-top navbar-expand navbar-light border-bottom">
        <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-5 pt-5">
            <div class="collapse navbar-collapse">
            <form class="form-inline mr-sm-3 p-2">
              <div class="form-group mb-0">
                <div class="input-group input-group-alternative input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-secondary "><i class="fas fa-search"></i></span>
                  </div>
                  <input class="form-control bg-secondary" id="" placeholder="Search Api reference" type="text">
                </div>
              </div>
            </form>
        </div>
      </div>
   </nav> --}}


                <div class="container-fluid pt-5">
                      <div class="card">
                          <div class="card-body">
                           <div class="container-fluid col-10" >

                            <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-5 pt-5" id="overview">
                              <div class="col-12">
                              <h1>Speedaf API Documentation</h1>
                              <p> Our Rate Api is a Rest API service that allows you to manage your logistical demands by providing you with real-time shipping rates for your parcel or cargo from one point to another in Nigeria.
                                 All the requests and response are in json format.</p>
                            </div>
                           </div>


                          <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-5  pt-5" id="introduction">
                               <div class="col-12">
                                    <h3>Introduction</h3>
                                    <p>This Rate API is based on a set of calculations including package weight and delivery zone mapping. This Tariff API offers your consumers a first-class logistic experience at a very affordable rate</p>
                               </div>
                         </div>

                         <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-5  pt-5" id="authentication">
                             <div class="col-12">
                                 <h3>Authentication</h3>
                                 <p>All connection with the API must be protected, and any requests performed without a valid Security Token will be rejected. Every request is authenticated by giving an Authorization Token (Token Bearer).</p>
                           </div>
                        </div>

                         <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-5  pt-5" id="parameter">
                          <div class="col-12">
                              <h3>Parameter</h3>
                              <div class="table-responsive">
                                  <table class="table  table-hover">
                                    <thead>
                                      <tr>
                                        <th>Param</th>
                                        <th>Required?</th>
                                        <th>DataType</th>
                                        <th>Description</th>
                                        <th>Example</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>country</td>
                                            <td>Required</td>
                                            <td>String</td>
                                            <td>The country in which the shipment(parcel) departs and arrive</td>
                                            <td>nigeria</td>
                                          </tr>
                                      <tr>
                                        <td>weight_in_kg</td>
                                        <td>Required</td>
                                        <td>Float</td>
                                        <td>The Parcel's Weight is measured in kg</td>
                                        <td>1.5</td>
                                      </tr>
                                      <tr>
                                          <td>departure_state_name</td>
                                          <td>Required</td>
                                          <td>String</td>
                                          <td>The location from which the shipment(parcel) departs  <span class="text-danger p-2">(reference the <a href="#listofstate">valid state</a> section of the document)</span></td>
                                          <td>lagos</td>
                                        </tr>
                                        <tr>
                                          <td>destination_state_name</td>
                                          <td>Required</td>
                                          <td>String</td>
                                          <td>The location to which the shipment(parcel) is expected to arrive <span class="text-danger p-2">(reference the <a href="#listofstate">valid state</a>  section of the document)</span></td>
                                          <td>abuja</td>
                                        </tr>
                                    </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>



                        <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-5  pt-5" id="listofstate">
                            <div class="col-12">
                                <h3>List of valid state</h3>
                                <p>This are the list of valid states allowed for the departure and destination state parameter,
                                    <span class="text-primary">you can also get the valid state via the endpoint, Get http://ng.speedafutility.com/api/valid-states <span class="text-danger">(please note security token is required)</span></span></p>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                      <thead>
                                        <tr>
                                          <th>Id</th>
                                          <th>Name</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @forelse ($state as $states)
                                        <tr>
                                          <td>{{ $states->id }}</td>
                                          <td>{{  $states->name }}</td>
                                          @empty
                                          <td>No Data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    </table>
                                  </div>
                              </div>
                          </div>


                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mb-5 pt-5" id="getrate">

                                <div class="wrapper pb-2">
                                    <h2>Get Rate</h2>
                                </div>

                                  <h3 >Request Example</h3>
                                      <div class="container">
                                        <ul class="nav nav-tabs" role="tablist">
                                          <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#example">Example</a>
                                          </li>
                                          <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#curl">Curl</a>
                                          </li>
                                          <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#php">Php</a>
                                          </li>
                                          <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab"  href="#node.js">Node.js</a>
                                          </li>
                                        </ul>
                                        <div class="tab-content">
                                          <div id="example" class="container tab-pane active"><br>
                                            <div class="card text-white bg-dark">
                                              <div class="card-header bg-dark text-white">
  <button type="button"  class="btn btn-light btn-sm"><span>Domain:</span></button><a class="pr-5" target="_blank" href="http://ng.speedafutility.com">http://ng.speedafutility.com</a><button type="button" class="btn btn-light btn-sm "><span class="">Get</span></button><span> /api/rates</span>
                                              </div>
                                              <div class="card-body">
<pre class="last literal-block text-white" style="position: relative;">
{
    "country" : "nigeria",
    "departure_state_name": "lagos",
    "destination_state_name": "anambra",
    "weight_in_kg" : 4
}</pre>
                                              </div>
                                            </div>
                                          </div>
                                          <div id="curl" class="container tab-pane fade"><br>
                                            <div class="card text-white bg-dark">
                                              <div class="card-header bg-dark text-white">
 <button type="button"  class="btn btn-light btn-sm"><span>Domain:</span></button><a class="pr-5" target="_blank" href="http://ng.speedafutility.com">http://ng.speedafutility.com</a><button type="button" class="btn btn-light btn-sm "><span class="">Get</span></button><span> /api/rates</span>
                                              </div>
                                              <div class="card-body">
<pre class="last literal-block text-white" style="position: relative;">





</pre>
                                              </div>
                                            </div>
                                          </div>
                                          <div id="php" class="container tab-pane fade"><br>
                                            <div class="card text-white bg-dark">
                                              <div class="card-header bg-dark text-white">
     <button type="button" class="btn btn-light btn-sm"><span>Domain:</span></button><a class="pr-5" target="_blank" href="http://ng.speedafutility.com">http://ng.speedafutility.com</a><button type="button" class="btn btn-light btn-sm "><span class="">Get</span></button><span> /api/rates</span>
                                              </div>
                                              <div class="card-body">
<pre class="last literal-block text-white" style="position: relative;">






</pre>
                                              </div>
                                            </div>                                        </div>
                                          <div id="node.js" class="container tab-pane fade"><br>
                                            <div class="card text-white bg-dark">
                                              <div class="card-header bg-dark text-white">
 <button type="button"  class="btn btn-light btn-sm"><span>Domain:</span></button><a class="pr-5" target="_blank" href="http://ng.speedafutility.com">http://ng.speedafutility.com</a><button type="button" class="btn btn-light btn-sm "><span class="">Get</span></button><span> /api/rates</span>
                                              </div>
                                              <div class="card-body">
<pre class="last literal-block text-white" style="position: relative;">




</pre>
                                              </div>
                                            </div>
                                           </div>
                                        </div>
                                  </div>
                                 </div>

                                <div class="mb-5 pt-5 col-lg-4 col-md-12 col-sm-12 col-xs-12 mb-5"  id="response"><br />
                                  <div class="container mt-5">
                                    <h3>Response Example</h3>
                                    <div class="card-header bg-dark text-white"><button type="button"  class="btn btn-light btn-sm"><span>Status:</span></button>&nbsp;&nbsp;<span> 200 OK</span> </div>
                                        <div class="card-body bg-dark">
<pre class="last literal-block text-white" style="position: relative;">
{
 "status": true,
 "data": [
   {
        "amount": 1900,
        "currency": "NG"
   }
 ]
}</pre>
                                            </div>
                                        </div>
                                    </div>

                                  </div>


                             <div class="col-12 mb-5" id="errors">
                                    <h3>Errors</h3>
                                    <div class="table-responsive">
                                                  <table class="table  table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Status Code-Message</th>
                                                            <th>Description</th>
                                                        </tr>
                                                     </thead>
                                                      <tbody>
                                                            <tr>
                                                            <td><span>200 - OK</span></td>
                                                            <td><span>Everything worked as expected.</span></td>
                                                            </tr>

                                                            <tr>
                                                            <td><span>400 - Bad Request</span></td>
                                                            <td><span>The request was unacceptable, often due to missing a required parameter.</span></td>
                                                            </tr>


                                                            <tr>
                                                              <td><span>404 - Not Found</span></td>
                                                              <td><span>No valid Security token provided or Wrong url or the request doesn’t exist.</span></td>
                                                            </tr>


                                                            <tr>
                                                              <td><span>500 - Server Error</span></td>
                                                              <td><span>we’ve got a problem on our side.</span></td>
                                                            </tr>


                                                            <tr>
                                                            <td><span>503 - Service Unavailable</span></td>
                                                            <td><span>Our API is down. Please try again.</span></td>
                                                            </tr>

                                                  </tbody>
                                                </table>
                                            </div>
                                    </div>
                      </div>
                    </div>
                  </div>
                </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(".nav-links").click(function() {
        var listItems = $(".nav-links");
        for (let i = 0; i < listItems.length; i++) {
            listItems[i].classList.remove("active");
        }
        this.classList.add("active");
    });
</script>

</body>
</html>
