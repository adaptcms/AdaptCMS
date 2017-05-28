import VueRouter from 'vue-router'
import Vue from 'vue'
import GettingStarted from './components/GettingStarted.vue'
import Customize from './components/Customize.vue'
import Snippets from './components/Snippets.vue'

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    routes: [
	    { path: '//admin/themes/build/getting-started', component: GettingStarted, name: 'gettingStarted' },
	    { path: '//admin/themes/build/customize', component: Customize, name: 'customize' },
	    { path: '//admin/themes/build/snippets', component: Snippets, name: 'snippets' }
    ]
})
