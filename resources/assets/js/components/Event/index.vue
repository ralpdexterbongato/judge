<template lang="html">
<div class="">
  <div class="previous-events-container">
    <div class="card z-depth-2">
      <div class="card-content white blue-text">
          <h4 class="card-stats-number">NEW</h4>
          <p class="card-stats-compare"><i class="mdi-hardware-keyboard-arrow-up"></i> Customize here
          </p>
      </div>
      <div class="card-action light-grey darken-2 bottom-btn-more">
          <div class="blue-text">Let's create!</div>
          <a href="/events-create" class="btn btn-floating pulse"><i class="material-icons">add</i></a>
      </div>
    </div>
    <div class="card" v-for="data in indexDetail">
      <div class="card-content blue white-text">
          <i class="material-icons right delete-event-btn" v-on:click="deleteEvent(data.id)">close</i>
          <div>
            <h5 class="card-stats-number">{{data.title}}</h5>
              <div class="chip" v-for="criteria in data.criteria">
                {{criteria.name}}
              </div>
          </div>
      </div>
      <div class="card-action blue darken-2 bottom-btn-more">
          <div class="white-text">{{data.created_at}}</div>
          <div class="">
            <a class="btn btn-floating" :href="'/setup-create/'+data.id"><i class="material-icons">add</i></a>
          </div>
      </div>
    </div>
  </div>
  <ul class="pagination">
      <li class="waves-effect" v-if="pagination.current_page > 1"><a href="#" @click.prevent="changepage(pagination.current_page - 1)"><i class="material-icons">chevron_left</i></a></li>
      <li v-for="page in pagesNumber" v-bind:class="[ page == isActive ? 'active blue':'']"><a href="#!" @click.prevent="changepage(page)">{{page}}</a></li>
      <li class="waves-effect" v-if="pagination.current_page < pagination.last_page"><a href="#!" @click.prevent="changepage(pagination.current_page + 1)"><i class="material-icons">chevron_right</i></a></li>
  </ul>
</div>
</template>

<script>
import axios from 'axios';
  export default {
    data () { return {
        pagination:[],
        indexDetail:[],
        offset:4,
      }
    },
    created()
    {
      this.fetchData();
    },
    computed:{
      isActive:function(){
        return this.pagination.current_page;
      },
      pagesNumber:function(){
        if (!this.pagination.to) {
           return [];
        }
        var from = this.pagination.current_page - this.offset;
        if (from < 1) {
            from = 1;
        }
        var to = from + (this.offset * 2);
        if (to >= this.pagination.last_page) {
            to = this.pagination.last_page;
        }
        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }
        return pagesArray;
      },
    },
    methods: {
      fetchData(page)
      {
        var vm=this;
        axios.get(`/events-panel-data?page=`+page).then(function(response)
        {
          console.log(response);
          vm.pagination=response.data;
          vm.indexDetail=response.data.data;
        })
      },
      changepage(next){
        this.pagination.current_page = next;
        this.fetchData(next);
      },
      deleteEvent(id)
      {
        var vm=this;
        swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!'
        }).then((result) => {
          if (result.value) {
            axios.delete(`/events-remove/`+id).then(function(response)
            {
              vm.fetchData();
              swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              )
            });
          }
        })
      }
    },
  }
</script>
