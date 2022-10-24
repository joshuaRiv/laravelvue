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

import Rol from './components/modulos/rol/index';
import RolCreate from './components/modulos/rol/create';
import RolEdit from './components/modulos/rol/edit';

import Permiso from './components/modulos/permiso/index';
import PermisoCreate from './components/modulos/permiso/create';

Vue.use(Router)

export default new Router({
  routes:[
    {path: '/', component: Dashboard},
    {path: '/categorias', component: Categoria},
    {path: '/clientes', component: Cliente},
    {path: '/pedidos', component: Pedido},
    {path: '/productos', component: Producto},
    {path: '/reportes', component: Reporte},
    
    {path: '/permisos', component: Permiso},
    {path: '/permisos/crear', component: PermisoCreate},

    {path: '/roles', component: Rol},
    {path: '/roles/crear', component: RolCreate},
    {
      path: '/roles/editar/:id',
      name:'rol.editar', 
      component: RolEdit,
      props: true
    },

    {path: '/usuarios', component: Usuario},
    {path: '/usuarios/crear', component: UsuarioCreate},
    {
      path: '/usuarios/editar/:id',
      name:'usuario.editar', 
      component: UsuarioEdit,
      props: true
    },
    {
      path: '/usuarios/ver/:id',
      name:'usuario.ver',
      component: UsuarioView,
      props: true,
    },

  ],
  mode: 'history',
  linkActiveClass: 'active',
})