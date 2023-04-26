import { createApp } from 'vue'
import App from './shared/layout.vue'
import router from './routes.js'
import '../css/app.css'

const VueApp = createApp(App);

VueApp.use(router)
    .mount("#vue-app");
