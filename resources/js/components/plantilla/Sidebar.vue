<template>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img :src="ruta + `/img/AdminLTELogo.png`" alt="Sistema Laravel y Vue" class="brand-image img-circle elevation-3"
        style="opacity: .8">
      <span class="brand-text font-weight-light">Sistema Laravel y Vue</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <template v-if="!usuario.file_id">
            <img :src="ruta + `/img/photo1.png`" class="img-circle elevation-2" :alt="usuario.fullname">
          </template>
          <template v-else>
            <img :src="usuario.file.path" class="img-circle elevation-2" :alt="usuario.fullname"
              style="height:34px !important;">
          </template>
        </div>
        <div class="info">
          <router-link class="d-block" :to="{ name: 'usuario.ver', params: { id: usuario.id } }">
            {{ usuario.fullname }}
          </router-link>
        </div>
      </div>

      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <button href="#" class="btn btn-flat text-white w-full" @click.prevent="logout"
            v-loading.fullscreen.lock="fullscreenLoading">
            <i class="fas  fa-sign-out-alt" /> Cerrar Sesión
          </button>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <template v-if="listPermisos.includes('dashboard.index')">
              <router-link class="nav-link" :to="`/`">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </router-link>
            </template>
          </li>
          <template v-if="listPermisos.includes('pedido.index', 'cliente.index')">
            <li class="nav-header">OPERACIONES</li>
            <li class="nav-item">
              <template v-if="listPermisos.includes('pedido.index')">
                <router-link class="nav-link" :to="`/pedidos`">
                  <i class="nav-icon fas fa-cash-register"></i>
                  <p>
                    Pedidos
                  </p>
                </router-link>
              </template>
            </li>
            <li class="nav-item">
              <template v-if="listPermisos.includes('cliente.index')">
                <router-link class="nav-link" :to="`/clientes`">
                  <i class="nav-icon fas fa-user-friends"></i>
                  <p>
                    Clientes
                  </p>
                </router-link>
              </template>
            </li>
          </template>
          <template v-if="listPermisos.includes('categoria.index', 'producto.index')">
            <li class="nav-header">CONFIGURACIÓN</li>
            <li class="nav-item">
              <template v-if="listPermisos.includes('categoria.index')">
                <router-link class="nav-link" :to="`/categorias`">
                  <i class="nav-icon fas fa-sitemap"></i>
                  <p>Categorías</p>
                </router-link>
              </template>
            </li>
            <li class="nav-item">
              <template v-if="listPermisos.includes('producto.index')">
                <router-link class="nav-link" :to="`/productos`">
                  <i class="nav-icon fas fa-apple-alt"></i>
                  <p>Productos</p>
                </router-link>
              </template>
            </li>
          </template>
          <template v-if="listPermisos.includes('usuario.index', 'rol.index', 'permiso.index')">
            <li class="nav-header">ADMINISTRACIÓN</li>
            <li class="nav-item">
              <template v-if="listPermisos.includes('usuario.index')">
                <router-link class="nav-link" :to="`/usuarios`">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Usuarios</p>
                </router-link>
              </template>
            </li>
            <li class="nav-item has-treeview">
              <template v-if="listPermisos.includes('rol.index')">
                <router-link class="nav-link" :to="`/roles`">
                  <i class="nav-icon fas fa-unlock-alt"></i>
                  <p>
                    Roles
                  </p>
                </router-link>
              </template>
            </li>
            <li class="nav-item has-treeview">
              <template v-if="listPermisos.includes('permiso.index')">
                <router-link class="nav-link" :to="`/permisos`">
                  <i class="nav-icon fas fa-key"></i>
                  <p>
                    Permisos
                  </p>
                </router-link>
              </template>
            </li>
          </template>

          <template v-if="listPermisos.includes('reporte.pedido.index')">
            <li class="nav-header">REPORTES</li>
            <li class="nav-item">
              <template v-if="listPermisos.includes('reporte.pedido.index')">
                <router-link class="nav-link" :to="`/reportes`">
                  <i class="fas fa-file-export nav-icon"></i>
                  <p>Pedidos</p>
                </router-link>
              </template>
            </li>
          </template>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
import Axios from 'axios';

export default {
  props: ['ruta', 'usuario', 'listPermisos'],
  data() {
    return {
      fullscreenLoading: false,
    }
  },
  methods: {
    logout() {
      this.fullscreenLoading = true;
      const url = '/authenticate/logout';
      Axios.post(url).then(res => {
        if (res.data.code == 204) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      }).catch(error => {
        console.log(error.response);
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    }
  }
}
</script>