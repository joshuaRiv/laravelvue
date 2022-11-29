<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Categorías</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <template v-if="listRolPermisosByUsuario.includes('categoria.crear')">
              <router-link class="btn btn-info btn-sm" :to="{ name: 'categoria.crear' }">
                <i class="fas fa-plus-square"></i> Nueva categoria
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
                          <input v-model="fillBsqCategoria.cNombre" @keyup.enter="getListarCategorias" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Descripción</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqCategoria.cDescripcion" @keyup.enter="getListarCategorias" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row justify-content-center">
                  <button class="btn w-25 btn-info btn-flat" @click="getListarCategorias"
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
                <template v-if="listarCategoriasPaginated.length">
                  <table class="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(categoria, index) in listarCategoriasPaginated" :key="index">
                        <td> {{ categoria.name }} </td>
                        <td> {{ categoria.description }} </td>
                        <td>
                          <template v-if="listRolPermisosByUsuario.includes('categoria.editar')">
                            <router-link class="btn btn-flat btn-info btn-sm"
                              :to="{ name: 'categoria.editar', params: { id: categoria.id } }">
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
      fillBsqCategoria: {
        cNombre: '',
        cDescripcion: '',
      },
      listCategorias: [],
      listRolPermisosByUsuario: JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario')),
      pageNumber: 0,
      perPage: 5,
      fullscreenLoading: false,
    }
  },
  mounted() {
    this.getListarCategorias();
  },
  computed: {
    //obtener el numero de paginas
    pageCount() {
      let a = this.listCategorias.length,
        b = this.perPage;
      return Math.ceil(a / b);
    },
    listarCategoriasPaginated() {
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listCategorias.slice(inicio, fin);
    },
    pagesList() {
      let a = this.listCategorias.length,
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
  methods: {
    limpiarCriteriosBsq() {
      this.fillBsqCategoria.cNombre = '';
      this.fillBsqCategoria.cDescripcion = '';
    },
    limpiarBandejaUsuarios() {
      this.listCategorias = [];
    },
    getListarCategorias() {
      this.fullscreenLoading = true;

      const url = '/configuracion/categoria/getListarCategorias';
      axios.get(url, {
        params: {
          cNombre: this.fillBsqCategoria.cNombre,
          cDescripcion: this.fillBsqCategoria.cDescripcion,
        }
      }).then(res => {
        this.inicializarPaginacion();
        this.listCategorias = res.data;
        this.fullscreenLoading = false;
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