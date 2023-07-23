<?php

namespace App\Http\Controllers;
use App\Models\Invoices;
use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }
    public function index(){
        $chartjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 350, 'height' => 200])
        ->labels(['الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا', 'الفواتير الغير مدفوعة'])
        ->datasets([
            [
                "label" => "الفواتير المدفوعة",
                'backgroundColor' => ["#99BB00",'#36A2EB',"#FF6384"],
                'data' => [round(invoices::where('Value_Status','=','1')->count()/invoices::count() * 100,2), round(invoices::where('Value_Status','=','3')->count()/invoices::count() * 100,2), round(invoices::where('Value_Status','=','2')->count()/ invoices::count() * 100,2)],
            ]
        ])
        ->options([]);
        $chartjs2 = app()->chartjs
        ->name('pieChartTest')
        ->type('pie')
        ->size(['width' => 200, 'height' => 200])
        ->labels(['الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا', 'الفواتير الغير مدفوعة'])
        ->datasets([
            [
                "label" => "الفواتير المدفوعة",
                'backgroundColor' => ["#99BB00",'#36A2EB',"#FF6384"],
                'data' => [round(invoices::where('Value_Status','=','1')->count()/invoices::count() * 100,2), round(invoices::where('Value_Status','=','3')->count()/invoices::count() * 100,2), round(invoices::where('Value_Status','=','2')->count()/ invoices::count() * 100,2)],
            ]
        ])
        ->options([]);
        return view('home',compact('chartjs','chartjs2'));
    }
}