<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Editar Permiso</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="`/permisos`">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Formulario Editar Permiso
                </h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: Administrador">Nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarPermiso.cNombre" @keyup.enter="setEditarPermisos" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: rol.ejemplo">Url Amigable</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarPermiso.cSlug" @keyup.enter="setEditarPermisos" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btnWidth btn-info btn-flat" @click="setEditarPermisos"
                      v-loading.fullscreen.lock="fullscreenLoading">Editar</button>
                    <button class="btn btnWidth btn-flat btn-default" @click="limpiarCriteriosBsq">Limpiar</button>
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
      fillEditarPermiso: {
        nIdPermiso: this.$attrs.id,
        cNombre: '',
        cSlug: '',
      },
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
  mounted() {
    this.getListarPermisos();
  },
  computed: {
  },
  methods: {
    limpiarCriteriosBsq() {
      this.fillEditarPermiso.cNombre = '';
      this.fillEditarPermiso.cSlug = '';
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    getListarPermisos() {
      this.fullscreenLoading = true;

      const url = '/administracion/permiso/getListarPermisos';
      axios.get(url, {
        params: {
          'nIdPermiso': this.fillEditarPermiso.nIdPermiso,
        }
      }).then(res => {
        this.fillEditarPermiso.cNombre = res.data[0].name;
        this.fillEditarPermiso.cSlug = res.data[0].slug;
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
    setEditarPermisos() {
      if (this.validarEditarPermisos()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;

      const url = '/administracion/permiso/setEditarPermisos';

      axios.post(url, {
        'nIdPermiso': this.fillEditarPermiso.nIdPermiso,
        'cNombre': this.fillEditarPermiso.cNombre,
        'cSlug': this.fillEditarPermiso.cSlug,
      }).then(res => {
        this.fullscreenLoading = false;
        Swal.fire({
          icon: 'success',
          title: 'Se actualizó el rol correctamente',
          showConfirmButton: false,
          timer: 1500
        });
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
    validarEditarPermisos() {
      this.error = 0;
      this.mensajeError = [];

      if (!this.fillEditarPermiso.cNombre) {
        this.mensajeError.push("El Nombre es un campo obligatorio");
      }
      if (!this.fillEditarPermiso.cSlug) {
        this.mensajeError.push("La Url Amigable es un campo obligatorio");
      }

      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    }
  }
}
</script>