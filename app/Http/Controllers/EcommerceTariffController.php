<?php

namespace App\Http\Controllers;

use Exception;
use App\EcommerceTariff;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Services\TarriffQuotationServices;

class EcommerceTariffController extends Controller
{

    public $tarriffQuotationServices = null;
    public function __construct(TarriffQuotationServices $tarriffQuotationServices)
    {
         $this->middleware('auth');
         $this->tarriffQuotationServices = $tarriffQuotationServices;
    }

    public function index()
    {
        $ecommerce_tariff = EcommerceTariff::get();
        return view('tarriff.ecommerce')->with('ecommerce_tariff', $ecommerce_tariff);
    }

    public function getEcommerceTarriffs()
    {
        try
        {
            return response()->json(['success' => true, 'message' => 'Ecommerce tariffs retrieved successfully', 'data' =>$this->tarriffQuotationServices->getEcommerceTarriffs() ]);
        }
        catch(Exception $error)
        {
             return response()->json(['success' => false, 'message' => 'Oops, Could not tariffs']);
        }
    }

    public function getEcommerceTarriffsV2(Request $request)
     {
        if($request->ajax())
        {
            $ecommerce_tariff = EcommerceTariff::select(['weight_start', 'zone_1_cost', 'zone_2_cost', 'zone_3_cost', 'zone_4_cost'])->get();
            return Datatables::of($ecommerce_tariff)
            ->filter(function ($instance) use ($request) {
                if ($request->filled('weight_start')) 
                {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            return Str::contains($row['weight_start'], $request->get('weight_start')) ? true : false;
                        });
                }
                else
                {
                    return $instance;
                }
            })
            ->editColumn('weight_start', function($ecommerce_tariff){
                return floatval($ecommerce_tariff->weight_start);
            })
            ->editColumn('zone_1_cost', function($ecommerce_tariff){
                return $this->formatNumber($ecommerce_tariff->zone_1_cost);
            })
            ->editColumn('zone_2_cost', function($ecommerce_tariff){
                return $this->formatNumber($ecommerce_tariff->zone_2_cost);
            })
            ->editColumn('zone_3_cost', function($ecommerce_tariff){
                return $this->formatNumber($ecommerce_tariff->zone_3_cost);
            })
            ->editColumn('zone_4_cost', function($ecommerce_tariff){
                return $this->formatNumber($ecommerce_tariff->zone_4_cost);
            })
            ->make(true);
        }
     }

    public function formatNumber($number, $precision = 2)
     {
        return number_format((float)$number, $precision, '.', '');
     }
}
