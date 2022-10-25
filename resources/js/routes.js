import Vue from "vue";
import Router from "vue-router";

import Dashboard from './components/modulos/dashboard/index';
import Categoria from './components/modulos/categoria/index';
import Cliente from './components/modulos/cliente/index';
import Pedido from './components/modulos/pedido/index';
import Producto from './components/modulos/producto/index';
import Reporte from './components/modulos/reporte/index';

import Usuario from './components/modulos/usuario/index';
import UsuarioCreate from './components/modulos/usuario/create';
import UsuarioEdit from './components/modulos/usuario/edit';
import UsuarioView from './components/modulos/usuario/view';
import UsuarioPermission from './components/modulos/usuario/permission';

import Rol from './components/modulos/rol/index';
import RolCreate from './components/modulos/rol/create';
import RolEdit from './components/modulos/rol/edit';

import Permiso from './components/modulos/permiso/index';
import PermisoCreate from './components/modulos/permiso/create';
import PermisoEdit from './components/modulos/permiso/edit';

import Login from './components/modulos/authenticate/login';

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login,
    },


    {
      path: '/',
      name: 'dashboard.index',
      component: Dashboard
    },

    
    { 
      path: '/categorias',
      name: 'categoria.index',
      component: Categoria
    },


    { 
      path: '/clientes',
      name: 'cliente.index',
      component: Cliente
    },


    { 
      path: '/pedidos',
      name: 'pedido.index',
      component: Pedido
    },
    
    
    { 
      path: '/productos',
      name: 'producto.index',
      component: Producto
    },
    
    
    { 
      path: '/reportes',
      name: 'reporte.index',
      component: Reporte
    },


    { 
      path: '/permisos',
      name: 'permiso.index',
      component: Permiso
    },
    { 
      path: '/permisos/crear', 
      name: 'permiso.crear',
      component: PermisoCreate
    },
    {
      path: '/permisos/editar/:id',
      name: 'permiso.editar',
      component: PermisoEdit,
      props: true
    },


    { 
      path: '/roles', 
      name: 'rol.index',
      component: Rol
    },
    { 
      path: '/roles/crear',
      name: 'rol.crear',
      component: RolCreate
    },
    {
      path: '/roles/editar/:id',
      name: 'rol.editar',
      component: RolEdit,
      props: true
    },


    { 
      path: '/usuarios',
      name: 'usuario.index',
      component: Usuario
    },
    { 
      path: '/usuarios/crear',
      name: 'usuario.crear',
      component: UsuarioCreate
    },
    {
      path: '/usuarios/editar/:id',
      name: 'usuario.editar',
      component: UsuarioEdit,
      props: true
    },
    {
      path: '/usuarios/ver/:id',
      name: 'usuario.ver',
      component: UsuarioView,
      props: true,
    },
    {
      path: '/usuarios/permiso/:id',
      name: 'usuario.permiso',
      component: UsuarioPermission,
      props: true,
    },

  ],
  mode: 'history',
  linkActiveClass: 'active',
})