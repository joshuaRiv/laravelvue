<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;

class RolesController extends Controller
{
    public function getListarRoles(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdRol = $request->nIdRol;
        $cNombre = $request->cNombre;
        $cSlug = $request->cSlug;

        $nIdRol = ($nIdRol == NULL)  ? ($nIdRol = 0) : $nIdRol;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cSlug = ($cSlug == NULL)  ? ($cSlug = '') : $cSlug;

        $res = DB::select(
            'call sp_Rol_getListarRoles (?,?,?)',
            [
                $nIdRol,
                $cNombre,
                $cSlug,
            ]
        );

        return $res;
    }
    
    public function getListarPermisosByRol(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdRol = $request->nIdRol;

        $res = DB::select('call sp_Rol_getListarPermisosByRol  (?)', 
        [
            $nIdRol,
        ]);

        return $res;
    }
    
    public function setRegistrarRolPermisos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cNombre = $request->cNombre;
        $cSlug = $request->cSlug;

        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cSlug = ($cSlug == NULL)  ? ($cSlug = '') : $cSlug;
        
        try {
            $res = DB::select(
                'call sp_Rol_setRegistrarRol (?,?)',
                [
                    $cNombre,
                    $cSlug,
                ]
            );
            
            $nIdRol = $res[0]->nIdRol;

            DB::beginTransaction();
            $listPermisos = $request->listPermisosFilter;
            $listPermisosSize = sizeof($listPermisos);
            if ($listPermisosSize > 0) {
                foreach ($listPermisos as $key => $value) {
                    if ($value['checked'] == true) {
                        DB::select(
                            'call sp_Rol_setRegistrarRolPermiso (?,?)',
                            [
                                $nIdRol,
                                $value['id'],
                            ]
                        );
                    }
                }
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function setEditarRolPermisos(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdRol = $request->nIdRol;
        $cNombre = $request->cNombre;
        $cSlug = $request->cSlug;

        $nIdRol = ($nIdRol == NULL)  ? ($nIdRol = 0) : $nIdRol;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cSlug = ($cSlug == NULL)  ? ($cSlug = '') : $cSlug;
        
        try {
            $res = DB::select(
                'call sp_Rol_setEditarRol (?,?,?)',
                [
                    $nIdRol,
                    $cNombre,
                    $cSlug,
                ]
            );
            
            DB::beginTransaction();
            $listPermisos = $request->listPermisosFilter;
            $listPermisosSize = sizeof($listPermisos);
            if ($listPermisosSize > 0) {
                foreach ($listPermisos as $key => $value) {
                    if ($value['checked'] == true) {
                        DB::select(
                            'call sp_Rol_setRegistrarRolPermiso (?,?)',
                            [
                                $nIdRol,
                                $value['id'],
                            ]
                        );
                    }
                }
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
