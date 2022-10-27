<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Usuario</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="row">
        <div class="col-md-4">
          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img v-if="!fillVerUsuario.cRutaArchivo" class="profile-user-img img-fluid img-circle img-max-height" src="/img/avatar.jpg"
                  alt="User profile picture">
                <img v-else class="profile-user-img img-fluid img-circle img-max-height" :src="fillVerUsuario.cRutaArchivo"
                  alt="User profile picture">
              </div>
              <h3 class="profile-username text-center">{{ fillVerUsuario.cfullname }}</h3>
              <p class="text-muted text-center"> {{ fillVerUsuario.cNombreRol}} </p>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

          <!-- About Me Box -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Sobre Mi</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <strong><i class="fab fa-mailchimp"></i> Nombre Completo</strong>
              <p class="text-muted" v-text="fillVerUsuario.cfullname"></p>
              <hr>
              <strong><i class="fas fa-envelope-open-text mr-1"></i>Correo Electrónico</strong>
              <p class="text-muted">{{ fillVerUsuario.cCorreo }}</p>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <div class="col-md-8">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills position-reverse">
                <li class="nav-item">
                  <router-link class="nav-link active" :to="'/usuarios'">
                    <i class="fas fa-arrow-left"></i> Regresar  
                  </router-link>
                </li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane active" id="settings">
                  <form class="form-horizontal">
                    <div class="form-group-row">
                      <label class="col-md-3 col-form-label">Primer nombre</label>
                      <div class="col-md-9">
                        <input v-model="fillEditarUsuario.cPrimerNombre" @keyup.enter="setEditarUsuario" type="text"
                          class="form-control">
                      </div>
                    </div>
                    <div class="form-group-row">
                      <label class="col-md-3 col-form-label">Segundo nombre</label>
                      <div class="col-md-9">
                        <input v-model="fillEditarUsuario.cSegundoNombre" @keyup.enter="setEditarUsuario" type="text"
                          class="form-control">
                      </div>
                    </div>
                    <div class="form-group-row">
                      <label class="col-md-3 col-form-label">Apellido</label>
                      <div class="col-md-9">
                        <input v-model="fillEditarUsuario.cApellido" @keyup.enter="setEditarUsuario" type="text"
                          class="form-control">
                      </div>
                    </div>
                    <div class="form-group-row">
                      <label class="col-md-3 col-form-label">Usuario</label>
                      <div class="col-md-9">
                        <input v-model="fillEditarUsuario.cUsuario" @keyup.enter="setEditarUsuario" type="text"
                          class="form-control">
                      </div>
                    </div>
                    <div class="form-group-row">
                      <label class="col-md-3 col-form-label">Correo electrónico</label>
                      <div class="col-md-9">
                        <input v-model="fillEditarUsuario.cCorreo" @keyup.enter="setEditarUsuario" type="email"
                          class="form-control">
                      </div>
                    </div>
                    <div class="form-group-row">
                      <label class="col-md-3 col-form-label">Contraseña</label>
                      <div class="col-md-9">
                        <el-input placeholder="Ingrese una contraseña" v-model="fillEditarUsuario.cContrasena"
                          @keyup.enter="setEditarUsuario" show-password></el-input>
                        <!-- <input v-model="fillEditarUsuario.cContrasena" @keyup.enter="setEditarUsuario" type="password"
                            class="form-control"> -->
                      </div>
                    </div>
                    <div class="form-group-row">
                      <label class="col-md-3 col-form-label">Fotografía</label>
                      <div class="col-md-9">
                        <!-- <el-button slot="trigger" size="small" type="primary">Selecciona un archivo</el-button> -->
                        <input type="file" class="form-control" @change="getFile">
                      </div>
                    </div>
                    <div class="form-group-row">
                      <div class="col-sm-12 col-form-label">
                        <button class="btn btnFull btn-info btn-flat" @click.prevent="setEditarUsuario"
                          v-loading.fullscreen.lock="fullscreenLoading">Editar</button>
                      </div>
                    </div>

                  </form>
                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
      </div>
    </div>
    <div class="toast fade bg-primary" :class="{show: modalShow}" :style="modalShow ? mostrarModal : ocultarModal"
      style="position: absolute; right: 36%; top: 3em; z-index: 9999;">
      <div class="toast-header">
        <div class="rounded mr-2 bg-light" style="height: 20px; width: 20px"></div>
        <img src="" class="rounded mr-1" alt="" />
        <strong class="mr-auto">Sistema Laravel y Vue</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" @click="abrirModal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body p-3" v-if="mensajeError.length == 1 ">
        {{mensajeError[0]}}
      </div>
      <div class="toast-body" v-else="mensajeError.length > 1 ">
        Por favor completa el formulario antes de continuar
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      fillEditarUsuario: {
        cfullname: '',
        nIdUsuario: this.$attrs.id,
        cPrimerNombre: '',
        cSegundoNombre: '',
        cApellido: '',
        cUsuario: '',
        cCorreo: '',
        cContrasena: '',
        oFotografia: '',
      },
      fillVerUsuario: {
        cfullname: '',
        nIdUsuario: this.$attrs.id,
        cPrimerNombre: '',
        cSegundoNombre: '',
        cApellido: '',
        cUsuario: '',
        cCorreo: '',
        cContrasena: '',
        oFotografia: '',
        cNombreRol: '',
      },
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
    this.getRolByUsuario();
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
        console.log(res.data);
        this.getUsuarioEditar(res.data[0]);
        this.getUsuarioVer(res.data[0]);

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
    getUsuarioEditar({ fullname, firstname, secondname, lastname, username, email }) {

      this.fillEditarUsuario.cfullname = fullname;
      this.fillEditarUsuario.cPrimerNombre = firstname;
      this.fillEditarUsuario.cSegundoNombre = secondname;
      this.fillEditarUsuario.cApellido = lastname;
      this.fillEditarUsuario.cUsuario = username;
      this.fillEditarUsuario.cCorreo = email;
    },
    getUsuarioVer({ fullname, firstname, secondname, lastname, username, email, profile_image}) {
      this.fillVerUsuario.cfullname = fullname;
      this.fillVerUsuario.cPrimerNombre = firstname;
      this.fillVerUsuario.cSegundoNombre = secondname;
      this.fillVerUsuario.cApellido = lastname;
      this.fillVerUsuario.cUsuario = username;
      this.fillVerUsuario.cCorreo = email;
      this.fillVerUsuario.cRutaArchivo = profile_image;
    },
    getRolByUsuario() {
      const url = '/administracion/usuario/getRolByUsuario';
      axios.get(url, {
        params: {
          'nIdUsuario': this.fillEditarUsuario.nIdUsuario,
        }
      }).then(res => {
        this.fillVerUsuario.cNombreRol = (res.data.length == 0) ? '' : res.data[0].name;
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
    abrirModal() {
      this.modalShow = !this.modalShow;
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
        this.getRefrescarUsuarioAutenticado();
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
    getRefrescarUsuarioAutenticado(){
      const url = '/authenticate/getRefrescarUsuarioAutenticado';
      axios.get(url).then(res =>{
        // console.log(res.data);
        EventBus.$emit('verifyAuthenticatedUser', res.data);
        this.fullscreenLoading = false;
        this.getUsuarioById();
        Swal.fire({
          icon: 'success',
          title: 'Se actualizó el usuario correctamente',
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
      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    }
  },

}
</script>
<style>
  .position-reverse{
    flex-direction: row-reverse !important;
  }

  .img-max-height{
    min-height: 100px !important;
    max-height: 100px !important;
    max-width: 100px !important;
  }
</style>