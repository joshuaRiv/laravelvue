<template>
  <div>
    <Navbar :ruta="ruta" :usuario="authUser" :listPermisos="listRolPermisosByUsuario" />
    <Sidebar :ruta="ruta" :usuario="authUser" :listPermisos="listRolPermisosByUsuario" />

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
      listRolPermisosByUsuario: [],
    }
  },
  mounted() {
    // console.log(JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario')));
    this.listRolPermisosByUsuario = JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario'));
    
    EventBus.$on('verifyAuthenticatedUser', data => {
      this.authUser = data;
    });
    EventBus.$on('notifyRolPermisosByUsuario', data => {
      this.listRolPermisosByUsuario = data;
    });
  },
}
</script>

<style>

</style>