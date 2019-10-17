<template>
  <nav class="navbar navbar-expand-lg navbar-absolute"
       :class="{'bg-white': showMenu, 'navbar-transparent': !showMenu}">
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <div class="navbar-toggle d-inline" :class="{toggled: $sidebar.showSidebar}">
          <button type="button"
                  class="navbar-toggler"
                  aria-label="Navbar toggle button"
                  @click="toggleSidebar">
            <span class="navbar-toggler-bar bar1"></span>
            <span class="navbar-toggler-bar bar2"></span>
            <span class="navbar-toggler-bar bar3"></span>
          </button>
        </div>
        <a class="navbar-brand" href="#pablo">{{routeName}}</a>
      </div>
      <button class="navbar-toggler" type="button"
              @click="toggleMenu"
              data-toggle="collapse"
              data-target="#navigation"
              aria-controls="navigation-index"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-bar navbar-kebab"></span>
        <span class="navbar-toggler-bar navbar-kebab"></span>
        <span class="navbar-toggler-bar navbar-kebab"></span>
      </button>

      <collapse-transition>
        <div class="collapse navbar-collapse show" v-show="showMenu">
          <ul class="navbar-nav" :class="$rtl.isRTL ? 'mr-auto' : 'ml-auto'">
            <base-dropdown tag="li"
                           :menu-on-right="!$rtl.isRTL"
                           title-tag="a"
                           class="nav-item"
                           menu-classes="dropdown-navbar">
              <a slot="title" href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="true">
                <div class="photo">
                  <img src="img/anime3.png">
                </div>
                <b class="caret d-none d-lg-block d-xl-block"></b>
                <p class="d-lg-none">
                  Log out
                </p>
              </a>              
              <li class="nav-link">
                <a href="#" @click="profile" class="nav-item dropdown-item">Profile</a>
              </li>
              <li class="nav-link">
                <a href="#" @click.prevent="logout()" class="nav-item dropdown-item">Log out</a>
              </li>
            </base-dropdown>
          </ul>
        </div>
      </collapse-transition>
    </div>
    <div v-if="showProfileModal">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Your Profile</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="showProfileModal = false">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <card type="user">
                    <p class="card-text">
                    </p>
                    <div class="author">
                      <div class="block block-one"></div>
                      <div class="block block-two"></div>
                      <div class="block block-three"></div>
                      <div class="block block-four"></div>
                      <a href="#">
                        <img class="avatar" src="img/anime3.png" alt="...">
                        <h5 class="title">{{userInfo.name}}</h5>
                      </a>
                      <h5 class="title">
                        {{userInfo.email}}
                      </h5>
                    </div>
                    <p></p>
                    <p class="card-description">
                      Points: {{userInfo.points}}
                    </p>
                  </card>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </nav>
</template>
<script>
  import { CollapseTransition } from 'vue2-transitions';
  import Modal from '@/components/Modal';
  import axios from 'axios'

  export default {
    components: {
      CollapseTransition,
      Modal
    },
    computed: {
      routeName() {
        const { name } = this.$route;
        return this.capitalizeFirstLetter(name);
      },
      isRTL() {
        return this.$rtl.isRTL;
      }
    },
    data() {
      return {
        userInfo: [],
        showProfileModal: false,
        activeNotifications: false,
        showMenu: false,
        searchModalVisible: false,
        searchQuery: ''
      };
    },
    methods: {
      getCurrentUserInfo(){
        axios.get('/auth/user/'+localStorage.email)
          .then((response)=>{
            // handle success
            this.userInfo = response.data
          })
          .catch((error)=>{
            // handle error
            console.log(error);
          })

      },
      profile(){
        this.showProfileModal = true
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      toggleNotificationDropDown() {
        this.activeNotifications = !this.activeNotifications;
      },
      closeDropDown() {
        this.activeNotifications = false;
      },
      toggleSidebar() {
        this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
      },
      hideSidebar() {
        this.$sidebar.displaySidebar(false);
      },
      toggleMenu() {
        this.showMenu = !this.showMenu;
      },
      logout() {
        // var token = localStorage.token
        // axios.post('auth/logout', {
        //   token: this.token,
        // })
        // .then(function (response) {
        //   console.log(response);
          localStorage.removeItem('token')
          this.$router.push({path: '/login'})
        // })
        // .catch(function (error) {
        //   console.log(error);
        // });
        
      }
    },
    mounted() {
      this.getCurrentUserInfo()
    },
  };
</script>
<style>
</style>
