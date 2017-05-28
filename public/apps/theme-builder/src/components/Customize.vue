<template>
	<form @submit.prevent="submitForm()" class="ui form">
	      <div class="ui pointing menu architecture-block" :class="{ 'active': activeBlock == 'header' }" @click.prevent="chooseBlock('header')"  v-if="header.enabled">
	        <a class="active item">
		    Home
		      </a>
		        <a class="item">
			    Messages
			      </a>
			        <a class="item">
				    Friends
				      </a>
				        <div class="right menu">
					    <div class="item">
					          <div class="ui transparent icon input">
						          <input type="text" placeholder="Search...">
							          <i class="search link icon"></i>
								        </div>
									    </div>
									      </div>
									      </div>

									      <div class="ui grid">

             <div class="four wide column architecture-block" :class="{ 'active': activeBlock == 'left_sidebar' }" @click.prevent="chooseBlock('left_sidebar')"  v-if="left_sidebar.enabled">
	         <div class="ui vertical menu">
		       <a class="active item">
		               Bio
			             </a>
				           <a class="item">
					           Pics
						         </a>
							       <a class="item">
							               Companies
								             </a>
									           <a class="item">
										           Links
											         </a>
												     </div>
												       </div>
									      
	    <div class="eight wide stretched column">
	        <div class="ui segment">
		     To customize, click on a block to edit it's contents.
		          </div>
			    </div>

			    <div class="wide column architecture-block" :class="getBlockClasses('right_sidebar')" @click.prevent="chooseBlock('right_sidebar')"  v-if="right_sidebar.enabled">
			        <div class="ui vertical right menu">
				      <a class="active item">
				              Bio
					            </a>
						          <a class="item">
							          Pics
								        </a>
									      <a class="item">
									              Companies
										            </a>
											          <a class="item">
												          Links
													        </a>
														    </div>

														    </div>

          </div>

	  <div class="ui inverted vertical footer segment architecture-block" :class="{ 'active': activeBlock == 'footer' }" @click.prevent="chooseBlock('footer')" v-if="footer.enabled">
	           <div class="ui container">
		   	      <div class="ui stackable inverted divided equal height stackable grid">
			      	           <div class="three wide column">
					   	          <h4 class="ui inverted header">About</h4>
							                <div class="ui inverted link list">
									                 <a href="#" class="item">Sitemap</a>
											                <a href="#" class="item">Contact Us</a>
													               <a href="#" class="item">Religious Ceremonies</a>
														       	              <a href="#" class="item">Gazebo Plans</a>
																      	           </div>
																			        </div>
																					        <div class="three wide column">
																						               <h4 class="ui inverted header">Services</h4>
																							       	             <div class="ui inverted link list">
																									     	              <a href="#" class="item">Banana Pre-Order</a>
																											      	             <a href="#" class="item">DNA FAQ</a>
																													     	            <a href="#" class="item">How To Access</a>
																															                   <a href="#" class="item">Favorite X-Men</a>
																																	                </div>
																																				        </div>
																																						        <div class="seven wide column">
																																							               <h4 class="ui inverted header">Footer Header</h4>
																																								       	             <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
																																										     	              </div>
																																													      </div>
																																														    </div>
																																															  </div>

        <div class="ui modal">
	  <i class="close icon"></i>
	    <div class="header">
	        Editing Block: {{ activeBlock }}
		  </div>
		    <div class="content">
				      <div class="description">
				            <div class="ui header">You may edit the information for this block below:</div>

					    	<div class="field" v-if="getBlock(activeBlock, 'backgroundColor')">
						      <label>Background Color</label>
						      <input type="text" class="backgroundColor-picker" v-model="backgroundColor">
						      
						      <div class="backgroundColor-container"></div>
						    </div>
							 <div class="field" v-if="getBlock(activeBlock, 'borderColor')">
							 	yo yo
							      <label>Border Color</label>
							      <input type="text" class="backgroundColor-picker" v-model="borderColor">
							      
							      <div class="backgroundColor-container"></div>
							 </div>
							 <div class="field" v-if="getBlock(activeBlock, 'borderWidth')">
							      <label>Border Width</label>
							      <input type="text" class="color-picker" v-model="borderWidth">
							 </div>
							 <div class="field" v-if="getBlock(activeBlock, 'width')">
							      <label>Width of Block</label>
							      
							      <select v-model="width">
							      		<option value="six">37.5%</option>
							      		<option value="five">31.25%</option>
							      		<option value="four">25%</option>
							      		<option value="three">18.75%</option>
							      </select>
							 </div>
					    </div>

							      </div>
							        <div class="actions margin-top-10">
								    <div class="ui black deny labeled icon button" @click.prevent="closeModal()">
								          Cancel 
								          <i class="ban icon"></i>
									      </div>
									          <div class="ui positive right labeled icon button" @click.prevent="saveBlock()">
										        Continue
											      <i class="checkmark icon"></i>
											          </div>
												    </div>
												    </div>

	</form>
