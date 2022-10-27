<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Crear Usuario</h1>
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
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Formulario registrar usuario
                </h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Primer nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillCreateUsuario.cPrimerNombre" @keyup.enter="setRegistrarUsuario"
                            type="text" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Segundo nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillCreateUsuario.cSegundoNombre" @keyup.enter="setRegistrarUsuario"
                            type="text" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Apellido</label>
                        <div class="col-md-9">
                          <input v-model="fillCreateUsuario.cApellido" @keyup.enter="setRegistrarUsuario" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Usuario</label>
                        <div class="col-md-9">
                          <input v-model="fillCreateUsuario.cUsuario" @keyup.enter="setRegistrarUsuario" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Correo electrónico</label>
                        <div class="col-md-9">
                          <input v-model="fillCreateUsuario.cCorreo" @keyup.enter="setRegistrarUsuario" type="email"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Contraseña</label>
                        <div class="col-md-9">
                          <el-input placeholder="Ingrese una contraseña" v-model="fillCreateUsuario.cContrasena"
                            @keyup.enter="setRegistrarUsuario" show-password></el-input>
                          <!-- <input v-model="fillCreateUsuario.cContrasena" @keyup.enter="setRegistrarUsuario" type="password"
                            class="form-control"> -->
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Rol</label>
                        <div class="col-md-9">
                          <el-select v-model="fillCreateUsuario.nIdRol" placeholder="Seleccione un rol" clearable>
                            <el-option v-for="item in listRoles" :key="item.id" :label="item.name" :value="item.id">
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Fotografía</label>
                        <div class="col-md-9">
                          <!-- <el-button slot="trigger" size="small" type="primary">Selecciona un archivo</el-button> -->
                          <input type="file" class="form-control" @change="getFile">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row justify-content-center">
                  <button class="btn btnWidth btn-info btn-flat" @click="setRegistrarUsuario"
                    v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
                  <button class="btn btnWidth btn-flat btn-default" @click="limpiarCriteriosBsq">Limpiar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="toast fade bg-primary" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal"
      style="position: absolute; right: 36%; top: 3em; z-index: 9999;">
      <div class="toast-header">
        <div class="rounded mr-2 bg-light" style="height: 20px; width: 20px"></div>
        <img src="" class="rounded mr-1" alt="" />
        <strong class="mr-auto">Sistema Laravel y Vue</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" @click="abrirModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body p-3" v-if="mensajeError.length == 1">
        {{ mensajeError[0] }}
      </div>
      <div class="toast-body" v-else="mensajeError.length > 1 ">
        Por favor completa el formulario antes de continuar
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      fillCreateUsuario: {
        cPrimerNombre: '',
        cSegundoNombre: '',
        cApellido: '',
        cUsuario: '',
        cCorreo: '',
        cContrasena: '',
        oFotografia: '',
        nIdRol: '',
      },
      listRoles: [],
      form: new FormData,
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
    this.getListarRoles();
  },
  computed: {
  },
  methods: {
    limpiarCriteriosBsq() {
      this.fillCreateUsuario.cPrimerNombre = '';
      this.fillCreateUsuario.cApellido = '';
      this.fillCreateUsuario.cUsuario = '';
      this.fillCreateUsuario.cCorreo = '';
      this.fillCreateUsuario.cContrasena = '';
      this.fillCreateUsuario.oFotografia = null;
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    getListarRoles() {
      this.fullscreenLoading = true;

      const url = '/administracion/rol/getListarRoles';
      axios.get(url).then(res => {
        this.listRoles = res.data;
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
    getFile(e) {
      this.fillCreateUsuario.oFotografia = e.target.files[0];
    },
    setRegistrarUsuario() {
      if (this.validarRegistrarUsuario()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      if (!this.fillCreateUsuario.oFotografia || this.fillCreateUsuario.oFotografia == undefined) {
        this.setGuardarUsuario(0);
      }
      else {
        this.setRegistrarArchivo();
      }
    },
    setRegistrarArchivo() {
      this.form.append('file', this.fillCreateUsuario.oFotografia);
      const config = { headers: { 'Content-Type': 'multipart/form-data' } };
      var url = '/archivo/setRegistrarArchivo';
      axios.post(url, this.form, config).then(res => {
        let nIdFile = res.data[0].nIdFile;
        this.setGuardarUsuario(nIdFile);
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
    setGuardarUsuario(nIdFile) {
      let url = '/administracion/usuario/setRegistrarUsuario';
      axios.post(url, {
        'cPrimerNombre': this.fillCreateUsuario.cPrimerNombre,
        'cSegundoNombre': this.fillCreateUsuario.cSegundoNombre,
        'cApellido': this.fillCreateUsuario.cApellido,
        'cUsuario': this.fillCreateUsuario.cUsuario,
        'cCorreo': this.fillCreateUsuario.cCorreo,
        'cContrasena': this.fillCreateUsuario.cContrasena,
        'oFotografia': nIdFile,
      }).then(res => {
        console.log(res.data)
        this.setEditarRolByUsuario(res.data);
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
    setEditarRolByUsuario(nIdUsuario) {
      let url = '/administracion/usuario/setEditarRolByUsuario';
      axios.post(url, {
        'nIdUsuario': nIdUsuario,
        'nIdRol': this.fillCreateUsuario.nIdRol,
      }).then(res => {
        console.log(res.data);
        this.fullscreenLoading = false;
        this.$router.push('/usuarios');
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
    validarRegistrarUsuario() {
      this.error = 0;
      this.mensajeError = [];

      if (!this.fillCreateUsuario.cPrimerNombre) {
        this.mensajeError.push("El primer nombre es un campo obligatorio");
      }
      if (!this.fillCreateUsuario.cApellido) {
        this.mensajeError.push("El apellido es un campo obligatorio");
      }
      if (!this.fillCreateUsuario.cUsuario) {
        this.mensajeError.push("El usuario es un campo obligatorio");
      }
      if (!this.fillCreateUsuario.cCorreo) {
        this.mensajeError.push("El correo es un campo obligatorio");
      }
      if (!this.fillCreateUsuario.cContrasena) {
        this.mensajeError.push("La contraseña es un campo obligatorio");
      }
      if (!this.fillCreateUsuario.nIdRol) {
        this.mensajeError.push("La contraseña es un campo obligatorio");
      }
      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    }
  }
}
</script>