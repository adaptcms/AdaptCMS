<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AdaptCMS {$adaptcms_version} - {$acp_page}</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel='stylesheet' href='inc/js/menu.css'>
<script type='text/javascript' src='inc/js/menu.js'></script>
<script type='text/javascript' src='inc/js/sortmenu.js'></script>
{literal}
<script type="text/javascript" >
	$('document').ready(function(){
		$('#flip-container').quickFlip();
		
		$('#flip-navigation li a').each(function(){
			$(this).click(function(){
				$('#flip-navigation li').each(function(){
					$(this).removeClass('selected');
				});
				$(this).parent().addClass('selected');
				var flipid=$(this).attr('id').substr(4);
				$('#flip-container').quickFlipper({ }, flipid, 1);
				
				return false;
			});
		});
	});



	function toggle(id,img1,img2,idname)
	{
 	 
		if (id.src.indexOf(img1)>0 )
		{
	 	
			document.getElementById(idname).src='{/literal}{$siteurl}{literal}images/arrow-up.jpg';	
			 
		}
		else if (id.src.indexOf(img2)>0)
		{

	 		document.getElementById(idname).src='{/literal}{$siteurl}{literal}images/arrow-down.jpg';
			 		 
		}
 
	}
	
	
function toggle1(id,img1,img2,idname)
	{
 	 
		if (id.src.indexOf(img1)>0 )
		{
	 	
			document.getElementById(idname).src='{/literal}{$siteurl}{literal}images/arrow-up2.jpg';	
			 
		}
		else if (id.src.indexOf(img2)>0)
		{

	 		document.getElementById(idname).src='{/literal}{$siteurl}{literal}images/arrow-down2.jpg';
			 		 
		}
 
	}
	


</script>




<script type="text/javascript">

$(document).ready(function() {	


  //Get all the LI from the #tabMenu UL
  $('#tabMenu > li').click(function(){
        
    //remove the selected class from all LI    
    $('#tabMenu > li').removeClass('selected');
    
    //Reassign the LI
    $(this).addClass('selected');
    
    //Hide all the DIV in .boxBody
    $('.boxBody div').slideUp('1500');
    
    //Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.
    $('.boxBody div:eq(' + $('#tabMenu > li').index(this) + ')').slideDown('1500');
    
  }).mouseover(function() {

    //Add and remove class, Personally I dont think this is the right way to do it, anyone please suggest    
    $(this).addClass('mouseover');
    $(this).removeClass('mouseout');   
    
  }).mouseout(function() {
    
    //Add and remove class
    $(this).addClass('mouseout');
    $(this).removeClass('mouseover');    
    
  });

  //Mouseover with animate Effect for Category menu list
  $('.boxBody #category li').mouseover(function() {

    //Change background color and animate the padding
    $(this).css('backgroundColor','#888');
    $(this).children().animate({paddingLeft:"20px"}, {queue:false, duration:300});
  }).mouseout(function() {
    
    //Change background color and animate the padding
    $(this).css('backgroundColor','');
    $(this).children().animate({paddingLeft:"0"}, {queue:false, duration:300});
  });  
	
  //Mouseover effect for Posts, Comments, Famous Posts and Random Posts menu list.
  $('.boxBody li').click(function(){
    window.location = $(this).find("a").attr("href");
  }).mouseover(function() {
    $(this).css('backgroundColor','#888');
  }).mouseout(function() {
    $(this).css('backgroundColor','');
  });  	
	
});

