<div class="header pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 d-inline-block mb-0">
                        {{ config('app.name') }}
                    </h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Dashboard</a>
                            </li>
                            {{-- <li class="breadcrumb-item">
                                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Dashboard</a>
                            </li> --}}
                            @yield('test')
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    @yield('button')
                    {{-- <a href="#" class="btn btn-sm btn-neutral">New</a>
                    <a href="#" class="btn btn-sm btn-neutral">Filters</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>

