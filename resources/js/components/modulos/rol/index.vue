<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Rol</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="`/roles/crear`">
              <i class="fas fa-plus-square"></i> Nuevo Rol
            </router-link>
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
                          <input v-model="fillBsqRol.cNombre" @keyup.enter="getListarRoles" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Url Amigable</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqRol.cSlug" @keyup.enter="getListarRoles" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row justify-content-center">
                  <button class="btn btnWidth btn-info btn-flat" @click="getListarRoles"
                    v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                  <button class="btn btnWidth btn-flat btn-default" @click="limpiarCriteriosBsq">Limpiar</button>
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
                <template v-if="listarRolesPaginated.length">
                  <table class="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Url Amigable</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(rol, index) in listarRolesPaginated" :key="index">

                        <td> {{ rol.name }} </td>
                        <td> {{ rol.slug }} </td>
                        <td>
                          <button class="btn btn-flat btn-primary btn-sm"
                            @click.prevent="abrirModalByOption('rol', 'ver', rol)">
                            <i class="fas fa-folder"></i> Ver
                          </button>
                          <router-link class="btn btn-flat btn-info btn-sm"
                            :to="{ name: 'rol.editar', params: { id: rol.id } }">
                            <i class="fas fa-pencil-alt"></i> Editar
                          </router-link>
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

    <!-- toast & modal -->
    <div>
      <div v-if="modalOption == 1">
        <div class="toast-container top-0 end-0 p-2" v-for="error in mensajeError" :key="error">
          <div class="toast fade bg-primary top-0 end-0" :class="{ show: modalShow }"
            :style="modalShow ? mostrarModal : ocultarModal">
            <div class="toast-header">
              <div class="rounded mr-2 bg-light" style="height: 20px; width: 20px"></div>
              <img src="" class="rounded mr-1" alt="" />
              <strong class="mr-auto">Sistema Laravel y Vue</strong>
              <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" @click="abrirModal">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="toast-body p-3">
              {{ error }}
            </div>
          </div>
        </div>
      </div>
      <div v-if="modalOption == 2">
        <div class="modal fade" title="Shipping address" :class="{ show: modalShow }"
          :style="modalShow ? mostrarModal : ocultarModal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                Sistema Laravel y Vue
              </div>
              <div class="modal-body">
                <div class="container-fluid">
                  <div class="card card-info">
                    <div class="card-header">
                      <h3 class="card-title">
                        Información del rol
                      </h3>
                    </div>
                    <div class="card-body">
                      <form role="form">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group-row">
                              <label class="col-md-12 col-form-label">Nombre</label>
                              <div class="col-md-12">
                                <span class="form-control" v-text="fillVerRol.cNombre"></span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group-row">
                              <label class="col-md-12 col-form-label">Url Amigable</label>
                              <div class="col-md-12">
                                <span class="form-control" v-text="fillVerRol.cUrl"></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="card card-info">
                    <div class="card-header">
                      <h3 class="card-title">
                        Listado de permisos
                      </h3>
                    </div>
                    <div class="card-body table-responsive">
                      <template v-if="listPermisos.length">
                        <div class="scrollTable">
                          <table class="table table-hover table-head-fixed text-nowrap projects">
                            <thead>
                              <tr>
                                <th>Nombre</th>
                                <th>Url Amigable</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="(rol, index) in listPermisos" :key="index">
                                <td> {{ rol.name }} </td>
                                <td> {{ rol.slug }} </td>
                              </tr>
                            </tbody>
                          </table>
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
              <div class="modal-footer">
                <button class="btn btn-secondary" @click="abrirModal">Cerrar</button>
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
      fillBsqRol: {
        cNombre: '',
        cUrl: '',
      },
      fillVerRol: {
        cNombre: '',
        cUrl: '',
      },
      listRoles: [],
      listPermisos: [],
      pageNumber: 0,
      perPage: 5,
      fullscreenLoading: false,
      // toast
      modalShow: false,
      modalOption: 0,
      mostrarModal: {
        display: 'block',
        background: '#0000006b',
      },
      ocultarModal: {
        display: 'none',
      },
      error: 0,
      mensajeError: [],
      // -----
    }
  },
  computed: {
    //obtener el numero de paginas
    pageCount() {
      let a = this.listRoles.length,
        b = this.perPage;
      return Math.ceil(a / b);
    },
    listarRolesPaginated() {
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listRoles.slice(inicio, fin);
    },
    pagesList() {
      let a = this.listRoles.length,
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
    abrirModal() {
      this.modalShow = !this.modalShow;
      this.limpiarModal();
    },
    limpiarModal() {
      this.fillVerRol.cNombre = '';
      this.fillVerRol.cUrl = '';
      this.listPermisos = [];
      this.modalOption = 0;
    },
    limpiarCriteriosBsq() {
      this.fillBsqRol.cNombre = '';
      this.fillBsqRol.cSlug = '';
    },
    limpiarBandejaUsuarios() {
      this.listRoles = [];
    },
    getListarRoles() {
      this.fullscreenLoading = true;

      const url = '/administracion/rol/getListarRoles';
      axios.get(url, {
        params: {
          cNombre: this.fillBsqRol.cNombre,
          cSlug: this.fillBsqRol.cSlug,
        }
      }).then(res => {
        this.inicializarPaginacion();
        this.listRoles = res.data;
        this.fullscreenLoading = false;
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
    getListarPermisosByRol(id) {
      const ruta = '/administracion/rol/getListarPermisosByRol';

      axios.get(ruta, {
        params: {
          'nIdRol': id,
        }
      }).then(
        res => {
          this.listPermisos = res.data;
          this.modalShow = true;
          this.modalOption = 2;
        }
      );
    },
    abrirModalByOption(modulo, accion, data) {
      switch (modulo) {
        case 'rol':
          switch (accion) {
            case 'ver':
              // Setear info del arreglo
              this.fillVerRol.cNombre = data.name;
              this.fillVerRol.cUrl = data.slug;
              // Obtener los permisos del rol seleccionado
              this.getListarPermisosByRol(data.id);
              break;

            default:
              break;
          }
          break;

        default:
          break;
      }
    },
  },
  mounted() {
    this.getListarRoles();
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