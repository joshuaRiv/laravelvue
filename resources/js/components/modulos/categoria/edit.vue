<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Editar Categoria</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info btn-sm" :to="`/categorias`">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Formulario Editar Categoría
                </h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: Administrador">Nombre</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarCategoria.cNombre" @keyup.enter="setEditarCategoria" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group-row">
                        <label class="col-md-3 col-form-label" placeholder="Ej: rol.ejemplo">Descripción</label>
                        <div class="col-md-9">
                          <input v-model="fillEditarCategoria.cDescripcion" @keyup.enter="setEditarCategoria" type="text"
                            class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btnWidth btn-info btn-flat" @click="setEditarCategoria"
                      v-loading.fullscreen.lock="fullscreenLoading">Editar</button>
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
import { shallowReadonly } from 'vue';

export default {
  data() {
    return {
      fillEditarCategoria: {
        nIdCategoria: this.$attrs.id,
        cNombre: '',
        cDescripcion: '',
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
      mensajeError: [],
    }
  },
  mounted() {
    this.getListarCategorias();
  },
  methods: {
    limpiarCriteriosBsq() {
      this.fillEditarCategoria.cNombre = '';
      this.fillEditarCategoria.cDescripcion = '';
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    getListarCategorias() {
      this.fullscreenLoading = true;

      const url = '/configuracion/categoria/getListarCategorias';
      axios.get(url, {
        params: {
          'nIdCategoria': this.fillEditarCategoria.nIdCategoria,
        }
      }).then(res => {
        this.fillEditarCategoria.cNombre = res.data[0].name;
        this.fillEditarCategoria.cDescripcion = res.data[0].description;
        this.fullscreenLoading = false;
      }).catch(error => {
        console.log('a');
        console.log(error.response);
        if (error.code == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    setEditarCategoria() {
      if (this.validarEditarCategoria()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;

      const url = '/configuracion/categoria/setEditarCategoria';
      
      axios.post(url, {
        'nIdCategoria': this.fillEditarCategoria.nIdCategoria,
        'cNombre': this.fillEditarCategoria.cNombre,
        'cDescripcion': this.fillEditarCategoria.cDescripcion,
      }).then(res => {
        this.fullscreenLoading = false;
        Swal.fire({
          icon: 'success',
          title: 'Se actualizó la categoría correctamente',
          showConfirmButton: false,
          timer: 1500
        })
      }).catch(error=>{
        console.log('b');
        console.log(error.response);
        if (error.code == 401) {
          this.$router.push({ name: 'login' });
          location.reload();
          sessionStorage.clear();
          this.fullscreenLoading = false;
        }
      });
    },
    validarEditarCategoria() {
      this.error = 0;
      this.mensajeError = [];

      if (!this.fillEditarCategoria.cNombre) {
        this.mensajeError.push("El Nombre es un campo obligatorio");
      }
      if (!this.fillEditarCategoria.cDescripcion) {
        this.mensajeError.push("La Url Amigable es un campo obligatorio");
      }

      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    }
  }
}
</script>