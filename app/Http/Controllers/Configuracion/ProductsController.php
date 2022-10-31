<?php

namespace App\Http\Controllers\Configuracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function getListarProductos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdProducto = $request->nIdProducto;
        $cNombre = $request->cNombre;
        $cDescripcion = $request->cDescripcion;
        $nIdCategoria = $request->nIdCategoria;

        $nIdProducto = ($nIdProducto == NULL)  ? ($nIdProducto = 0) : $nIdProducto;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cDescripcion = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;
        $nIdCategoria = ($nIdCategoria == NULL)  ? ($nIdCategoria = 0) : $nIdCategoria;

        $res = DB::select(
            'call sp_Producto_getListarProductos (?,?,?,?)',
            [
                $nIdProducto,
                $cNombre,
                $cDescripcion,
                $nIdCategoria,
            ]
        );

        return $res;
    }

    public function setRegistrarProducto(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cNombre = $request->cNombre;
        $cDescripcion = $request->cDescripcion;
        $nStock = $request->nStock;
        $fPrecio = $request->fPrecio;
        $nIdCategoria = $request->nIdCategoria;
        $nIdAuthUser = Auth::id();

        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cDescripcion = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;
        $nStock = ($nStock == NULL) ? ($nStock = 0) : $nStock;
        $fPrecio = ($fPrecio == NULL) ? ($fPrecio = 0) : $fPrecio;
        $nIdCategoria = ($nIdCategoria == NULL)  ? ($nIdCategoria = 0) : $nIdCategoria;

        $res = DB::select(
            'call sp_Producto_setRegistrarProducto (?,?,?,?,?,?)',
            [
                $cNombre,
                $cDescripcion,
                $nStock,
                $fPrecio,
                $nIdCategoria,
                $nIdAuthUser,
            ]
        );

        return $res;
    }
}
