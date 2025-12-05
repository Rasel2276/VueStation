import { createApp } from 'vue'
import App from './App.vue'
import router from './router'   // ← Router import করো

const app = createApp(App)

app.use(router)   // ← Router install করো
app.mount('#app')
