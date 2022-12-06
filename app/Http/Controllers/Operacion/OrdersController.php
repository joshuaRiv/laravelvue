<?php

namespace App\Http\Controllers\Operacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function getListarPedidos(request $req)
    {
        if (!$req->ajax()) return redirect('/');

        $cNombre = $req->cNombre;
        $cDocumento = $req->cDocumento;
        $cPedido = $req->cPedido;
        $nIdEstado = $req->nIdEstado;

        $cNombre = ($cNombre == NULL)  ? ($cNombre = '') : $cNombre;
        $cDocumento = ($cDocumento == NULL)  ? ($cDocumento = '') : $cDocumento;
        $cPedido = ($cPedido == NULL) ? ($cPedido = '') : $cPedido;
        $nIdEstado = ($nIdEstado == NULL)  ? ($nIdEstado = 0) : $nIdEstado;

        $res = DB::select(
            'call sp_Pedido_getListarPedidos (?,?,?,?)',
            [
                $cNombre,
                $cDocumento,
                $cPedido,
                $nIdEstado,
            ]
        );

        return $res;
    }
}
