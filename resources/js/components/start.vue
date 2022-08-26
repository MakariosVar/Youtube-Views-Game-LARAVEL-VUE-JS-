<template>
    <div>
        <div id="StartGame">
      <h1 class="startgametext"> WHICH HAS MORE VIEWS??  </h1>
      <img src="/storage/imgs/ytb.svg" class="svgYtb">
      <button  class="button-40" role="button" @click="StartGame">START</button>
      <div id="moreInfo" class="dropdown">
        <button class="dropbtn">...</button>
        <div class="dropdown-content">
           <a href="/videos">videos on server</a> 
          <a @click.prevent="generateNew()">Generate New Videos</a>
          <a href="#">code on github</a>
          <div class="clear">
          </div>
        </div>  
      
      </div>  
    </div>
    </div>
</template>
<script>
</script>