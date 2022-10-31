<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::post('/authenticate/login', 'Auth\LoginController@login');

Route::group(['middleware' => ['auth']], function () {
    Route::post('/authenticate/logout', 'Auth\LoginController@logout');
    Route::get('/authenticate/getRefrescarUsuarioAutenticado', function(){
        return Auth::user()->load('file');
    });

    Route::post('/archivo/setRegistrarArchivo', 'FilesController@setRegistrarArchivo');

    Route::get('/administracion/usuario/getListarRolPermisosByUsuario', 'Administracion\UsersController@getListarRolPermisosByUsuario');
    Route::get('/administracion/usuario/getListarUsuarios', 'Administracion\UsersController@getListarUsuarios');
    Route::post('/administracion/usuario/setRegistrarUsuario', 'Administracion\UsersController@setRegistrarUsuario');
    Route::post('/administracion/usuario/setEditarUsuario', 'Administracion\UsersController@setEditarUsuario');
    Route::post('/administracion/usuario/setCambiarEstadoUsuario', 'Administracion\UsersController@setCambiarEstadoUsuario');
    Route::post('/administracion/usuario/setEditarRolByUsuario', 'Administracion\UsersController@setEditarRolByUsuario');
    Route::get('/administracion/usuario/getRolByUsuario', 'Administracion\UsersController@getRolByUsuario');
    Route::get('/administracion/usuario/getListarPermisosByRolAsignado', 'Administracion\UsersController@getListarPermisosByRolAsignado');
    Route::get('/administracion/usuario/getListarPermisosByUsuario', 'Administracion\UsersController@getListarPermisosByUsuario');
    Route::post('/administracion/usuario/setRegistrarPermisosByUsuario', 'Administracion\UsersController@setRegistrarPermisosByUsuario');

    Route::get('/administracion/rol/getListarRoles', 'Administracion\RolesController@getListarRoles');
    Route::get('/administracion/rol/getListarPermisosByRol', 'Administracion\RolesController@getListarPermisosByRol');
    Route::post('/administracion/rol/setRegistrarRolPermisos', 'Administracion\RolesController@setRegistrarRolPermisos');
    Route::post('/administracion/rol/setEditarRolPermisos', 'Administracion\RolesController@setEditarRolPermisos');

    Route::get('/administracion/permiso/getListarPermisos', 'Administracion\PermissionController@getListarPermisos');
    Route::post('/administracion/permiso/setRegistrarPermisos', 'Administracion\PermissionController@setRegistrarPermisos');
    Route::post('/administracion/permiso/setEditarPermisos', 'Administracion\PermissionController@setEditarPermisos');

    Route::get('/configuracion/categoria/getListarCategorias', 'Configuracion\CategoriaController@getListarCategorias');
    Route::post('/configuracion/categoria/setRegistrarCategoria', 'Configuracion\CategoriaController@setRegistrarCategoria');
    Route::post('/configuracion/categoria/setEditarCategoria', 'Configuracion\CategoriaController@setEditarCategoria');
    
    Route::get('/configuracion/producto/getListarProductos', 'Configuracion\ProductsController@getListarProductos');
    Route::post('/configuracion/categoria/setRegistrarProducto', 'Configuracion\ProductsController@setRegistrarProducto');
    Route::post('/configuracion/categoria/setEditarProducto', 'Configuracion\ProductsController@setEditarProducto');
});



Route::get('/{optional?}', function () {
    return view('app');
})->name('basepath')->where('optional', '.*');