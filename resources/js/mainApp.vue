
<template>
<div v-if="!(this.start)">
    <div id="StartGame">
      <h1 > WHICH HAS MORE VIEWS??  </h1>
      <img src="/storage/imgs/ytb.svg" class="svgYtb">
      <button  class="button-40" role="button" @click="StartGame">START</button>
      <div id="moreInfo" class="dropdown">
        <button class="dropbtn">...</button>
        <div class="dropdown-content">
           <a href="/videos">videos on server</a> 
          <a @click.prevent="generateNew()">Generate New Videos</a>
          <a href="#">code on github</a>
          <a href="https://makariosvar.site/" target="_blank">Me</a>
          <div class="clear">
          </div>
        </div>  
      
      </div>  
    </div>
</div>

<div v-else>
    <div id="header" class="text-center">
       <h1> WHICH HAVE MORE VIEWS?<br> <small class="mt-3">on youtube</small></h1>
   </div>  
 
  <div>
    <div class="main">
          <div class="split left" >
             <video1 v-bind:key="this.refreshKey1" :show='this.show1' @views1="setViewsForVideo1" @selectVideo="selection"/>
          </div>
    
          <div class="split right">
              <video2 v-bind:key="this.refreshKey2" :show='this.show2' @views2="setViewsForVideo2" @selectVideo="selection" />
          </div> 
    </div>

    <div  id="versus">
        <versus />
    </div>
    <div id="generateNew" class="text-center">
        <h3>Score : {{this.score}}</h3>
        <span>HighScore : {{this.highscore}}</span>  
        <h3>Volume</h3>
         <div v-if="this.music" class="text-center">
            <img src="/storage/imgs/sound.svg" @click="muteMusic" class="svgYtb">
        
          </div>
          <div v-else>
            <img src="/storage/imgs/mute.svg" @click="muteMusic" class="svgYtb">
          </div>
            <input class="responseHide" type="range" @change="volumeSet" min="0.001" max="1" value="0.5" step="0.001">
        
    </div>    
     <div id="moreInfo" class="dropdown">
        <button class="dropbtn">...</button>
        <div class="dropdown-content">
           <a href="/videos">videos on server</a> 
          <a @click.prevent="generateNew()">Generate New Videos</a>
          <a href="#">code on github</a>
          <a href="https://makariosvar.site/" target="_blank">Me</a>
          <div class="clear"></div>
        </div>  
      
    </div>   
    <div v-if="this.nextphase && this.gameover" class="GameOver">
      
        <div>
          <h2>{{this.video1views.toLocaleString()}} Views </h2>
        </div>
        <div class="text-center">
          <img class="gameoversvg" src="/storage/imgs/gameOver.svg">
          <h1>YOU LOSE</h1>
          <a class="white restartBtn" @click="restart">Restart</a> 
        </div>
        <div>
          <h2>{{this.video2views.toLocaleString()}} Views </h2>
        </div>
      
    </div>
    
    
    <div class="NextPhase text-center" v-if="this.nextphase && !(this.gameover)">
      
         <div>
          <h2>{{this.video1views.toLocaleString()}} Views </h2>
        </div>
        <div class="text-center">
          <img src="/storage/imgs/correct.svg">
          <h1>YOU WIN</h1>
          <a class="white nextBtn" @click="next">Continue</a> 
        </div>
        <div>
          <h2>{{this.video2views.toLocaleString()}} Views </h2>
        </div>
   
    </div>
  </div>
</div>
</template>



<script>
import randomWords from 'random-words'
import axios from 'axios';

const WinSound = new Audio('/storage/sound/win.wav');
const LoseSound =  new Audio('/storage/sound/lose.mp3');
const backgroundMusic = new Audio('/storage/sound/background.mp3');

    export default {
    
      data(){
        return{
          start:false,
          video1:{},
          video2:{},
          refreshKey1:1,
          refreshKey2:2,
          video1views:0,
          video2views:0,
          selectVideo:"",
          gameover:false,
          nextphase:false,
          score:0,
          streak:0,
          streakbreak:'',
          show1:false,
          show2:false,
          highscore:localStorage.getItem('highscore') || 0,
          music:true,
          

        }
      },
      methods:{

          
          generateNew() { 
            let query1 = randomWords()
          console.log('RANDOM QUERY KEY : ' +query1);
          let query={query:query1}
            axios.post('api/generate',query).then(response => {
              if(response.data.status == 'success'){
                this.generateNew();
              }else{
                alert('thank you')
              }
            })
            
          },
          muteMusic(){
            if(this.music){
              this.music = false;
              backgroundMusic.muted = true;

            }else{
              this.music = true;
              backgroundMusic.muted = false;
            }
          },
          volumeSet(e){
            var volume = parseFloat(e.target.value);
           
              backgroundMusic.volume = volume;
              WinSound.volume = volume;
              LoseSound.volume = volume;
          },
        
            setViewsForVideo1(e) {
              this.video1views= e;
            },
            setViewsForVideo2(e) {
              this.video2views= e;
            },
            selection(e) {
              this.selectVideo= e;
              if((this.selectVideo == '1' && this.video1views >= this.video2views) || (this.selectVideo == '2' && this.video1views <= this.video2views) )
              {
                console.log('WIN')
                
                WinSound.play();
                    this.score=this.score+1;
                    this.nextphase=true; 
              }else{
             
                LoseSound.play();
                this.gameover = true;
                console.log('YOU LOSE'); 
                this.nextphase=true;
              }
              
            },
            restart(){
              this.nextphase=false;
              this.gameover=false;
              this.score=0;
              this.show1 = false;
              this.show2 = false;
              this.refreshKey1=Math.random();
              this.refreshKey2=Math.random();
            },
             next(){
              let a=0;
              let b=0;
              if(this.score > this.highscore){localStorage.setItem('highscore', this.score); this.highscore=this.score}
              this.nextphase=false;
              if(this.selectVideo == '1'){
                if( this.streakbreak == '2' ){this.streak = 0; this.show2=false}
                this.streakbreak='1'
                 this.show1=true
                if(this.streak >= 5){
                  this.refreshKey1=Math.random();
                  this.show1=false;
                }
                this.refreshKey2=Math.random();
                this.streak = this.streak + 1;
              }else{
                    if( this.streakbreak == '1' ){this.streak = 0; this.show1=false}
                    this.streakbreak='2'
                    this.show2=true
                 if(this.streak >= 5){
                  this.refreshKey2=Math.random();
                  this.show2=false
                }
                this.refreshKey1=Math.random();
                this.streak = this.streak + 1;
              }
            },
            StartGame(){
              if (typeof backgroundMusic.loop == 'boolean')
              {
                  backgroundMusic.loop = true;
              }
              else
              {
                  backgroundMusic.addEventListener('ended', function() {
                      this.currentTime = 0;
                      this.play();
                  }, false);
              }
              backgroundMusic.play();
              this.start = true;

            },
          
          
      },
     
      
    }
    

</script>