</template>

<script>
export default {
   name: 'customize',
   ready: function() {
	   $('.backgroundColor-container').farbtastic('.backgroundColor-picker');
	   $('.borderColor-container').farbtastic('.borderColor-picker');
   },
   created: function() {
       var _this = this;
       $.get('/admin/themes/build?retrieve=1', function(response) {
       	    _.each(response.data, function(value, key) {
		        _this[key] = value;
		    });
       });
   },
   data: function() {
	  return {
	     activeBlock: '',
   	     body: {
       	 	  backgroundColor: 'white',
	   	 	  backgroundImage: '',
	   	 	  width: 'eight'
   	     },
   		 left_sidebar: {
	   		 enabled: true,
	   		 full: true,
	   		 backgroundColor: 'gray',
	   		 backgroundImage: '',
	   		 borderColor: '',
	   		 borderWidth: '',
	   		 width: 'four',
	   		 snippets: []
   		 },
   		 right_sidebar: {
	   		 enabled: true,
	   		 full: true,
	   		 backgroundColor: 'gray',
	   		 backgroundImage: '',
	   		 borderColor: '',
	   		 borderWidth: '',
	   		 width: 'four',
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
   		 },
   		 width: '',
		 backgroundColor: 'gray',
		 backgroundImage: '',
		 borderColor: '',
		 borderWidth: ''
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
				step: 'snippets'
		     };

		     var _this = this;
	             $.post('/admin/themes/build', data, function(response) {
				 if (response.status) {
				     toastr.success('Your progress has been saved.');
	
				     _this.$router.push({ name: 'snippets' });
				 } else {
				     toastr.error('Could not save, please try again');
				 }
		     }, 'json');
		},
		getBlock: function(block, key) {
			  if (typeof this[block] === 'undefined' || typeof this[block][key] === 'undefined') {
			     return '';
			  } else {
			    return this[block][key];
			  }
		},
		getBlockClasses: function(block)
		{
			var classes = [];
			
			if (this.activeBlock == block) {
				classes.push('active');
			}
			
			var widthClass = this.getBlock(block, 'width');
			if (widthClass) {
				classes.push(widthClass);
			}
			
			classes = classes.join(' ');
			
			return classes;
		},
		chooseBlock: function(block) {
		    this.activeBlock = block;
		    
		    this.backgroundColor = this.getBlock(this.activeBlock, 'backgroundColor');
		    this.backgroundImage = this.getBlock(this.activeBlock, 'backgroundImage');
		    this.borderColor = this.getBlock(this.activeBlock, 'borderColor');
		    this.borderWidth = this.getBlock(this.activeBlock, 'borderWidth');
		    this.width = this.getBlock(this.activeBlock, 'width');

		    $('.ui.modal').modal('show');
		},
		saveBlock: function() {
			this.closeModal();
		},
		closeModal: function() {
		    $('.ui.modal').modal('hide');

		    this.activeBlock = '';
		    this.backgroundColor = '';
		    this.backgroundImage = '';
		    this.borderColor = '';
		    this.borderWidth = '';
		    this.width = '';
		}
   }
}
</script>