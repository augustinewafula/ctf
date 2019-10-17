<template>
  <div>
    <div class="row">
      <div class="col-md-8 col-sm-12">
        <button class="btn btn-primary mb-3" @click="showSubmitFlagModal = true">Submit flag</button>
        <card class="card" :header-classes="{'text-right': isRTL}">
          <a href="#" @click.prevent="refreshUsers()"><i class="tim-icons icon-refresh-02" :class="refreshingAnimation" style="font-size: 25px; margin-bottom: 10px"></i></a>          
          <vue-good-table
          :columns="columns"
          :rows="rows"
          :row-style-class="rowStyleClassFn"
          theme="nocturnal"
          :sort-options="{
            enabled: true,
            initialSortBy: {field: 'points', type: 'desc'}
          }"
          :search-options="{
            enabled: true
          }"
          :pagination-options="{
            enabled: true,
            perPage: 10,
          }">
            <div slot="emptystate">
              <div v-show="isLoading" class="progress-horizontal">
                <div class="bar-horizontal"></div>
              </div>
              <span v-show="!isLoading">
                There are no users
              </span>
            </div>
          </vue-good-table>
        </card>
      </div>
    </div>
    <div v-if="showSubmitFlagModal">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Submit Flag</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" @click="showSubmitFlagModal = false">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form v-on:submit.prevent="onSubmit">
                    <div class="input-group mb-6">
                      <input type="text" name="" v-model="form.flag" :class="{ 'is-invalid': form.errors.has('flag') }" class="form-control input_user" value="" placeholder="flag" required>
                                    <has-error :form="form" field="flag"></has-error>
                    </div>
                    <div class="input-group mb-6">                            
                        <button type="submit" style="margin-top: 20px" name="button" class="btn login_btn">
                            <div v-show="isLoading" class="ld ld-ring ld-spin"></div>
                            <span v-show="!isLoading">Submit</span>
                        </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>
<style>
@import 'https://loading.io/css/loading.css';
@import 'https://loading.io/css/loading-btn.css';    
</style>
<script>
  import config from '@/config';  
  import axios from 'axios';
  import moment from 'moment';

  export default {
    data() {
      return {
        showSubmitFlagModal: false,
        isLoading : false,
        isLoading : true,
        isRefreshing : false,
        refreshingAnimation : '',
        form: new Form({
            flag: ''
        }),
        columns: [
        {
          label: 'Name',
          field: 'name',
        },
        {
          label: 'Email',
          field: 'email',
        },
        {
          label: 'Points',
          field: 'points',
          type: 'number',
        },
      ],
      rows: [],
      }
    },
    computed: {
      enableRTL() {
        return this.$route.query.enableRTL;
      },
      isRTL() {
        return this.$rtl.isRTL;
      }
    },
    methods: {
      getUsers () {
        // Make a request for rows
        axios.get('/users')
          .then((response)=>{
            // handle success
            this.rows = response.data
            this.isRefreshing = false
          })
          .catch((error)=>{
            // handle error
            console.log(error);
            this.isRefreshing = false
          })
      },
      rowStyleClassFn(row) {
        return row.email == localStorage.email ? 'selected' : 'nothing';
      },
      refreshUsers () {
        console.log('refreshing...')
        this.isRefreshing = true
        this.getUsers ()

      },
    },
    watch : {
      isRefreshing(){
        if (this.isRefreshing) {
          this.refreshingAnimation = 'rotating'
        }  else{
          this.refreshingAnimation = ''
        }     
      },
      rows(){
        this.isLoading = false
      }
    },
    mounted() {
      this.i18n = this.$i18n;
      if (this.enableRTL) {
        this.i18n.locale = 'ar';
        this.$rtl.enableRTL();
      }
      this.getUsers()
    },
    beforeDestroy() {
      if (this.$rtl.isRTL) {
        this.i18n.locale = 'en';
        this.$rtl.disableRTL();
      }
    }
  };
</script>
<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}
.progress-horizontal {
  display: block;
  width: 100%;
  height: 4px;
  margin: 0;
  background-color: rgba(0,0,0,0.34);
  overflow: hidden;
}

.selected {
  background-color: rgb(16, 24, 20)
}

.bar-horizontal {
  width: 50%;
  height: 100%;
  margin-left: 0;
  background-color: #3EAA78;
  
  animation-name: spinner;
  animation-duration: 2000ms;
  animation-timing-function: ease-in-out;
  animation-play-state: running;
  animation-direction: normal;
  animation-iteration-count: infinite;
}

@keyframes spinner {
  0% {
    width: 0;
  }
  50% {
    width: 100%;
    margin-left: 50%;
  }
  100% {
    width: 0;
    margin-left: 100%;
  }
}
table.vgt-table{
  font-family: Poppins,sans-serif;
  font-size: .875rem !important;
}
.fixed-content {
    position:sticky ;
    top: 60px;
   
}
.v--modal{
  background-color: #27293D !important;
}
.first-to-upper:first-letter{
  text-transform: uppercase;
}
</style>

<style scoped>
.game-info span{
  margin-left: 4px;
}
.rotating {
  -webkit-animation: rotation 2s infinite linear;
  animation: rotation 2s infinite linear;
}
@keyframes rotation{
  from {
				-webkit-transform: rotate(0deg);
		}
		to {
				-webkit-transform: rotate(359deg);
		}
}
@-webkit-keyframes rotation {
		from {
				-webkit-transform: rotate(0deg);
		}
		to {
				-webkit-transform: rotate(359deg);
		}
}
</style>
