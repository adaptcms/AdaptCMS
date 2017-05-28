<template>
  <div id="app">
       <div class="ui container">
  	<div class="ui three top attached steps">
	  <div class="step">
	    <i class="play icon"></i>
	    <div class="content">
	      <div class="title"><router-link :to="{ name: 'gettingStarted' }" @click.native="goToPage('gettingStarted')">Getting Started</router-link></div>
	      <div class="description">Choose your core layout elements</div>
	    </div>
	  </div>
	  <div class="disabled step">
	    <i class="edit icon"></i>
	    <div class="content">
	      <div class="title">
	      	<router-link :to="{ name: 'customize' }" @click.native="goToPage('gettingStarted')" :class="{ 'disabled': $route.name == 'gettingStarted' }">Customize</router-link>
	      </div>
	      <div class="description">Edit the look and feel of your core layout elements</div>
	    </div>
	  </div>
	  <div class="disabled step">
	    <i class="building icon"></i>
	    <div class="content">
	      <div class="title">
	      	<router-link :to="{ name: 'snippets' }" @click.native="goToPage('gettingStarted')" :class="{ 'disabled': $route.name == 'gettingStarted' || $route.name == 'customize' }">Snippets</router-link>
	      </div>
	      <div class="description">Dynamically build your theme</div>
	    </div>
	  </div>
	</div>
	
	<div class="ui red segment" v-if="!$route.name">
		<h1>Welcome to the Theme Builder!</h1>
		
		<p>
			In this first initial version of our theme builder, we hope to provide an easy and advanced interface to bridge the gap between the core CMS and installations of themes. Allowing users to create thousands of different variety of themes without any coding knowledge. Enjoy!
		</p>
		
		<a href="" @click.prevent="goToPage('gettingStarted')" class="ui button primary huge">Get Started <i class="play icon"></i></a>
	</div>
	</div>
  
  	<router-view></router-view>
  </div>
</template>

<script>
import GettingStarted from './components/GettingStarted.vue'
import Customize from './components/Customize.vue'
import Snippets from './components/Snippets.vue'

export default {
  name: 'app',
  components: {
  	 gettingStarted: GettingStarted,
  	 customize: Customize,
  	 snippets: Snippets
  },
  methods: {
	  goToPage: function(page) {
		  this.$router.push({ name: page });
      }
  }
}
</script>