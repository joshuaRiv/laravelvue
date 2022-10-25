<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Editar Usuario</h1>
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
                  Formulario editar usuario
                </h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Primer nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarUsuario.cPrimerNombre" @keyup.enter="setEditarUsuario" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Segundo nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarUsuario.cSegundoNombre" @keyup.enter="setEditarUsuario" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Apellido</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarUsuario.cApellido" @keyup.enter="setEditarUsuario" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Usuario</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarUsuario.cUsuario" @keyup.enter="setEditarUsuario" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Correo electrónico</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarUsuario.cCorreo" @keyup.enter="setEditarUsuario" type="email"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Contraseña</label>
                        <div class="col-md-9">
                          <el-input placeholder="Ingrese una contraseña" v-model="fillEditarUsuario.cContrasena"
                            @keyup.enter="setEditarUsuario" show-password></el-input>
                          <!-- <input v-model="fillEditarUsuario.cContrasena" @keyup.enter="setEditarUsuario" type="password"
                            class="form-control"> -->
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Rol</label>
                        <div class="col-md-9">
                          <el-select v-model="fillEditarUsuario.nIdRol" placeholder="Seleccione un rol" clearable>
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
                  <button class="btn btnWidth btn-info btn-flat" @click="setEditarUsuario"
                    v-loading.fullscreen.lock="fullscreenLoading">Editar</button>
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
      fillEditarUsuario: {
        nIdUsuario: this.$attrs.id,
        cPrimerNombre: '',
        cSegundoNombre: '',
        cApellido: '',
        cUsuario: '',
        cCorreo: '',
        cContrasena: '',
        nIdRol: '',
        oFotografia: '',
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
    this.getUsuarioById();
    this.getListarRoles();
  },
  methods: {
    getUsuarioById() {
      this.fullscreenLoading = true;

      const url = '/administracion/usuario/getListarUsuarios';
      axios.get(url, {
        params: {
          'nIdUsuario': this.fillEditarUsuario.nIdUsuario,
        }
      }).then(res => {
        this.fillEditarUsuario.cPrimerNombre = res.data[0].firstname;
        this.fillEditarUsuario.cSegundoNombre = res.data[0].secondname;
        this.fillEditarUsuario.cApellido = res.data[0].lastname;
        this.fillEditarUsuario.cUsuario = res.data[0].username;
        this.fillEditarUsuario.cCorreo = res.data[0].email;
        this.fullscreenLoading = false;
      });
    },
    limpiarCriteriosBsq() {
      this.fillEditarUsuario.cPrimerNombre = '';
      this.fillEditarUsuario.cApellido = '';
      this.fillEditarUsuario.cUsuario = '';
      this.fillEditarUsuario.cCorreo = '';
      this.fillEditarUsuario.cContrasena = '';
      this.fillEditarUsuario.oFotografia = null;
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
        this.getRolByUsuario();
      });
    },
    getRolByUsuario() {
      const url = '/administracion/usuario/getRolByUsuario';
      axios.get(url, {
        params: {
          'nIdUsuario': this.fillEditarUsuario.nIdUsuario,
        }
      }).then(res => {
        console.log('rol del usuario' + res.data);
        this.fillEditarUsuario.nIdRol = (res.data.length == 0) ? '' : res.data[0].nIdRol;
        this.fullscreenLoading = false;
      });
    },
    getFile(e) {
      this.fillEditarUsuario.oFotografia = e.target.files[0];
    },
    setEditarUsuario() {
      if (this.validarRegistrarUsuario()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      if (!this.fillEditarUsuario.oFotografia || this.fillEditarUsuario.oFotografia == undefined) {
        this.setGuardarUsuario(0);
      }
      else {
        this.setRegistrarArchivo();
      }
    },
    setRegistrarArchivo() {
      this.form.append('file', this.fillEditarUsuario.oFotografia);
      const config = { headers: { 'Content-Type': 'multipart/form-data' } };
      var url = '/archivo/setRegistrarArchivo';
      axios.post(url, this.form, config).then(res => {
        let nIdFile = res.data[0].nIdFile;
        this.setGuardarUsuario(nIdFile);
      });
    },
    setGuardarUsuario(nIdFile) {
      let url = '/administracion/usuario/setEditarUsuario';
      axios.post(url, {
        'nIdUsuario': this.fillEditarUsuario.nIdUsuario,
        'cPrimerNombre': this.fillEditarUsuario.cPrimerNombre,
        'cSegundoNombre': this.fillEditarUsuario.cSegundoNombre,
        'cApellido': this.fillEditarUsuario.cApellido,
        'cUsuario': this.fillEditarUsuario.cUsuario,
        'cCorreo': this.fillEditarUsuario.cCorreo,
        'cContrasena': this.fillEditarUsuario.cContrasena,
        'oFotografia': nIdFile,
      }).then(res => {
        this.setEditarRolByUsuario();
      });
    },
    setEditarRolByUsuario() {
      const url = '/administracion/usuario/setEditarRolByUsuario';
      axios.post(url, {
        'nIdUsuario': this.fillEditarUsuario.nIdUsuario,
        'nIdRol': this.fillEditarUsuario.nIdRol,
      }).then(res => {
        this.fullscreenLoading = false;
        Swal.fire({
          icon: 'success',
          title: 'Se actualizó el usuario correctamente',
          showConfirmButton: false,
          timer: 1500
        })
      });
    },
    validarRegistrarUsuario() {
      this.error = 0;
      this.mensajeError = [];

      if (!this.fillEditarUsuario.cPrimerNombre) {
        this.mensajeError.push("El primer nombre es un campo obligatorio");
      }
      if (!this.fillEditarUsuario.cApellido) {
        this.mensajeError.push("El apellido es un campo obligatorio");
      }
      if (!this.fillEditarUsuario.cUsuario) {
        this.mensajeError.push("El usuario es un campo obligatorio");
      }
      if (!this.fillEditarUsuario.cCorreo) {
        this.mensajeError.push("El correo es un campo obligatorio");
      }
      if (!this.fillEditarUsuario.nIdRol) {
        this.mensajeError.push("El rol es un campo obligatorio");
      }
      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    }
  }
}
</script>