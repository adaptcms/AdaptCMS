<template>
	<div class="ui container">
	<div class="ui red segment">
		<form @submit.prevent="submitForm()" class="ui form">
		      <h1>Getting Started</h1>
		      
		      <div class="required field">
		      	<label>Left Sidebar</label>
		      	
		      	<div class="ui buttons">
		      		<a href="" @click.prevent="chooseLeftSidebar('tall')" class="ui button" :class="{ 'active': left_sidebar.size == 'tall' }">
		      			Tall
		      		</a>
		      		<a href="" @click.prevent="chooseLeftSidebar('short')" class="ui button" :class="{ 'active': left_sidebar.size == 'short' }">
		      			Short
		      		</a>
		      		<a href="" @click.prevent="chooseLeftSidebar()" class="ui button" :class="{ 'active': !left_sidebar.enabled }">
		      			None
		      		</a>
		      	</div>
		      </div>
		      <div class="required field">
		      	<label>Right Sidebar</label>
		      	
		      	<div class="ui buttons">
		      		<a href="" @click.prevent="chooseRightSidebar('tall')" class="ui button" :class="{ 'active': right_sidebar.size == 'tall' }">
		      			Tall
		      		</a>
		      		<a href="" @click.prevent="chooseRightSidebar('short')" class="ui button" :class="{ 'active': right_sidebar.size == 'short' }">
		      			Short
		      		</a>
		      		<a href="" @click.prevent="chooseRightSidebar()" class="ui button" :class="{ 'active': !right_sidebar.enabled }">
		      			None
		      		</a>
		      	</div>
		      </div>
		      <div class="required field">
		      	<label>Header</label>
		      	
		      	<div class="inline field">
		      		<div class="ui toggle checkbox" @click.prevent="toggleHeaderEnabled()">
				      <input type="checkbox" tabindex="0" class="hidden" value="1" :checked="header.enabled">
				      <label>Enable</label>
				    </div>
		      	</div>
		      	<div class="inline field">
		      		<div class="ui toggle checkbox" @click.prevent="toggleHeaderHeroEnabled()">
				      <input type="checkbox" tabindex="0" class="hidden" value="1" :checked="header.hero_enabled">
				      <label>Enable Hero</label>
				    </div>
		      	</div>
		      </div>
		      <div class="required field">
		      	<label>Footer</label>
		      	
		      	<div class="ui buttons">
		      		<a href="" @click.prevent="chooseFooter('large')" class="ui button" :class="{ 'active': footer.size == 'large' }">
		      			Large
		      		</a>
		      		<a href="" @click.prevent="chooseFooter('regular')" class="ui button" :class="{ 'active': footer.size == 'regular' }">
		      			Regular
		      		</a>
		      		<a href="" @click.prevent="chooseFooter('small')" class="ui button" :class="{ 'active': footer.size == 'small' }">
		      			Small
		      		</a>
		      		<a href="" @click.prevent="chooseFooter()" class="ui button" :class="{ 'active': !footer.enabled }">
		      			None
		      		</a>
		      	</div>
		      </div>
		      
		      <button type="submit" class="ui button primary large right">Continue <i class="save icon"></i></button>
		</form>
	</div>
	</div>
</template>

<script>
export default {
   name: 'gettingStarted',
   ready: function() {
   		$('.ui.checkbox').checkbox();
   },
   data: function() {
	  return {
   	     body: {
       	 	  backgroundColor: 'white',
	   	 	  backgroundImage: '' 
   	     },
   		 left_sidebar: {
	   		 enabled: true,
	   		 size: 'tall',
	   		 backgroundColor: 'gray',
	   		 backgroundImage: '',
	   		 borderColor: '',
	   		 borderWidth: '',
	   		 width: '',
	   		 snippets: []
   		 },
   		 right_sidebar: {
	   		 enabled: true,
	   		 size: 'tall',
	   		 backgroundColor: 'gray',
	   		 backgroundImage: '',
	   		 borderColor: '',
	   		 borderWidth: '',
	   		 width: '',
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
	        var data = {
		        body: this.body,
				left_sidebar: this.left_sidebar,
				right_sidebar: this.right_sidebar,
				header: this.header,
				footer: this.footer,
				step: 'customize'
		     };

		     var _this = this;
	             $.post('/admin/themes/build', data, function(response) {
				 if (response.status) {
				     toastr.success('Your progress has been saved.');
	
				     _this.$router.push({ name: 'customize' });
				 } else {
				     toastr.error('Could not save, please try again');
				 }
		     }, 'json');
		},
		chooseLeftSidebar: function(value) {
			this.left_sidebar.enabled = (value !== null);
			this.left_sidebar.size = value;
		},
		chooseRightSidebar: function(value) {
			this.right_sidebar.enabled = (value !== null);
			this.right_sidebar.size = value;
		},
		chooseFooter: function(value) {
			this.footer.enabled = (value !== null);
			this.footer.size = value;
		},
		toggleHeaderEnabled: function() {
			this.header.enabled = !this.header.enabled;
		},
		toggleHeaderHeroEnabled: function() {
			this.header.hero_enabled = !this.header.hero_enabled;
		}
   }
}
</script>