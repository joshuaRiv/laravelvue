<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getProductosMasVendidos(Request $req)
    {
        if (!$req->ajax()) return redirect('/');

        $res = DB::select( 'call sp_Dashboard_getProductosMasVendidos' );

        return $res;
    }
    
    public function getVentasPorDia(Request $req)
    {
        if (!$req->ajax()) return redirect('/');

        $res = DB::select( 'call sp_Dashboard_getVentasPorDia' );

        return $res;
    }
}
