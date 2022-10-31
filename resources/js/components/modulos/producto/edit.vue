<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Editar Producto</h1>
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
                  Formulario Editar Producto
                </h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: Administrador">Nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarProducto.cNombre" @keyup.enter="setEditarProducto" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: rol.ejemplo">Descripción</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarProducto.cDescripcion" @keyup.enter="setEditarProducto"
                            type="text" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: rol.ejemplo">Stock</label>
                        <div class="col-md-9">
                            <el-input-number v-model="fillEditarProducto.nStock" controls-position="right" @keyup.enter="setEditarProducto" :min="1"></el-input-number>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: rol.ejemplo">Precio</label>
                        <div class="col-md-9">
                            <el-input-number v-model="fillEditarProducto.fPrecio" controls-position="right" @keyup.enter="setEditarProducto" :min="1"></el-input-number>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label">Categoria</label>
                        <div class="col-md-9">
                          <el-select v-model="fillEditarProducto.nIdCategoria" placeholder="Seleccione una categoria"
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
                    <button class="btn btnWidth btn-info btn-flat" @click="setEditarProducto" v-loading.fullscreen.lock="fullscreenLoading">Editar</button>
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
      fillEditarProducto: {
        nIdProducto: this.$attrs.id,
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
    this.getListarProductos();
  },
  methods: {
    limpiarCriteriosBsq() {
      this.fillEditarProducto.cNombre = '';
      this.fillEditarProducto.cDescripcion = '';
      this.fillEditarProducto.nStock = 1;
      this.fillEditarProducto.fPrecio = 1;
      this.fillEditarProducto.nIdCategoria = '';
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
        if (error.code == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    getListarProductos() {
      this.fullscreenLoading = true;

      const url = '/configuracion/producto/getListarProductos';
      axios.get(url, {
        params: {
          nIdProducto: this.fillEditarProducto.nIdProducto,
        }
      }).then(res => {
        this.fillEditarProducto.cNombre = res.data[0].name;
        this.fillEditarProducto.cDescripcion = res.data[0].description;
        this.fillEditarProducto.nStock = res.data[0].stock;
        this.fillEditarProducto.fPrecio = res.data[0].price;
        this.fillEditarProducto.nIdCategoria = res.data[0].nidcategoria;
        this.fullscreenLoading = false;
      }).catch(error => {
        console.log(error.response);
        if (error.code == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    setEditarProducto() {
      if (this.validarEditarProducto()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;

      const url = '/configuracion/categoria/setEditarProducto';

      axios.post(url, {
        'nIdProducto': this.fillEditarProducto.nIdProducto,
        'cNombre': this.fillEditarProducto.cNombre,
        'cDescripcion': this.fillEditarProducto.cDescripcion,
        'nStock': this.fillEditarProducto.nStock,
        'fPrecio': this.fillEditarProducto.fPrecio,
        'nIdCategoria': this.fillEditarProducto.nIdCategoria,
      }).then(res => {
        this.fullscreenLoading = false;
        Swal.fire({
          icon: 'success',
          title: 'Se actualizó el producto correctamente',
          showConfirmButton: false,
          timer: 1500
        });
      }).catch(error => {
        console.log(error.response);
        if (error.code == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    validarEditarProducto() {
      this.error = 0;
      this.mensajeError = [];

      if (!this.fillEditarProducto.cNombre) {
        this.mensajeError.push("El Nombre es un campo obligatorio");
      }
      if (!this.fillEditarProducto.nStock) {
        this.mensajeError.push("El Stock es un campo obligatorio");
      }
      if (!this.fillEditarProducto.fPrecio) {
        this.mensajeError.push("El Precio es un campo obligatorio");
      }
      if (!this.fillEditarProducto.nIdCategoria) {
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