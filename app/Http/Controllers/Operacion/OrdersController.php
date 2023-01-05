<?php

namespace App\Http\Controllers\Operacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
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

    public function setRegistrarPedido(request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdCliente     =   $request->nIdCliente;
        $cComentario    =   $request->cComentario;
        $fTotalPedido   =   $request->fSubtotal;
        $nIdAuthUser    =   Auth::id();

        $cComentario    =   ($cComentario   ==  NULL) ? ($cComentario   =   '-') :   $cComentario;
        $fTotalPedido    =   ($fTotalPedido   ==  NULL) ? ($fTotalPedido   =   0) :   $fTotalPedido;

        try {
            DB::beginTransaction();

            $rpta       =   DB::select(
                'call sp_Pedido_setRegistrarPedido (?, ?, ?, ?)',
                [
                    $nIdCliente,
                    $cComentario,
                    $fTotalPedido,
                    $nIdAuthUser
                ]
            );

            $nIdPedido =   $rpta[0]->nIdPedido;

            $listPedido       =   $request->listPedido;
            $listPedidoSize   =   sizeof($listPedido);
            if ($listPedidoSize > 0) {
                foreach ($listPedido as $key => $value) {
                    DB::select(
                        'call sp_Pedido_setRegistrarDetallePedido (?, ?, ?, ?)',
                        [
                            $nIdPedido,
                            $value['nIdProducto'],
                            $value['nStock'],
                            $value['fSubTotal']
                        ]
                    );
                }
            }
            DB::commit();
            return $rpta;
        } catch (Exception $e) {
            // capturara algun error ocurrido en el "try"
            return $e;
            DB::rollBack();
        }
    }
}
