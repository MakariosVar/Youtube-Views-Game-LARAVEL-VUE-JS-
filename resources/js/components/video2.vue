<template>
  <div class="centered" @click="$emit('selectVideo', '2')" >

      <div v-if="loaded">
        <img class="mediaScreenIMG" :src="this.video.thumbnail" alt=""> 
        <h1>{{this.video.title}}</h1>
         <h2 v-if="show == true">{{this.video.views.toLocaleString()}} views</h2>   
        <h3>Capture number of views at: {{this.video.captureDate}}</h3>
      </div>
   
  </div>
</template>

<script>

import axios from 'axios';
    export default {
     props:['show'],
      data(){
        return{
          video:{},
  
          
          loaded:false 
        }
      },
      methods:{
        getRandom(){
          axios.get('/api/getrandom').then(response => {
            this.video = response.data;
            this.$emit('views2', this.video.views);
            this.loaded = true;
          });
        }
      },
      created(){
        this.getRandom();
      }
    }
</script>