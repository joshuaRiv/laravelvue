import Vue from "vue";
import Router from "vue-router";

import Dashboard from './components/modulos/dashboard/index';
import Cliente from './components/modulos/cliente/index';
import Pedido from './components/modulos/pedido/index';
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

import Categoria from './components/modulos/categoria/index';
import CategoriaCreate from './components/modulos/categoria/create';
import CategoriaEdit from './components/modulos/categoria/edit';

import Producto from './components/modulos/producto/index';
import ProductoCrear from './components/modulos/producto/create';
import ProductoEditar from './components/modulos/producto/edit';

import Undefined from './components/plantilla/404';

Vue.use(Router)

function verificarAcceso(to, from, next) {
  let authUser = JSON.parse(sessionStorage.getItem('authUser'));
  if (authUser) {
    // console.log('El usuario esta autenticado');
    let listRolPermisosByUsuario = JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario'));
    if (listRolPermisosByUsuario.includes(to.name)) {
      next();
    }
    else {
      let listRolPermisosByUsuarioFilter = [];
      listRolPermisosByUsuario.map(function (x) {
        if (x.includes('index')) {
          listRolPermisosByUsuarioFilter.push(x);
        }
      })
      if (to.name == 'dashboard.index') {
        next({ name: listRolPermisosByUsuarioFilter[0] });
      } else {
        next(from.path);
      }
    }
  }
  else {
    next('/login');
  }
}

export const rutas = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    beforeEnter(to, from, next) {
      let authUser = JSON.parse(sessionStorage.getItem('authUser'));
      if (authUser) { 
        next({name: 'dashboard.index'});
      }
      else{
        next();
      }
    }
  },


  {
    path: '/',
    name: 'dashboard.index',
    component: Dashboard,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },


  {
    path: '/categorias',
    name: 'categoria.index',
    component: Categoria,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/categorias',
    name: 'categoria.crear',
    component: CategoriaCreate,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/categorias/editar/:id',
    name: 'categoria.editar',
    component: CategoriaEdit,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    },
    props: true,
  },


  {
    path: '/clientes',
    name: 'cliente.index',
    component: Cliente,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },


  {
    path: '/pedidos',
    name: 'pedido.index',
    component: Pedido,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },


  {
    path: '/reportes',
    name: 'reporte.pedido.index',
    component: Reporte,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },


  {
    path: '/permisos',
    name: 'permiso.index',
    component: Permiso,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/permisos/crear',
    name: 'permiso.crear',
    component: PermisoCreate,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/permisos/editar/:id',
    name: 'permiso.editar',
    component: PermisoEdit,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    },
    props: true
  },


  {
    path: '/roles',
    name: 'rol.index',
    component: Rol,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/roles/crear',
    name: 'rol.crear',
    component: RolCreate,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/roles/editar/:id',
    name: 'rol.editar',
    component: RolEdit,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    },
    props: true
  },


  {
    path: '/usuarios',
    name: 'usuario.index',
    component: Usuario,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/usuarios/crear',
    name: 'usuario.crear',
    component: UsuarioCreate,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/usuarios/editar/:id',
    name: 'usuario.editar',
    component: UsuarioEdit,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    },
    props: true
  },
  {
    path: '/usuarios/ver/:id',
    name: 'usuario.ver',
    component: UsuarioView,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    },
    props: true,
  },
  {
    path: '/usuarios/permiso/:id',
    name: 'usuario.permiso',
    component: UsuarioPermission,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    },
    props: true,
  },
  

  {
    path: '/productos',
    name: 'producto.index',
    component: Producto,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/productos/crear',
    name: 'producto.crear',
    component: ProductoCrear,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    }
  },
  {
    path: '/productos/editar/:id',
    name: 'producto.editar',
    component: ProductoEditar,
    beforeEnter: (to, from, next) => {
      verificarAcceso(to, from, next);
    },
    props: true,
  },


  {
    path: '*',
    component: Undefined,
  },

];

export default new Router({
  routes: rutas,
  mode: 'history',
  linkActiveClass: 'active',
})