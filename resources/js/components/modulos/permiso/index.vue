<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Permiso</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <template v-if="listRolPermisosByUsuario.includes('permiso.crear')">
              <router-link class="btn btn-info btn-sm" :to="{name: 'permiso.crear'}">
                <i class="fas fa-plus-square"></i> Nuevo Permiso
              </router-link>
            </template>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Criterios de búsqueda
                </h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqPermiso.cNombre" @keyup.enter="getListarPermisos" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Url Amigable</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqPermiso.cSlug" @keyup.enter="getListarPermisos" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row justify-content-center">
                  <button class="btn w-25 btn-info btn-flat" @click="getListarPermisos"
                    v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                  <button class="btn w-25 btn-flat btn-default" @click="limpiarCriteriosBsq">Limpiar</button>
                </div>
              </div>
            </div>
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Bandeja de resultados
                </h3>
              </div>
              <div class="card-body table-responsive">
                <template v-if="listarPermisosPaginated.length">
                  <table class="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Url Amigable</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(rol, index) in listarPermisosPaginated" :key="index">

                        <td> {{ rol.name }} </td>
                        <td> {{ rol.slug }} </td>
                        <td>
                          <template v-if="listRolPermisosByUsuario.includes('permiso.editar')">
                            <router-link class="btn btn-flat btn-info btn-sm"
                              :to="{ name: 'permiso.editar', params: { id: rol.id } }">
                              <i class="fas fa-pencil-alt"></i> Editar
                            </router-link>
                          </template>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                      <li class="page-item" v-if="pageNumber > 0">
                        <a href="#" class="page-link" @click.prevent="prevPage">Ant</a>
                      </li>
                      <li class="page-item" v-for="(pagina, index) in pagesList" :key="index"
                        :class="[pagina == pageNumber ? 'active' : '']">
                        <a href="#" class="page-link" @click.prevent="selectPage(pagina)">{{ pagina + 1 }}</a>
                      </li>
                      <li class="page-item" v-if="pageNumber < pageCount - 1">
                        <a href="#" class="page-link" @click.prevent="nextPage">Post</a>
                      </li>
                    </ul>
                  </div>
                </template>
                <template v-else>
                  <div class="callout callout-info">
                    <h5>No se encontraron resultados</h5>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      fillBsqPermiso: {
        cNombre: '',
        cSlug: '',
      },
      listPermisos: [],
      listRolPermisosByUsuario: JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario')),
      pageNumber: 0,
      perPage: 5,
      fullscreenLoading: false,
    }
  },
  computed: {
    //obtener el numero de paginas
    pageCount() {
      let a = this.listPermisos.length,
        b = this.perPage;
      return Math.ceil(a / b);
    },
    listarPermisosPaginated() {
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listPermisos.slice(inicio, fin);
    },
    pagesList() {
      let a = this.listPermisos.length,
        b = this.perPage;
      let pageTotal = Math.ceil(a / b);

      let count = 0, pagesArray = [];

      while (count < pageTotal) {
        pagesArray.push(count);
        count++;
      }

      return pagesArray;
    },
  },
  methods: {
    limpiarModal() {
      this.fillVerRol.cNombre = '';
      this.fillVerRol.cSlug = '';
      this.listPermisos = [];
      this.modalOption = 0;
    },
    limpiarCriteriosBsq() {
      this.fillBsqPermiso.cNombre = '';
      this.fillBsqPermiso.cSlug = '';
    },
    limpiarBandejaUsuarios() {
      this.listPermisos = [];
    },
    getListarPermisos() {
      this.fullscreenLoading = true;

      const url = '/administracion/permiso/getListarPermisos';
      axios.get(url, {
        params: {
          cNombre: this.fillBsqPermiso.cNombre,
          cSlug: this.fillBsqPermiso.cSlug,
        }
      }).then(res => {
        this.inicializarPaginacion();
        this.listPermisos = res.data;
        this.fullscreenLoading = false;
      }).catch(error=>{
        console.log(error.response);
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    nextPage() {
      this.pageNumber++;
    },
    prevPage() {
      this.pageNumber--;
    },
    selectPage(pagina) {
      this.pageNumber = pagina;
    },
    inicializarPaginacion() {
      this.pageNumber = 0;
    },
  },
  mounted() {
    this.getListarPermisos();
  }
}
</script>

<style>
.position-reverse {
  flex-direction: row-reverse !important;
}

.img-max-height {
  max-height: 100px !important;
}
</style>