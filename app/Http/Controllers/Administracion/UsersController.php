<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function getListarUsuarios(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
        $cNombre = $request->cNombre;
        $cUsuario = $request->cUsuario;
        $cCorreo = $request->cCorreo;
        $cEstado = $request->cEstado;

        $nIdUsuario = ($nIdUsuario == NULL)  ? ($nIdUsuario = 0) : $nIdUsuario;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cUsuario = ($cUsuario == NULL) ? ($cUsuario = '') : $cUsuario;
        $cCorreo = ($cCorreo == NULL)  ? ($cCorreo = '') : $cCorreo;
        $cEstado = ($cEstado == NULL)  ? ($cEstado = '') : $cEstado;


        $res = DB::select(
            'call sp_Usuario_getListarUsuarios (?,?,?,?,?)',
            [
                $nIdUsuario,
                $cNombre,
                $cUsuario,
                $cCorreo,
                $cEstado
            ]
        );

        return $res;
    }

    public function setRegistrarUsuario(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $cPrimerNombre = $request->cPrimerNombre;
        $cSegundoNombre = $request->cSegundoNombre;
        $cApellido = $request->cApellido;
        $cUsuario = $request->cUsuario;
        $cCorreo = $request->cCorreo;
        $cContrasena = Hash::make($request->cContrasena);
        $oFotografia = $request->oFotografia;

        $cPrimerNombre  = ($cPrimerNombre == NULL)  ? ($cPrimerNombre = '') : $cPrimerNombre;
        $cSegundoNombre = ($cSegundoNombre == NULL) ? ($cSegundoNombre = '') : $cSegundoNombre;
        $cApellido      = ($cApellido == NULL) ? ($cApellido = '') : $cApellido;
        $cUsuario       = ($cUsuario == NULL) ? ($cUsuario = '') : $cUsuario;
        $cCorreo        = ($cCorreo == NULL) ? ($cCorreo = '') : $cCorreo;
        $cContrasena    = ($cContrasena == NULL)  ? ($cContrasena = '') : $cContrasena;
        $oFotografia    = ($oFotografia == NULL)  ? ($oFotografia = NULL) : $oFotografia;
    
        $res = DB::select('call sp_Usuario_setRegistrarUsuario (?,?,?,?,?,?,?)',
        [
            $cPrimerNombre,
            $cSegundoNombre,
            $cApellido,
            $cUsuario,
            $cCorreo,
            $cContrasena,
            $oFotografia,
        ]);

        return $res[0]->nIdUsuario;
    }

    public function setEditarUsuario(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
        $cPrimerNombre = $request->cPrimerNombre;
        $cPrimerNombre = $request->cPrimerNombre;
        $cSegundoNombre = $request->cSegundoNombre;
        $cApellido = $request->cApellido;
        $cUsuario = $request->cUsuario;
        $cCorreo = $request->cCorreo;
        $cContrasena = $request->cContrasena;
        if ($cContrasena != NULL) {
            $cContrasena = Hash::make($cContrasena);
        }
        $oFotografia = $request->oFotografia;

        $nIdUsuario     = ($nIdUsuario == NULL)  ? ($nIdUsuario = '') : $nIdUsuario;
        $cPrimerNombre = ($cPrimerNombre == NULL)  ? ($cPrimerNombre = '') : $cPrimerNombre;
        $cSegundoNombre = ($cSegundoNombre == NULL) ? ($cSegundoNombre = '') : $cSegundoNombre;
        $cApellido = ($cApellido == NULL) ? ($cApellido = '') : $cApellido;
        $cUsuario = ($cUsuario == NULL) ? ($cUsuario = '') : $cUsuario;
        $cCorreo = ($cCorreo == NULL) ? ($cCorreo = '') : $cCorreo;
        $cContrasena = ($cContrasena == NULL)  ? ($cContrasena = '') : $cContrasena;
        $oFotografia = ($oFotografia == NULL)  ? ($oFotografia = NULL) : $oFotografia;
    
        DB::select('call sp_Usuario_setEditarUsuario (?,?,?,?,?,?,?,?)',
        [
            $nIdUsuario,
            $cPrimerNombre,
            $cSegundoNombre,
            $cApellido,
            $cUsuario,
            $cCorreo,
            $cContrasena,
            $oFotografia,
        ]);
    }

    public function setCambiarEstadoUsuario(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
        $cEstado = $request->cEstado;
        
        $nIdUsuario     = ($nIdUsuario == NULL)  ? ($nIdUsuario = 0) : $nIdUsuario;
        $cEstado     = ($cEstado == NULL)  ? ($cEstado = 0) : $cEstado;

        DB::select('call sp_Usuario_setCambiarEstadoUsuario (?,?)',
        [
            $nIdUsuario,
            $cEstado,
        ]);
    }

    public function setEditarRolByUsuario(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
        $nIdRol = $request->nIdRol;

        $nIdUsuario  = ($nIdUsuario == NULL)  ? ($nIdUsuario = '') : $nIdUsuario;
        $nIdRol = ($nIdRol == NULL) ? ($nIdRol = '') : $nIdRol;
    
        DB::select('call sp_Usuario_setEditarRolByUsuario (?,?)',
        [
            $nIdUsuario,
            $nIdRol,
        ]);
    }

    // 29 - 8:10 | Res retorna html y no el valor
    public function getRolByUsuario(Request $request){
        if (!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;

        $nIdUsuario  = ($nIdUsuario == NULL)  ? ($nIdUsuario = '') : $nIdUsuario;
    
        $res = DB::select('call sp_Usuario_getRolByUsuario (?)',
        [
            $nIdUsuario,
        ]);

        return $res[0]->nIdRol;
    }
}
