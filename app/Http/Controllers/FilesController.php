<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FilesController extends Controller
{
    public function setRegistrarArchivo(Request $request)
    {
        $file = $request->file; 
        $bandera = str_random(10);
        $filename = $file->getClientOriginalName();
        $fileserver = $bandera . '_' . $filename;
        $nIdAuthUser = Auth::id();

        Storage::putFileAs('public/users', $file, $fileserver);

        $res = DB::select(
            'call sp_Archvio_setRegistrarArchivo (?,?, ?)',
            [
                asset('storage/users/'.$fileserver),
                $filename,
                $nIdAuthUser,
            ]
        );

        return $res;
    }

}
