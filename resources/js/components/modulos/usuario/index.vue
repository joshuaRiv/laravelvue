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
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <template v-if="listRolPermisosByUsuario.includes('usuario.crear')">
              <router-link class="btn btn-info btn-sm" :to="{ name: 'usuario.crear' }">
                <i class="fas fa-plus-square"></i> Nuevo usuario
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
                          <input v-model="fillBsqUsuario.cNombre" @keyup.enter="getListarUsuarios" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Usuario</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqUsuario.cUsuario" @keyup.enter="getListarUsuarios" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Correo Electrónico</label>
                        <div class="col-md-9">
                          <input v-model="fillBsqUsuario.cCorreo" @keyup.enter="getListarUsuarios" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Estado</label>
                        <div class="col-md-9">
                          <el-select v-model="fillBsqUsuario.cEstado" placeholder="Seleccione un estado" clearable>
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
                  <button class="btn btnWidth btn-info btn-flat" @click="getListarUsuarios"
                    v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                  <button class="btn btnWidth btn-flat btn-default" @click="limpiarCriteriosBsq">Limpiar</button>
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
                <template v-if="listarUsuariosPaginated.length">
                  <table class="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>Fotografía</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(usuario, index) in listarUsuariosPaginated" :key="index">
                        <td>
                          <template v-if="!usuario.profile_image">
                            <div>
                              <img src="/img/avatar.png" alt="usuario.username"
                                class="img-fluid img-circle img-max-height" />
                            </div>
                          </template>
                          <template v-else>
                            <div>
                              <img :src="usuario.profile_image" alt="usuario.username"
                                class="img-fluid img-circle img-max-height" />
                            </div>
                          </template>
                        </td>
                        <td> {{ usuario.fullname }} </td>
                        <td> {{ usuario.email }} </td>
                        <td> {{ usuario.username }} </td>
                        <td>
                          <template v-if="usuario.state == 'A'">
                            <span class="badge badge-success">{{ usuario.state_alias }}</span>
                          </template>
                          <template v-else>
                            <span class="badge badge-danger">{{ usuario.state_alias }}</span>
                          </template>
                        </td>
                        <td>
                          <template v-if="listRolPermisosByUsuario.includes('usuario.ver')">
                            <router-link class="btn btn-flat btn-primary btn-sm"
                              :to="{ name: 'usuario.ver', params: { id: usuario.id } }">
                              <i class="fas fa-folder"></i> Ver
                            </router-link>
                          </template>

                          <template v-if="usuario.state == 'A'">
                            <template v-if="listRolPermisosByUsuario.includes('usuario.editar')">
                              <router-link class="btn btn-flat btn-info btn-sm"
                                :to="{ name: 'usuario.editar', params: { id: usuario.id } }">
                                <i class="fas fa-pencil-alt"></i> Editar
                              </router-link>
                            </template>
                            <template v-if="listRolPermisosByUsuario.includes('usuario.permiso')">
                              <router-link class="btn btn-flat btn-success btn-sm"
                                :to="{ name: 'usuario.permiso', params: { id: usuario.id } }">
                                <i class="fas fa-key"></i> Permiso
                              </router-link>
                            </template>
                            <!-- 1 desactivar -->
                            <template v-if="listRolPermisosByUsuario.includes('usuario.desactivar')">
                              <button class="btn btn-flat btn-danger btn-sm"
                                @click="setCambiarEstadoUsuario(1, usuario.id)">
                                <i class="fas fa-trash"></i> Desactivar
                              </button>
                            </template>
                          </template>
                          <template v-else>
                            <!-- 2 activar -->
                            <template v-if="listRolPermisosByUsuario.includes('usuario.activar')">
                              <button class="btn btn-flat btn-danger btn-sm"
                                @click="setCambiarEstadoUsuario(2, usuario.id)">
                                <i class="fas fa-check"></i> Activar
                              </button>
                            </template>
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
      fillBsqUsuario: {
        cNombre: '',
        cUsuario: '',
        cCorreo: '',
        cEstado: '',
      },
      listUsuarios: [],
      listEstados: [
        { value: 'A', label: 'Activo' },
        { value: 'I', label: 'Inactivo' }
      ],
      listRolPermisosByUsuario: JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario')),
      pageNumber: 0,
      perPage: 5,
      fullscreenLoading: false,
    }
  },
  mounted() {
    this.getListarUsuarios();
  },
  computed: {
    //obtener el numero de paginas
    pageCount() {
      let a = this.listUsuarios.length,
        b = this.perPage;
      return Math.ceil(a / b);
    },
    listarUsuariosPaginated() {
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listUsuarios.slice(inicio, fin);
    },
    pagesList() {
      let a = this.listUsuarios.length,
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
      this.fillBsqUsuario.cNombre = '';
      this.fillBsqUsuario.cUsuario = '';
      this.fillBsqUsuario.cCorreo = '';
      this.fillBsqUsuario.cEstado = '';
    },
    limpiarBandejaUsuarios() {
      this.listUsuarios = [];
    },
    getListarUsuarios() {
      this.fullscreenLoading = true;

      const url = '/administracion/usuario/getListarUsuarios';
      axios.get(url, {
        params: {
          cNombre: this.fillBsqUsuario.cNombre,
          cUsuario: this.fillBsqUsuario.cUsuario,
          cCorreo: this.fillBsqUsuario.cCorreo,
          cEstado: this.fillBsqUsuario.cEstado,
        }
      }).then(res => {
        this.inicializarPaginacion();
        this.listUsuarios = res.data;
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
    setCambiarEstadoUsuario(op, id) {
      Swal.fire({
        title: '¿Está seguro de ' + ((op == 1) ? 'desactivar' : 'activar') + ' el usuario?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: (op == 1) ? 'Sí, desactivar' : 'Sí, activar'
      }).then((result) => {
        if (result.isConfirmed) {
          this.fullscreenLoading = true;
          let url = '/administracion/usuario/setCambiarEstadoUsuario';
          axios.post(url, {
            'nIdUsuario': id,
            'cEstado': (op == 1) ? 'I' : 'A',
          }).then(
            response => {
              Swal.fire({
                icon: 'success',
                title: 'Se ' + ((op == 1) ? 'desactivó' : 'activó') + ' el usuario.',
                showConfirmButton: false,
                timer: 1500,
                cancelButtonText: 'Cancelar'
              });
              this.getListarUsuarios();
            }).catch(error => {
              console.log(error.response);
              if (error.response.status == 401) {
                this.$router.push({ name: 'login' });
                location.reload();
                sessionStorage.clear();
                this.fullscreenLoading = false;
              }
            });
        }
      })
    }
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