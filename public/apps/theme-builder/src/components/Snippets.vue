<template>
	<form @submit.prevent="submitForm()" class="ui form">
	      <div class="ui sidebar inverted vertical menu">
	      	<p>Left Sidebar</p>
	      </div>
	      
	      <p>snippets</p>
	</form>
</template>

<script>
export default {
   name: 'snippets',
   ready: function() {
   	      console.log('snippets');
   },
   data: function() {
	  return {
   	     body: {
       	 	  backgroundColor: 'white',
	   	 	  backgroundImage: '' 
   	     },
   		 left_sidebar: {
	   		 enabled: true,
	   		 full: true,
	   		 backgroundColor: 'gray',
	   		 backgroundImage: '',
	   		 borderColor: '',
	   		 borderWidth: '',
	   		 snippets: []
   		 },
   		 right_sidebar: {
	   		 enabled: true,
	   		 full: true,
	   		 backgroundColor: 'gray',
	   		 backgroundImage: '',
	   		 borderColor: '',
	   		 borderWidth: '',
	   		 snippets: []
   		 },
   		 header: {
	   		 enabled: true,
	   		 hero_enabled: false,
	   		 backgroundColor: 'gray',
	   		 backgroundImage: '',
	   		 borderColor: '',
	   		 borderWidth: '',
	   		 snippets: []
   		 },
   		 footer: {
	   		 enabled: true,
	   		 size: 'regular',
	   		 backgroundColor: 'gray',
	   		 backgroundImage: '',
	   		 borderColor: '',
	   		 borderWidth: '',
	   		 snippets: []
   		 }
      }
   },
   methods: {
   		submitForm: function() {
		    console.log('submit form');
		}
   }
}
</script>