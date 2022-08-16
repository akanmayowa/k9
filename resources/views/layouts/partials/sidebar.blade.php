<style>
    #sidenav-main a:hover {
        color: orange !important;
    }

    #sidenav-main a:active {
        color: orange !important;
    }

    .active {
        color: orange !important;
        background-color: LightGray !important;
    }

</style>
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{route('home')}}">
                <img src=" {{ asset('img/speedaf_logo.png') }}" class="navbar-brand-img" alt="speedaf_logo">
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
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{(request()->routeIs('home*')) ? 'active' : ''  }}" href="/home">
                            <i class="fas fa-home text-default"></i>
                            <i class="ni ni-archive-2 text-primary"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-manifest" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-manifest">
                            <i class="ni ni-map-big text-success"></i>
                            <i class="fas fa-clipboard-list text-success"></i>
                            <i class="fas fa-file-alt text-orange"></i>
                            <span class="nav-link-text">Manifests</span>
                        </a>
                        <div class="collapse" id="navbar-manifest">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item">
                                    <a href="{{route("timestamp.index")}}" class="nav-link">Groupings </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("createManifest")}}" class="nav-link">Create</a>
                                </li>
                                @hasanyrole('Quality Control Personnel')
                                <li class="nav-item">-</li>
                                <li class="nav-item">
                                    <a href="{{route("manifest.index")}}" class="nav-link">All</a>
                                </li>
                                @endhasanyrole
                                <li class="nav-item">
                                    <a href="{{route("waybills.index")}}" class="nav-link">All Waybills</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route("escalator.notifications")}}" class="nav-link">Escalator
                                        Notifications</a>
                                </li>
                                @hasanyrole('Operations|Site Supervisor')
                                <li class="nav-item">
                                    <a href="{{route("timestamp.index")}}" class="nav-link">Create</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewDispatchedManifests")}}" class="nav-link">Dispatched</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewDispatchedManifestsSummary")}}" class="nav-link">Dispatched
                                        Summary</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewIncomingManifests")}}" class="nav-link">Incoming</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewIncomingManifestsSummary")}}" class="nav-link">Incoming
                                        Summary</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewAcknowledgedManifests")}}" class="nav-link">Acknowledged</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewPartiallyAcknowledgedManifests")}}" class="nav-link">Partially
                                        Acknowledged</a>
                                </li>
                                @endhasanyrole
                                <li class="nav-item">
                                    <a href="{{route("manifestCompliance")}}" class="nav-link">Compliance</a>
                                </li>

                            </ul>
                        </div>
                    </li>



                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-waybill" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-waybill">
                            <i class="ni ni-map-big text-success"></i>
                            <i class="fas fa-clipboard-list text-success"></i>
                            <i class="fas fa-box text-success"></i>
                            <span class="nav-link-text">Waybills</span>
                        </a>
                        <div class="collapse" id="navbar-waybill">
                            <ul class="nav nav-sm flex-column">
                                @hasanyrole('Quality Control Personnel')
                                <li class="nav-item">-</li>
                                <li class="nav-item">
                                    <a href="{{route("manifest.index")}}" class="nav-link">All Manifests</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("waybills.index")}}" class="nav-link">All</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route("escalator.notifications")}}" class="nav-link">Escalator
                                        Notifications</a>
                                </li>
                                @endhasanyrole

                                @hasanyrole('Operations|Site Supervisor')
                                <li class="nav-item">
                                    <a href="{{route("viewDispatchedWaybills")}}" class="nav-link">Dispatched</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewDispatchedWaybillsSummary")}}" class="nav-link">Dispatched
                                        Summary</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewIncomingWaybills")}}" class="nav-link">Incoming</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewIncomingWaybillsSummary")}}" class="nav-link">Incoming
                                        Summary</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewAcknowledgedWaybills")}}" class="nav-link">Acknowledged</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("viewPendingWaybills")}}" class="nav-link">Pending</a>
                                </li>
                                @endhasanyrole
                            </ul>
                        </div>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-bags" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-bags">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="nav-link-text">Bags</span>
                        </a>
                        <div class="collapse" id="navbar-bags">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item">
                                    <a href="{{route("bags.index")}}" class="nav-link">All</a>
                                </li>

                                @hasanyrole('Operations|Site Supervisor')
                                <li class="nav-item">
                                    <a href="{{route("bags.transfer")}}" class="nav-link">Make Transfer</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("transfers.outgoing")}}" class="nav-link">Outgoing Transfers</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("transfers.incoming")}}" class="nav-link">Incoming Transfers</a>
                                </li>
                                @endhasanyrole
                            </ul>
                        </div>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-scancompliance" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-scancompliance">
                            <i class="ni ni-map-big text-warning"></i>
                            <i class="fas fa-tasks text-warning"></i>
                            <span class="nav-link-text">Scan Compliance</span>
                        </a>
                        <div class="collapse" id="navbar-scancompliance">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route("scan-complaince")}}" class="nav-link">Run Checks</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Recent Checks</a>
                                </li>
                            </ul>
                        </div>
                    </li>



                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-k9" data-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="navbar-employee">
                            <i class="ni ni-map-big text-primary"></i>
                            <span class="nav-link-text">K9</span>
                        </a>
                        <div class="collapse" id="navbar-k9">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route("waybills.k9_getDepartureScans")}}"
                                        class="nav-link {{(request()->routeIs('waybills.k9_getDepartureScans*')) ? 'active' : ''  }}">Departure
                                        List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("importUsers")}}" class="nav-link">Arrival List</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-summary" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-summary">
                            <i class="fas fa-eye text-gray"></i>
                            <span class="nav-link-text">Summary</span>
                        </a>
                        <div class="collapse" id="navbar-summary">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item">
                                    <a class="nav-link" href="/tarriff-quotation">
                                        <span class="nav-link-text">Dispatch</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="/watch" class="nav-link">Incoming</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/track">
                                        <span class="nav-link-text">Pending</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-my-tools" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-scancompliance">
                            <i class="fas fa-tools text-danger"></i>
                            <span class="nav-link-text">My Tools</span>
                        </a>
                        <div class="collapse" id="navbar-my-tools">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route("waybills.insights")}}" class="nav-link">Waybill Insight</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Remind Me</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Egale Eye</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/watch" class="nav-link">Watch</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/track">
                                        <span class="nav-link-text">Track Waybill</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>




                    @hasanyrole('Operations|Site Supervisor ')
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-configuration" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-configuration">
                            <i class="ni ni-map-big text-default"></i>
                            <i class="fas fa-cogs text-default"></i>
                            <span class="nav-link-text">Management</span>
                        </a>
                        <div class="collapse" id="navbar-configuration">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{route("users.manage")}}" class="nav-link ">Employees</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("sites.index")}}" class="nav-link">Sites</a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link">Roles & Permissions</a>
                                </li>

                                <li class="nav-item">
                                    <a href="" class="nav-link">Synchronization</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Settings</a>
                                </li>
                                <li class="nav-item">
                                    <a href="" class="nav-link">Import Tarriffs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#navbar-employee" data-toggle="collapse" role="button"
                                        aria-expanded="false" aria-controls="navbar-employee">
                                        <i class="ni ni-map-big text-primary"></i>
                                        <span class="nav-link-text">Employees</span>
                                    </a>
                                    <div class="collapse" id="navbar-employee">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{route("users.manage")}}" class="nav-link">Employee
                                                    Management</a>

                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @endhasanyrole
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-track" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-bags">
                            <i class="ni ni-map-big text-success"></i>
                            <i class="fas fa-clipboard-list text-success"></i>
                            <i class="fas fa-eye text-default"></i>
                            <span class="nav-link-text">Waybill Insights</span>
                        </a>
                        <div class="collapse" id="navbar-track">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item">
                                    <a class="nav-link" href="/track">
                                        <i class="ni ni-archive-2 text-warning"></i>
                                        <i class="fas fa-eye text-gray"></i>
                                        <span class="nav-link-text">Track by <b class="text-orange">Manifest</b></span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route("getK9DepartureScanSummary")}}" class="nav-link">Dispatched
                                        Summary</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("getWaybillsArrivalStatus")}}" class="nav-link">Waybills Status</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route("getK9IncomingScanSummary")}}" class="nav-link">Incomming
                                        Summary</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route("getK9DepartureScanSummary")}}" class="nav-link">Incmoming
                                        Waybills</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/trackOnK9">
                                        <i class="ni ni-archive-2 text-warning"></i>
                                        <i class="fas fa-eye text-orange"></i>
                                        <span class="nav-link-text">Track </span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route("StationArrivedWaybillsScanRecord")}}">

                                        Arrived Scans Monitor
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>



                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-tarriffs" data-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="navbar-tarriffs">
                            <i class="fas fa-funnel-dollar text-default"></i>
                            <span class="nav-link-text">Finance</span>
                        </a>
                        <div class="collapse" id="navbar-tarriffs">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="/tarriff-quotation">
                                        <span class="nav-link-text">Rate Calculator</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{route("tarriff.ecommerce")}}" class="nav-link">Speedaf Ecommerce Tarriff
                                        List</a>
                                </li>


                                <li class="nav-item">
                                    <a href="{{route("tarriff")}}" class="nav-link">Speedaf Tarriff List</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/account">
                                        <span class="nav-link-text">COD</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/account">
                                        <span class="nav-link-text">Pickup</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/account">
                                        <span class="nav-link-text">Pickup</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">LTL Tarriff List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">FTL Tarriff List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/tarriff/zonnings" class="nav-link">Zonnings matrix</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/track">
                                        <span class="nav-link-text">Track Waybill</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{(request()->routeIs('account.index*')) ? 'active' : ''  }}"
                                        href="{{ route('account.index') }}">
                                        <span class="nav-link-text">Delivered Waybills</span>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link  {{(request()->routeIs('commission.pickup*')) ? 'active' : ''  }}"
                                        href="{{ route('commission.pickup') }}">
                                        <span class="nav-link-text">Pickedup Waybills</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->routeIs('about*')) ? 'active' : ''  }}"
                            href="{{ url('/about') }}">
                            <i class="ni ni-archive-2 text-default"></i>
                            <span class="nav-link-text">About K9x</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{(request()->routeIs('api-docs.index*')) ? 'active' : ''  }}"
                            href="{{ route('api-docs.index') }}">
                            <i class="ni ni-archive-2 text-default"></i>
                            <span class="nav-link-text">Api Documentation</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{(request()->routeIs('api-users.index*')) ? 'active' : ''  }}"
                            href="{{ route('api-users.index') }}">
                            <i class="ni ni-archive-2 text-default"></i>
                            <span class="nav-link-text">Api Users</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
