<template>
  <div>
    <Navbar :ruta="ruta" />
    <Sidebar :ruta="ruta" :usuario="authUser" />

    <!-- Routes -->
    <div class="content-wrapper">
      <transition name="slide-fade" mode="out-in">
        <router-view></router-view>
      </transition>
    </div>

    <!-- <Content :ruta="ruta" /> -->
    <Footer :ruta="ruta" />
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
  </div>
</template>

<script>
import Navbar from './plantilla/Navbar';
import Sidebar from './plantilla/Sidebar';
import Footer from './plantilla/Footer';
import { EventBus } from '../app';

export default {
  props: ['ruta', 'usuario'],
  components: {
    Navbar,
    Sidebar,
    Footer,
  },
  data(){
    return {
      authUser: this.usuario,
    }
  },
  mounted() {
    EventBus.$on('verifyAuthenticatedUser', data => {
      console.log('Evento ejecutado desde el Componente App.vue');
      console.log(data);
      this.authUser = data;
    });
  },
}
</script>

<style>

</style>