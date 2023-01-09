<?php

namespace App\Http\Controllers\Operacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function getListarClientes(request $req)
    {
        if (!$req->ajax()) return redirect('/');

        $cDocumento = $req->cDocumento;
        $cNombre = $req->cNombre;

        $cDocumento = ($cDocumento == NULL)  ? ($cDocumento = '') : $cDocumento;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;

        $res = DB::select('call sp_Cliente_getListarClientes (?, ?)', [
            $cNombre,
            $cDocumento,
        ]);

        return $res;
    }

    public function setRegistrarCliente(request $req)
    {
        if (!$req->ajax()) return redirect('/');

        $cDocumento = $req->cDocumento;
        $cNombre = $req->cNombre;
        $cApellido = $req->cApellido;
        $cEmail = $req->cEmail;
        $cTelefono = $req->cTelefono;
        $nIdAuthUser = Auth::id();

        $cDocumento = ($cDocumento == NULL)  ? ($cDocumento = '') : $cDocumento;
        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cApellido = ($cApellido == NULL) ? ($cApellido = '') : $cApellido;
        $cEmail = ($cEmail == NULL)  ? ($cEmail = '') : $cEmail;
        $cTelefono = ($cTelefono == NULL)  ? ($cTelefono = 0) : $cTelefono;

        $res = DB::select(
            'call sp_Cliente_setRegistrarCliente (?,?,?,?,?,?)',
            [
                $cDocumento,
                $cNombre,
                $cApellido,
                $cEmail,
                $cTelefono,
                $nIdAuthUser,
            ]
        );

        return $res;
    }
}
