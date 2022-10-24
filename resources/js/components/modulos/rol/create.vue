<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Crear Rol</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="`/roles`">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-5">
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">
                      Formulario Registrar Rol
                    </h3>
                  </div>
                  <div class="card-body">
                    <form role="form">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group-row">
                            <label class="col-md-3 col-form-label" placeholder="Ej: Administrador">Nombre</label>
                            <div class="col-md-9">
                              <input v-model="fillCrearRol.cNombre" @keyup.enter="setRegistrarRolPermisos" type="text"
                                class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group-row">
                            <label class="col-md-3 col-form-label" placeholder="Ej: rol.ejemplo">Url Amigable</label>
                            <div class="col-md-9">
                              <input v-model="fillCrearRol.cSlug" @keyup.enter="setRegistrarRolPermisos" type="text"
                                class="form-control">
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="card-footer">
                    <div class="row justify-content-center">
                      <button class="btn btnWidth btn-info btn-flat" @click="setRegistrarRolPermisos"
                        v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
                      <button class="btn btnWidth btn-flat btn-default" @click="limpiarCriteriosBsq">Limpiar</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-7">
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">
                      Listar Permisos
                    </h3>
                  </div>
                  <div class="card-body table-responsive">
                    <template v-if="listPermisosFilter.length">
                      <div class="scrollTable">
                        <table class="table table-hover table-head-fixed text-nowrap projects">
                          <thead>
                            <tr>
                              <th>Acción</th>
                              <th>Nombre</th>
                              <th>Url Amigable</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(rol, index) in listPermisosFilter" :key="index"
                              @click.prevent="marcarFila(index)">
                              <td>
                                <el-checkbox v-model="rol.checked"></el-checkbox>
                              </td>
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
          </div>
        </div>
      </div>
    </div>
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
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      fillCrearRol: {
        cNombre: '',
        cSlug: '',
      },
      listPermisos: [],
      listPermisosFilter: [],
      fullscreenLoading: false,
      modalShow: false,
      mostrarModal: {
        display: 'block',
        background: '#0000006b',
      },
      ocultarModal: {
        display: 'none',
      },
      error: 0,
      mensajeError: [],
    }
  },
  computed: {
  },
  mounted() {
    this.getListarPermisosByRol();
  },
  methods: {
    limpiarCriteriosBsq() {
      this.fillCrearRol.cNombre = '';
      this.fillCrearRol.cSlug = '';
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    getListarPermisosByRol() {
      const ruta = '/administracion/rol/getListarPermisosByRol';

      axios.get(ruta).then(
        res => {
          this.listPermisos = res.data;
          this.filterPermisosByRol();
        }
      );
    },
    filterPermisosByRol() {
      let me = this;
      me.listPermisos.map(function (x) {
        me.listPermisosFilter.push(
          {
            'id': x.id,
            'name': x.name,
            'slug': x.slug,
            'checked': false,
          }
        );
      });
    },
    marcarFila(index) {
      this.listPermisosFilter[index].checked = !this.listPermisosFilter[index].checked;
    },
    setRegistrarRolPermisos() {
      if (this.validarRegistrarRolPermisos()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;

      const url = '/administracion/rol/setRegistrarRolPermisos';
      axios.post(url, {
        'cNombre': this.fillCrearRol.cNombre,
        'cSlug': this.fillCrearRol.cSlug,
        'listPermisosFilter': this.listPermisosFilter,
      }).then(res => {
        this.fullscreenLoading = false;
        this.$router.push('/roles');
      });
    },
    validarRegistrarRolPermisos() {
      this.error = 0;
      this.mensajeError = [];

      if (!this.fillCrearRol.cNombre) {
        this.mensajeError.push("El Nombre es un campo obligatorio");
      }
      if (!this.fillCrearRol.cSlug) {
        this.mensajeError.push("La Url Amigable es un campo obligatorio");
      }

      let contador = 0;
      this.listPermisosFilter.map(function (x) {
        if (x.checked == true) {
          contador++;
        }
      });

      if (contador == 0) {
        this.mensajeError.push("Debe seleccionar al menos un permiso");
      }

      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    }
  }
}
</script>