</script>
{/literal}
</head>
<body>
{$acp_bar}

	<!--[if !IE]>main<![endif]-->
    	<div id="main">
        
        	<!--[if !IE]>header-area<![endif]-->
            	
            	<div id="header-area">
                
                	<!--[if !IE]>search-bar<![endif]-->
            	
            			<div id="search-bar"><form action="index.php?view=search" method="get">
                			
                           <input class="search-button" type="submit" name="search" value="Search"/> 
                            
                            <input class="search-box" type="text" name="q" size="15"  height="3" onblur="if (this.value == '') {ldelim}this.value = 'Lets Search Here ...';{rdelim}" onfocus="if (this.value == 'Lets Search Here ...') {ldelim}this.value = '';{rdelim}" id="ls"  value="Lets Search Here ..."  /> 
                            </form>
                		</div>
                
            		<!--[if !IE]>search-bar<![endif]-->
                    
                    
                    	<!--[if !IE]>header<![endif]-->
            	
            				<div id="header">
                				
                                
                                <!--[if !IE]>logo<![endif]-->
                                	
                                    <div id="logo">
                                    	
                                        <a href="{$siteurl}"><img src="images/logo.jpg" width="263" height="46" alt="company name" border="0" align="middle"/></a>
                                    
                                    </div>
                                <!--[if !IE]>logo<![endif]-->
                                
                                
                                	 
                                     <!--[if !IE]>right-header<![endif]-->
                                	
                                    	<div id="right-header">
                                		<a href="http://www.adaptcms.com"><img src="images/banner3.jpg" width="464" height="72" alt="banner" align="right" border="0"/></a>
      
                                		</div>
                                    
                                	 <!--[if !IE]>right-header<![endif]-->
                                
                			</div>
                
            			<!--[if !IE]>header<![endif]-->
                        
                        
                        	
                            <!--[if !IE]>navigation<![endif]-->
                        	
                            	<div id="navigation">
                                
                                	<!--[if !IE]>button<![endif]-->
                        	
                            			<div id="button">
                            	
                                            <ul>
			<li><a href="admin.php">ACP</a></li>
			<li><a href="{$siteurl}">Website</a></li>
			<li><a href="admin.php?view=share">Share</a></li>
			<li><a href="admin.php?view=support">Support</a></li>
			<li><a href="{$siteurl}profile/{$user_name}">Your Profile</a></li>
			<li><a href="admin.php?quick_link=1"><img src="images/add.png" border="0"></a></li>
                                            </ul>
                                
                            			</div>
                                        
                                     <!--[if !IE]>button<![endif]-->
                                     
                                     
                                     	<!--[if !IE]>icon<![endif]-->
                        	
                            				<div id="icon">
                                            	
                                                <ul>
                                                   
                                                    <li>
													{php}
													echo help("", "no_text");
													{/php}<img src="images/help.jpg" width="41" height="43" alt="help" border="0"/></a></li>  
                                                    <li><a href="{$siteurl}messages"><img src="images/icons4.png" width="46" height="43" alt="new" border="0"/></a></li>


                                                 </ul>
                                                 
                                            </div>
                                            
                                       	<!--[if !IE]>icon<![endif]-->     
                                
                                </div>
                                	
                			<!--[if !IE]>navigation<![endif]-->
                        
                </div>
                
            <!--[if !IE]>header-area<![endif]-->
        
        </div>
    <!--[if !IE]>main<![endif]-->
    
    
    	
        <!--[if !IE]>bg-pattern<![endif]-->
                        	
             <div id="bg-pattern">
             
             	<!--[if !IE]>content-part<![endif]-->
                	
                    <div id="content-part">
                    
     
                
                	<!--[if !IE]>sidebar<![endif]-->
                	
                    	<div id="sidebar">
 
                                <!--[if !IE]>sidebar1<![endif]-->
                        
                                    <div id="sidebar1" class="menu">
                            
                                      
                                      <!--[if !IE]> basic menu<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class="sidebar-heading2">
                
                			
                            <div class="heading-text1"> Content <span class="drop"> Posting</span></div>
                
                				<div class="arrow2">
                    
                    <span onClick="if(document.getElementById('text4').style.display=='block'){ldelim}document.getElementById('text4').style.display='none'; {rdelim}else{ldelim}document.getElementById('text4').style.display='block'; {rdelim}" class="view" onmouseover="document.getElementById('text4').style='hand';"><img src="images/arrow-down2.jpg" border="none" width="23" height="48" onclick="toggle1(this,'images/arrow-down2.jpg','images/arrow-up2.jpg','id3');" id='id3'  />
                    
                    </span>

                    	</div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id="text4" style="display:block; width: 218px; margin:0 0 0 0; float:left;">                      	
                  <ul class="side-menu2">
                   
                   	<li><a href="admin.php?view=content"> Content </a></li>
                   	<li><a href="admin.php?view=fields"> Custom Fields </a></li>
                   	<li><a href="admin.php?view=sections"> Sections </a></li>
                   	<li><a href="admin.php?view=media"> Media </a></li>
					<li><a href="admin.php?view=skins"> Skins </a></li>

                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading2<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->                        
                                        
                                    
                              </div>
                           
                           <!--[if !IE]>sidebar1<![endif]-->
                                
                                
                                
                                
                              <!--[if !IE]>acp<![endif]-->
                        
                                 <div id="acp" class="menu">
                                    
                                    	
                                        <!--[if !IE]> basic menu<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class="sidebar-heading2">
                
                			
                            <div class="heading-text1">User <span class="drop"> Management</span></div>
                
                				<div class="arrow2">
                    
                    <span onClick="if(document.getElementById('text5').style.display=='block'){ldelim}document.getElementById('text5').style.display='none'; {rdelim}else{ldelim}ocument.getElementById('text5').style.display='block'; {rdelim}" class="view" onmouseover="document.getElementById('text5').style='hand';"><img src="images/arrow-down2.jpg" border="none" width="23" height="48" onclick="toggle1(this,'images/arrow-down2.jpg','images/arrow-up2.jpg','id4');" id='id4'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading2<![endif]-->     
                               
                                 
              <div id="text5" style="display:block; width: 218px; margin:0 0 0 0; float:left;">                      	
                  <ul class="side-menu2">
                   
                   	<li><a href="admin.php?view=users"> Users </a></li>
                   	<li><a href="admin.php?view=groups"> Groups </a></li>
                   	<li><a href="admin.php?view=levels"> Levels </a></li>
			<li><a href="admin.php?view=social"> Social </a></li>
           
                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->  
                            
                                       
                                    </div>
                           
                                <!--[if !IE]>acp<![endif]-->


								                              <!--[if !IE]>acp<![endif]-->
                        
                                 <div id="acp" class="menu">
                                    
                                    	
                                        <!--[if !IE]> basic menu<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class="sidebar-heading2">
                
                			
                            <div class="heading-text1">Advanced <span class="drop"> Misc</span></div>
                
                				<div class="arrow2">
                    
                    <span onClick="if(document.getElementById('text3').style.display=='block'){ldelim}document.getElementById('text3').style.display='none'; {rdelim}else{ldelim}document.getElementById('text3').style.display='block'; {rdelim}" class="view" onmouseover="document.getElementById('text3').style='hand';"><img src="images/arrow-down2.jpg" border="none" width="23" height="48" onclick="toggle1(this,'images/arrow-down2.jpg','images/arrow-up2.jpg','id2');" id='id2'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading2<![endif]-->     
                               
                                 
              <div id="text3" style="display:block; width: 218px; margin:0 0 0 0; float:left;">                      	
                  <ul class="side-menu2">
                   
                   	<li><a href="admin.php?view=polls"> Polls </a></li>
                   	<li><a href="admin.php?view=pages"> Pages </a></li>
                   	<li><a href="admin.php?view=settings"> Settings </a></li>
					<li><a href="admin.php?view=plugins"> Plugins </a></li>
					<li><a href="admin.php?view=stats"> Stats </a></li>
           
                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->  
                            
                                       
                                    </div>
                           
                                <!--[if !IE]>acp<![endif]-->
                                
                                
								                	<!--[if !IE]>skin chooser<![endif]-->
                     <div id="vsitors-online" class="menu">
                           
                           <!--[if !IE]>sidebar-heading<![endif]-->   
                            
                            <div class="sidebar-heading2">
                            
                                <div class="heading-text1">Admin <span class="drop"> Skin</span></div>
                            
                                <div class="arrow2">
                                
                                <span onClick="if(document.getElementById('text2').style.display=='block'){ldelim}document.getElementById('text2').style.display='none'; {rdelim}else{ldelim}document.getElementById('text2').style.display='block'; {rdelim}" class="view" onmouseover="document.getElementById('text2').style='hand';"><img src="images/arrow-down2.jpg"   border="none"  width="23" height="48" onclick="toggle1(this,'images/arrow-down2.jpg','images/arrow-up2.jpg','id1');" id='id1'    />
                                
                                </span>
            
                                	</div>
                         
                            </div>
                        <!--[if !IE]>sidebar-heading<![endif]-->
                        
                        
                  <div id="text2" style="display:block; width: 218px; margin:0 0 0 0; float:left;">                      	
                  <ul class="vsitors-online">
                   
                        <div align="center">{$change_skin}</div>
                   
               	  	</ul>

             
            	</div> 
            </div>
            
          	<!--[if !IE]> skin chooser<![endif]-->            
                                
                               		
                                    <!--[if !IE]>vsitors-online<![endif]-->
                        
                                        <div id="vsitors-online" class="menu">
                                
                                            
                                            <!--[if !IE]> Visitors<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class="sidebar-heading2">
                
                			
                            <div class="heading-text1">Basic <span class="drop"> Stats</span></div>
                
                				<div class="arrow2">
                    
                    <span onClick="if(document.getElementById('text6').style.display=='block'){ldelim}document.getElementById('text6').style.display='none'; {rdelim}else{ldelim}document.getElementById('text6').style.display='block'; {rdelim}" class="view" onmouseover="document.getElementById('text6').style='hand';"><img src="images/arrow-down2.jpg" border="none" width="23" height="48" onclick="toggle1(this,'images/arrow-down2.jpg','images/arrow-up2.jpg','id5');" id='id5'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id="text6" style="display:block; width: 218px; margin:0 0 0 0; float:left;">                      	
                  <ul class="vsitors-online">
                   
                   		<li>Members online -<span class="drop">{php}echo online("members", 10);{/php}</span></li>
                         <li>Guest online - <span class="drop">{php}echo online("guests", 10);{/php}</span></li>
                         <li>Total Members -<span class="drop">{php}echo stats("users");{/php}</span></li>
						 <li>Users online - <span class="drop">{php}echo users_online(10);{/php}</span></li>
                 </ul>

                   
                     
            	</div> 
                           
                                    
                                        <!--[if !IE]>sidebar-heading2<![endif]-->
                                        
                                    <!--[if !IE]> Visitors<![endif]--> 
                                            
                                           
                                        </div>
                           
                                <!--[if !IE]>vsitors-online<![endif]-->
                               
                        
                        </div>
                       
                    <!--[if !IE]>sidebar<![endif]-->
                    
                    	
                        
                        <!--[if !IE]>right<![endif]-->
                        	<div id="right">
                                                        {if $smarty.get.view}
                            	<!--[if !IE]>directory-content<![endif]-->
                        				
                                        <div id="directory-content">
                                        
                                       <h3> {$acp_page_view} / <span class="add-content">{$acp_page_do} {$acp_page_view2}</span>{$acp_bar_data}<br />                
</h3>
                                        
                                        </div>
                                <!--[if !IE]>directory-content<![endif]-->
                                
                                {/if}
                                
                                	
                        				
                                        <div id="form">