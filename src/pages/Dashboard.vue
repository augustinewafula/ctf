<template>
  <div>
    <div class="row">
      <div class="col-md-8 col-sm-12">
        <card class="card" :header-classes="{'text-right': isRTL}">
          <a href="#" @click.prevent="refreshUsers()"><i class="tim-icons icon-refresh-02" :class="refreshingAnimation" style="font-size: 25px; margin-bottom: 10px"></i></a>          
          <vue-good-table
          :columns="columns"
          :rows="rows"
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
  </div>
</template>
<script>
  import config from '@/config';  
  import axios from 'axios';
  import moment from 'moment';

  export default {
    data() {
      return {
        isLoading : true,
        isRefreshing : false,
        refreshingAnimation : '',
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
          field: 'ponits',
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
.progress-horizontal {
  display: block;
  width: 100%;
  height: 4px;
  margin: 0;
  background-color: rgba(0,0,0,0.34);
  overflow: hidden;
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
