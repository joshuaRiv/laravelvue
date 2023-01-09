<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Cliente</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <template v-if="listRolPermisosByUsuario.includes('cliente.crear')">
              <router-link class="btn btn-info btn-sm" :to="{ name: 'cliente.crear' }">
                <i class="fas fa-plus-square"></i> Nuevo Cliente
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
                          <input v-model="fillBsqCliente.cNombre" @keyup.enter="getListarClientes" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">No. Documento</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqCliente.cDocumento" @keyup.enter="getListarClientes" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row justify-content-center">
                  <button class="btn w-25 btn-info btn-flat" @click="getListarClientes"
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
                <template v-if="listarClientesPaginated.length">
                  <table class="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listarClientesPaginated" :key="index">
                        <td> {{ item.fullname }} </td>
                        <td> {{ item.email }} </td>
                        <td> {{ item.phone }} </td>
                        <td>
                          <template v-if="listRolPermisosByUsuario.includes('cliente.editar')">
                            <router-link class="btn btn-flat btn-info btn-sm"
                                :to="{ name: 'pedido.editar', params: { id: item.id } }">
                                <i class="fas fa-pencil-alt"></i> Editar
                              </router-link>
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
                fillBsqCliente</template>
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
      fillBsqCliente: {
        cNombre: '',
        cDocumento: '',
      },
      listClientes: [],
      listRolPermisosByUsuario: JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario')),
      pageNumber: 0,
      perPage: 5,
      fullscreenLoading: false,
    }
  },
  computed: {
    //obtener el numero de paginas
    pageCount() {
      let a = this.listClientes.length,
        b = this.perPage;
      return Math.ceil(a / b);
    },
    // 1:09 - 37 listar productos
    listarClientesPaginated() {
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listClientes.slice(inicio, fin);
    },
    pagesList() {
      let a = this.listClientes.length,
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
    this.getListarClientes();
  },
  methods: {
    limpiarCriteriosBsq() {
      this.fillBsqCliente.cNombre = '';
      this.fillBsqCliente.cDocumento = '';
    },
    getListarClientes() {
      this.fullscreenLoading = true;

      const url = '/operacion/cliente/getListarClientes';
      axios.get(url, {
        params: {
          'cNombre': this.fillBsqCliente.cNombre,
          'cDocumento': this.fillBsqCliente.cDocumento,
        }
      }).then(res => {
        this.inicializarPaginacion();
        this.listClientes = res.data;
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