<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function getListarPermisos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdPermiso = $request->nIdPermiso;
        $cNombre = $request->cNombre;
        $cSlug = $request->cSlug;

        $nIdPermiso = ($nIdPermiso == NULL)  ? ($nIdPermiso = 0) : $nIdPermiso;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cSlug = ($cSlug == NULL)  ? ($cSlug = '') : $cSlug;

        $res = DB::select(
            'call sp_Permiso_getListarPermisos (?,?,?)',
            [
                $nIdPermiso,
                $cNombre,
                $cSlug,
            ]
        );

        return $res;
    }
    
    public function setRegistrarPermisos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cNombre = $request->cNombre;
        $cSlug = $request->cSlug;

        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cSlug = ($cSlug == NULL)  ? ($cSlug = '') : $cSlug;

        DB::select(
            'call sp_Permiso_setRegistrarPermisos (?,?)',
            [
                $cNombre,
                $cSlug,
            ]
        );
    }

    public function setEditarPermisos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdPermiso = $request->nIdPermiso;
        $cNombre = $request->cNombre;
        $cSlug = $request->cSlug;

        $nIdPermiso = ($nIdPermiso == NULL)  ? ($nIdPermiso = 0) : $nIdPermiso;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cSlug = ($cSlug == NULL)  ? ($cSlug = '') : $cSlug;
        
        DB::select('call sp_Rol_setEditarPermisos (?,?,?)',
            [
                $nIdPermiso,
                $cNombre,
                $cSlug,
            ]
        );
            
    
    }
}
