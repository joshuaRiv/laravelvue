<?php

namespace App\Http\Controllers\Configuracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function getListarCategorias (Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdCategoria = $request->nIdCategoria;
        $cNombre = $request->cNombre;
        $cDescripcion = $request->cDescripcion;

        $nIdCategoria = ($nIdCategoria == NULL)  ? ($nIdCategoria = 0) : $nIdCategoria;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cDescripcion = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;

        $res = DB::select(
            'call sp_Categoria_getListarCategorias (?,?,?)',
            [
                $nIdCategoria,
                $cNombre,
                $cDescripcion,
            ]
        );

        return $res;
    }

    public function setRegistrarCategoria(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cNombre = $request->cNombre;
        $cDescripcion = $request->cDescripcion;
        $nIdAuthUser = Auth::id();

        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cDescripcion = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;

        $res = DB::select(
            'call sp_Categoria_setRegistrarCategoria (?,?,?)',
            [
                $cNombre,
                $cDescripcion,
                $nIdAuthUser
            ]
        );

        return $res;
    }

    public function setEditarCategoria(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdCategoria = $request->nIdCategoria;
        $cNombre = $request->cNombre;
        $cDescripcion = $request->cDescripcion;
        $nIdAuthUser = Auth::id();

        echo $nIdAuthUser;

        $nIdCategoria = ($nIdCategoria == NULL)  ? ($nIdCategoria = 0) : $nIdCategoria;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cDescripcion = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;

        $res = DB::select(
            'call sp_Categoria_setEditarCategoria (?,?,?,?)',
            [
                $nIdCategoria,
                $cNombre,
                $cDescripcion,
                $nIdAuthUser
            ]
        );

        return $res;
    }
}
