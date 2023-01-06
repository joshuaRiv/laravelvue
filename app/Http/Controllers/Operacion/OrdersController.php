<?php

namespace App\Http\Controllers\Operacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

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

        $cComentario    =   ($cComentario   ==  NULL) ? ($cComentario   =   '') :   $cComentario;
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
            return $nIdPedido;
        } catch (Exception $e) {
            // capturara algun error ocurrido en el "try"
            return $e;
            DB::rollBack();
        }
    }

    public function setGenerarDocumento(request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $nIdPedido = $request->nIdPedido;

        $logo = public_path('img/AdminLTELogo.png');

        $rptaPedido        =   DB::select(
            'call sp_Pedido_getPedido (?)',
            [
                $nIdPedido,
            ]
        );
        
        $rptaDetalle       =   DB::select(
            'call sp_Pedido_getDetallePedido (?)',
            [
                $nIdPedido,
            ]
        );

        $pdf = PDF::loadView('reportes.pedido.pdf.ver',[
            'pedido' => $rptaPedido,
            'detallesPedido' => $rptaDetalle,
            'logo' => $logo,
        ]);
        return $pdf->download('invoice.pdf');
    }
}
