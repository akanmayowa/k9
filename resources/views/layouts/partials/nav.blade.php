<?php

//Unread messages
    $personal_messages = Auth::user()->summary_personal_messages;
?>

<nav class="navbar navbar-top navbar-expand navbar-light bg-secondary border-bottom">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Search form -->
                {{-- <form class="navbar-search navbar-search-dark form-inline mr-sm-3" id="navbar-search-main">
                    <div class="form-group mb-0">
                    <div class="input-group input-group-alternative input-group-merge">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input class="form-control" placeholder="Search" type="text">
                    </div>
                    </div>
                    <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </form> --}}

                <!-- Navbar links -->
                <ul class="navbar-nav align-items-center ml-md-auto">
                    <li class="nav-item d-xl-none">
                    <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-light" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                    </li>
                    {{-- <li class="nav-item d-sm-none">
                    <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                        <i class="ni ni-zoom-split-in"></i>
                    </a>
                    </li> --}}
                    <li class="nav-item dropdown">
                    {{-- <a class="nav-link" href="{{route("personal-messages.index")}}">
                      @if (Auth::user()->unread_personal_message_count > 0)
                      <i class="ni ni-bell-55 text-red"></i>
                      @else
                      <i class="ni ni-bell-55"></i>
                      @endif
                        <span class="display-6">({{Auth::user()->unread_personal_message_count }})</span>
                    </a> --}}

                    </li>
                    {{-- <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ni ni-ungroup"></i>
                        <span>Quick Links</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-warning dropdown-menu-right">
                        <div class="row shortcuts px-4">
                        <a href="#!" class="col-4 shortcut-item">
                            <span class="shortcut-media avatar rounded-circle bg-gradient-red">
                            <i class="ni ni-calendar-grid-58"></i>
                            </span>
                            <small>Track</small>
                        </a>
                        <a href="#!" class="col-4 shortcut-item">
                            <span class="shortcut-media avatar rounded-circle bg-gradient-orange">
                            <i class="ni ni-email-83"></i>
                            </span>
                            <small></small>
                        </a>
                        <a href="#!" class="col-4 shortcut-item">
                            <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                            <i class="ni ni-credit-card"></i>
                            </span>
                            <small>Rate</small>
                        </a>
                        <a href="#!" class="col-4 shortcut-item">
                            <span class="shortcut-media avatar rounded-circle bg-gradient-green">
                            <i class="ni ni-books"></i>
                            </span>
                            <small>Reports</small>
                        </a>
                        <a href="#!" class="col-4 shortcut-item">
                            <span class="shortcut-media avatar rounded-circle bg-gradient-purple">
                            <i class="ni ni-pin-3"></i>
                            </span>
                            <small>Maps</small>
                        </a>
                        <a href="#!" class="col-4 shortcut-item">
                            <span class="shortcut-media avatar rounded-circle bg-gradient-yellow">
                            <i class="ni ni-basket"></i>
                            </span>
                            <small>Shop</small>
                        </a>
                        </div>
                    </div>
                    </li> --}}
                </ul>
                <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                    <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Image placeholder" src="{{ asset('img/team-1.jpg')}}">
                        </span>
                        <div class="bg-white media-body ml-2 d-none d-lg-block"> {{--null user on time out session ?--}}
                            @if (Auth::check())
                            <span class="badge ">{{ Auth::user()->first_name }}  {{ Auth::user()->name }}</span>[<span class="badge bg-white">{{Auth::id()}}</span>]
                            @endif
                        </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome!</h6>
                        </div>
                        <a href="{{route('profile')}}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>My profile</span>
                        </a>
                        <a href="{{route('changePassword')}}" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>Change Password</span>
                        </a>
                        {{-- <a href="#!" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>Activity</span>
                        </a>
                        <a href="#!" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>Support</span>
                        </a> --}}
                        <div class="dropdown-divider"></div>
                        <a href="{{route('logout')}}" class="dropdown-item">
                        <i class="ni ni-user-run"></i>
                        <span>Logout</span>
                        </a>
                    </div>
                    </li>
                </ul>
                </div>
            </div>
            </nav>
