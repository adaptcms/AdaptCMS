<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AdaptCMS {$adaptcms_version} - {$acp_page}</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel='stylesheet' href='inc/js/menu.css'>
<script type='text/javascript' src='inc/js/menu.js'></script>
<script type='text/javascript' src='inc/js/sortmenu.js'></script>

</head>
<body>

	<!--[if !IE]>main<![endif]-->
    	<div id="main">
        
        	<!--[if !IE]>header-area<![endif]-->
            	
            	<div id="header-area">
                    
                    <div id="search-bar"><form action="admin.php?view=search" method="get">
                			
                           
                            
                            
                            </form>
                		</div>

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
                        	
                            		
                                        
                                     <!--[if !IE]>button<![endif]-->
                                     
                                     
                                     	<!--[if !IE]>icon<![endif]-->
                        	
                            				<div id="icon">
                                            	
                                                <ul>
                                                   
                                                    <li></li>  
                                                    <li></li>


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
                	
                    	
                       
                    <!--[if !IE]>sidebar<![endif]-->
                    
                    	
                        
                        <!--[if !IE]>right<![endif]-->
                        	<div id="right">
                                                        
                            	<!--[if !IE]>directory-content<![endif]-->
                        				
                                        <div id="directory-content">
                                        
                                       <h3> Admin / <span class="add-content">Login</span>
                                       <br />                
</h3>
                                        
                                        </div>
                                <!--[if !IE]>directory-content<![endif]-->
                                
                                
                                
                                	
                        				
                                        <div id="form">

					<form action='admin.php?view=login&act=login' method='post'>
					<table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Username</p><input type='text' name='username' class='addtitle' size='16' value='{$username}'></td></tr><tr><td><p><span class='drop'>Password</span></p><input type='password' name='password' class='addtitle' size='16'></td></tr><tr><td><p>Captcha</p>{$captcha}</td></tr><tr><td><br /><input type='submit' value='Login' class='addContent-button'></td></tr></table></form>