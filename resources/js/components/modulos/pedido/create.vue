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
                <div class="card " :class="(switchCliente) ? 'card-info' : 'card-success'">
                  <div class="card-header">
                    <h3 class="card-title">Formulario {{ (switchCliente) ? 'Registrar' : 'Buscar' }}
                      Cliente</h3>
                  </div>
                  <div class="card-body">
                    <form role="form">
                      <div class="row">
                        <div class="col-md-3">
                          <vs-switch square v-model="switchCliente" @change="limpiarCriterios">
                            <template #off>
                              <i class="fas fa-plus-square"></i>
                            </template>
                            <template #on>
                              <i class="fas fa-search"></i>
                            </template>
                          </vs-switch>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label">Documento</label>
                            <div class="col-md-12">
                              <template v-if="switchCliente">
                                <input type="text" class="form-control" v-model="fillCrearCliente.cDocumento"
                                  @keyup.enter="setRegistrarPedido">
                              </template>
                              <template v-else>
                                <el-autocomplete class="inline-input" v-model="cBusqueda"
                                  :fetch-suggestions="querySearch" placeholder="Buscar..." :trigger-on-focus="true"
                                  size="medium" @select="clientSelect">
                                  <i class="el-icon-search el-input__icon" slot="suffix">
                                  </i>
                                </el-autocomplete>
                              </template>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label">Nombre</label>
                            <div class="col-md-12">
                              <input type="text" class="form-control" v-model="fillCrearCliente.cNombre"
                                @keyup.enter="setRegistrarPedido" :disabled="(switchCliente) ? false : true">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label">Apellido</label>
                            <div class="col-md-12">
                              <input type="text" class="form-control" v-model="fillCrearCliente.cApellido"
                                @keyup.enter="setRegistrarPedido" :disabled="(switchCliente) ? false : true">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label">Email</label>
                            <div class="col-md-12">
                              <vs-input v-model="fillCrearCliente.cEmail" placeholder="correo@gmail.com"
                                :disabled="(switchCliente) ? false : true">
                                <template v-if="validEmail" #message-success>Correo
                                  Electronico válido</template>
                                <template v-if="!validEmail && fillCrearCliente.cEmail !== ''" #message-danger>Correo
                                  Electronico
                                  invalido</template>
                              </vs-input>
                              <!-- <input type="email" class="form-control" v-model="fillCrearCliente.cEmail" @keyup.enter="setRegistrarPedido" :disabled="(switchCliente) ? false : true"> -->
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-md-12 col-form-label">Teléfono</label>
                            <div class="col-md-12">
                              <input type="tel" class="form-control" v-model="fillCrearCliente.cTelefono"
                                @keyup.enter="setRegistrarPedido" :disabled="(switchCliente) ? false : true">
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="card-footer">
                    <div class="row">
                      <button v-if="fTotalPedido > 0 && listPedidos.length > 0" class="btn btn-flat btn-info btnFull"
                        @click.prevent="setRegistrarPedido">Registrar</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">
                      Seleccionar Productos
                    </h3>
                  </div>
                  <div class="card-body table-responsive">
                    <vs-tooltip not-arrow right>
                      <vs-button icon relief animation-type="rotate" @click.prevent="agregarProducto">
                        <i class="fa-regular fa-square-plus"></i>
                        <template #animate>
                          <i class="fa-solid fa-square-plus"></i>
                        </template>
                      </vs-button>
                      <template #tooltip>
                        Agregar producto
                      </template>
                    </vs-tooltip>
                    <template v-if="listPedidos.length">
                      <div class="scrollTable">
                        <table class="table table-hover table-head-fixed text-nowrap projects">
                          <thead>
                            <tr>
                              <th>Artículo</th>
                              <th>Stock</th>
                              <th>Precio</th>
                              <th>SubTotal</th>
                              <th>Acción</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(item, index) in listPedidos" :key="index">
                              <td>
                                <el-select v-model="item.nIdProducto" placeholder="Seleccione un producto" clearable
                                  filterable @change="obtenerProducto(item.nIdProducto, index)">
                                  <el-option v-for="item in listProductos" :key="item.id" :label="item.name"
                                    :value="item.id">
                                  </el-option>
                                </el-select>
                              </td>
                              <td>
                                <el-input-number v-model="item.nStock" controls-position="right" :min="1"
                                  :max="(item.nIdProducto) ? item.nStockFlag : 1"></el-input-number>
                                <!-- :min="item.nStock >= 1 ? 1 : 0" :max="item.nStock >= 1 ? item.nStock : 0"></el-input-number> -->
                              </td>
                              <td> {{ item.fPrecio }} </td>
                              <td> {{ (item.fSubTotal = item.nStock * item.fPrecio) }} </td>
                              <td>
                                <el-tooltip class="item" effect="dark" content="Remover producto" placement="left">
                                  <vs-button icon relief animation-type="rotate" @click="removerProducto(index)">
                                    <i class="fas fa-trash" />
                                    <template #animate>
                                      <i class="fas fa-trash" />
                                    </template>
                                  </vs-button>
                                </el-tooltip>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <el-row :gutter="20">
                        <el-col :span="16">
                          <vs-input border v-model="cComentario" placeholder="Comentario" />
                        </el-col>
                        <el-col :span="8">
                          <strong>Total = </strong> {{ fTotalPedido = totalPedido }}
                        </el-col>
                      </el-row>
                    </template>
                    <template v-else>
                      <div class="callout callout-info">
                        <h5>No se encontraron resultados...</h5>
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
      switchCliente: false,

      cBusqueda: '',
      listClientes: [],
      listClientesFilter: [],

      listProductos: [],
      listPedidos: [],
      cComentario: '',
      fTotalPedido: 0,

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
    totalPedido() {
      return this.listPedidos.reduce(function (valorAnterior, valorActual) {
        return valorAnterior + parseFloat(valorActual.fSubTotal)
      }, 0)
    }
  },
  mounted() {
    this.agregarProducto();
    this.getListarClientes();
    this.getListarProductos();
  },
  methods: {
    querySearch(queryString, cb) {
      var links = this.listClientesFilter;
      var results = queryString ? links.filter(this.createFilter(queryString)) : links;
      // call callback function to return suggestions
      cb(results);
    },
    createFilter(queryString) {
      return (link) => {
        return (link.value.toLowerCase().indexOf(queryString.toLowerCase()) != -1);
      };
    },
    getListarClientes() {
      const ruta = '/operacion/cliente/getListarClientes';
      axios.get(ruta).then(response => {
        this.listClientes = response.data;
        this.filterListarClientes();
      }).catch(error => {
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' })
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      })
    },
    filterListarClientes() {
      let me = this;
      me.listClientesFilter = [];
      me.listClientes.map(function (x, y) {
        me.listClientesFilter.push({
          'value': x.document + ' - ' + x.fullname,
          'link': x.id
        })
      })
    },
    clientSelect(item) {
      let rpta = this.listClientes.filter(cliente => {
        return ((String(cliente.id)).indexOf(String(item.link)) != -1);
      })
      this.fillCrearCliente.nIdCliente = rpta[0].id;
      this.fillCrearCliente.cDocumento = rpta[0].document;
      this.fillCrearCliente.cNombre = rpta[0].name;
      this.fillCrearCliente.cApellido = rpta[0].lastname;
      this.fillCrearCliente.cEmail = rpta[0].email;
      this.fillCrearCliente.cTelefono = rpta[0].phone;
    },

    getListarProductos() {
      const ruta = '/configuracion/producto/getListarProductos'
      axios.get(ruta).then(response => {
        this.listProductos = response.data;
      }).catch(error => {
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' })
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      })
    },

    limpiarCriterios() {
      this.fillCrearCliente.nIdCliente = '';
      this.cBusqueda = '';
      this.fillCrearCliente.cDocumento = '';
      this.fillCrearCliente.cNombre = '';
      this.fillCrearCliente.cApellido = '';
      this.fillCrearCliente.cEmail = '';
      this.fillCrearCliente.cTelefono = '';
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    agregarProducto() {
      let me = this;

      if (this.listPedidos.length == 0) {
        this.listPedidos.push({
          'nIdProducto': '',
          'nStock': '',
          'nStockFlag': '',
          'fPrecio': '',
          'fSubTotal': ''
        })
      } else {
        let contador = 0;
        this.listPedidos.map(function (x, y) {
          if (!x.nIdProducto || !x.nStock || !x.fPrecio || !x.fSubTotal) {
            contador++;
            //Notificacion
            const noti = me.$vs.notification({
              square: false,
              color: 'warn',
              title: 'Alerta',
              text: 'Debe completar la información de la fila = ' + (y + 1)
            })
          }
        })
        if (contador == 0) {
          this.listPedidos.push({
            'nIdProducto': '',
            'nStock': '',
            'nStockFlag': '',
            'fPrecio': '',
            'fSubTotal': ''
          })
        }
      }

    },
    removerProducto(index) {
      this.$delete(this.listPedidos, index);
    },
    obtenerProducto(nIdProducto, index) {
      let me = this;

      if (!nIdProducto) {
        Vue.nextTick(function () {
          me.listPedidos[index].nStock = '';
          me.listPedidos[index].nStockFlag = '';
          me.listPedidos[index].fPrecio = '';
        })
      }

      let contador = 0;
      this.listPedidos.map(function (x, y) {
        if (x.nIdProducto == nIdProducto && y != index) {
          contador++;
          //Notificacion
          const noti = me.$vs.notification({
            square: false,
            color: 'warn',
            title: 'Alerta',
            text: 'El producto ya se encuentra en  la fila ' + (y + 1)
          })
        }
      });
      if (contador == 0) {
        let rpta = this.listProductos.filter(producto => {
          return ((String(producto.id)).indexOf(String(nIdProducto)) != -1);
        })
        if (rpta[0].stock > 0) {
          this.listPedidos[index].nStock = rpta[0].stock;
          this.listPedidos[index].nStockFlag = rpta[0].stock;
          this.listPedidos[index].fPrecio = rpta[0].price;
        } else {
          this.listPedidos[index].nIdProducto = '';
          //Notificacion
          const noti = me.$vs.notification({
            color: 'warn',

            title: 'Alerta',
            text: 'El producto producto seleccionado no cuenta con stock disponible'
          })
        }
      } else {
        this.listPedidos[index].nIdProducto = '';
      }
    },
    filterPermisosByRol() {
      let me = this;
      me.listPedidos.map(function (x) {
        me.listClientesFilter.push(
          {
            'id': x.id,
            'name': x.name,
            'slug': x.slug,
            'checked': false,
          }
        );
      });
    },
    setRegistrarPedido() {
      console.log("set registrar pedido")
      if (this.validarRegistrarPedido()) {
        this.modalShow = true;
        console.log("error")
        return;
      }

      const loading = this.$vs.loading({
        type: 'square',
        color: '#ff0000',
        text: "Cargando",
      });

      if (this.switchCliente) {
        console.log("switch true")
        this.setRegistrarCliente(loading);
      }
      else{
        console.log("switch false")
        this.setGuardarPedido(this.fillCrearCliente.nIdCliente, loading);
      }


    },
    setRegistrarCliente(loading) {
      console.log("registrar cliente")
      const url = '/operacion/cliente/setRegistrarCliente';
      axios.post(url, {
        'cDocumento': this.fillCrearCliente.cDocumento,
        'cNombre': this.fillCrearCliente.cNombre,
        'cApellido': this.fillCrearCliente.cApellido,
        'cEmail': this.fillCrearCliente.cEmail,
        'cTelefono': this.fillCrearCliente.cTelefono,
      }).then(res => {
        console.log(res.data);
        let nIdCliente = res.data[0].nIdCliente;
        this.setGuardarPedido(nIdCliente, loading);
      }).catch(error => {
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    setGuardarPedido(nIdCliente, loading) {
      console.log("Guardar pedido idcliente: " + nIdCliente);
      console.log("Total pedido cca: " + this.fTotalPedido);
      const url = '/operacion/pedido/setRegistrarPedido';
      axios.post(url, {
        'nIdCliente': nIdCliente,
        'cComentario': this.cComentario,
        'fSubtotal': this.fTotalPedido,
        'listPedido': this.listPedidos,
      }).then(res => {
        this.setGenerarDocumento(res.data, loading);
      }).catch(error => {
        if (error.res.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    setGenerarDocumento(nIdPedido, loading) {
      var config = {
        responseType: 'blob'
      };

      const url = '/operacion/pedido/setGenerarDocumento';
      axios.post(url, {
        'nIdPedido': nIdPedido,
      }, config).then(res => {
        console.log(res.data);
        
        const url = URL.createObjectURL(
          new Blob([res.data], { type: 'application/pdf' })
        );
        window.open(url);

        loading.close();

        this.$router.push('/pedidos');

      }).catch(error => {
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          setTimeout(() => {
            loading.close()
          }, 3000)
        }
      });
    },
    validarRegistrarPedido() {
      this.error = 0;
      this.mensajeError = [];

      if (this.switchCliente) {
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
      } else {
        if (!this.fillCrearCliente.nIdCliente) {
          this.mensajeError.push("El Cliente es necesario cargar")
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
