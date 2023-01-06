<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Pedido</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <template v-if="listRolPermisosByUsuario.includes('pedido.crear')">
              <router-link class="btn btn-info btn-sm" :to="{ name: 'pedido.crear' }">
                <i class="fas fa-plus-square"></i> Nuevo Pedido
              </router-link>
            </template>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Criterios de búsqueda
                </h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqPedido.cNombre" @keyup.enter="getListarPedidos" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">No. Documento</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqPedido.cDocumento" @keyup.enter="getListarPedidos" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">No. Pedido</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqPedido.cDocumento" @keyup.enter="getListarPedidos" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Estado</label>
                        <div class="col-md-9">
                          <el-select v-model="fillBsqPedido.nIdEstado" placeholder="Seleccione un estado" clearable>
                            <!-- modificar value y label -->
                            <el-option v-for="item in listEstados" :key="item.value" :label="item.label"
                              :value="item.value">
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row justify-content-center">
                  <button class="btn w-25 btn-info btn-flat" @click="getListarPedidos"
                    v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                  <button class="btn w-25 btn-flat btn-default" @click="limpiarCriteriosBsq">Limpiar</button>
                </div>
              </div>
            </div>
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Bandeja de resultados
                </h3>
              </div>
              <div class="card-body table-responsive">
                <template v-if="listarPedidosPaginated.length">
                  <table class="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>No. Pedido</th>
                        <th>No. Documento</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Vendedor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listarPedidosPaginated" :key="index">
                        <td> {{ item.pedido }} </td>
                        <td> {{ item.documento }} </td>
                        <td> {{ item.cliente }} </td>
                        <td> {{ item.total }} </td>
                        <td> {{ item.vendedor }} </td>
                        <td> {{ item.estado }} </td>
                        <td>
                          <template v-if="listRolPermisosByUsuario.includes('pedido.ver')">
                            <button class="btn btn-flat btn-info btn-sm" @click.prevent="setGenerarDocumento(item.id)">
                              <i class="far fa-file-pdf"></i> Ver PDF
                            </button>
                          </template>
                          <template v-if="listRolPermisosByUsuario.includes('pedido.rechazar')">
                            <button class="btn btn-flat btn-danger btn-sm">
                              <i class="fas fa-trash"></i> Rechazar
                            </button>
                          </template>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                      <li class="page-item" v-if="pageNumber > 0">
                        <a href="#" class="page-link" @click.prevent="prevPage">Ant</a>
                      </li>
                      <li class="page-item" v-for="(pagina, index) in pagesList" :key="index"
                        :class="[pagina == pageNumber ? 'active' : '']">
                        <a href="#" class="page-link" @click.prevent="selectPage(pagina)">{{ pagina + 1 }}</a>
                      </li>
                      <li class="page-item" v-if="pageNumber < pageCount - 1">
                        <a href="#" class="page-link" @click.prevent="nextPage">Post</a>
                      </li>
                    </ul>
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
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      fillBsqPedido: {
        cNombre: '',
        cDocumento: '',
        cPedido: '',
        nIdEstado: '',
      },
      listPedidos: [],
      listEstados: [
        { value: 'A', label: 'Activo' },
        { value: 'I', label: 'Inactivo' },
      ],
      listRolPermisosByUsuario: JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario')),
      pageNumber: 0,
      perPage: 5,
      fullscreenLoading: false,
    }
  },
  computed: {
    //obtener el numero de paginas
    pageCount() {
      let a = this.listPedidos.length,
        b = this.perPage;
      return Math.ceil(a / b);
    },
    // 1:09 - 37 listar productos
    listarPedidosPaginated() {
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listPedidos.slice(inicio, fin);
    },
    pagesList() {
      let a = this.listPedidos.length,
        b = this.perPage;
      let pageTotal = Math.ceil(a / b);

      let count = 0, pagesArray = [];

      while (count < pageTotal) {
        pagesArray.push(count);
        count++;
      }

      return pagesArray;
    },
  },
  mounted(){
    this.getListarPedidos();
  },
  methods: {
    limpiarCriteriosBsq() {
      this.fillBsqPedido.cNombre = '';
      this.fillBsqPedido.cDocumento = '';
      this.fillBsqPedido.cPedido = '';
      this.fillBsqPedido.nIdEstado = '';
    },
    limpiarBandejaUsuarios() {
      this.listPedidos = [];
    },
    getListarPedidos() {
      this.fullscreenLoading = true;

      const url = '/operacion/pedido/getListarPedidos';
      axios.get(url, {
        params: {
          'cNombre': this.fillBsqPedido.cNombre,
          'cDocumento': this.fillBsqPedido.cDocumento,
          'cPedido': this.fillBsqPedido.cPedido,
          'nIdEstado': this.fillBsqPedido.nIdEstado,
        }
      }).then(res => {
        this.inicializarPaginacion();
        this.listPedidos = res.data;
        this.fullscreenLoading = false;
      }).catch(error => {
        if (error.response.status == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    setGenerarDocumento(nIdPedido) {
      const loading = this.$vs.loading({
        type: 'square',
        color: '#ff0000',
        text: "Cargando",
      });
      // 7:22 udemy pt1
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

        loading.close()
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
    nextPage() {
      this.pageNumber++;
    },
    prevPage() {
      this.pageNumber--;
    },
    selectPage(pagina) {
      this.pageNumber = pagina;
    },
    inicializarPaginacion() {
      this.pageNumber = 0;
    },
  }
}
</script>

<style>
.position-reverse {
  flex-direction: row-reverse !important;
}

.img-max-height {
  max-height: 100px !important;
}
</style>