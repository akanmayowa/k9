<footer class="footer pt-50">
    <div class="row align-items-center justify-content-lg-between">
    <div class="col">
        {{-- <div class="copyright text-center bg-dark text-white p-4">
        &copy; {{ date('Y') }} <a href="{{route("home")}}" class="font-weight-bold ml-1">K9x - An Initiative of Speedaf IT Department</a>
        </div> --}}
        <div>
            <h2 class="badge badge-dark text-white"><span>Site : {{Auth::user()->site->name }}</h3>
        </div>
    </div>
    </div>
</footer>
