<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScanErrorDetector;
use App\Services\SmsServices;

class ScanComplianceController extends Controller
{
    public function index() {
        return view("/scan-compliance.index");
    }

    public function runChecks()
    {

        $path = request()->file('file');
        $ScanErrorDectector = new ScanErrorDetector();
        $ScanErrorDectector->ReadFile($path);
        $check_result =  $ScanErrorDectector->DectAllErrors();
        // dd($result);
        return view("scan-compliance.result", compact('check_result'));
    }

}
