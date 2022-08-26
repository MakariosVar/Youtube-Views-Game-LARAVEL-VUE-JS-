require('./bootstrap');
import '../css/app.css';
import { createApp } from 'vue'
import mainApp from './mainApp'
import video1 from './components/video1'
import video2 from './components/video2'
import versus from './components/versus'
const app = createApp({})

app.component('main-app', mainApp)
app.component('video1', video1)
app.component('video2', video2) 
app.component('versus', versus) 

app.mount('#app') 