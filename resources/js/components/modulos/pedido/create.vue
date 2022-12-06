<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Crear Pedido</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="`/pedidos`">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-4">
                <div class="card" :class="(switchCliente) ? 'card-info' : 'card-success'">
                  <div class="card-header">
                    <h3 class="card-title">
                      Formulario {{ (switchCliente) ? 'Registrar' : 'Buscar' }} Cliente
                    </h3>
                  </div>
                  <div class="card-body">
                    <form role="form">
                      <div class="row">
                        <!-- 4:15 class 41 registrar perdido p1 -->
                        <div class="col-md-3">
                          <vs-switch square v-model="switchCliente" @change="limpiarCriterios">
                            <template #off>
                              <i class="fas fa-search" />
                            </template>
                            <template #on>
                              <i class="fas fa-plus-square" />
                            </template>
                          </vs-switch>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label" placeholder="Ej: Administrador">Documento</label>
                            <div class="col-md-12">
                              <template v-if="switchCliente">
                                <input v-model="fillCrearCliente.cDocumento" type="text" class="form-control">
                              </template>
                              <template v-else>
                                <el-autocomplete v-model="cBusqueda" :fetch-suggestions="querySearch"
                                  placeholder="Buscar..." :trigger-on-focus="true" size="medium" @select="clientSelect">
                                  <i class="el-icon-search el-input__icon" slot="suffix" />
                                </el-autocomplete>
                              </template>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label" placeholder="Ej: Administrador">Nombre</label>
                            <div class="col-md-12">
                              <input v-model="fillCrearCliente.cNombre" type="text"
                                :disabled="(switchCliente) ? false : true" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label" placeholder="Ej: Administrador">Apellido</label>
                            <div class="col-md-12">
                              <input v-model="fillCrearCliente.cApellido" type="text"
                                :disabled="(switchCliente) ? false : true" class="form-control">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label" placeholder="Ej: Administrador">Email</label>
                            <div class="col-md-12">
                              <vs-input v-model="fillCrearCliente.cEmail" :disabled="(switchCliente) ? false : true"
                                class="">
                                <template v-if="validEmail" #message-success>
                                  Correo electrónico válido
                                </template>
                                <template v-if="!validEmail && fillCrearCliente.cEmail !== ''" #message-danger>
                                  Correo electrónico inválido
                                </template>
                              </vs-input>
                              <!-- <input v-model="fillCrearCliente.cEmail" 
                                  type="email" :disabled="(switchCliente) ? false : true" class="form-control"> -->
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label" placeholder="Ej: Administrador">Télefono</label>
                            <div class="col-md-12">
                              <input v-model="fillCrearCliente.cTelefono" type="tel"
                                :disabled="(switchCliente) ? false : true" class="form-control">
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="card-footer">
                    <div class="row justify-content-center">
                      <button class="btn btnFull btn-info btn-flat" @click="validarCliente"
                        v-loading.fullscreen.lock="fullscreenLoading">{{ (switchCliente) ? 'Registrar' : 'Buscar'
                        }}</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
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
    <div class="toast-container ">
      <div class="p-2" v-for="error in mensajeError" :key="error">
        <div class="toast fade bg-primary" :class="{ show: modalShow }"
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
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      fillCrearCliente: {
        nIdCliente: 0,
        cDocumento: '',
        cNombre: '',
        cApellido: '',
        cEmail: '',
        cTelefono: '',
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
      switchCliente: false,
      cBusqueda: "",
      listClientes: [],
      listClientesFilter: [],

    }
  },
  computed: {
    validEmail() {
      return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.fillCrearCliente.cEmail)
    }
  },
  mounted() {
    this.getListarPermisosByRol();
    this.getListarClientes();
  },
  methods: {
    limpiarCriterios() {
      this.fillCrearCliente.nIdCliente = '';
      this.fillCrearCliente.cDocumento = '';
      this.fillCrearCliente.cNombre = '';
      this.fillCrearCliente.cApellido = '';
      this.fillCrearCliente.cEmail = '';
      this.fillCrearCliente.cTelefono = '';
      this.cBusqueda = '';
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
      ).catch(error => {
        console.log(error.response);
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
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
    validarCliente() {
      if (this.validarRegistrarPedido()) {
        this.modalShow = true;
        return;
      }

      this.fullscreenLoading = true;

      if (this.switchCliente) {
        this.setRegistrarCliente();
      }


    },
    setRegistrarCliente() {
      const url = '/operacion/cliente/setRegistrarCliente';
      axios.post(url, {
        'cDocumento': this.fillCrearCliente.cDocumento,
        'cNombre': this.fillCrearCliente.cNombre,
        'cApellido': this.fillCrearCliente.cApellido,
        'cEmail': this.fillCrearCliente.cEmail,
        'cTelefono': this.fillCrearCliente.cTelefono,
      }).then(res => {
        this.fullscreenLoading = false;
        console.log(res.data);
        this.getListarClientes();
      }).catch(error => {
        console.log(error.response);
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    validarRegistrarPedido() {
      this.error = 0;
      this.mensajeError = [];

      if (this.switchCliente) {
        if (!this.fillCrearCliente.cDocumento) {
          this.mensajeError.push("El documento es un campo obligatorio");
        }
        if (!this.fillCrearCliente.cNombre) {
          this.mensajeError.push("El Nombre es un campo obligatorio");
        }
        if (!this.fillCrearCliente.cApellido) {
          this.mensajeError.push("El Apellido es un campo obligatorio");
        }
        if (this.fillCrearCliente.cEmail) {
          if (!this.validEmail) {
            this.mensajeError.push("El correo electrónico tiene un formato inválido");
          }
        }
      }
      else {
        if (!this.fillCrearCliente.nIdCliente) {
          this.mensajeError.push("El cliente es necesario cargar");
        }
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
    },
    querySearch(queryString, cb) {
      var links = this.listClientesFilter;
      var results = queryString ? links.filter(this.createFilter(queryString)) : links;
      // call callback function to return suggestion objects
      cb(results);
    },
    createFilter(queryString) {
      return (link) => {
        return (link.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
      };
    },
    getListarClientes() {
      const ruta = '/operacion/cliente/getListarClientes';

      axios.get(ruta).then(
        res => {
          this.listClientes = res.data;
          this.filterListarClientes();
        }
      ).catch(error => {
        console.log(error.response);
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    filterListarClientes() {
      let me = this;
      me.listClientesFilter = [];
      me.listClientes.map(function (x, y) {

        me.listClientesFilter.push({
          'value': x.document + ' - ' + x.fullname,
          'link': x.id
        });

      });
    },
    clientSelect(item) {
      let res = this.listClientes.filter(cliente => {
        return ((String(cliente.id)).indexOf(String(item.link)) != -1);
      });
      console.log(res);
      this.fillCrearCliente.nIdCliente = res[0].id;
      this.fillCrearCliente.cDocumento = res[0].document;
      this.fillCrearCliente.cNombre = res[0].name;
      this.fillCrearCliente.cApellido = res[0].lastname;
      this.fillCrearCliente.cEmail = res[0].email;
      this.fillCrearCliente.cTelefono = res[0].phone;
    },
  }
}
</script>