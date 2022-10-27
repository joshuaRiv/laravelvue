<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Asignar Permiso</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="`/usuarios`">
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
                      Listar Permisos del Rol {{ fillPermiso.cNombreRol }}
                    </h3>
                  </div>
                  <div class="card-body table-responsive">
                    <template v-if="listPermisosByRolAsignado.length">
                      <div class="scrollTable">
                        <table class="table table-hover table-head-fixed text-nowrap projects">
                          <thead>
                            <tr>
                              <th>Nombre</th>
                              <th>Url Amigable</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(rol, index) in listPermisosByRolAsignado" :key="index">
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
                  <div class="card-footer">
                    <button class="btn btnWidth btn-info btn-flat" @click.prevent="setRegistrarPermisosByUsuario"
                      v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
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
      fillPermiso: {
        nIdUsuario: this.$attrs.id,
        cNombreRol: this.$attrs.id,
      },
      listPermisosByRolAsignado: [],
      listPermisos: [],
      listPermisosFilter: [],
      listRolPermisosByUsuario: [],
      listRolPermisosByUsuarioFilter: [],
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
    this.getListarPermisosByRolAsignado();
    this.getRolByUsuario();
    this.getListarPermisosByUsuario();
  },
  methods: {
    limpiarCriteriosBsq() {
      this.fillPermiso.cNombre = '';
      this.fillPermiso.cSlug = '';
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    getListarPermisosByRolAsignado() {
      const ruta = '/administracion/usuario/getListarPermisosByRolAsignado';

      axios.get(ruta, {
        params: {
          'nIdUsuario': this.fillPermiso.nIdUsuario,
        }
      }).then(
        res => {
          this.listPermisosByRolAsignado = res.data;
        }
      ).catch(error=>{
        console.log(error.response);
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    getListarPermisosByUsuario() {
      const ruta = '/administracion/usuario/getListarPermisosByUsuario';

      axios.get(ruta, {
        params: {
          'nIdUsuario': this.fillPermiso.nIdUsuario,
        }
      }).then(
        res => {
          this.listPermisos = res.data;
          this.filterPermisosByUsuario();
        }
      ).catch(error=>{
        console.log(error.response);
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    getRolByUsuario() {
      const url = '/administracion/usuario/getRolByUsuario';
      axios.get(url, {
        params: {
          'nIdUsuario': this.fillPermiso.nIdUsuario,
        }
      }).then(res => {
        this.fillPermiso.cNombreRol = (res.data.length == 0) ? '' : res.data[0].name;
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
    filterPermisosByUsuario() {
      let me = this;
      me.listPermisos.map(function (x) {
        me.listPermisosFilter.push(
          {
            'id': x.id,
            'name': x.name,
            'slug': x.slug,
            'checked': (x.checked == 1) ? true : false,
          }
        );
      });
    },
    marcarFila(index) {
      this.listPermisosFilter[index].checked = !this.listPermisosFilter[index].checked;
    },
    setRegistrarPermisosByUsuario() {
      if (this.validarRegistrarPermisosByUsuario()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;

      const url = '/administracion/usuario/setRegistrarPermisosByUsuario';
      axios.post(url, {
        'nIdUsuario': this.fillPermiso.nIdUsuario,
        'listPermisosFilter': this.listPermisosFilter,
      }).then(res => {
        this.getListarRolPermisosByUsuario();
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
    getListarRolPermisosByUsuario() {
      const ruta = '/administracion/usuario/getListarRolPermisosByUsuario';

      axios.get(ruta).then(
        res => {
          this.listRolPermisosByUsuario = res.data;
          this.filterListarRolPermisosByUsuario();
        }
      ).catch(error=>{
        console.log(error.response);
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    filterListarRolPermisosByUsuario() {
      let me = this;
      me.listRolPermisosByUsuarioFilter = [];
      me.listRolPermisosByUsuario.map(function (x) {
        me.listRolPermisosByUsuarioFilter.push(x.slug);
      });
      // sessionStorage.clear();
      sessionStorage.setItem('listRolPermisosByUsuario', JSON.stringify(me.listRolPermisosByUsuarioFilter));
      EventBus.$emit('notifyRolPermisosByUsuario', me.listRolPermisosByUsuarioFilter);
      this.fullscreenLoading = false;
        Swal.fire({
          icon: 'success',
          title: 'Se otorgaron los permisos al usuario correctamente',
          showConfirmButton: false,
          timer: 1500
        });
    },
    validarRegistrarPermisosByUsuario() {
      this.error = 0;
      this.mensajeError = [];

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