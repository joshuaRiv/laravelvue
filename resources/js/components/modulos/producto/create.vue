<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Crear Producto</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="`/productos`">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Formulario Registrar Producto
                </h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: Administrador">Nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillCrearProducto.cNombre" @keyup.enter="setRegistrarProducto" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: rol.ejemplo">Descripción</label>
                        <div class="col-md-9">
                          <input v-model="fillCrearProducto.cDescripcion" @keyup.enter="setRegistrarProducto"
                            type="text" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: rol.ejemplo">Stock</label>
                        <div class="col-md-9">
                            <el-input-number v-model="fillCrearProducto.nStock" controls-position="right" @keyup.enter="setRegistrarProducto" :min="1"></el-input-number>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: rol.ejemplo">Precio</label>
                        <div class="col-md-9">
                            <el-input-number v-model="fillCrearProducto.fPrecio" controls-position="right" @keyup.enter="setRegistrarProducto" :min="1"></el-input-number>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Categoria</label>
                        <div class="col-md-9">
                          <el-select v-model="fillCrearProducto.nIdCategoria" placeholder="Seleccione una categoria"
                            clearable>
                            <el-option v-for="item in listCategorias" :key="item.id" :label="item.name"
                              :value="item.id">
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btnWidth btn-info btn-flat" @click="setRegistrarProducto" v-loading.fullscreen.lock="fullscreenLoading">Registrar</button>
                    <button class="btn btnWidth btn-flat btn-default" @click="limpiarCriteriosBsq">Limpiar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="toast-container top-0 end-0 p-2" v-for="error in mensajeError" :key="error">
      <div class="toast fade bg-primary top-0 end-0" :class="{ show: modalShow }"
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
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      fillCrearProducto: {
        cNombre: '',
        cDescripcion: '',
        nStock: 1,
        fPrecio: 1,
        nIdCategoria: '',
      },
      listCategorias: [],
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
    this.getListarCategorias();
  },
  methods: {
    limpiarCriteriosBsq() {
      this.fillCrearProducto.cNombre = '';
      this.fillCrearProducto.cDescripcion = '';
      this.fillCrearProducto.nStock = 1;
      this.fillCrearProducto.fPrecio = 1;
      this.fillCrearProducto.nIdCategoria = '';
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    getListarCategorias() {
      this.fullscreenLoading = true;

      const url = '/configuracion/categoria/getListarCategorias';
      axios.get(url).then(res => {
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
    setRegistrarProducto() {
      if (this.validarRegistrarProducto()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;

      const url = '/configuracion/categoria/setRegistrarProducto';

      axios.post(url, {
        'cNombre': this.fillCrearProducto.cNombre,
        'cDescripcion': this.fillCrearProducto.cDescripcion,
        'nStock': this.fillCrearProducto.nStock,
        'fPrecio': this.fillCrearProducto.fPrecio,
        'nIdCategoria': this.fillCrearProducto.nIdCategoria,
      }).then(res => {
        this.fullscreenLoading = false;
        this.$router.push('/productos');
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
    validarRegistrarProducto() {
      this.error = 0;
      this.mensajeError = [];

      if (!this.fillCrearProducto.cNombre) {
        this.mensajeError.push("El Nombre es un campo obligatorio");
      }
      if (!this.fillCrearProducto.nIdCategoria) {
        this.mensajeError.push("La categoría campo obligatorio");
      }

      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    }
  }
}
</script>