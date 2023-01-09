<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Crear Cliente</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="`/clientes`">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Formulario Registrar Cliente</h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-12 col-form-label">Documento</label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" v-model="fillCrearCliente.cDocumento"
                            @keyup.enter="setRegistrarPedido">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-12 col-form-label">Nombre</label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" v-model="fillCrearCliente.cNombre"
                            @keyup.enter="setRegistrarPedido">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-12 col-form-label">Apellido</label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" v-model="fillCrearCliente.cApellido"
                            @keyup.enter="setRegistrarPedido">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-12 col-form-label">Email</label>
                        <div class="col-md-12">
                          <vs-input v-model="fillCrearCliente.cEmail" placeholder="correo@gmail.com">
                            <template v-if="validEmail" #message-success>Correo
                              Electronico válido</template>
                            <template v-if="!validEmail && fillCrearCliente.cEmail !== ''" #message-danger>Correo
                              Electronico
                              invalido</template>
                          </vs-input>
                          <!-- <input type="email" class="form-control" v-model="fillCrearCliente.cEmail" @keyup.enter="setRegistrarPedido"> -->
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-12 col-form-label">Teléfono</label>
                        <div class="col-md-12">
                          <input type="tel" class="form-control" v-model="fillCrearCliente.cTelefono"
                            @keyup.enter="setRegistrarPedido">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row">
                  <button class="btn btn-flat btn-info btnFull" @click.prevent="setRegistrarPedido">Registrar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="toast-container ">
    <div class="p-2" v-for="error in mensajeError" :key="error">
      <div class="toast fade bg-primary" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
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
export default {
  data() {
    return {
      fillCrearCliente: {
        nIdCliente: '',
        cDocumento: '',
        cNombre: '',
        cApellido: '',
        cEmail: '',
        cTelefono: ''
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
      mensajeError: []
    }
  },
  computed: {
    validEmail() {
      return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.fillCrearCliente.cEmail)
    },
  },
  methods: {
    limpiarCriterios() {
      this.fillCrearCliente.cDocumento = '';
      this.fillCrearCliente.cNombre = '';
      this.fillCrearCliente.cApellido = '';
      this.fillCrearCliente.cEmail = '';
      this.fillCrearCliente.cTelefono = '';
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    setRegistrarCliente(loading) {
      if (this.validarRegistrarCliente()) {
        this.modalShow = true;
        console.log("error")
        return;
      }

      const loading = this.$vs.loading({
        type: 'square',
        color: '#ff0000',
        text: "Cargando",
      });

      const url = '/operacion/cliente/setRegistrarCliente';
      axios.post(url, {
        'cDocumento': this.fillCrearCliente.cDocumento,
        'cNombre': this.fillCrearCliente.cNombre,
        'cApellido': this.fillCrearCliente.cApellido,
        'cEmail': this.fillCrearCliente.cEmail,
        'cTelefono': this.fillCrearCliente.cTelefono,
      }).then(res => {
        this.loading.close();
        this.$router.push('/clientes')
      }).catch(error => {
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    validarRegistrarCliente() {
      this.error = 0;
      this.mensajeError = [];

      if (!this.fillCrearCliente.cDocumento) {
        this.mensajeError.push("El Documento es un campo obligatorio")
      }
      if (!this.fillCrearCliente.cNombre) {
        this.mensajeError.push("La Nombre es un campo obligatorio")
      }
      if (!this.fillCrearCliente.cApellido) {
        this.mensajeError.push("La Apellido es un campo obligatorio")
      }
      if (this.fillCrearCliente.cEmail) {
        if (!this.validEmail) {
          this.mensajeError.push("El correo electronico, tiene un formato invalido")
        }
      }

      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    },
  }
}
</script>

<style>
.el-row {
  margin-bottom: 20px;
}

.el-col {
  border-radius: 4px;
}

.bg-purple-dark {
  background: #99a9bf;
}

.bg-purple {
  background: #d3dce6;
}

.bg-purple-light {
  background: #e5e9f2;
}

.grid-content {
  border-radius: 4px;
  min-height: 36px;
}

.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
</style>
