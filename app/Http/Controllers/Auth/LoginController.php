<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{   
    public function login(Request $request)
    {
        $cEmail = $request->cEmail;
        $cContrasena = $request->cContrasena;

        $res = Auth::attempt(['email' => $cEmail, 'password' => $cContrasena, 'state' => 'A']);

        return $res ? 
            response()->json([
                'authUser' => Auth::user(),
                'code' => 200
            ]) 
            : 
            response()->json([
                'code' => 401 
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'code' => 204
        ]);
    }
}
