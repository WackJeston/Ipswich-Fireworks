// require('./bootstrap');
import { createApp } from 'vue'

import * as Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

import VueGoogleMaps from '@fawmi/vue-google-maps'


// PUBLIC
import Vueheader from './vue/components/public/site/vueHeader.vue'
import Vuemenu from './vue/components/public/site/vueMenu.vue'
import Vuefooter from './vue/components/public/site/vueFooter.vue'

import Publicerror from './vue/components/public/site/publicError.vue'
import Publicmessage from './vue/components/public/site/publicMessage.vue'
import Publicalert from './vue/components/public/site/publicAlert.vue'

import Bannerhometop from './vue/components/public/homepage/bannerHomeTop.vue'

import Contactmain from './vue/components/public/contactMain.vue'

import Googlemaps from './vue/components/public/googleMaps.vue'


// ADMIN
import Adminheader from './vue/components/admin/site/adminHeader.vue'
import Adminfooter from './vue/components/admin/site/adminFooter.vue'
import Alerterror from './vue/components/admin/site/alertError.vue'
import Alertmessage from './vue/components/admin/site/alertMessage.vue'

import Adminlogin from './vue/components/admin/auth/login.vue'

import Admincontactfunctions from './vue/components/admin/contactFunctions.vue'

import Userscreate from './vue/components/admin/usersCreate.vue'

import Userprofilefunctions from './vue/components/admin/userProfileFunctions.vue'

import Customerscreate from './vue/components/admin/customersCreate.vue'

import Customerprofilefunctions from './vue/components/admin/customerProfileFunctions.vue'





// PUBLIC
const vueHeader = createApp({})
vueHeader.component('vueheader', Vueheader).mount('#vueheader')

const vueMenu = createApp({})
vueMenu.component('vuemenu', Vuemenu).mount('#vuemenu')

const vueFooter = createApp({})
vueFooter.component('vuefooter', Vuefooter).mount('#vuefooter')


const publicError = createApp({})
publicError.component('publicerror', Publicerror).mount('#publicerror')

const publicMessage = createApp({})
publicMessage.component('publicmessage', Publicmessage).mount('#publicmessage')

const publicAlert = createApp({})
publicAlert.component('publicalert', Publicalert).mount('#publicalert')


const bannerHomeTop = createApp({})
bannerHomeTop.component('bannerhometop', Bannerhometop).mount('#bannerhometop')


const contactMain = Vue.createApp({})
contactMain.component('contactmain', Contactmain).mount('#contactmain')

const googleMaps = Vue.createApp({})
const gmapKey = process.env.MIX_GOOGLE_MAPS_KEY
googleMaps.use(VueGoogleMaps, {
	load: {
		key: gmapKey,
	},
}).mount('#app')
googleMaps.component('googlemaps', Googlemaps).mount('#googlemaps')



// ADMIN
const adminHeader = createApp({})
adminHeader.component('adminheader', Adminheader).mount('#adminheader')

const adminFooter = createApp({})
adminFooter.component('adminfooter', Adminfooter).mount('#adminfooter')

const alertError = createApp({})
alertError.component('alerterror', Alerterror).mount('#alerterror')

const alertMessage = createApp({})
alertMessage.component('alertmessage', Alertmessage).mount('#alertmessage')


const adminLogin = createApp({})
adminLogin.component('adminlogin', Adminlogin).mount('#adminlogin')


const adminContactFunctions = createApp({})
adminContactFunctions.use(VueAxios, axios)
adminContactFunctions.component('admincontactfunctions', Admincontactfunctions).mount('#admincontactfunctions')


const usersCreate = createApp({})
usersCreate.component('userscreate', Userscreate).mount('#userscreate')


const userProfileFunctions = createApp({})
userProfileFunctions.component('userprofilefunctions', Userprofilefunctions).mount('#userprofilefunctions')


const customersCreate = createApp({})
customersCreate.component('customerscreate', Customerscreate).mount('#customerscreate')


const customerProfileFunctions = createApp({})
customerProfileFunctions.component('customerprofilefunctions', Customerprofilefunctions).mount('#customerprofilefunctions')
