<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>AdaptCMS 2.0</title>
	<link rel="stylesheet" href="{$siteurl}style.css" />
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
</script>
{/literal}
	<script type="text/javascript" >
	function toggle(id,img1,img2,idname)
	{
 	 
		if (id.src.indexOf(img1)>0 )
		{
	 	
			document.getElementById(idname).src='{$siteurl}images/arrow-up2.jpg';	
			 
		}
		else if (id.src.indexOf(img2)>0)
		{

	 		document.getElementById(idname).src='{$siteurl}images/arrow-down2.jpg';
			 		 
		}
 
	}

</script>
{literal}
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

	<!--[if !IE]>main<![endif]-->
    	<div id="main">
        
        	<!--[if !IE]>header-area<![endif]-->
            	
            	<div id="header-area">
                
                	<!--[if !IE]>search-bar<![endif]-->
            	
            			<div id="search-bar"><form action="{$siteurl}index.php" method="get">
                			
                           <input class="search-button" type="submit" value="Search"/> <input type='hidden' name='view' value='search'>
                            
                            <input class="search-box" name="q" type="text" size="15" maxlength="25" />
                            </form>
                		</div>
                
            		<!--[if !IE]>search-bar<![endif]-->
                    
                    
                    	<!--[if !IE]>header<![endif]-->
            	
            				<div id="header">
                				
                                <!--[if !IE]>logo<![endif]-->
                                	
                                    <div id="logo">
                                    	
                                        <a href="{$siteurl}"><img src="{$siteurl}images/logo.jpg" alt="AdaptCMS 2.0" border="0" align="middle"/></a>
                                    
                                    </div>
                                <!--[if !IE]>logo<![endif]-->
                                
                                
                                	 <!--[if !IE]>right-header<![endif]-->
                                	
                                    	<div id="right-header">
                                		<img src="{$siteurl}images/banner2.jpg" width="468" height="60" alt="banner" align="right" border="0"/>
                                		</div>
                                    
                                	 <!--[if !IE]>right-header<![endif]-->
                                
                			</div>
                
            			<!--[if !IE]>header<![endif]-->
                        
                        
                        	<!--[if !IE]>navigation<![endif]-->
                        	
                            	<div id="navigation">
                                
                                	<!--[if !IE]>button<![endif]-->
                        	
                            			<div id="button">
                            	
                                            <ul>
			<li><a href="{$siteurl}">Home</a></li>
			<li><a href="{$siteurl}section/News">News</a></li>
			<li><a href="{$siteurl}section/reviews">Reviews</a></li>
			<li><a href="{$siteurl}media">Media</a></li>
			<li><a href="{$siteurl}page/1/Contact-Us/">Contact Us</a></li>
                                            </ul>
                                
                            			</div>
                                        
                                     <!--[if !IE]>button<![endif]-->
                                     
                                     
                                     	<!--[if !IE]>icon<![endif]-->
                        	
                            				<div id="icon">
                                            	
                                                <a href="{$siteurl}rss/"><img src="{$siteurl}images/rss.png" width="42" height="44" alt="icon" border="0" align="right"/></a>
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
                                        <!--[if !IE]>sidebar-heading<![endif]-->   
            								
                                            <div class="sidebar-heading2">
                
                			
                            <div class="heading-text1"> Basic menu</div>
                
                				<div class="arrow2">
                    <span onClick="if(document.getElementById('text6').style.display=='block'){ldelim}document.getElementById('text6').style.display='none'; {rdelim}else{ldelim}document.getElementById('text6').style.display='block'; {rdelim}" class="view" onmouseover="document.getElementById('text6').style='hand';"><img src="{$siteurl}images/arrow-down2.jpg" width="23" height="48" onclick="toggle(this,'{$siteurl}images/arrow-down2.jpg','{$siteurl}images/arrow-up.jpg','id5');" id='id5'  />
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id="text6" style="display:block; width: 218px; margin:0 0 0 0; float:left;">                      	
                  <ul class="side-menu2">
                   
			<li><a href="{$siteurl}"> Main Page</a></li>
                   	<li><a href="{$siteurl}polls/"> Polls </a></li>
                   	<li><a href="{$siteurl}archive/"> Archive </a></li>
                   	<li><a href="{$siteurl}pages/"> Pages </a></li>
                    <li><a href="{$siteurl}profile/"> Profile </a></li>
           
                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->
                                
                        		</div>
                    		<!--[if !IE]>sidebar1<![endif]-->
                            
                            <br /><br />
                    		<!--[if !IE]>sidebar3<![endif]-->
                    		<div id="sidebar3" class="menu">
                        
                                     
                                    
                                    <!--[if !IE]> basic menu<![endif]-->  
                                        <!--[if !IE]>sidebar-heading<![endif]-->   
            								
                                            <div class="sidebar-heading2">
                
                			
                            <div class="heading-text1">Poll</div>
                
                				<div class="arrow2">
                    
                    <span onClick="if(document.getElementById('text8').style.display=='block'){ldelim}document.getElementById('text8').style.display='none'; {rdelim}else{ldelim}document.getElementById('text8').style.display='block'; {rdelim}" class="view" onmouseover="document.getElementById('text8').style='hand';"><img src="{$siteurl}images/arrow-down2.jpg" width="23" height="48" onclick="toggle(this,'{$siteurl}images/arrow-down2.jpg','{$siteurl}images/arrow-up.jpg','id6');" id='id6'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id="text8" style="display:block; width: 218px; margin:0 0 0 0; float:left;">      
                        {php}
                            echo poll(1);
                            {/php}
                            </div>
                            
                            </div>
                            <br />
                            <!--[if !IE]>sidebar4<![endif]-->
                    		<div id="sidebar4" class="menu">
                        
                                     
                                    
                                    <!--[if !IE]> basic menu<![endif]-->  
                                        <!--[if !IE]>sidebar-heading<![endif]-->   
            								
                                            <div class="sidebar-heading2">
                
                			
                            <div class="heading-text1">Media</div>
                
                				<div class="arrow2">
                    
                    <span onClick="if(document.getElementById('text9').style.display=='block'){ldelim}document.getElementById('text9').style.display='none'; {rdelim}else{ldelim}document.getElementById('text9').style.display='block'; {rdelim}" class="view" onmouseover="document.getElementById('text9').style='hand';"><img src="{$siteurl}images/arrow-down2.jpg" width="23" height="48" onclick="toggle(this,'{$siteurl}images/arrow-down2.jpg','{$siteurl}images/arrow-up.jpg','id7');" id='id7'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id="text9" style="display:block; width: 218px; margin:0 0 0 0; float:left;">    
              
              <div align="center">     	
              {php}
echo media("media_page", "latestmedia", 3);
{/php}
</div>
              </div></div>
                            
                            <!--[if !IE]>endmenu<![endif]-->
                        </div>
                       
                    <!--[if !IE]>sidebar<![endif]-->
                    
                    	<!--[if !IE]>right<![endif]-->
                        	<div id="right">
                            

                                
                                
                                	<!--[if !IE]>content<![endif]-->
                        				
                                        <div id="form">