<template>
  <div class="login-box">
    <div class="login-logo">
      <router-link :to="{ name: 'login' }">
        <b>Iniciar Sesión</b>
      </router-link>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Ingresa tus credenciales para Iniciar Sesión</p>

        <form method="post">
          <div class="input-group mb-3">
            <vs-input :state="(error) ? 'danger' : ''" icon-after @keyup.enter="login" v-model="fillLogin.cEmail" placeholder="Email">
              <template #icon>
                <i class='fas fa-envelope'></i>
              </template>
            </vs-input>
            <!-- <input type="email" @keyup.enter="login" v-model="fillLogin.cEmail" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div> -->
          </div>
          <div class="input-group mb-3">
            <vs-input :state="(error) ? 'danger' : ''" icon-after @keyup.enter="login" type="password" v-model="fillLogin.cContrasena" placeholder="Password">
              <template #icon>
                <i class='fas fa-lock'></i>
              </template>
            </vs-input>
            <!-- <input type="password" @keyup.enter="login" v-model="fillLogin.cContrasena" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div> -->
          </div>
        </form>

        <div class="row">
          <div class="col-md-12">
            <span v-if="error">
              <div v-for="(e, index) in mensajeError" :key="index" class="callout callout-danger">
                {{ e }}
              </div>
            </span>
          </div>
        </div>

        <div class="social-auth-links text-center mb-3">
          <button href="#" class="btn btn-flat btn-block btn-danger" @click.prevent="login">
            Iniciar Sesión
          </button>
        </div>
        <!-- /.social-auth-links -->

        <p class="mb-1">
          <a href="forgot-password.html">I forgot my password</a>
        </p>
        <p class="mb-0">
          <a href="register.html" class="text-center">Register a new membership</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
</template>

<script>
import Axios from 'axios';

export default {
  data() {
    return {
      fillLogin: {
        cEmail: '',
        cContrasena: '',
      },
      fullscreenLoading: false,
      error: 0,
      mensajeError: [],
      listRolPermisosByUsuario: [],
      listRolPermisosByUsuarioFilter: [],
    }
  },
  methods: {
    login() {
      if (this.validarLogin()) {
        return;
      }

      const loading = this.$vs.loading({
        type: 'square',
        color: '#ff0000',
        text: "Cargando",
      });

      const url = '/authenticate/login';
      Axios.post(url, {
        'cEmail': this.fillLogin.cEmail,
        'cContrasena': this.fillLogin.cContrasena,
      }).then(res => {
        // console.log(res.data);
        if (res.data.code == 401) {
          this.loginFailed();
        }
        else if (res.data.code == 200) {
          // this.loginSuccess();
          this.getListarRolPermisosByUsuario(res.data.authUser);
        }
        
        setTimeout(() => {
          loading.close()
        }, 3000)

      });
    },
    getListarRolPermisosByUsuario(authUser) {
      const ruta = '/administracion/usuario/getListarRolPermisosByUsuario';

      axios.get(ruta, {
        params: {
          'nIdUsuario': authUser.id,
        }
      }).then(
        res => {
          this.listRolPermisosByUsuario = res.data;
          this.filterListarRolPermisosByUsuario(authUser);
        }
      );
    },
    filterListarRolPermisosByUsuario(authUser) {
      let me = this;
      me.listRolPermisosByUsuario.map(function (x) {
        me.listRolPermisosByUsuarioFilter.push(x.slug);
      });
      sessionStorage.setItem('listRolPermisosByUsuario', JSON.stringify(me.listRolPermisosByUsuarioFilter));
      sessionStorage.setItem('authUser', JSON.stringify(authUser));
      this.loginSuccess();
    },
    validarLogin() {
      this.error = 0;
      this.mensajeError = [];

      if (!this.fillLogin.cEmail) {
        this.mensajeError.push('El correo electrónico es un campo obligatorio');
      }
      if (!this.fillLogin.cContrasena) {
        this.mensajeError.push('La contraseña es un campo obligatorio');
      }
      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    },
    loginFailed() {
      this.error = 0;
      this.mensajeError = [];
      this.mensajeError.push('Credenciales incorrectas.');
      this.fillLogin.cEmail = '';
      this.fillLogin.cContrasena = '';
      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    },
    loginSuccess() {
      this.$router.push({ name: 'dashboard.index' });
      location.reload();
    },
  }
}
</script>

<style>

</style>