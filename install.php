<?php
$install = "yes";
include("config.php");

$sqldata["2.0"] = "INSERT INTO ".$pre."data VALUES (null, 'AdaptCMS', 'plugin_affiliates', 'http://www.adaptcms.com|yes|http://www.adaptcms.com/button.png|1|".time()."|0', 0);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Insane Visions', 'plugin_affiliates', 'http://www.insanevisions.com|yes||1|".time()."|0', 0);
-- --------------------------------------------------------
INSERT INTO ".$pre."pages VALUES (1, 'Contact Us', 'Hello and welcome to the site. We will update this page shortly on ways to contact us, thank you!', 1, '".time()."', 0);
-- --------------------------------------------------------
INSERT INTO ".$pre."plugins VALUES (null, 'Affiliates', 'affiliates.php', '1.0', 'On');
-- --------------------------------------------------------
INSERT INTO ".$pre."plugins VALUES (null, 'Sitemap', 'sitemap.php', '1.0', 'On');
-- --------------------------------------------------------
INSERT INTO ".$pre."plugins VALUES (null, 'TinyURL', 'tinyurl.php', '1.0', 'On');
-- --------------------------------------------------------
INSERT INTO ".$pre."plugins VALUES (null, 'Form Builder', 'form_builder.php', '1.0', 'On');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (null, 'affiliate_email', 'If there is an email entered, you will be notified when someone applies to be an affiliate.', 'webmaster@".$_SERVER['HTTP_HOST']."', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (null, 'sitemap_update', 'How often should a sitemap file be generated? (in days)', '14', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (null, 'sitemap_yahoo_key', 'Put in your yahoo API key (only required for yahoo to be pinged upon sitemap update)', '', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (null, 'date_type', 'Choosing \"new\" results in the format \"x minutes ago\" vs. something like \"Sep 13, 2011\" and enter \"old\" as the value.', 'old', 'setting', 'Modules');";

$install_temp[0] = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">

<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<title>AdaptCMS ".$version."</title>
	<link rel=\"stylesheet\" href=\"style.css\" />
	
	<script type=\"text/javascript\" >
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
	 	
			document.getElementById(idname).src='images/arrow-up2.jpg';	
			 
		}
		else if (id.src.indexOf(img2)>0)
		{

	 		document.getElementById(idname).src='images/arrow-down2.jpg';
			 		 
		}
 
	}

</script>


<script type=\"text/javascript\">

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
    $(this).children().animate({paddingLeft:\"20px\"}, {queue:false, duration:300});
  }).mouseout(function() {
    
    //Change background color and animate the padding
    $(this).css('backgroundColor','');
    $(this).children().animate({paddingLeft:\"0\"}, {queue:false, duration:300});
  });  
	
  //Mouseover effect for Posts, Comments, Famous Posts and Random Posts menu list.
  $('.boxBody li').click(function(){
    window.location = $(this).find(\"a\").attr(\"href\");
  }).mouseover(function() {
    $(this).css('backgroundColor','#888');
  }).mouseout(function() {
    $(this).css('backgroundColor','');
  });  	
	
});

</script>
</head>

<body>

	<!--[if !IE]>main<![endif]-->
    	<div id=\"main\">
        
        	<!--[if !IE]>header-area<![endif]-->
            	
            	<div id=\"header-area\">
                
                	<!--[if !IE]>search-bar<![endif]-->
            	
            			<div id=\"search-bar\">
                            </form>
                		</div>
                
            		<!--[if !IE]>search-bar<![endif]-->
                    
                    
                    	<!--[if !IE]>header<![endif]-->
            	
            				<div id=\"header\">
                				
                                <!--[if !IE]>logo<![endif]-->
                                	
                                    <div id=\"logo\">
                                    	
                                        <a href=\"\"><img src=\"images/logo.jpg\" alt=\"AdaptCMS ".$version."\" border=\"0\" align=\"middle\"/></a>
                                    
                                    </div>
                                <!--[if !IE]>logo<![endif]-->
                                
                                
                                	 <!--[if !IE]>right-header<![endif]-->
                                	
                                    	<div id=\"right-header\">
                                		<img src=\"images/banner2.jpg\" width=\"468\" height=\"60\" alt=\"banner\" align=\"right\" border=\"0\"/>
                                		</div>
                                    
                                	 <!--[if !IE]>right-header<![endif]-->
                                
                			</div>
                
            			<!--[if !IE]>header<![endif]-->
                        
                        
                        	<!--[if !IE]>navigation<![endif]-->
                        	
                            	<div id=\"navigation\">
                                
                                	<!--[if !IE]>button<![endif]-->
                        	
                            			<div id=\"button\">
                            	
                                            <ul>
			<li><a href=\"\">Home</a></li>
			<li><a href=\"section/News\">News</a></li>
			<li><a href=\"section/reviews\">Reviews</a></li>
			<li><a href=\"media\">Media</a></li>
			<li><a href=\"page/contact-us\">Contact Us</a></li>
                                            </ul>
                                
                            			</div>
                                        
                                     <!--[if !IE]>button<![endif]-->
                                     
                                     
                                     	<!--[if !IE]>icon<![endif]-->
                        	
                            				<div id=\"icon\">
                                            	
                                                <a href=\"rss\"><img src=\"images/rss.png\" width=\"42\" height=\"44\" alt=\"icon\" border=\"0\" align=\"right\"/></a>
                                            </div>
                                            
                                       	<!--[if !IE]>icon<![endif]-->     
                                
                                </div>
                                	
                			<!--[if !IE]>navigation<![endif]-->
                        
                </div>
                
            <!--[if !IE]>header-area<![endif]-->
        
        </div>
    <!--[if !IE]>main<![endif]-->
    
    
    	<!--[if !IE]>bg-pattern<![endif]-->
                        	
             <div id=\"bg-pattern\">
             
             	<!--[if !IE]>content-part<![endif]-->
                	
                    <div id=\"content-part\">
                    
     
                
                	<!--[if !IE]>sidebar<![endif]-->
                	
                    	<div id=\"sidebar\">
                        
                        	<!--[if !IE]>sidebar1<![endif]-->
                	
                    			<div id=\"sidebar1\" class=\"menu\">
                        
                                   
                                   
                                    
                                    
                                    <!--[if !IE]> basic menu<![endif]-->  
                                        <!--[if !IE]>sidebar-heading<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\"> Basic menu</div>
                
                				<div class=\"arrow2\">
                    <span onClick=\"if(document.getElementById('text6').style.display=='block'){ldelim}document.getElementById('text6').style.display='none'; {rdelim}else{ldelim}document.getElementById('text6').style.display='block'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById('text6').style='hand';\"><img src=\"images/arrow-down2.jpg\" width=\"23\" height=\"48\" onclick=\"toggle(this,'images/arrow-down2.jpg','images/arrow-up.jpg','id5');\" id='id5'  />
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id=\"text6\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"side-menu2\">
                   
                   	<li><a href=\"#\"> menu1 </a></li>
                   	<li><a href=\"#\"> menu2 </a></li>
                   	<li><a href=\"#\"> menu3 </a></li>
                    <li><a href=\"#\"> menu4 </a></li>
                    <li><a href=\"#\"> menu5 </a></li>
           
                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->
            
             
                                
                        		</div>
                    		<!--[if !IE]>sidebar1<![endif]-->
                            
                            
                            	
                        </div>
                       
                    <!--[if !IE]>sidebar<![endif]-->
                    
                    	<!--[if !IE]>right<![endif]-->
                        	<div id=\"right\">
                            

                                
                                
                                	<!--[if !IE]>content<![endif]-->
                        				
                                        <div id=\"form\">";

$install_temp[1] = "                                    	</div>
                                        
                                    <!--[if !IE]>content<![endif]-->
                            
                            </div>
                        <!--[if !IE]>right<![endif]-->    
                   
                        
                        
                         </div>
                <!--[if !IE]>content-part<![endif]-->
                
                
                	<!--[if !IE]>footer<![endif]-->
                        	<div id=\"footer\">
                        	<a href=\"http://www.adaptcms.com\"><img src=\"http://www.adaptcms.com/button.png\" align=\"left\" style=\"padding-left:5px;padding-top:6px\"></a>
                           <p>Copyright 2006-2011 - <a href=\"http://www.insanevisions.com\">Insane Visions</a><p>
                            
                            </div>
                        <!--[if !IE]>footer<![endif]-->	
             
             </div>
             	
		<!--[if !IE]>bg-pattern<![endif]-->

</body>
</html>";
$sqldata["tables"] = "-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."comments (
  id int(11) unsigned NOT NULL auto_increment,
  article_id text NOT NULL,
  user_id text NOT NULL,
  `comment` text NOT NULL,
  rating text NOT NULL,
  email text NOT NULL,
  website text NOT NULL,
  ip text NOT NULL,
  `status` text NOT NULL,
  `date` int(12) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."content (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  section text NOT NULL,
  user_id int(11) NOT NULL,
  `status` text NOT NULL,
  `date` int(11) NOT NULL,
  last_edit int(11) NOT NULL,
  mdate int(3) NOT NULL,
  ydate int(4) NOT NULL,
  rating text NOT NULL,
  views int(11) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."data (
  id int(11) unsigned NOT NULL auto_increment,
  field_name text NOT NULL,
  field_type text NOT NULL,
  `data` text NOT NULL,
  item_id text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."fields (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  section text NOT NULL,
  `type` text NOT NULL,
  description text NOT NULL,
  `data` text NOT NULL,
  editable text NOT NULL,
  `limit` text NOT NULL,
  required text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."files (
  id int(11) unsigned NOT NULL auto_increment,
  filename text NOT NULL,
  filedir text NOT NULL,
  caption text NOT NULL,
  media_id int(11) NOT NULL,
  `date` int(12) NOT NULL default '0',
  rating text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."groups (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  color text NOT NULL,
  image text NOT NULL,
  options text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."levels (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `data` text NOT NULL,
  points int(11) NOT NULL,
  `group` text NOT NULL,
  color text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."media (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  views int(11) NOT NULL,
  rating text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."messages (
  id int(11) NOT NULL auto_increment,
  viewed int(1) default '0',
  `subject` text,
  message text,
  sender_id int(11) default NULL,
  receiver_id int(11) default NULL,
  `date` int(11) default NULL,
  box text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."pages (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  content text NOT NULL,
  user_id int(11) NOT NULL,
  `date` int(12) NOT NULL default '0',
  views int(11) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."permissions (
  id int(11) unsigned NOT NULL auto_increment,
  `group` text NOT NULL,
  `name` text NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."plugins (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  url text NOT NULL,
  version text NOT NULL,
  `status` varchar(5) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."polls (
  id int(11) unsigned NOT NULL auto_increment,
  article_id int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `type` text NOT NULL,
  options text NOT NULL,
  poll_id int(11) NOT NULL,
  votes text NOT NULL,
  `date` int(12) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."sections (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."settings (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  description text NOT NULL,
  `data` text NOT NULL,
  `type` text NOT NULL,
  section text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."skins (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  skin text NOT NULL,
  template text NOT NULL,
  `date` int(12) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."stats (
  id int(11) unsigned NOT NULL auto_increment,
  `page` text NOT NULL,
  referer_url text NOT NULL,
  referer_name text NOT NULL,
  referer_keyword text NOT NULL,
  visits_num int(11) NOT NULL,
  visit_type text NOT NULL,
  cookie_id text NOT NULL,
  browser text NOT NULL,
  os text NOT NULL,
  user_id int(11) NOT NULL,
  ip text NOT NULL,
  `day` int(3) NOT NULL,
  tday int(3) NOT NULL,
  `month` int(2) NOT NULL,
  `week` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  time_first_visit int(11) NOT NULL,
  time_last_visit int(11) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."stats_archive (
  id int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `data` text NOT NULL,
  `week` int(2) NOT NULL,
  `month` int(2) NOT NULL,
  `year` int(4) NOT NULL,
  views int(20) NOT NULL,
  uniques int(20) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS ".$pre."users (
  id int(11) unsigned NOT NULL auto_increment,
  username text NOT NULL,
  `password` text NOT NULL,
  email text NOT NULL,
  `group` text NOT NULL,
  `level` text NOT NULL,
  last_login int(12) NOT NULL default '0',
  reg_date int(12) NOT NULL default '0',
  act char(3) NOT NULL default 'no',
  ver char(3) NOT NULL default 'no',
  `status` text NOT NULL,
  status_time int(11) NOT NULL,
  `skin` text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=1;";

$sqldata["content"] = "INSERT INTO ".$pre."content VALUES (1, 'Welcome to AdaptCMS!', 'News', 1, '', '".time()."', '0', '".date("m")."', '".date("Y")."', '0|0', '0');
-- --------------------------------------------------------";
$sqldata["data"] = "INSERT INTO ".$pre."data VALUES (1, 'name', 'content-name', 'Welcome to AdaptCMS!', '1');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (2, 'description', 'content-custom-data', 'Hellow and welcome to your copy of AdaptCMS 2.0! This is a default news content item to simply show the basic workings of the CMS in the frontend area of the script. You can easily delete this by going to the admin area of the script at \'admin.php\', click content and you will see a delete icon next to the title of this posting. Please enjoy your copy of this script.', '1');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Content', 'help-file', '<p>This is where it\'s all at! AdaptCMS is geared towards content websites and here we have it, content. You can completely manage content (including comments) here.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Add', 'help-file', '<p><strong>Adding Content</strong></p>\r\n<p>From the main content page, the top right you will see \"add\" and then a dropdown for the section in which you want to add content to.</p>\r\n<p>On the Add Content form what you will see depends on the sections you have and the custom fields added. But you will first see the field \"title\" for the name of the content piece. (this will show in the URL) Below that is the ability to \"link\" content, so if you have a game section and are adding a news story you could click on the game title - you could then show the game information on the news article or vice-versa, very powerful.</p>\r\n<p>After that are any custom fields you have added and then a \"Publish Later\" options. It\'s for various uses, but the jist is you choose a date by clicking the calendar icon and the content piece will not go live for the public to see until that date.</p>\r\n<p>Lastly is a \"Tags\" feature commonly seen across the web, better for searching as well as for a tag cloud. You can then either choose to add the content item or \"Save Draft\", so it will be added but not viewable yet - great if you have an editor/publisher staff situation setup or just want to work on it later.</p>', 'Content');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Content</strong></p>\r\n<p>Let\'s start out with the simple - click the red icon and after a confirm box, the content piece is deleted forever. The yellow exclamation is to \"verify\" the item. This is used in various ways, but it means the user who submitted it has there permissions edited so there content pieces have to be approved before going live. Clicking the icon sends the content live for all to see.</p>\r\n<p>For specifics on the various fields and such you can see the \"Add Content\" help file. The difference is at the end of the form where you can choose to \"Update\" which simply updates the content piece and keeps its status as is or you can \"Publish Later\" which sends it to draft, or if it is already saved to draft you can update it and have it go live.</p>', 'Content');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Custom Fields', 'help-file', '<p>One of the key aspects of content and very helpful to make AdaptCMS adaptable to any content website. With custom fields you can setup a&nbsp; wide vary of different setups.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Add', 'help-file', '<p><strong>Add Field</strong></p>\r\n<p>To start, pick a name for the field - don\'t worry about caps, all spaces are removed and converted to all lowercase after submission. Next you need to pick the section to add the field to - hold \"control\" and click on others to add it for multiple sections. Choosing \"User Profiles\" is not a normal section, it\'s for the profile area of the CMS - so you can add things like \"AIM\" or \"Website\" so they have a nice informative profile for others to see.</p>\r\n<p>If your not sure what these types are - \"textfield\" is just a normal input like what you\'d put your username in, \"textarea\" what you will see right below the type selection, dropdown is what you\'re using radiobox is the circular little button selection and checkbox a little square and lastly - file, where you can select an uploaded file for the field data.</p>\r\n<p>Data is for only dropdown, radio and checkbox. So you enter the data, like \"XBOX 360,PS3,PC,PSP\" will produce say a dropdown with those selections. Description you can put in brief info about the use of this field, useful for other staff members.</p>\r\n<p>New to AdaptCMS 2.x is the last 3 options - clicking editable will let regular logged in users be able to edit this field in the frontend. (but the permissions to let them first edit the section the content item belongs in has to be selected) Max/Minimum is a character limit on the field data and lastly - Required, if clicked then the content item won\'t be added until this field has data.</p>', 'Custom Fields');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Field</strong></p>\r\n<p>Clicking the red icon will delete the field after a confirm, editing is pretty much unchanged from adding a field except the fields are filled out. Refer to the \"Add\" help file for information on that aspect.</p>', 'Custom Fields');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Sections', 'help-file', '<p>Another aspect of Content, Sections let you manage content in a nice clean way. However since there is just a name to fill out for this, no point in having more than this brief help file.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Media', 'help-file', '<p>Formerly known as \"Gallery\" in AdaptCMS 1.x, in AdaptCMS 2.x is a heavily improved (actually re-done) feature that boasts both image and video support.</p>\r\n<p>Media is tool to let you manage all video and images, simply upload some pictures or have a full blown media gallery with albums and hundreds of pictures inside for people to check out, have fun!</p>\r\n<p><strong>Main Media Page</strong></p>\r\n<p>For AdaptCMS 1.x users, the main media page will look completely different - it\'s broken up into two different areas - the top has the media albums with appropiate actions and below a file list (with a click of a button via the dropdown, can show all files or files not associated with an album).</p>\r\n<p>In the bar above both areas you will find a link to \"Add File\" and then \"Add Media\", the former that lets you create an album. For the album area you can either click on the preview picture or the plus button to upload new files to the album, the wrench to edit or the red icon to delete the album and files within it.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Add File', 'help-file', '<p><strong>Add a File</strong></p>\r\n<p>Before you start, find out how many to upload so you can first enter in how many up top by \"Add more Files\" to populate the appropiate amount of fields. Next you will see that list.</p>\r\n<p>You can choose to either upload this new file from a file on your computer or link to a file on an external website. (linking to another site is useful if the file is outside your \"upload\" folder, however the watermark and re-size will not work for the file in that case)</p>\r\n<p>Clicking watermark will put a little watermark icon like you\'d see the FOX logo when watching TV, it uses the \"watermark.png\" image or whatever you enter in the settings area. Re-size lets you re-size the image, do not use if it is anything other than an image. (such as a video) Lastly a caption - all files are optional except of course the file itself.</p>', 'Media');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Edit File', 'help-file', '<p><strong>Edit File</strong></p>\r\n<p>With a preview (if it\'s an image or recognized file type) to the left, you can rename the file (but recommend not to change file extension) and the \"watermark\", \"re-size\" and \"caption\" features detailed in the \"Add File\" help file. Clicking the delete checkbox will delete the file both in database and in physical form. You can then lastly choose a media album to link the file to.</p>', 'Media');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Manage Album', 'help-file', '<p><strong>Manage Media</strong></p>\r\n<p>At the top you can rename the media album and below all files linked to the album will be listed and you can edit there details - info on those functions can be found in \"Add File\".</p>', 'Media');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Pages', 'help-file', '<p>Maybe not the most important aspect of AdaptCMS, but still a very useful feature that all websites use. Static pages - such as \"Contact Us\", \"Privacy Policy\", \"About\" and the like - what you will find on 90% of websites.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Add', 'help-file', '<p><strong>Add Page</strong></p>\r\n<p>This will be pretty simple - choose a name, which will appear in the URL and then enter in the page contents below. Yep, that simple.</p>\r\n<p>When returning to the main pages area you will see the page and can click on it for the URL.</p>', 'Pages');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Users', 'help-file', '<p>Without users there is no website! You need viewers and after time you need those viewers to become members to contribute and not only that, help bring more traffic in.</p>\r\n<p>With the users area of the ACP you can completely manage an individual users information in conjuction with \"Groups\" and \"Levels\".</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Add', 'help-file', '<p><strong>Add Users</strong></p>\r\n<p>Traditionally you will just have people sing up through the frontend of the website but you may need to manually add a user for whatever reason. Simply fill out the username, password, email and assign a group and you\'re done!</p>', 'Users');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Users</strong></p>\r\n<p>On the main users page under a specific user you will find either a green check mark or a red X under \"verified\" and \"activated\". You can change the status of both at any time - verified is your staffs responsibility, if the setting is turned on new members cannot login into there account until there account is verified by staff. Activate is e-mail activation.</p>\r\n<p>When editing a user account you can choose to rename the user, set a new password, change there e-mail or assign them to a different group.</p>', 'Users');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Groups', 'help-file', '<p>Formerly known as \"Permissions\" and \"Levels\" in AdaptCMS 1.x, \"Groups\" forms both things into just one. You can manage the various groups such as Members, Admins and Staff and adjust there permission settings - which basically tells the CMS what features those users can use.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Add', 'help-file', '<p><strong>Add Group</strong></p>\r\n<p>To start you need to enter in a group name - keep it simple. Next you choose a default permission set - you will be able to edit the full permissions after it\'s been added. (helps keep things simple) As well a default color for the group, image icon can be set.</p>\r\n<p>Lastly - default is an important aspect you will want to pay attention to. The primary use is for regular members, to mainly help the CMS - be sure to only have on checked and it be your normal member group.</p>', 'Groups');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Group</strong></p>\r\n<p>Let\'s just skip over the top fields, you can find out there info in the \"Add\" group help file - we\'ll skip the fun part, permissions!</p>\r\n<p>The permissions are split into two areas - \"admin\" which is the various modules and \"content\" for section-specific permissions. To begin with the admin options are self-explanatory, if you dissallow adding say a field for the group \"Staff\" and user \"joebloe\" is assigned to that group, they cannot add a field.</p>\r\n<p>The section-specific permissions have more meaning. First, if you don\'t have edit or delete clicked but just \"Add\" of the three, then you can add a content item to that section as well as edit/delete your own but not others. \"Verify\" means if a user from that group adds a content item for that section it will not go live for others to see until it has been verified by staff.</p>\r\n<p>Lastly for user-content or users to be able to edit an article, you need to grant them permission here - but don\'t worry, if the normal \"content\" permissions under \"admin\" aren\'t selected, they cant access the ACP. For normal users, requiring verification would be a wise thing.</p>', 'Groups');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Skins', 'help-file', '<p>What you see! Basically true, as without a skin you would see nothing but a white page on the frontend and no admin design in the ACP. New to 2.x is \"skins\", transforming the old template system to a more versatile advanced system.</p>\r\n<p>Now using smarty (tags that were {title} are now {$title}!) with template caching and the ability to not only edit the admin skin but to also have multiple skins.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Add', 'help-file', '<p><strong>Add Skin</strong></p>\r\n<p>First to add a skin is an insanely easy process, simply enter a name and the default templates are inserted automatically.</p>\r\n<p><strong>Add Template</strong></p>\r\n<p>An easy process as well, you start out with entering in a template name and then you can choose (but optional) a skin to assign it to. Lastly enter the template data.</p>', 'Skins');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Skins/Templates</strong></p>\r\n<p>Explained on the \"add\" help file under Skins, but on the main page of \"Skins\" you will see the top area list show the skins and next to the name is a dropdown to edit a specific template from that skin. The other list is just all templates added.</p>', 'Skins');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Polls', 'help-file', '<p>One important aspect of user interaction is polls. A similar pollinig feature that you saw in AdaptCMS 1.x returns but in a improved form in 2.x.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Add', 'help-file', '<p><strong>Add Poll</strong></p>\r\n<p>As with every other module, you need a name, in this case it\'s the poll question. Then you have two options - can people choose more than one option when voting on the poll and can a user enter a new poll \"option\". (something unique to adaptcms)</p>\r\n<p>The other part is the actuall poll options. Just one will be listed, but you can click \"Add Option\" to populate additional ones and click the red X to delete it. When you are done, click \"Add Poll\".</p>', 'Polls');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Poll</strong></p>\r\n<p>The info detailed in \"Add\" help file already, but starting with the options list you can edit the name or click the check mark to have the option deleted upon submit. Then you can add brand new options as well, clicking \"Add Option\" to populate a new option.</p>\r\n<p>Lastly click \"Edit Poll\" to submit&nbsp; or if you click \"Delete Poll\", the poll and it\'s associated options will be deleted from the database.</p>', 'Polls');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'ACP', 'help-file', '<p><strong>A</strong>dmin <strong>C</strong>ontrol <strong>P</strong>anel</p>\r\n<p>This help file will contain just random tidbits of information on the ACP.</p>\r\n<p><span style=\"text-decoration: underline;\">ACP Bar</span> - One of the new features to 2.x, the top ACP bar is present in the ACP and if you put the {$acp_bar} tag in your \"header\" template, in the frontend as well. Simply click on \"Toggle\" which will be at the top right of your screen where you will find a plethora of information, from newest members to stats for the day.</p>\r\n<p><span style=\"text-decoration: underline;\">Version Check</span> - If you are using the default admin_footer template, on the bottom left, you will see \"You are running <span class=\"drop\">- AdaptCMS 2.0.0 Beta\" (or whatever version you are running) as well as an image to the left that\'s either a check mark or a red X. If it is a red X like so <img src=\"http://www.adaptcms.com/images/cancel.png\" alt=\"\" width=\"32\" height=\"32\" />which means you need to upgrade. Just click the icon and it will take you to the AdaptCMS website to upgrade your copy of AdaptCMS.<br /></span></p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Social', 'help-file', '<p><strong>Social</strong></p>\r\n<p>This is a combination of basic aspects of social management from AdaptCMS 1.x and largely new features. With the new Social set of features the AdaptCMS team has laid the foundation of social management in AdaptCMS.</p>\r\n<p>To start you can begin from your own profile page, which will look like this URL wise - http://www.yoursite.com/profile/yourusername</p>\r\n<p>It will of course depend on how you design it (yes, you can skin the social features!) but by default you will find your latest update on the top left, your username and update twitter-like box, below that the social menu\'s and then the profile info and latest status updates.</p>\r\n<p>\"Edit\" will take you to edit your profile info, from password and e-mail to the website skin and any custom fields. Under profile you can return to the main page as well as the status update page.</p>\r\n<p>\"Friends\" is another new feature, letting you add friends or accept them as well as see which ones you have. The main use at the moment is for your \"feed\", status updates will include your friends. \"Blogs\" another new feature which lets you manage your own little small blog, you can add/edit/delete as well users can comment on them.</p>\r\n<p>Lastly from AdaptBB is \"Messages\", a private-messaging type system.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'AdaptCMS', 'plugin_affiliates', 'http://www.adaptcms.com|yes|http://www.adaptcms.com/button.png|1|".time()."|0', 0);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Insane Visions', 'plugin_affiliates', 'http://www.insanevisions.com|yes||1|".time()."|0', 0);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Female Black (pink shirt)', 'social-avatar', 'user_female_black_pink.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Male White (ginger hair, blue shirt)', 'social-avatar', 'user_male_white_blue_ginger.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Male White (blue shirt)', 'social-avatar', 'user_male_white_blue.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Male Olive (blue shirt)', 'social-avatar', 'user_male_olive_blue.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Male White (blue shirt, blonde hair)', 'social-avatar', 'user_male_white_blue_blonde.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Female White (pink shirt, black hair)', 'social-avatar', 'user_female_white_pink_black.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Female White (pink shirt, blonde hair)', 'social-avatar', 'user_female_white_pink_blonde.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Male Black (blue shirt, black hair)', 'social-avatar', 'user_male_black_blue_black.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Female Black (red shirt)', 'social-avatar', 'user_female_black_red.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'Female White (pink shirt, brown hair)', 'social-avatar', 'user_female_white_pink_brown.png', 1);
-- --------------------------------------------------------
INSERT INTO ".$pre."data VALUES (null, 'avatar', 'custom-profile-data', '".$siteurl."upload/avatar/user_male_white_blue.png', 1);";

$sqldata["fields"] = "INSERT INTO ".$pre."fields VALUES (1, 'description', 'News', 'textarea', 'Description of the news story.', '', 'yes', '10000/25', 'yes');
-- --------------------------------------------------------
INSERT INTO ".$pre."fields VALUES (2, 'full_story', 'News', 'textarea', 'Full story of the news article.', '', '', '/', 'yes');
-- --------------------------------------------------------
INSERT INTO ".$pre."fields VALUES (3, 'review_contents', 'Reviews', 'textarea', 'Description of the news story.', '', 'yes', '10000/25', 'yes');";

$sqldata["files"] = "INSERT INTO ".$pre."files VALUES (1, 'zune-hd-sample.jpg', 'upload/', '', 1, '".time()."', '0|0');
-- --------------------------------------------------------";

$sqldata["groups"] = "INSERT INTO ".$pre."groups VALUES (1, 'Administrator', '', '', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."groups VALUES (2, 'Member', '', '', 'default-member');
-- --------------------------------------------------------
INSERT INTO ".$pre."groups VALUES (3, 'Staff', '', '', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."groups VALUES (4, 'Guest', '', '', 'default-guest');";

$sqldata["levels"] = "INSERT INTO ".$pre."levels VALUES (1, 'Veteran', 'level', '', 100, '', 'red');
-- --------------------------------------------------------
INSERT INTO ".$pre."levels VALUES (2, 'Posted Comment', 'point', 'index.php?do=comments&submit=yes', 25, '', '');";

$sqldata["media"] = "INSERT INTO ".$pre."media VALUES (1, 'Sample Album', 0, '0|0');
-- --------------------------------------------------------";

$sqldata["pages"] = "INSERT INTO ".$pre."pages VALUES (1, 'Contact Us', 'Hello and welcome to the site. We will update this page shortly on ways to contact us, thank you!', 1, '".time()."', 0);
-- --------------------------------------------------------";

$sqldata["permissions"] = "INSERT INTO ".$pre."permissions VALUES (30, 'Administrator', 'comments', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (53, 'Staff', 'comments', '1||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (5, 'Administrator', 'content', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (18, 'Member', 'content', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (52, 'Staff', 'content', '1||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (4, 'Administrator', 'fields', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (17, 'Member', 'fields', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (51, 'Staff', 'fields', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (14, 'Administrator', 'files', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (29, 'Member', 'files', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (64, 'Staff', 'files', '1||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (2, 'Administrator', 'groups', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (19, 'Member', 'groups', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (54, 'Staff', 'groups', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (8, 'Administrator', 'levels', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (22, 'Member', 'levels', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (57, 'Staff', 'levels', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (3, 'Administrator', 'media', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (16, 'Member', 'media', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (50, 'Staff', 'media', '1||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (15, 'Administrator', 'News', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (65, 'Staff', 'News', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (13, 'Administrator', 'pages', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (28, 'Member', 'pages', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (63, 'Staff', 'pages', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (6, 'Administrator', 'plugins', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (20, 'Member', 'plugins', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (55, 'Staff', 'plugins', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (12, 'Administrator', 'polls', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (27, 'Member', 'polls', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (62, 'Staff', 'polls', '1||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (31, 'Member', 'Reviews', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (32, 'Administrator', 'Reviews', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (66, 'Staff', 'Reviews', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (7, 'Administrator', 'sections', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (21, 'Member', 'sections', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (56, 'Staff', 'sections', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (9, 'Administrator', 'settings', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (23, 'Member', 'settings', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (58, 'Staff', 'settings', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (10, 'Administrator', 'skins', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (24, 'Member', 'skins', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (59, 'Staff', 'skins', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (11, 'Administrator', 'tools', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (26, 'Member', 'tools', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (61, 'Staff', 'tools', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (1, 'Administrator', 'users', '1|1|1|');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (25, 'Member', 'users', '||');
-- --------------------------------------------------------
INSERT INTO ".$pre."permissions VALUES (60, 'Staff', 'users', '||');";

$sqldata["polls"] = "INSERT INTO ".$pre."polls VALUES (1, 0, 'Your favorite social website?', 'poll', ',', 1, '0', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."polls VALUES (2, 0, 'Facebook', 'option', '', 1, '0', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."polls VALUES (3, 0, 'Google+', 'option', '', 1, '0', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."polls VALUES (4, 0, 'LinkedIn', 'option', '', 1, '0', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."polls VALUES (5, 0, 'MySpace', 'option', '', 1, '0', '".time()."');";

$sqldata["plugins"] = "INSERT INTO ".$pre."plugins VALUES (null, 'Affiliates', 'affiliates.php', '1.0', 'On');
-- --------------------------------------------------------
INSERT INTO ".$pre."plugins VALUES (null, 'Sitemap', 'sitemap.php', '1.0', 'On');
-- --------------------------------------------------------
INSERT INTO ".$pre."plugins VALUES (null, 'TinyURL', 'tinyurl.php', '1.0', 'On');
-- --------------------------------------------------------
INSERT INTO ".$pre."plugins VALUES (null, 'Form Builder', 'form_builder.php', '1.0', 'On');";

$sqldata["sections"] = "INSERT INTO ".$pre."sections VALUES (1, 'News');
-- --------------------------------------------------------
INSERT INTO ".$pre."sections VALUES (2, 'Reviews');";

$sqldata["settings"] = "INSERT INTO ".$pre."settings VALUES (1, 'General', '', '', 'section', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (2, 'Other', '', '', 'section', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (3, 'wysiwyg', '', 'yes', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (4, 'mod_rewrite', 'Enable search engine friendly URLs?', 'yes', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (5, 'online', 'Website Online? Enter yes or no.', 'yes', 'setting', 'General');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (6, 'sitename', 'Name of your website', '".$_POST['sitename']."', 'setting', 'General');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (7, 'limit', 'How many items to list per page in the ACP', '15', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (8, 'Variables', '', '', 'section', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (9, 'date_format', 'Date format for content items and pretty much whever you see a date', 'M d, Y - g:i a', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (10, 'home_section', 'On main page, what section of content to show? (blank shows content from all sections)', 'News', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (11, 'Modules', '', '', 'section', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (12, 'cache', 'Enable cache for content? Recommended no at the moment.', 'no', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (13, 'poll_type', 'Type of display for polls, graphic or text.', 'graphic', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (14, 'gallery_width', 'Width of a thumbnail image for media.', '120', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (15, 'gallery_height', 'Height of a thumbnail image for media.', '90', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (16, 'banned_ips', 'IPs to be banned from your website, useful for spammers or any other unsavory characters.', 'localhost,127.0.0.0', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (17, 'banned_users', 'Users to be banned, though banning IP seems much more usefl', '', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (18, 'sitemap_update', 'How often should your sitemap be updated? Anything less than a week is really useless', '14', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (19, 'sitemap_yahoo_key', '', 'yQ4td7V34EiEsp1YGCR85fId87lSebdN5vcNLziHQClPyavUarX0XlEPsq7v2w', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (20, 'captcha_comments', 'Require captcha to be filled in for a posted content? Recommended to enter yes.', 'yes', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (21, 'offline_message', '', 'Sorry, but we are currently offline. Please come back soon.', 'setting', 'General');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (22, 'captcha_login', 'Require captcha to be filled in for users to login? Highly recommend yes', 'yes', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (23, 'trackback_urls', '', 'http://www.bblog.com/ping.php,http://ping.blo.gs', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (24, 'Polls', '', '', 'section', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (25, 'custom_poll_limit', 'What is the max amount of custom poll options that can be entered? (note that the custom poll option must be checked for the specific poll and someone may only enter 1 themselves)', '3', 'setting', 'Polls');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (26, 'custom_poll_guest', 'Can guests enter in a custom poll option? (applicable if the \"custom poll option\" is checked for the specific poll)', 'no', 'setting', 'Polls');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (27, 'admin_limit', '', '20', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (28, 'word_filter', 'Word filter, these words will be stripped out of comments.', 'fuck,bitch,cunt,whore,nigger', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (29, 'upload_folder', 'Folder where uploaded media items are saved to.', 'upload/', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (30, 'file_extensions', 'Allowed file extensions of uploaded items in media.', 'jpg,png,mp3,wmv,zip,txt', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (31, 'ratings_guests_comments', 'Can guests rate comments?', 'yes', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (32, 'ratings_guests_content', 'Can guests rate content?', 'yes', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (33, 'comment_flood_limit', 'Amount of time to pass before another comment can be posted by someone', '20', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (34, 'cookie_prefix', 'Prefix for adaptcms cookies', 'adaptcms_', 'setting', 'General');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (35, 'message_limit', 'Max allowed messages in inbox', '50', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (36, 'profile_username_change', 'Can users change there username via edit profile?', 'yes', 'setting', 'Variables');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (37, 'status_char_limit', 'Limit the amount of characters allowed in a status update.', '140', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (38, 'section_limit', 'Amount of content items to display on a section listing page.', '10', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (39, 'status_limit', '', '3', 'setting', 'General');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (40, 'message_limit_page', 'Amount of messages to display per page.', '10', 'setting', 'General');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (41, 'blog_limit', 'Amount of blog entries to show', '5', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (null, 'affiliate_email', 'If there is an email entered, you will be notified when someone applies to be an affiliate.', 'webmaster@".$_SERVER['HTTP_HOST']."', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (null, 'sitemap_update', 'How often should a sitemap file be generated? (in days)', '14', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (null, 'sitemap_yahoo_key', 'Put in your yahoo API key (only required for yahoo to be pinged upon sitemap update)', '', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."settings VALUES (null, 'date_type', 'Choosing \"new\" results in the format \"x minutes ago\" vs. something like \"Sep 13, 2011\" and enter \"old\" as the value.', 'old', 'setting', 'Modules');";

$sqldata["skins"] = "INSERT INTO ".$pre."skins VALUES(2, 'admin_header', 'main', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>AdaptCMS {$adaptcms_version} - {$acp_page}</title>
<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\" />
<link rel=\'stylesheet\' href=\'inc/js/menu.css\'>
<script type=\'text/javascript\' src=\'inc/js/menu.js\'></script>
<script type=\'text/javascript\' src=\'inc/js/sortmenu.js\'></script>
{literal}
<script type=\"text/javascript\" >
	$(\'document\').ready(function(){
		$(\'#flip-container\').quickFlip();
		
		$(\'#flip-navigation li a\').each(function(){
			$(this).click(function(){
				$(\'#flip-navigation li\').each(function(){
					$(this).removeClass(\'selected\');
				});
				$(this).parent().addClass(\'selected\');
				var flipid=$(this).attr(\'id\').substr(4);
				$(\'#flip-container\').quickFlipper({ }, flipid, 1);
				
				return false;
			});
		});
	});



	function toggle(id,img1,img2,idname)
	{
 	 
		if (id.src.indexOf(img1)>0 )
		{
	 	
			document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-up.jpg\';	
			 
		}
		else if (id.src.indexOf(img2)>0)
		{

	 		document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-down.jpg\';
			 		 
		}
 
	}
	
	
function toggle1(id,img1,img2,idname)
	{
 	 
		if (id.src.indexOf(img1)>0 )
		{
	 	
			document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-up2.jpg\';	
			 
		}
		else if (id.src.indexOf(img2)>0)
		{

	 		document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-down2.jpg\';
			 		 
		}
 
	}
	


</script>




<script type=\"text/javascript\">

$(document).ready(function() {	


  //Get all the LI from the #tabMenu UL
  $(\'#tabMenu > li\').click(function(){
        
    //remove the selected class from all LI    
    $(\'#tabMenu > li\').removeClass(\'selected\');
    
    //Reassign the LI
    $(this).addClass(\'selected\');
    
    //Hide all the DIV in .boxBody
    $(\'.boxBody div\').slideUp(\'1500\');
    
    //Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.
    $(\'.boxBody div:eq(\' + $(\'#tabMenu > li\').index(this) + \')\').slideDown(\'1500\');
    
  }).mouseover(function() {

    //Add and remove class, Personally I dont think this is the right way to do it, anyone please suggest    
    $(this).addClass(\'mouseover\');
    $(this).removeClass(\'mouseout\');   
    
  }).mouseout(function() {
    
    //Add and remove class
    $(this).addClass(\'mouseout\');
    $(this).removeClass(\'mouseover\');    
    
  });

  //Mouseover with animate Effect for Category menu list
  $(\'.boxBody #category li\').mouseover(function() {

    //Change background color and animate the padding
    $(this).css(\'backgroundColor\',\'#888\');
    $(this).children().animate({paddingLeft:\"20px\"}, {queue:false, duration:300});
  }).mouseout(function() {
    
    //Change background color and animate the padding
    $(this).css(\'backgroundColor\',\'\');
    $(this).children().animate({paddingLeft:\"0\"}, {queue:false, duration:300});
  });  
	
  //Mouseover effect for Posts, Comments, Famous Posts and Random Posts menu list.
  $(\'.boxBody li\').click(function(){
    window.location = $(this).find(\"a\").attr(\"href\");
  }).mouseover(function() {
    $(this).css(\'backgroundColor\',\'#888\');
  }).mouseout(function() {
    $(this).css(\'backgroundColor\',\'\');
  });  	
	
});

</script>
{/literal}
</head>
<body>
{$acp_bar}

	<!--[if !IE]>main<![endif]-->
    	<div id=\"main\">
        
        	<!--[if !IE]>header-area<![endif]-->
            	
            	<div id=\"header-area\">
                
                	<!--[if !IE]>search-bar<![endif]-->
            	
            			<div id=\"search-bar\"><form action=\"admin.php\" method=\"get\">
                			
                           <input class=\"search-button\" type=\"submit\" value=\"Search\"/> <input type=\'hidden\' name=\'view\' value=\'search\'>
                            
                            <input class=\"search-box\" type=\"text\" name=\"search\" size=\"15\"  height=\"3\" onblur=\"if (this.value == \'\') {ldelim}this.value = \'Lets Search Here ...\';{rdelim}\" onfocus=\"if (this.value == \'Lets Search Here ...\') {ldelim}this.value = \'\';{rdelim}\" id=\"ls\"  value=\"Lets Search Here ...\"  /> 
                            </form>
                		</div>
                
            		<!--[if !IE]>search-bar<![endif]-->
                    
                    
                    	<!--[if !IE]>header<![endif]-->
            	
            				<div id=\"header\">
                				
                                
                                <!--[if !IE]>logo<![endif]-->
                                	
                                    <div id=\"logo\">
                                    	
                                        <a href=\"{$siteurl}\"><img src=\"images/logo.jpg\" width=\"263\" height=\"46\" alt=\"company name\" border=\"0\" align=\"middle\"/></a>
                                    
                                    </div>
                                <!--[if !IE]>logo<![endif]-->
                                
                                
                                	 
                                     <!--[if !IE]>right-header<![endif]-->
                                	
                                    	<div id=\"right-header\">
                                		<a href=\"http://www.adaptcms.com\"><img src=\"images/banner3.jpg\" width=\"464\" height=\"72\" alt=\"banner\" align=\"right\" border=\"0\"/></a>
      
                                		</div>
                                    
                                	 <!--[if !IE]>right-header<![endif]-->
                                
                			</div>
                
            			<!--[if !IE]>header<![endif]-->
                        
                        
                        	
                            <!--[if !IE]>navigation<![endif]-->
                        	
                            	<div id=\"navigation\">
                                
                                	<!--[if !IE]>button<![endif]-->
                        	
                            			<div id=\"button\">
                            	
                                            <ul>
			<li><a href=\"admin.php\">ACP</a></li>
			<li><a href=\"{$siteurl}\">Website</a></li>
			<li><a href=\"admin.php?view=share\">Share</a></li>
			<li><a href=\"admin.php?view=support\">Support</a></li>
			<li><a href=\"{$siteurl}profile/{$user_name}\">Your Profile</a></li>
			<li><a href=\"admin.php?quick_link=1\"><img src=\"images/add.png\" border=\"0\"></a></li>
                                            </ul>
                                
                            			</div>
                                        
                                     <!--[if !IE]>button<![endif]-->
                                     
                                     
                                     	<!--[if !IE]>icon<![endif]-->
                        	
                            				<div id=\"icon\">
                                            	
                                                <ul>
                                                   
                                                    <li>
													{php}
													echo help(\"\", \"no_text\");
													{/php}<img src=\"images/help.jpg\" width=\"41\" height=\"43\" alt=\"help\" border=\"0\"/></a></li>  
                                                    <li><a href=\"{$siteurl}messages\"><img src=\"images/icons4.png\" width=\"46\" height=\"43\" alt=\"new\" border=\"0\"/></a></li>


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
                        	
             <div id=\"bg-pattern\">
             
             	<!--[if !IE]>content-part<![endif]-->
                	
                    <div id=\"content-part\">
                    
     
                
                	<!--[if !IE]>sidebar<![endif]-->
                	
                    	<div id=\"sidebar\">
 
                                <!--[if !IE]>sidebar1<![endif]-->
                        
                                    <div id=\"sidebar1\" class=\"menu\">
                            
                                      
                                      <!--[if !IE]> basic menu<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\"> Content <span class=\"drop\"> Posting</span></div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text4\').style.display==\'block\'){ldelim}document.getElementById(\'text4\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text4\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text4\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id3\');\" id=\'id3\'  />
                    
                    </span>

                    	</div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id=\"text4\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"side-menu2\">
                   
                   	<li><a href=\"admin.php?view=content\"> Content </a></li>
                   	<li><a href=\"admin.php?view=fields\"> Custom Fields </a></li>
                   	<li><a href=\"admin.php?view=sections\"> Sections </a></li>
                   	<li><a href=\"admin.php?view=media\"> Media </a></li>
					<li><a href=\"admin.php?view=skins\"> Skins </a></li>

                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading2<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->                        
                                        
                                    
                              </div>
                           
                           <!--[if !IE]>sidebar1<![endif]-->
                                
                                
                                
                                
                              <!--[if !IE]>acp<![endif]-->
                        
                                 <div id=\"acp\" class=\"menu\">
                                    
                                    	
                                        <!--[if !IE]> basic menu<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\">User <span class=\"drop\"> Management</span></div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text5\').style.display==\'block\'){ldelim}document.getElementById(\'text5\').style.display=\'none\'; {rdelim}else{ldelim}ocument.getElementById(\'text5\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text5\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id4\');\" id=\'id4\'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading2<![endif]-->     
                               
                                 
              <div id=\"text5\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"side-menu2\">
                   
                   	<li><a href=\"admin.php?view=users\"> Users </a></li>
                   	<li><a href=\"admin.php?view=groups\"> Groups </a></li>
                   	<li><a href=\"admin.php?view=levels\"> Levels </a></li>
           
                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->  
                            
                                       
                                    </div>
                           
                                <!--[if !IE]>acp<![endif]-->


								                              <!--[if !IE]>acp<![endif]-->
                        
                                 <div id=\"acp\" class=\"menu\">
                                    
                                    	
                                        <!--[if !IE]> basic menu<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\">Advanced <span class=\"drop\"> Misc</span></div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text3\').style.display==\'block\'){ldelim}document.getElementById(\'text3\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text3\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text3\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id2\');\" id=\'id2\'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading2<![endif]-->     
                               
                                 
              <div id=\"text3\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"side-menu2\">
                   
                   	<li><a href=\"admin.php?view=polls\"> Polls </a></li>
                   	<li><a href=\"admin.php?view=pages\"> Pages </a></li>
                   	<li><a href=\"admin.php?view=settings\"> Settings </a></li>
					<li><a href=\"admin.php?view=plugins\"> Plugins </a></li>
					<li><a href=\"admin.php?view=stats\"> Stats </a></li>
					<li><a href=\"admin.php?view=tools\"> Tools </a></li>
           
                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->  
                            
                                       
                                    </div>
                           
                                <!--[if !IE]>acp<![endif]-->
                                
                                
								                	<!--[if !IE]>skin chooser<![endif]-->
                     <div id=\"vsitors-online\" class=\"menu\">
                           
                           <!--[if !IE]>sidebar-heading<![endif]-->   
                            
                            <div class=\"sidebar-heading2\">
                            
                                <div class=\"heading-text1\">Admin <span class=\"drop\"> Skin</span></div>
                            
                                <div class=\"arrow2\">
                                
                                <span onClick=\"if(document.getElementById(\'text2\').style.display==\'block\'){ldelim}document.getElementById(\'text2\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text2\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text2\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\"   border=\"none\"  width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id1\');\" id=\'id1\'    />
                                
                                </span>
            
                                	</div>
                         
                            </div>
                        <!--[if !IE]>sidebar-heading<![endif]-->
                        
                        
                  <div id=\"text2\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"vsitors-online\">
                   
                        <div align=\"center\">{$change_skin}</div>
                   
               	  	</ul>

             
            	</div> 
            </div>
            
          	<!--[if !IE]> skin chooser<![endif]-->            
                                
                               		
                                    <!--[if !IE]>vsitors-online<![endif]-->
                        
                                        <div id=\"vsitors-online\" class=\"menu\">
                                
                                            
                                            <!--[if !IE]> Visitors<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\">Basic <span class=\"drop\"> Stats</span></div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text6\').style.display==\'block\'){ldelim}document.getElementById(\'text6\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text6\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text6\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id5\');\" id=\'id5\'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id=\"text6\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                                   <ul class=\"vsitors-online\">
                   
                   		<li>Members online -<span class=\"drop\">{php}echo online(\"members\", 10);{/php}</span></li>
                         <li>Guest online - <span class=\"drop\">{php}echo online(\"guests\", 10);{/php}</span></li>
                         <li>Total Members -<span class=\"drop\">{php}echo stats(\"users\");{/php}</span></li>
						 <li>Users online - <span class=\"drop\">{php}echo users_online(10);{/php}</span></li>
                 </ul>

                   
                     
            	</div> 
                           
                                    
                                        <!--[if !IE]>sidebar-heading2<![endif]-->
                                        
                                    <!--[if !IE]> Visitors<![endif]--> 
                                            
                                           
                                        </div>
                           
                                <!--[if !IE]>vsitors-online<![endif]-->
                               
                        
                        </div>
                       
                    <!--[if !IE]>sidebar<![endif]-->
                    
                    	
                        
                        <!--[if !IE]>right<![endif]-->
                        	<div id=\"right\">
                                                        {if $smarty.get.view}
                            	<!--[if !IE]>directory-content<![endif]-->
                        				
                                        <div id=\"directory-content\">
                                        
                                       <h3> {$acp_page_view} / <span class=\"add-content\">{$acp_page_do} {$acp_page_view2}</span>{$acp_bar_data}<br />                
</h3>
                                        
                                        </div>
                                <!--[if !IE]>directory-content<![endif]-->
                                
                                {/if}
                                
                                	<!--[if !IE]>form<![endif]-->
                        				
                                        <div id=\"form\">', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(3, 'admin_footer', 'main', '</div>\r\n                                        \r\n                                    <!--[if !IE]>form<![endif]-->\r\n                            \r\n                            </div>\r\n                        <!--[if !IE]>right<![endif]-->    \r\n                   \r\n                        \r\n                        \r\n                         </div>\r\n                <!--[if !IE]>content-part<![endif]-->\r\n                \r\n                \r\n                	<!--[if !IE]>footer<![endif]-->\r\n                        	<div id=\"footer\">\r\n                           \r\n                           <ul>\r\n                           <li><a href=\"http://www.adaptcms.com/download\"><img src=\"http://www.adaptcms.com/version.php?version={$adaptcms_version}\" width=\"38\" height=\"32\" alt=\"icon\" border=\"0\" /></a></li>\r\n                           <li>You are running <span class=\"drop\">- AdaptCMS {$adaptcms_version}</span></li>\r\n                           </ul>\r\n                           \r\n                           		<p>Copyright 2006-2010 <a href=\'http://www.insanevisions.com\'>Insane Visions</a><p>\r\n                            \r\n                            </div>\r\n                        <!--[if !IE]>footer<![endif]-->	\r\n             \r\n             </div>\r\n             	\r\n		<!--[if !IE]>bg-pattern<![endif]-->\r\n\r\n\r\n\r\n\r\n</body>\r\n</html>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(4, 'news', 'main', '				<h2>{$title}</h2>\r\n				<span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span><br />\r\n								<span class=\"what\">On:</span> {$date}<br />\r\n							\r\n\r\n<b>Current Rating:</b> {$current_rating}<br />\r\n<b>Rate Content:</b> {$rating_form}<br><br>\r\n\r\n{$description}<br /><br />{$full_story}<br />\r\n\r\n{$comments_form}<br />\r\n\r\n<b>Comments</b><br />\r\n<div id=\"comments\">\r\n{section name=r loop=$comments}\r\n<table class=\"newstxt\" cellpadding=\"5\" cellspacing=\"2\" border=\"0\" style=\"border: 2px solid #868585\" width=\"100%\"><tr><td bgcolor=\"#868585\"> {$comments_username[r]}, {$comments_date[r]}</td></tr><tr><td>{$comments_comment[r]}</td></tr><tr><td bgcolor=\"#868585\"><b>Rating:</b> {$comments_rating[r]}, <b>Rate Comment:</b> {$comments_rating_form[r]}</td></tr></table><br />\r\n{/section}\r\n</div>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(5, 'page', 'main', '<title>Page - {$name}</title>\r\n\r\n<b>{$name}</b> by {$username} @ <i>{$date}</i><br />\r\n<p>{$content}</p>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(6, 'poll_results', 'main', '<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'75%\'><tr><td><b>{$question}</b></td></tr></table><br clear=\'all\'>\r\n\r\n<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'75%\'>\r\n\r\n{section name=sec loop=$options}\r\n<tr><td>{$options[sec]}</td><td>{$options_data[sec]}</td></tr>\r\n{/section}\r\n\r\n<tr><td><b>Votes:</b> {$vote_total}</td></tr></table><br clear=\'all\'>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(7, 'poll_vote', 'main', '{$poll_header}\r\n<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'200\'><tr><td><b>{$question}</b></td></tr></table><br>\r\n\r\n<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'200\'>\r\n\r\n{section name=sec loop=$options}\r\n<tr><td>{$options_data[sec]}</td><td>{$options[sec]}</td></tr>\r\n{/section}\r\n\r\n<tr><td><br><br>{$submit}</td><td><a href=\"{$siteurl}poll-results\">Results</a></td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(8, 'homepage', 'main', '{php}\r\necho content(\'homepage_content\', \'{$home_section}\', 5, \'\', \'\');\r\n{/php}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(9, 'homepage_content', 'main', '				<h2>{$link}</h2>\r\n				<span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span><br />\r\n								<span class=\"what\">On:</span> {$date}<br /><br />\r\n						{$story}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(10, 'footer', 'main', '                                    	</p></div>\r\n                                        \r\n                                    <!--[if !IE]>content<![endif]-->\r\n                            \r\n                            </div>\r\n                        <!--[if !IE]>right<![endif]-->    \r\n                   \r\n                        \r\n                        \r\n                         </div>\r\n                <!--[if !IE]>content-part<![endif]-->\r\n                \r\n                \r\n                	<!--[if !IE]>footer<![endif]-->\r\n                        	<div id=\"footer\">\r\n                        	<a href=\"http://www.adaptcms.com\"><img src=\"http://www.adaptcms.com/button.png\" align=\"left\" style=\"padding-left:5px;padding-top:6px\"></a>\r\n                           <p>Copyright 2006-2010 - <a href=\"http://www.insanevisions.com\">Insane Visions</a><p>\r\n                            \r\n                            </div>\r\n                        <!--[if !IE]>footer<![endif]-->	\r\n             \r\n             </div>\r\n             	\r\n		<!--[if !IE]>bg-pattern<![endif]-->\r\n\r\n\r\n\r\n\r\n</body>\r\n</html>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(11, 'header', 'main', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n\r\n<head>\r\n	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n	<title>{$sitename}</title>\r\n	<link rel=\"stylesheet\" href=\"{$siteurl}style.css\" />\r\n	<script type=\'text/javascript\' src=\'{$siteurl}style2.js\'></script>\r\n	{literal}\r\n	<script type=\"text/javascript\" >\r\n	$(\'document\').ready(function(){\r\n		$(\'#flip-container\').quickFlip();\r\n		\r\n		$(\'#flip-navigation li a\').each(function(){\r\n			$(this).click(function(){\r\n				$(\'#flip-navigation li\').each(function(){\r\n					$(this).removeClass(\'selected\');\r\n				});\r\n				$(this).parent().addClass(\'selected\');\r\n				var flipid=$(this).attr(\'id\').substr(4);\r\n				$(\'#flip-container\').quickFlipper({ }, flipid, 1);\r\n				\r\n				return false;\r\n			});\r\n		});\r\n	});\r\n\r\n\r\n\r\n	function toggle(id,img1,img2,idname)\r\n	{\r\n 	 \r\n		if (id.src.indexOf(img1)>0 )\r\n		{\r\n	 	\r\n			document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-up2.jpg\';	\r\n			 \r\n		}\r\n		else if (id.src.indexOf(img2)>0)\r\n		{\r\n\r\n	 		document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-down2.jpg\';\r\n			 		 \r\n		}\r\n \r\n	}\r\n\r\n</script>\r\n\r\n\r\n<script type=\"text/javascript\">\r\n\r\n$(document).ready(function() {	\r\n\r\n\r\n  //Get all the LI from the #tabMenu UL\r\n  $(\'#tabMenu > li\').click(function(){\r\n        \r\n    //remove the selected class from all LI    \r\n    $(\'#tabMenu > li\').removeClass(\'selected\');\r\n    \r\n    //Reassign the LI\r\n    $(this).addClass(\'selected\');\r\n    \r\n    //Hide all the DIV in .boxBody\r\n    $(\'.boxBody div\').slideUp(\'1500\');\r\n    \r\n    //Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.\r\n    $(\'.boxBody div:eq(\' + $(\'#tabMenu > li\').index(this) + \')\').slideDown(\'1500\');\r\n    \r\n  }).mouseover(function() {\r\n\r\n    //Add and remove class, Personally I dont think this is the right way to do it, anyone please suggest    \r\n    $(this).addClass(\'mouseover\');\r\n    $(this).removeClass(\'mouseout\');   \r\n    \r\n  }).mouseout(function() {\r\n    \r\n    //Add and remove class\r\n    $(this).addClass(\'mouseout\');\r\n    $(this).removeClass(\'mouseover\');    \r\n    \r\n  });\r\n\r\n  //Mouseover with animate Effect for Category menu list\r\n  $(\'.boxBody #category li\').mouseover(function() {\r\n\r\n    //Change background color and animate the padding\r\n    $(this).css(\'backgroundColor\',\'#888\');\r\n    $(this).children().animate({paddingLeft:\"20px\"}, {queue:false, duration:300});\r\n  }).mouseout(function() {\r\n    \r\n    //Change background color and animate the padding\r\n    $(this).css(\'backgroundColor\',\'\');\r\n    $(this).children().animate({paddingLeft:\"0\"}, {queue:false, duration:300});\r\n  });  \r\n	\r\n  //Mouseover effect for Posts, Comments, Famous Posts and Random Posts menu list.\r\n  $(\'.boxBody li\').click(function(){\r\n    window.location = $(this).find(\"a\").attr(\"href\");\r\n  }).mouseover(function() {\r\n    $(this).css(\'backgroundColor\',\'#888\');\r\n  }).mouseout(function() {\r\n    $(this).css(\'backgroundColor\',\'\');\r\n  });  	\r\n	\r\n});\r\n\r\n</script>\r\n{/literal}\r\n</head>\r\n\r\n<body>\r\n{$acp_bar}\r\n\r\n	<!--[if !IE]>main<![endif]-->\r\n    	<div id=\"main\">\r\n        \r\n        	<!--[if !IE]>header-area<![endif]-->\r\n            	\r\n            	<div id=\"header-area\">\r\n                \r\n                	<!--[if !IE]>search-bar<![endif]-->\r\n            	\r\n            			<div id=\"search-bar\"><form action=\"{$siteurl}index.php\" method=\"get\">\r\n                			\r\n                           <input class=\"search-button\" type=\"submit\" value=\"Search\"/><input type=\'hidden\' name=\'view\' value=\'search\'> \r\n                            \r\n                            <input class=\"search-box\" type=\"text\" name=\"q\" size=\"15\" maxlength=\"25\" />\r\n                            </form>\r\n                		</div>\r\n                \r\n            		<!--[if !IE]>search-bar<![endif]-->\r\n                    \r\n                    \r\n                    	<!--[if !IE]>header<![endif]-->\r\n            	\r\n            				<div id=\"header\">\r\n                				\r\n                                <!--[if !IE]>logo<![endif]-->\r\n                                	\r\n                                    <div id=\"logo\">\r\n                                    	\r\n                                        <a href=\"{$siteurl}\"><img src=\"{$siteurl}images/banner1.jpg\" width=\"284\" height=\"45\" alt=\"{$sitename}\" border=\"0\" align=\"middle\"/></a>\r\n                                    \r\n                                    </div>\r\n                                <!--[if !IE]>logo<![endif]-->\r\n                                \r\n                                \r\n                                	 <!--[if !IE]>right-header<![endif]-->\r\n                                	\r\n                                    	<div id=\"right-header\">\r\n                                		<img src=\"{$siteurl}images/banner2.jpg\" width=\"468\" height=\"60\" alt=\"banner\" align=\"right\" border=\"0\"/>\r\n                                		</div>\r\n                                    \r\n                                	 <!--[if !IE]>right-header<![endif]-->\r\n                                \r\n                			</div>\r\n                \r\n            			<!--[if !IE]>header<![endif]-->\r\n                        \r\n                        \r\n                        	<!--[if !IE]>navigation<![endif]-->\r\n                        	\r\n                            	<div id=\"navigation\">\r\n                                \r\n                                	<!--[if !IE]>button<![endif]-->\r\n                        	\r\n                            			<div id=\"button\">\r\n                            	\r\n                                            <ul>\r\n			<li><a href=\"{$siteurl}\">Home</a></li>\r\n			<li><a href=\"{$siteurl}section/News\">News</a></li>\r\n			<li><a href=\"{$siteurl}section/reviews\">Reviews</a></li>\r\n			<li><a href=\"{$siteurl}media\">Media</a></li>\r\n			<li><a href=\"{$siteurl}page/contact-us\">Contact Us</a></li>\r\n                                            </ul>\r\n                                \r\n                            			</div>\r\n                                        \r\n                                     <!--[if !IE]>button<![endif]-->\r\n                                     \r\n                                     \r\n                                     	<!--[if !IE]>icon<![endif]-->\r\n                        	\r\n                            				<div id=\"icon\">\r\n                                            	\r\n                                                <a href=\"{$siteurl}rss\"><img src=\"{$siteurl}images/rss.png\" width=\"42\" height=\"44\" alt=\"icon\" border=\"0\" align=\"right\"/></a>\r\n                                            </div>\r\n                                            \r\n                                       	<!--[if !IE]>icon<![endif]-->     \r\n                                \r\n                                </div>\r\n                                	\r\n                			<!--[if !IE]>navigation<![endif]-->\r\n                        \r\n                </div>\r\n                \r\n            <!--[if !IE]>header-area<![endif]-->\r\n        \r\n        </div>\r\n    <!--[if !IE]>main<![endif]-->\r\n    \r\n    \r\n    	<!--[if !IE]>bg-pattern<![endif]-->\r\n                        	\r\n             <div id=\"bg-pattern\">\r\n             \r\n             	<!--[if !IE]>content-part<![endif]-->\r\n                	\r\n                    <div id=\"content-part\">\r\n                    \r\n     \r\n                \r\n                	<!--[if !IE]>sidebar<![endif]-->\r\n                	\r\n                    	<div id=\"sidebar\">\r\n                        \r\n                        	<!--[if !IE]>sidebar1<![endif]-->\r\n                	\r\n                    			<div id=\"sidebar1\" class=\"menu\">\r\n                        \r\n                                   \r\n                                   \r\n                                    \r\n                                    \r\n                                    <!--[if !IE]> basic menu<![endif]-->  \r\n                                        <!--[if !IE]>sidebar-heading<![endif]-->   \r\n            								\r\n                                            <div class=\"sidebar-heading2\">\r\n                \r\n                			\r\n                            <div class=\"heading-text1\"> Basic menu</div>\r\n                \r\n                				<div class=\"arrow2\">\r\n                    <span onClick=\"if(document.getElementById(\'text6\').style.display==\'block\'){ldelim}document.getElementById(\'text6\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text6\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text6\').style=\'hand\';\"><img src=\"{$siteurl}images/arrow-down2.jpg\" width=\"23\" height=\"48\" onclick=\"toggle(this,\'{$siteurl}images/arrow-down2.jpg\',\'{$siteurl}images/arrow-up.jpg\',\'id5\');\" id=\'id5\'  />\r\n                    </span>\r\n\r\n                    </div>\r\n                    \r\n\r\n\r\n            	</div>\r\n        	<!--[if !IE]>sidebar-heading<![endif]-->     \r\n                               \r\n                                 \r\n              <div id=\"text6\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	\r\n                  <ul class=\"side-menu2\">\r\n                   \r\n                   	<li><a href=\"#\"> menu1 </a></li>\r\n                   	<li><a href=\"#\"> menu2 </a></li>\r\n                   	<li><a href=\"#\"> menu3 </a></li>\r\n                    <li><a href=\"#\"> menu4 </a></li>\r\n                    <li><a href=\"#\"> menu5 </a></li>\r\n           \r\n                 </ul>\r\n\r\n                   \r\n                     \r\n            	</div> \r\n            \r\n            	<!--[if !IE]>sidebar-heading<![endif]-->\r\n                \r\n          	<!--[if !IE]> basic menu<![endif]-->\r\n            \r\n             \r\n                                \r\n                        		</div>\r\n                    		<!--[if !IE]>sidebar1<![endif]-->\r\n                            \r\n                            \r\n                            	<!--[if !IE]>sidebar2<![endif]-->\r\n                	\r\n                    			<div id=\"sidebar2\" class=\"menu\">\r\n                        \r\n                                     \r\n                                    \r\n                                    <!--[if !IE]> basic menu<![endif]-->  \r\n                                        <!--[if !IE]>sidebar-heading<![endif]-->   \r\n            								\r\n                                            <div class=\"sidebar-heading2\">\r\n                \r\n                			\r\n                            <div class=\"heading-text1\"> Latest News</div>\r\n                \r\n                				<div class=\"arrow2\">\r\n                    \r\n                    <span onClick=\"if(document.getElementById(\'text7\').style.display==\'block\'){ldelim}document.getElementById(\'text7\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text7\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text7\').style=\'hand\';\"><img src=\"{$siteurl}images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle(this,\'{$siteurl}images/arrow-down2.jpg\',\'{$siteurl}images/arrow-up.jpg\',\'id6\');\" id=\'id6\'  />\r\n                    \r\n                    </span>\r\n\r\n                    </div>\r\n                    \r\n\r\n\r\n            	</div>\r\n        	<!--[if !IE]>sidebar-heading<![endif]-->     \r\n                               \r\n                                 \r\n              <div id=\"text7\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	\r\n                  <ul class=\"side-menu2\">\r\n                   {php}\r\necho content(\"latestnews\", \"News\", 5);\r\n{/php}\r\n                 </ul>\r\n\r\n                   \r\n                     \r\n            	</div> \r\n            \r\n            	<!--[if !IE]>sidebar-heading<![endif]-->\r\n                \r\n          	<!--[if !IE]> basic menu<![endif]-->\r\n                                \r\n                        		</div>\r\n                       <br /><br />\r\n                    		<!--[if !IE]>sidebar3<![endif]-->\r\n                    		<div id=\"sidebar3\" class=\"menu\">\r\n                        \r\n                                     \r\n                                    \r\n                                    <!--[if !IE]> basic menu<![endif]-->  \r\n                                        <!--[if !IE]>sidebar-heading<![endif]-->   \r\n            								\r\n                                            <div class=\"sidebar-heading2\">\r\n                \r\n                			\r\n                            <div class=\"heading-text1\">Poll</div>\r\n                \r\n                				<div class=\"arrow2\">\r\n                    \r\n                    <span onClick=\"if(document.getElementById(\'text8\').style.display==\'block\'){ldelim}document.getElementById(\'text8\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text8\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text8\').style=\'hand\';\"><img src=\"{$siteurl}images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle(this,\'{$siteurl}images/arrow-down2.jpg\',\'{$siteurl}images/arrow-up.jpg\',\'id7\');\" id=\'id7\'  />\r\n                    \r\n                    </span>\r\n\r\n                    </div>\r\n                    \r\n\r\n\r\n            	</div>\r\n        	<!--[if !IE]>sidebar-heading<![endif]-->     \r\n                               \r\n                                 \r\n              <div id=\"text8\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">      \r\n                        {php}\r\n                            echo poll(1);\r\n                            {/php}\r\n                            </div>\r\n                            \r\n                            </div>\r\n                            <br />\r\n                            <!--[if !IE]>sidebar4<![endif]-->\r\n                    		<div id=\"sidebar4\" class=\"menu\">\r\n                        \r\n                                     \r\n                                    \r\n                                    <!--[if !IE]> basic menu<![endif]-->  \r\n                                        <!--[if !IE]>sidebar-heading<![endif]-->   \r\n            								\r\n                                            <div class=\"sidebar-heading2\">\r\n                \r\n                			\r\n                            <div class=\"heading-text1\">Media</div>\r\n                \r\n                				<div class=\"arrow2\">\r\n                    \r\n                    <span onClick=\"if(document.getElementById(\'text9\').style.display==\'block\'){ldelim}document.getElementById(\'text9\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text9\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text9\').style=\'hand\';\"><img src=\"{$siteurl}images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle(this,\'{$siteurl}images/arrow-down2.jpg\',\'{$siteurl}images/arrow-up.jpg\',\'id8\');\" id=\'id8\'  />\r\n                    \r\n                    </span>\r\n\r\n                    </div>\r\n                    \r\n\r\n\r\n            	</div>\r\n        	<!--[if !IE]>sidebar-heading<![endif]-->     \r\n                               \r\n                                 \r\n              <div id=\"text9\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">    \r\n              \r\n              <div align=\"center\">     	\r\n              {php}\r\necho media(\"media_page\", \"latestmedia\", 3);\r\n{/php}\r\n</div>\r\n              </div></div>\r\n                            \r\n                            <!--[if !IE]>endmenu<![endif]-->\r\n                        </div>\r\n                       \r\n                    <!--[if !IE]>sidebar<![endif]-->\r\n                    \r\n                    	<!--[if !IE]>right<![endif]-->\r\n                        	<div id=\"right\">\r\n                            \r\n                            	<!--[if !IE]>banner<![endif]-->\r\n                        			<div id=\"banner\">\r\n                            	\r\n                                		<img src=\"{$siteurl}images/banner.jpg\" width=\"724\" height=\"209\" alt=\"banner\" />\r\n                            		</div>\r\n                        		<!--[if !IE]>banner<![endif]-->\r\n                                \r\n                                \r\n                                	<!--[if !IE]>content<![endif]-->\r\n                        				\r\n                                        <div id=\"content\"><p>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(12, 'media_list', 'main', '<table cellpadding=\"5\" cellspacing=\"2\"><tr>\r\n{section name=med loop=$media}\r\n<td><a href=\'{$media_url[med]}\'>{$media_image[med]}</a><br />{$media_name[med]}</td>\r\n{if $smarty.section.med.iteration % 3 == 0}\r\n</tr><tr>\r\n{/if}\r\n{/section}\r\n</tr></table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(13, 'media_page', 'main', '<title>{$sitename} - {$media_name}</title>\r\n\r\n<h2>{$media_name}</h2>\r\n\r\n<table cellpadding=\"5\" cellspacing=\"1\"><tr>\r\n{section name=r loop=$file}\r\n<td><a href=\'{$file_view[r]}\'>{$file_code[r]}</a></td>\r\n{if $smarty.section.r.iteration % 3 == 0}\r\n</tr><tr>\r\n{/if}\r\n{/section}\r\n</tr></table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(14, 'file_view', 'main', '<title>{$sitename} - {$media_name}</title>\r\n\r\n<h2>{$media_name}</h2>\r\n\r\n<table cellpadding=\"3\"><tr><td align=\"center\">\r\n{$file_code}\r\n</td></tr><tr><td><i>{$file_caption}</i></td></tr></table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(15, 'latestnews', 'main', '<li>{$link}</li>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(16, 'section', 'main', '				<div class=\"cBoxHeader\"><h2>{$link}</h2></div>\r\n				<div class=\"cBoxBg\">\r\n					<div class=\"cBoxText\">\r\n						<div class=\"cBoxTextInfo\">\r\n							<ol>\r\n								<li><span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span></li>\r\n								<li><span class=\"what\">On:</span> {$date}</li>\r\n							</ol>\r\n						</div>\r\n						{$story}\r\n</div>\r\n					</div>\r\n				</div>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(17, 'latestmedia', 'main', '{section name=abc loop=$file}\r\n<a href=\'{$file_view[abc]}\' class=\'input\' alt=\'{$file_name[abc]}\'>{$file_code[abc]}</a><br /><br />\r\n{/section}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(18, 'reviews', 'main', '				<div class=\"cBoxHeader\"><h2>{$title}</h2></div>\r\n				<div class=\"cBoxBg\">\r\n					<div class=\"cBoxText\">\r\n						<div class=\"cBoxTextInfo\">\r\n							<ol>\r\n								<li><span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span></li>\r\n								<li><span class=\"what\">On:</span> {$date}</li>\r\n							</ol>\r\n						</div>\r\n\r\n<b>Current Rating:</b> {$current_rating}<br />\r\n<b>Rate Content:</b> {$rating_form}<br><br>\r\n\r\n{$review_contents}<br />\r\n\r\n</div>\r\n					</div>\r\n				</div>\r\n\r\n{$comments_form}<br />\r\n\r\n<b>Comments</b><br />\r\n<div id=\"comments\">\r\n{section name=r loop=$comments}\r\n<table class=\"newstxt\" cellpadding=\"5\" cellspacing=\"2\" border=\"0\" style=\"border: 2px solid #868585\" width=\"100%\"><tr><td bgcolor=\"#868585\"> {$comments_username[r]}, {$comments_date[r]}</td></tr><tr><td>{$comments_comment[r]}</td></tr><tr><td bgcolor=\"#868585\"><b>Rating:</b> {$comments_rating[r]}, <b>Rate Comment:</b> {$comments_rating_form[r]}</td></tr></table><br />\r\n{/section}\r\n</div>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(19, 'admin_bar', 'main', '<p style=\'padding:10px\'>\r\nTesting stuff here!\r\n</p>\r\n<br style=\'clear: left\' />\r\n<img src=\'{$siteurl}images/cancel.png\' class=\'closepanel\'> <b class=\'closepanel\'>Close</b>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(20, 'view_profile', 'main', '<table cellpadding=\"3\" cellspacing=\"0\" width=\"90%\" align=\"center\">\r\n<tr><td>Group: {$group}</td></tr>\r\n<tr><td>Level: {$level}</td></tr>\r\n<tr><td>Last Login: {$last_login}</td></tr>\r\n</table>\r\n\r\n<div align=\"center\"><h3>Updates</h3></div>\r\n<table cellpadding=\"3\" cellspacing=\"2\" width=\"90%\" align=\"center\">\r\n\r\n{section name=r loop=$statuses}\r\n<tr><td><img src=\"{$status_avatar[r]}\" width=\"48\"></td><td><b>{$status_username[r]}</b> {$status_data[r]}<br /><small>{$status_date[r]}</td></tr>\r\n{/section}\r\n</table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(21, 'register', 'main', '<title>{$sitename} - Register</title><a href=\'index.php\'>Directory</a>  -  Register / Form<br /><br />\r\n{$form_start}<table><tr><td>Username</td><td>{$username_input}</td></tr><tr><td>Password</td><td>{$password_input}</td></tr><tr><td>Password Confirm</td><td>{$password_input2}</td></tr><tr><td>E-Mail</td><td>{$email_input}</td></tr><tr><td>Captcha</td><td>{$captcha}</td></tr><tr><td><input type=\'submit\' value=\'Register\' class=\'input\'></td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(22, 'login', 'main', '<title>{$sitename} - Login</title><a href=\'index.php\'>Directory</a>  -  Login / Form - <a href=\'{$register_link}\'>Signup</a><br /><br />\r\n{$form_start}<table><tr><td>Username</td><td>{$username_input}</td></tr><tr><td>Password</td><td>{$password_input}</td></tr><tr><td>Captcha</td><td>{$captcha}</td></tr><tr><td><input type=\'submit\' value=\'Login\' class=\'input\'></td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(23, 'edit_profile', 'main', '<title>{$sitename} - Edit Profile</title><a href=\'index.php\'>Directory</a>  -  Social / Edit Profile<br /><br />\r\n{$form_start}<table>\r\n<tr><td>Username</td><td>{$username_input}</td></tr>\r\n<tr><td>New Password</td><td>{$password_input}</td></tr>\r\n<tr><td>Password Confirm</td><td>{$password_input2}</td></tr>\r\n<tr><td>E-Mail</td><td>{$email_input}</td></tr>\r\n<tr><td>Skin</td><td>{$skin_input}</td></tr>\r\n<tr><td></td><td></td></tr>\r\n{section name=r loop=$fields}\r\n<tr><td>{$field_name[r]}</td><td>{$field_input[r]}</td><td>{$field_info[r]}</td></tr>\r\n{/section}\r\n<tr><td><input type=\'submit\' value=\'Update\' class=\'input\'></td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(24, 'message_view', 'main', '<table cellpadding=\'5\' cellspacing=\'0\' border=\'0\' width=\'100%\' align=\'center\' style=\'border: 2px solid #dddddd\'><tr style=\'background:url({$siteurl}inc/images/topbg.jpg) repeat-x;\'><td><b>Private Messages</b> - {$folder}</td></tr>\r\n<tr><td align=\"center\" class=\"light\"><b>{$folder}</b> - {$messages_num} messages with a total of {$max_messages} permitted. ({$messages_percent})<br />{$folder_dropdown}</td></tr></table><br>\r\n\r\n<table cellpadding=\'5\' cellspacing=\'0\' border=\'0\' width=\'100%\' align=\'center\' style=\'border: 2px solid #dddddd\'><tr style=\'background:url({$siteurl}inc/images/topbg.jpg) repeat-x;\'><td valign=\"top\" align=\"center\" width=\"150\" style=\"border-right: 1px solid #dddddd\"><i>{$date}</i><br><br><font size=\"3\"><b><u>{$poster_username}</u></b></font><br>{$poster_rank_image}<font size=\"1\"><br><br><img src=\"{$poster_avatar}\"><br><br>\r\n\r\n<table cellpadding=\'3\' cellspacing=\'5\' border=\'0\'><tr bgcolor=\"#DDDDDD\"><td><b>Joined:</b> {$poster_join_date}</td></tr><tr><td><b>Online Tag:</b> {$poster_online_tag}</td></tr><tr><td style=\"padding-top:5px\">{$poster_aim} {$poster_msn} {$poster_gtalk}</td></tr></table>\r\n\r\n<br><br>User is <b>{$poster_status}</b></td>\r\n\r\n<td style=\"padding-left:10pxborder-left:1px solid #dddddd\" valign=\"top\">\r\n\r\n<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' width=\'100%\'><tr><td> {$icon} <b>{$subject}</b></td><td align=\"right\">{$reply} {$forward} {$delete}</td></tr></table><br><br clear=\'all\'>\r\n{$message}\r\n<br><br>\r\n<center><hr color=\"#CCCCCC\" width=\"90%\"></center><br><p>\r\n{$poster_signature}\r\n</p></td></tr></table><br clear=\"all\">', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(25, 'message_list', 'main', '<table cellpadding=\'5\' cellspacing=\'0\' border=\'0\' width=\'100%\' align=\'center\' style=\'border: 2px solid #dddddd\'><tr style=\'background:url({$siteurl}inc/images/topbg.jpg) repeat-x;\'><td><b>Private Messages</b> - {$folder}</td><td align=\'right\'>{$send_message}</td></tr>\r\n<tr><td align=\"center\" class=\"light\"><b>{$folder}</b> - {$messages_num} messages with a total of {$max_messages} permitted. ({$messages_percent})<br />{$folder_dropdown}</td></tr></table><br>\r\n\r\n<table cellpadding=\'5\' cellspacing=\'0\' border=\'0\' width=\'100%\' align=\'center\' style=\'border: 2px solid #dddddd\'><tr style=\'background:url({$siteurl}inc/images/topbg.jpg) repeat-x;\'><td align=\'center\'><b>Icon</b></td><td><b>Subject</b></td><td align=\'center\' style=\'padding-right:10px\'><b>Options</b></td></tr>\r\n\r\n{section name=r loop=$messages}\r\n<tr{$class}><td align=\'center\'>{$icon[r]}</td><td>{$subject[r]}<br />From: {$sender[r]} @ {$date[r]}</td><td align=\'center\' style=\'padding-right:10px\'>{$options[r]}</td></tr>\r\n{/section}\r\n\r\n</table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(26, 'message_send', 'main', '{$form_start}\r\n<table cellpadding=\'5\' cellspacing=\'2\' border=\'0\' width=\'90%\' align=\'left\' style=\'border: 2px solid #dddddd\'><tr><td><font size=\"4\"><b>Send Message</b></font></td><td> </td></tr>\r\n<tr class=\'light\'><td><b>Recipient(s)</b></td><td>{$receivers_input}</td></tr>\r\n<tr class=\'dark\'><td><b>Subject</b></td><td>{$subject_input}</td></tr>\r\n<tr class=\'light\'><td><b>Message</b></td><td>{$message_input}</td></tr>\r\n<tr class=\'dark\'><td><b>Captcha</b></td><td>{$captcha_input}</td></tr>\r\n<tr class=\'light\'><td></td><td>{$submit}</td></tr></table>\r\n{$form_end}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(27, 'social_header', 'main', '<table width=\'95%\' cellpadding=\'2\' cellspacing=\'0\' style=\'padding-left:10px\'><tr><td valign=\"top\">\r\n\r\n<table width=\'100%\' cellpadding=\'1\' cellspacing=\'0\'><tr><td width=\"28%\"><img src=\"{$avatar}\" width=\"100\"><br />{$status}<br>@ <i>{$status_time}</i></td><td width=\"72%\"><h2>{$username}</h2> {$status_update}\r\n\r\n<div id=\'js_menu\'>\r\n	<ul>\r\n	{if $username == $user_name}\r\n	<li><a href=\'{$edit_profile_url}\'>Edit</a></li>\r\n	{/if}\r\n		<li><a href=\'{$profile_url}\'>Profile</a>\r\n		<ul>\r\n				<li><a href=\'{$status_url}\'>Status Page</a></li>\r\n				</ul>	\r\n		</li>\r\n		<li><a href=\'{$friends_url}\'>Friends</a>			\r\n			<ul>\r\n						<li><a href=\'{$friends_url}\'>View all</a></li> \r\n						<li><a href=\'{$friends_url_req}\'>View Requests</a></li>\r\n			</ul>\r\n		</li>\r\n		<li><a href=\'{$blogs_url}\'>Blogs</a>\r\n			<ul>\r\n				<li><a href=\'{$blogs_url_add}\'>Add Blog</a></li>\r\n				<li><a href=\'{$blogs_url}\'>Manage Blogs</a></li>\r\n			</ul>	\r\n		</li>\r\n		<li><a href=\'{$messages_url}\'>Messages</a>\r\n	</ul>\r\n	</div>\r\n\r\n</td></tr></table>\r\n\r\n</td></tr><tr><td>\r\n\r\n<table width=\'100%\' cellpadding=\'2\' cellspacing=\'0\'><tr><td width=\"28%\">menu here</td><td width=\"72%\">', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(28, 'social_footer', 'main', '</td>\r\n\r\n</tr></table>\r\n\r\n</td></tr></table>	', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(29, 'main', '', 'yes|', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(30, 'social_friends', 'main', '{if $i == 0}\r\n<div align=\'center\'><h3>Friends List</h3></div>\r\n\r\n<table cellpadding=\'5\' cellspacing=\'2\' border=\'0\' width=\'90%\'>\r\n{/if}\r\n\r\n<tr><td>{$friend_username}</td><td>{$friend_last_login}</td><td>{$friend_status}</td></tr>\r\n\r\n{if $i == 0}\r\n</table><br />\r\n{/if}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(31, 'social_blogs_view', 'main', '<title>{$sitename} - {$title} Blog ({$username})</title>\r\n	<h2>{$link}</h2>\r\n	<span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span><br />\r\n	<span class=\"what\">On:</span> {$date}<br /><br />\r\n	{$blog}<br />\r\n\r\n{$comments_form}<br />\r\n\r\n<b>Comments</b><br />\r\n<div id=\"comments\">\r\n{section name=r loop=$comments}\r\n<table class=\"newstxt\" cellpadding=\"5\" cellspacing=\"2\" border=\"0\" style=\"border: 2px solid #868585\" width=\"100%\"><tr><td bgcolor=\"#868585\"> {$comments_username[r]}, {$comments_date[r]}</td></tr><tr><td>{$comments_comment[r]}</td></tr><tr><td bgcolor=\"#868585\"><b>Rating:</b> {$comments_rating[r]}, <b>Rate Comment:</b> {$comments_rating_form[r]}</td></tr></table><br />\r\n{/section}\r\n</div>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(32, 'social_blogs_list', 'main', '<h2>{$link}</h2>\r\n<span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span><br />\r\n								<span class=\"what\">On:</span> {$date}<br /><br />\r\n{$blog}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(33, 'social_blogs_add', 'main', '<title>{$sitename} - Blog</title><br />\r\n{$form_start}<table cellpadding=\'5\' cellspacing=\'5\' width=\'100%\'><tr><td><p>Title</p>{$title_input}</td></tr><tr><td><p>Blog</p>{$blog_input}</td></tr><tr><td><p>Captcha</p>{$captcha}</td></tr><tr><td>{$submit_button}</td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(34, 'search', 'main', '<h2>{$link}</h2>\r\n<span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span><br />\r\n	<span class=\"what\">On:</span> {$date}<br /><br />\r\n	{$story}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."skins VALUES(35, 'admin_login', 'main', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>AdaptCMS {$adaptcms_version} - {$acp_page}</title>
<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\" />
<link rel=\'stylesheet\' href=\'inc/js/menu.css\'>
<script type=\'text/javascript\' src=\'inc/js/menu.js\'></script>
<script type=\'text/javascript\' src=\'inc/js/sortmenu.js\'></script>

</head>
<body>

	<!--[if !IE]>main<![endif]-->
    	<div id=\"main\">
        
        	<!--[if !IE]>header-area<![endif]-->
            	
            	<div id=\"header-area\">
                    
                    <div id=\"search-bar\"><form action=\"admin.php?view=search\" method=\"get\">
                			
                           
                            
                            
                            </form>
                		</div>

                    	<!--[if !IE]>header<![endif]-->
            	
            				<div id=\"header\">
                				
                                
                                <!--[if !IE]>logo<![endif]-->
                                	
                                    <div id=\"logo\">
                                    	
                                        <a href=\"{$siteurl}\"><img src=\"images/logo.jpg\" width=\"263\" height=\"46\" alt=\"company name\" border=\"0\" align=\"middle\"/></a>
                                    
                                    </div>
                                <!--[if !IE]>logo<![endif]-->
                                
                                
                                	 
                                     <!--[if !IE]>right-header<![endif]-->
                                	
                                    	<div id=\"right-header\">
                                		<a href=\"http://www.adaptcms.com\"><img src=\"images/banner3.jpg\" width=\"464\" height=\"72\" alt=\"banner\" align=\"right\" border=\"0\"/></a>
      
                                		</div>
                                    
                                	 <!--[if !IE]>right-header<![endif]-->
                                
                			</div>
                
            			<!--[if !IE]>header<![endif]-->
                        
                        
                        	
                            <!--[if !IE]>navigation<![endif]-->
                        	
                            	<div id=\"navigation\">
                                
                                	<!--[if !IE]>button<![endif]-->
                        	
                            		
                                        
                                     <!--[if !IE]>button<![endif]-->
                                     
                                     
                                     	<!--[if !IE]>icon<![endif]-->
                        	
                            				<div id=\"icon\">
                                            	
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
                        	
             <div id=\"bg-pattern\">
             
             	<!--[if !IE]>content-part<![endif]-->
                	
                    <div id=\"content-part\">
                    
     
                
                	<!--[if !IE]>sidebar<![endif]-->
                	
                    	
                       
                    <!--[if !IE]>sidebar<![endif]-->
                    
                    	
                        
                        <!--[if !IE]>right<![endif]-->
                        	<div id=\"right\">
                                                        
                            	<!--[if !IE]>directory-content<![endif]-->
                        				
                                        <div id=\"directory-content\">
                                        
                                       <h3> Admin / <span class=\"add-content\">Login</span>
                                       <br />                
</h3>
                                        
                                        </div>
                                <!--[if !IE]>directory-content<![endif]-->
                                
                                
                                
                                	
                        				
                                        <div id=\"form\">

					<form action=\'admin.php?view=login&act=login\' method=\'post\'>
					<table cellpadding=\'5\' cellspacing=\'2\' border=\'0\' width=\'100%\' align=\'center\'><tr><td><p>Username</p><input type=\'text\' name=\'username\' class=\'addtitle\' size=\'16\' value=\'{$username}\'></td></tr><tr><td><p><span class=\'drop\'>Password</span></p><input type=\'password\' name=\'password\' class=\'addtitle\' size=\'16\'></td></tr><tr><td><p>Captcha</p>{$captcha}</td></tr><tr><td><br /><input type=\'submit\' value=\'Login\' class=\'addContent-button\'></td></tr></table></form>', '".time()."');";

$sqldata["users"] = "INSERT INTO ".$pre."users VALUES (1, '".$_POST['username']."', '".md5($salt.$_POST['username'].md5($_POST['password']))."', '".$_POST['email']."', 'Administrator', 'Veteran', '".time()."', '".time()."', 'yes', 'yes', '', 0, 'main');
-- --------------------------------------------------------";

$tables[1] = "comments";
$tables[2] = "content";
$tables[3] = "data";
$tables[4] = "fields";
$tables[5] = "files";
$tables[6] = "groups";
$tables[7] = "levels";
$tables[8] = "media";
$tables[9] = "messages";
$tables[10] = "pages";
$tables[11] = "permissions";
$tables[12] = "plugins";
$tables[13] = "polls";
$tables[14] = "sections";
$tables[15] = "settings";
$tables[16] = "skins";
$tables[17] = "stats";
$tables[18] = "stats_archive";
$tables[19] = "users";

// upgrade data for 1.5
if ($_POST['submit'] == "Upgrade - 1.5") {
require("inc/dbinfo.php");
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."comments ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[author]) {
$user = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."users WHERE username = '".$r[author]."'"));
}
$sqldata["1.5"] .= "INSERT INTO ".$pre."77comments VALUES ('".$r[id]."', '".$r[aid]."', '".$user[0]."', '".$r[comment]."', '0|0', '".$r[email]."', '".$r[url]."', '".$r[ip]."', '', '".$r[date]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."articles ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$user = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."users WHERE username = '".$r[username]."'"));
$sqldata["1.5"] .= "INSERT INTO ".$pre."77content VALUES ('".$r[id]."', '".$r[name]."', '".$r[section]."', '".$user[0]."', '".$r[ver]."', '".$r[date]."', '".$r[date]."', '".$r[mdate]."', '".$r[ydate]."', '".$r[rating]."', '".$r[views]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."fielddata ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$field = mysql_fetch_row(mysql_query("SELECT cat FROM ".$pre."fields WHERE name = '".$r[fname]."'"));
if ($field[0] == "user-profile") {
$type = "custom-profile-data";
} else {
$type = "content-custom-data";
}
$sqldata["1.5"] .= "INSERT INTO ".$pre."77data VALUES ('".$r[id]."', '".$r[fname]."', '".$type."', '".$r[data]."', '".$r[aid]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."fields ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77fields VALUES ('".$r[id]."', '".$r[name]."', '".$r[cat]."', '".$r[type]."', '".$r[des]."', '".$r[info]."', '', '/', '');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."files ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77files VALUES ('".$r[id]."', '".$r[filename]."', '".$r[filedir]."', '', '".$r[gallery]."', '".$r[date]."', '".$r[rate]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."gallery ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77media VALUES ('".$r[id]."', '".$r[name]."', 0, '0|0');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."levels ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[name] == "Member") {
$r[options] = "yes";
}
$sqldata["1.5"] .= "INSERT INTO ".$pre."77groups VALUES ('".$r[id]."', '".$r[name]."', '', '', '".$r[options]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."pages ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$user = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."users WHERE username = '".$r[username]."'"));
$sqldata["1.5"] .= "INSERT INTO ".$pre."77pages VALUES ('".$r[id]."', '".$r[name]."', '".$r[content]."', '".$user[0]."', '".$r[date]."', 0);
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."permissions ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[name] == "templates") {
$r[name] = "skins";
} elseif ($r[name] == "gallery") {
$r[name] = "media";
} elseif ($r[name] == "users") {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77permissions VALUES (null, '".$r[level]."', 'groups', '".$r[padd]."|".$r[pedit]."|".$r[pdelete]."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77permissions VALUES (null, '".$r[level]."', 'levels', '".$r[padd]."|".$r[pedit]."|".$r[pdelete]."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77permissions VALUES (null, '".$r[level]."', 'settings', '".$r[padd]."|".$r[pedit]."|".$r[pdelete]."');
-- --------------------------------------------------------";
}
$sqldata["1.5"] .= "INSERT INTO ".$pre."77permissions VALUES (null, '".$r[level]."', '".$r[name]."', '".$r[padd]."|".$r[pedit]."|".$r[pdelete]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."polls ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$poll = @mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."polls WHERE name = '".$r[pname]."'"));
$sqldata["1.5"] .= "INSERT INTO ".$pre."77polls VALUES ('".$r[id]."', 0, '".$r[name]."', '".$r[type]."', '".$r[options]."', '".$poll[0]."', '".$r[votes]."', '".$r[date]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."ranks ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77levels VALUES ('".$r[id]."', '".$r[name]."', 'level', '', '".$r[prequired]."', '".$r[image]."', '".$r[color]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77sections VALUES ('".$r[id]."', '".$r[name]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."settings ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77settings VALUES (null, '".$r[name]."', '".$r[des]."', '".$r[data]."', '".$r[type]."', '".$r[section]."');
-- --------------------------------------------------------";
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."stats_archive ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[name] != "last_week" && $r[name] != "last_month" && $r[name] != "last_year") {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77stats_archive VALUES ('".$r[id]."', '".$r[name]."', '".$r[data]."', '".$r[week]."', '".$r[month]."', '".$r[year]."', '".$r[views]."', '".$r[uniques]."', '".$r[date]."');
-- --------------------------------------------------------";
}
}

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."templates ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[name] == "layout") {
$ex = explode("{content}", $r[template]);
$ex[0] = str_replace('{', '{$', $ex[0]);
$ex[1] = str_replace('{', '{$', $ex[1]);
$sqldata["1.5"] .= "INSERT INTO ".$pre."77skins VALUES (null, 'header', 'main', '".$ex[0]."', '".$r[date]."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES (null, 'footer', 'main', '".$ex[1]."', '".$r[date]."');
-- --------------------------------------------------------";

$fh = fopen($sitepath."templates/main/header.tpl", 'w') or die("can't open file");
fwrite($fh, stripslashes($ex[0]));
fclose($fh);

$fh2 = fopen($sitepath."templates/main/footer.tpl", 'w') or die("can't open file");
fwrite($fh2, stripslashes($ex[1]));
fclose($fh2);
} else {
$cat = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."sections WHERE name = '".$r[name]."'"));
if ($cat > 0) {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77skins VALUES (null, '".$r[name]."', 'main', '".str_replace('{', '{$', $r[template])."', '".$r[date]."');
-- --------------------------------------------------------";
}
}
}
$sqldata["1.5"] .= "INSERT INTO ".$pre."77skins VALUES (null, 'main', '', 'yes|', '".time()."');
-- --------------------------------------------------------";

unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."users ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$sqldata["1.5"] .= "INSERT INTO ".$pre."77users VALUES ('".$r[id]."', '".$r[username]."', '".$r[password]."', '".$r[email]."', '".$r[level]."', '".$r[rank]."', '".$r[logged]."', '".$r[date]."', 'yes', 'yes', '', 0, 'main');
-- --------------------------------------------------------";
}

$sqldata["1.5"] .= "INSERT INTO ".$pre."77data VALUES (null, 'Content', 'help-file', '<p>This is where it\'s all at! AdaptCMS is geared towards content websites and here we have it, content. You can completely manage content (including comments) here.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Add', 'help-file', '<p><strong>Adding Content</strong></p>\r\n<p>From the main content page, the top right you will see \"add\" and then a dropdown for the section in which you want to add content to.</p>\r\n<p>On the Add Content form what you will see depends on the sections you have and the custom fields added. But you will first see the field \"title\" for the name of the content piece. (this will show in the URL) Below that is the ability to \"link\" content, so if you have a game section and are adding a news story you could click on the game title - you could then show the game information on the news article or vice-versa, very powerful.</p>\r\n<p>After that are any custom fields you have added and then a \"Publish Later\" options. It\'s for various uses, but the jist is you choose a date by clicking the calendar icon and the content piece will not go live for the public to see until that date.</p>\r\n<p>Lastly is a \"Tags\" feature commonly seen across the web, better for searching as well as for a tag cloud. You can then either choose to add the content item or \"Save Draft\", so it will be added but not viewable yet - great if you have an editor/publisher staff situation setup or just want to work on it later.</p>', 'Content');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Content</strong></p>\r\n<p>Let\'s start out with the simple - click the red icon and after a confirm box, the content piece is deleted forever. The yellow exclamation is to \"verify\" the item. This is used in various ways, but it means the user who submitted it has there permissions edited so there content pieces have to be approved before going live. Clicking the icon sends the content live for all to see.</p>\r\n<p>For specifics on the various fields and such you can see the \"Add Content\" help file. The difference is at the end of the form where you can choose to \"Update\" which simply updates the content piece and keeps its status as is or you can \"Publish Later\" which sends it to draft, or if it is already saved to draft you can update it and have it go live.</p>', 'Content');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Custom Fields', 'help-file', '<p>One of the key aspects of content and very helpful to make AdaptCMS adaptable to any content website. With custom fields you can setup a&nbsp; wide vary of different setups.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Add', 'help-file', '<p><strong>Add Field</strong></p>\r\n<p>To start, pick a name for the field - don\'t worry about caps, all spaces are removed and converted to all lowercase after submission. Next you need to pick the section to add the field to - hold \"control\" and click on others to add it for multiple sections. Choosing \"User Profiles\" is not a normal section, it\'s for the profile area of the CMS - so you can add things like \"AIM\" or \"Website\" so they have a nice informative profile for others to see.</p>\r\n<p>If your not sure what these types are - \"textfield\" is just a normal input like what you\'d put your username in, \"textarea\" what you will see right below the type selection, dropdown is what you\'re using radiobox is the circular little button selection and checkbox a little square and lastly - file, where you can select an uploaded file for the field data.</p>\r\n<p>Data is for only dropdown, radio and checkbox. So you enter the data, like \"XBOX 360,PS3,PC,PSP\" will produce say a dropdown with those selections. Description you can put in brief info about the use of this field, useful for other staff members.</p>\r\n<p>New to AdaptCMS 2.x is the last 3 options - clicking editable will let regular logged in users be able to edit this field in the frontend. (but the permissions to let them first edit the section the content item belongs in has to be selected) Max/Minimum is a character limit on the field data and lastly - Required, if clicked then the content item won\'t be added until this field has data.</p>', 'Custom Fields');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Field</strong></p>\r\n<p>Clicking the red icon will delete the field after a confirm, editing is pretty much unchanged from adding a field except the fields are filled out. Refer to the \"Add\" help file for information on that aspect.</p>', 'Custom Fields');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Sections', 'help-file', '<p>Another aspect of Content, Sections let you manage content in a nice clean way. However since there is just a name to fill out for this, no point in having more than this brief help file.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Media', 'help-file', '<p>Formerly known as \"Gallery\" in AdaptCMS 1.x, in AdaptCMS 2.x is a heavily improved (actually re-done) feature that boasts both image and video support.</p>\r\n<p>Media is tool to let you manage all video and images, simply upload some pictures or have a full blown media gallery with albums and hundreds of pictures inside for people to check out, have fun!</p>\r\n<p><strong>Main Media Page</strong></p>\r\n<p>For AdaptCMS 1.x users, the main media page will look completely different - it\'s broken up into two different areas - the top has the media albums with appropiate actions and below a file list (with a click of a button via the dropdown, can show all files or files not associated with an album).</p>\r\n<p>In the bar above both areas you will find a link to \"Add File\" and then \"Add Media\", the former that lets you create an album. For the album area you can either click on the preview picture or the plus button to upload new files to the album, the wrench to edit or the red icon to delete the album and files within it.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Add File', 'help-file', '<p><strong>Add a File</strong></p>\r\n<p>Before you start, find out how many to upload so you can first enter in how many up top by \"Add more Files\" to populate the appropiate amount of fields. Next you will see that list.</p>\r\n<p>You can choose to either upload this new file from a file on your computer or link to a file on an external website. (linking to another site is useful if the file is outside your \"upload\" folder, however the watermark and re-size will not work for the file in that case)</p>\r\n<p>Clicking watermark will put a little watermark icon like you\'d see the FOX logo when watching TV, it uses the \"watermark.png\" image or whatever you enter in the settings area. Re-size lets you re-size the image, do not use if it is anything other than an image. (such as a video) Lastly a caption - all files are optional except of course the file itself.</p>', 'Media');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Edit File', 'help-file', '<p><strong>Edit File</strong></p>\r\n<p>With a preview (if it\'s an image or recognized file type) to the left, you can rename the file (but recommend not to change file extension) and the \"watermark\", \"re-size\" and \"caption\" features detailed in the \"Add File\" help file. Clicking the delete checkbox will delete the file both in database and in physical form. You can then lastly choose a media album to link the file to.</p>', 'Media');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Manage Album', 'help-file', '<p><strong>Manage Media</strong></p>\r\n<p>At the top you can rename the media album and below all files linked to the album will be listed and you can edit there details - info on those functions can be found in \"Add File\".</p>', 'Media');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Pages', 'help-file', '<p>Maybe not the most important aspect of AdaptCMS, but still a very useful feature that all websites use. Static pages - such as \"Contact Us\", \"Privacy Policy\", \"About\" and the like - what you will find on 90% of websites.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Add', 'help-file', '<p><strong>Add Page</strong></p>\r\n<p>This will be pretty simple - choose a name, which will appear in the URL and then enter in the page contents below. Yep, that simple.</p>\r\n<p>When returning to the main pages area you will see the page and can click on it for the URL.</p>', 'Pages');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Users', 'help-file', '<p>Without users there is no website! You need viewers and after time you need those viewers to become members to contribute and not only that, help bring more traffic in.</p>\r\n<p>With the users area of the ACP you can completely manage an individual users information in conjuction with \"Groups\" and \"Levels\".</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Add', 'help-file', '<p><strong>Add Users</strong></p>\r\n<p>Traditionally you will just have people sing up through the frontend of the website but you may need to manually add a user for whatever reason. Simply fill out the username, password, email and assign a group and you\'re done!</p>', 'Users');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Users</strong></p>\r\n<p>On the main users page under a specific user you will find either a green check mark or a red X under \"verified\" and \"activated\". You can change the status of both at any time - verified is your staffs responsibility, if the setting is turned on new members cannot login into there account until there account is verified by staff. Activate is e-mail activation.</p>\r\n<p>When editing a user account you can choose to rename the user, set a new password, change there e-mail or assign them to a different group.</p>', 'Users');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Groups', 'help-file', '<p>Formerly known as \"Permissions\" and \"Levels\" in AdaptCMS 1.x, \"Groups\" forms both things into just one. You can manage the various groups such as Members, Admins and Staff and adjust there permission settings - which basically tells the CMS what features those users can use.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Add', 'help-file', '<p><strong>Add Group</strong></p>\r\n<p>To start you need to enter in a group name - keep it simple. Next you choose a default permission set - you will be able to edit the full permissions after it\'s been added. (helps keep things simple) As well a default color for the group, image icon can be set.</p>\r\n<p>Lastly - default is an important aspect you will want to pay attention to. The primary use is for regular members, to mainly help the CMS - be sure to only have on checked and it be your normal member group.</p>', 'Groups');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Group</strong></p>\r\n<p>Let\'s just skip over the top fields, you can find out there info in the \"Add\" group help file - we\'ll skip the fun part, permissions!</p>\r\n<p>The permissions are split into two areas - \"admin\" which is the various modules and \"content\" for section-specific permissions. To begin with the admin options are self-explanatory, if you dissallow adding say a field for the group \"Staff\" and user \"joebloe\" is assigned to that group, they cannot add a field.</p>\r\n<p>The section-specific permissions have more meaning. First, if you don\'t have edit or delete clicked but just \"Add\" of the three, then you can add a content item to that section as well as edit/delete your own but not others. \"Verify\" means if a user from that group adds a content item for that section it will not go live for others to see until it has been verified by staff.</p>\r\n<p>Lastly for user-content or users to be able to edit an article, you need to grant them permission here - but don\'t worry, if the normal \"content\" permissions under \"admin\" aren\'t selected, they cant access the ACP. For normal users, requiring verification would be a wise thing.</p>', 'Groups');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Skins', 'help-file', '<p>What you see! Basically true, as without a skin you would see nothing but a white page on the frontend and no admin design in the ACP. New to 2.x is \"skins\", transforming the old template system to a more versatile advanced system.</p>\r\n<p>Now using smarty (tags that were {title} are now {$title}!);
-- -------------------------------------------------------- with template caching and the ability to not only edit the admin skin but to also have multiple skins.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Add', 'help-file', '<p><strong>Add Skin</strong></p>\r\n<p>First to add a skin is an insanely easy process, simply enter a name and the default templates are inserted automatically.</p>\r\n<p><strong>Add Template</strong></p>\r\n<p>An easy process as well, you start out with entering in a template name and then you can choose (but optional) a skin to assign it to. Lastly enter the template data.</p>', 'Skins');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Skins/Templates</strong></p>\r\n<p>Explained on the \"add\" help file under Skins, but on the main page of \"Skins\" you will see the top area list show the skins and next to the name is a dropdown to edit a specific template from that skin. The other list is just all templates added.</p>', 'Skins');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Polls', 'help-file', '<p>One important aspect of user interaction is polls. A similar pollinig feature that you saw in AdaptCMS 1.x returns but in a improved form in 2.x.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Add', 'help-file', '<p><strong>Add Poll</strong></p>\r\n<p>As with every other module, you need a name, in this case it\'s the poll question. Then you have two options - can people choose more than one option when voting on the poll and can a user enter a new poll \"option\". (something unique to adaptcms)</p>\r\n<p>The other part is the actuall poll options. Just one will be listed, but you can click \"Add Option\" to populate additional ones and click the red X to delete it. When you are done, click \"Add Poll\".</p>', 'Polls');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Manage', 'help-file', '<p><strong>Manage Poll</strong></p>\r\n<p>The info detailed in \"Add\" help file already, but starting with the options list you can edit the name or click the check mark to have the option deleted upon submit. Then you can add brand new options as well, clicking \"Add Option\" to populate a new option.</p>\r\n<p>Lastly click \"Edit Poll\" to submit&nbsp; or if you click \"Delete Poll\", the poll and it\'s associated options will be deleted from the database.</p>', 'Polls');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'ACP', 'help-file', '<p><strong>A</strong>dmin <strong>C</strong>ontrol <strong>P</strong>anel</p>\r\n<p>This help file will contain just random tidbits of information on the ACP.</p>\r\n<p><span style=\"text-decoration: underline;\">ACP Bar</span> - One of the new features to 2.x, the top ACP bar is present in the ACP and if you put the {$acp_bar} tag in your \"header\" template, in the frontend as well. Simply click on \"Toggle\" which will be at the top right of your screen where you will find a plethora of information, from newest members to stats for the day.</p>\r\n<p><span style=\"text-decoration: underline;\">Version Check</span> - If you are using the default admin_footer template, on the bottom left, you will see \"You are running <span class=\"drop\">- AdaptCMS 2.0.0 Beta\" (or whatever version you are running) as well as an image to the left that\'s either a check mark or a red X. If it is a red X like so <img src=\"http://www.adaptcms.com/images/cancel.png\" alt=\"\" width=\"32\" height=\"32\" />which means you need to upgrade. Just click the icon and it will take you to the AdaptCMS website to upgrade your copy of AdaptCMS.<br /></span></p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77data VALUES (null, 'Social', 'help-file', '<p><strong>Social</strong></p>\r\n<p>This is a combination of basic aspects of social management from AdaptCMS 1.x and largely new features. With the new Social set of features the AdaptCMS team has laid the foundation of social management in AdaptCMS.</p>\r\n<p>To start you can begin from your own profile page, which will look like this URL wise - http://www.yoursite.com/profile/yourusername</p>\r\n<p>It will of course depend on how you design it (yes, you can skin the social features!) but by default you will find your latest update on the top left, your username and update twitter-like box, below that the social menu\'s and then the profile info and latest status updates.</p>\r\n<p>\"Edit\" will take you to edit your profile info, from password and e-mail to the website skin and any custom fields. Under profile you can return to the main page as well as the status update page.</p>\r\n<p>\"Friends\" is another new feature, letting you add friends or accept them as well as see which ones you have. The main use at the moment is for your \"feed\", status updates will include your friends. \"Blogs\" another new feature which lets you manage your own little small blog, you can add/edit/delete as well users can comment on them.</p>\r\n<p>Lastly from AdaptBB is \"Messages\", a private-messaging type system.</p>', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77levels VALUES (null, 'Veteran', 'level', '', 100, '', 'red');
-- --------------------------------------------------------
INSERT INTO ".$pre."77levels VALUES (null, 'Posted Comment', 'point', 'index.php?do=comments&submit=yes', 25, '', '');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'admin_limit', '', '20', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'word_filter', '', 'fuck,bitch,cunt,whore,nigger', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'upload_folder', '', 'upload/', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'file_extensions', '', 'jpg,png,mp3,wmv,zip,txt', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'ratings_guests_comments', '', 'yes', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'ratings_guests_content', '', 'yes', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'comment_flood_limit', '', '20', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'cookie_prefix', '', '', 'setting', 'General');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'message_limit', '', '50', 'setting', 'Other');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'profile_username_change', 'Can users change there username via edit profile?', 'yes', 'setting', 'Variables');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'status_char_limit', 'Limit the amount of characters allowed in a status update.', '140', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'section_limit', 'Amount of content items to display on a section listing page.', '10', 'setting', 'Modules');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'status_limit', '', '3', 'setting', 'General');
-- --------------------------------------------------------
INSERT INTO ".$pre."77settings VALUES (null, 'message_limit_page', 'Amount of messages to display per page.', '10', 'setting', 'General');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'admin_header', 'main', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>AdaptCMS {$adaptcms_version} - {$acp_page}</title>
<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\" />
<link rel=\'stylesheet\' href=\'inc/js/menu.css\'>
<script type=\'text/javascript\' src=\'inc/js/menu.js\'></script>
<script type=\'text/javascript\' src=\'inc/js/sortmenu.js\'></script>
{literal}
<script type=\"text/javascript\" >
	$(\'document\').ready(function(){
		$(\'#flip-container\').quickFlip();
		
		$(\'#flip-navigation li a\').each(function(){
			$(this).click(function(){
				$(\'#flip-navigation li\').each(function(){
					$(this).removeClass(\'selected\');
				});
				$(this).parent().addClass(\'selected\');
				var flipid=$(this).attr(\'id\').substr(4);
				$(\'#flip-container\').quickFlipper({ }, flipid, 1);
				
				return false;
			});
		});
	});



	function toggle(id,img1,img2,idname)
	{
 	 
		if (id.src.indexOf(img1)>0 )
		{
	 	
			document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-up.jpg\';	
			 
		}
		else if (id.src.indexOf(img2)>0)
		{

	 		document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-down.jpg\';
			 		 
		}
 
	}
	
	
function toggle1(id,img1,img2,idname)
	{
 	 
		if (id.src.indexOf(img1)>0 )
		{
	 	
			document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-up2.jpg\';	
			 
		}
		else if (id.src.indexOf(img2)>0)
		{

	 		document.getElementById(idname).src=\'{/literal}{$siteurl}{literal}images/arrow-down2.jpg\';
			 		 
		}
 
	}
	


</script>




<script type=\"text/javascript\">

$(document).ready(function() {	


  //Get all the LI from the #tabMenu UL
  $(\'#tabMenu > li\').click(function(){
        
    //remove the selected class from all LI    
    $(\'#tabMenu > li\').removeClass(\'selected\');
    
    //Reassign the LI
    $(this).addClass(\'selected\');
    
    //Hide all the DIV in .boxBody
    $(\'.boxBody div\').slideUp(\'1500\');
    
    //Look for the right DIV in boxBody according to the Navigation UL index, therefore, the arrangement is very important.
    $(\'.boxBody div:eq(\' + $(\'#tabMenu > li\').index(this) + \')\').slideDown(\'1500\');
    
  }).mouseover(function() {

    //Add and remove class, Personally I dont think this is the right way to do it, anyone please suggest    
    $(this).addClass(\'mouseover\');
    $(this).removeClass(\'mouseout\');   
    
  }).mouseout(function() {
    
    //Add and remove class
    $(this).addClass(\'mouseout\');
    $(this).removeClass(\'mouseover\');    
    
  });

  //Mouseover with animate Effect for Category menu list
  $(\'.boxBody #category li\').mouseover(function() {

    //Change background color and animate the padding
    $(this).css(\'backgroundColor\',\'#888\');
    $(this).children().animate({paddingLeft:\"20px\"}, {queue:false, duration:300});
  }).mouseout(function() {
    
    //Change background color and animate the padding
    $(this).css(\'backgroundColor\',\'\');
    $(this).children().animate({paddingLeft:\"0\"}, {queue:false, duration:300});
  });  
	
  //Mouseover effect for Posts, Comments, Famous Posts and Random Posts menu list.
  $(\'.boxBody li\').click(function(){
    window.location = $(this).find(\"a\").attr(\"href\");
  }).mouseover(function() {
    $(this).css(\'backgroundColor\',\'#888\');
  }).mouseout(function() {
    $(this).css(\'backgroundColor\',\'\');
  });  	
	
});

</script>
{/literal}
</head>
<body>
{$acp_bar}

	<!--[if !IE]>main<![endif]-->
    	<div id=\"main\">
        
        	<!--[if !IE]>header-area<![endif]-->
            	
            	<div id=\"header-area\">
                
                	<!--[if !IE]>search-bar<![endif]-->
            	
            			<div id=\"search-bar\"><form action=\"admin.php\" method=\"get\">
                			
                           <input class=\"search-button\" type=\"submit\" value=\"Search\"/> <input type=\'hidden\' name=\'view\' value=\'search\'>
                            
                            <input class=\"search-box\" type=\"text\" name=\"search\" size=\"15\"  height=\"3\" onblur=\"if (this.value == \'\') {ldelim}this.value = \'Lets Search Here ...\';{rdelim}\" onfocus=\"if (this.value == \'Lets Search Here ...\') {ldelim}this.value = \'\';{rdelim}\" id=\"ls\"  value=\"Lets Search Here ...\"  /> 
                            </form>
                		</div>
                
            		<!--[if !IE]>search-bar<![endif]-->
                    
                    
                    	<!--[if !IE]>header<![endif]-->
            	
            				<div id=\"header\">
                				
                                
                                <!--[if !IE]>logo<![endif]-->
                                	
                                    <div id=\"logo\">
                                    	
                                        <a href=\"{$siteurl}\"><img src=\"images/logo.jpg\" width=\"263\" height=\"46\" alt=\"company name\" border=\"0\" align=\"middle\"/></a>
                                    
                                    </div>
                                <!--[if !IE]>logo<![endif]-->
                                
                                
                                	 
                                     <!--[if !IE]>right-header<![endif]-->
                                	
                                    	<div id=\"right-header\">
                                		<a href=\"http://www.adaptcms.com\"><img src=\"images/banner3.jpg\" width=\"464\" height=\"72\" alt=\"banner\" align=\"right\" border=\"0\"/></a>
      
                                		</div>
                                    
                                	 <!--[if !IE]>right-header<![endif]-->
                                
                			</div>
                
            			<!--[if !IE]>header<![endif]-->
                        
                        
                        	
                            <!--[if !IE]>navigation<![endif]-->
                        	
                            	<div id=\"navigation\">
                                
                                	<!--[if !IE]>button<![endif]-->
                        	
                            			<div id=\"button\">
                            	
                                            <ul>
			<li><a href=\"admin.php\">ACP</a></li>
			<li><a href=\"{$siteurl}\">Website</a></li>
			<li><a href=\"admin.php?view=share\">Share</a></li>
			<li><a href=\"admin.php?view=support\">Support</a></li>
			<li><a href=\"{$siteurl}profile/{$user_name}\">Your Profile</a></li>
			<li><a href=\"admin.php?quick_link=1\"><img src=\"images/add.png\" border=\"0\"></a></li>
                                            </ul>
                                
                            			</div>
                                        
                                     <!--[if !IE]>button<![endif]-->
                                     
                                     
                                     	<!--[if !IE]>icon<![endif]-->
                        	
                            				<div id=\"icon\">
                                            	
                                                <ul>
                                                   
                                                    <li>
													{php}
													echo help(\"\", \"no_text\");
													{/php}<img src=\"images/help.jpg\" width=\"41\" height=\"43\" alt=\"help\" border=\"0\"/></a></li>  
                                                    <li><a href=\"{$siteurl}messages\"><img src=\"images/icons4.png\" width=\"46\" height=\"43\" alt=\"new\" border=\"0\"/></a></li>


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
                        	
             <div id=\"bg-pattern\">
             
             	<!--[if !IE]>content-part<![endif]-->
                	
                    <div id=\"content-part\">
                    
     
                
                	<!--[if !IE]>sidebar<![endif]-->
                	
                    	<div id=\"sidebar\">
 
                                <!--[if !IE]>sidebar1<![endif]-->
                        
                                    <div id=\"sidebar1\" class=\"menu\">
                            
                                      
                                      <!--[if !IE]> basic menu<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\"> Content <span class=\"drop\"> Posting</span></div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text4\').style.display==\'block\'){ldelim}document.getElementById(\'text4\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text4\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text4\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id3\');\" id=\'id3\'  />
                    
                    </span>

                    	</div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id=\"text4\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"side-menu2\">
                   
                   	<li><a href=\"admin.php?view=content\"> Content </a></li>
                   	<li><a href=\"admin.php?view=fields\"> Custom Fields </a></li>
                   	<li><a href=\"admin.php?view=sections\"> Sections </a></li>
                   	<li><a href=\"admin.php?view=media\"> Media </a></li>
					<li><a href=\"admin.php?view=skins\"> Skins </a></li>

                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading2<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->                        
                                        
                                    
                              </div>
                           
                           <!--[if !IE]>sidebar1<![endif]-->
                                
                                
                                
                                
                              <!--[if !IE]>acp<![endif]-->
                        
                                 <div id=\"acp\" class=\"menu\">
                                    
                                    	
                                        <!--[if !IE]> basic menu<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\">User <span class=\"drop\"> Management</span></div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text5\').style.display==\'block\'){ldelim}document.getElementById(\'text5\').style.display=\'none\'; {rdelim}else{ldelim}ocument.getElementById(\'text5\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text5\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id4\');\" id=\'id4\'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading2<![endif]-->     
                               
                                 
              <div id=\"text5\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"side-menu2\">
                   
                   	<li><a href=\"admin.php?view=users\"> Users </a></li>
                   	<li><a href=\"admin.php?view=groups\"> Groups </a></li>
                   	<li><a href=\"admin.php?view=levels\"> Levels </a></li>
           
                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->  
                            
                                       
                                    </div>
                           
                                <!--[if !IE]>acp<![endif]-->


								                              <!--[if !IE]>acp<![endif]-->
                        
                                 <div id=\"acp\" class=\"menu\">
                                    
                                    	
                                        <!--[if !IE]> basic menu<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\">Advanced <span class=\"drop\"> Misc</span></div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text3\').style.display==\'block\'){ldelim}document.getElementById(\'text3\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text3\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text3\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id2\');\" id=\'id2\'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading2<![endif]-->     
                               
                                 
              <div id=\"text3\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"side-menu2\">
                   
                   	<li><a href=\"admin.php?view=polls\"> Polls </a></li>
                   	<li><a href=\"admin.php?view=pages\"> Pages </a></li>
                   	<li><a href=\"admin.php?view=settings\"> Settings </a></li>
					<li><a href=\"admin.php?view=plugins\"> Plugins </a></li>
					<li><a href=\"admin.php?view=stats\"> Stats </a></li>
					<li><a href=\"admin.php?view=tools\"> Tools </a></li>
           
                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->  
                            
                                       
                                    </div>
                           
                                <!--[if !IE]>acp<![endif]-->
                                
                                
								                	<!--[if !IE]>skin chooser<![endif]-->
                     <div id=\"vsitors-online\" class=\"menu\">
                           
                           <!--[if !IE]>sidebar-heading<![endif]-->   
                            
                            <div class=\"sidebar-heading2\">
                            
                                <div class=\"heading-text1\">Admin <span class=\"drop\"> Skin</span></div>
                            
                                <div class=\"arrow2\">
                                
                                <span onClick=\"if(document.getElementById(\'text2\').style.display==\'block\'){ldelim}document.getElementById(\'text2\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text2\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text2\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\"   border=\"none\"  width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id1\');\" id=\'id1\'    />
                                
                                </span>
            
                                	</div>
                         
                            </div>
                        <!--[if !IE]>sidebar-heading<![endif]-->
                        
                        
                  <div id=\"text2\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"vsitors-online\">
                   
                        <div align=\"center\">{$change_skin}</div>
                   
               	  	</ul>

             
            	</div> 
            </div>
            
          	<!--[if !IE]> skin chooser<![endif]-->            
                                
                               		
                                    <!--[if !IE]>vsitors-online<![endif]-->
                        
                                        <div id=\"vsitors-online\" class=\"menu\">
                                
                                            
                                            <!--[if !IE]> Visitors<![endif]-->
                                       	
                                        <!--[if !IE]>sidebar-heading2<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\">Basic <span class=\"drop\"> Stats</span></div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text6\').style.display==\'block\'){ldelim}document.getElementById(\'text6\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text6\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text6\').style=\'hand\';\"><img src=\"images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle1(this,\'images/arrow-down2.jpg\',\'images/arrow-up2.jpg\',\'id5\');\" id=\'id5\'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id=\"text6\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"vsitors-online\">
                   
                   		<li>Members online -<span class=\"drop\">{php}echo online(\"members\", 10);{/php}</span></li>
                         <li>Guest online - <span class=\"drop\">{php}echo online(\"guests\", 10);{/php}</span></li>
                         <li>Total Members -<span class=\"drop\">{php}echo stats(\"users\");{/php}</span></li>
						 <li>Users online - <span class=\"drop\">{php}echo users_online(10);{/php}</span></li>
                 </ul>

                   
                     
            	</div> 
                           
                                    
                                        <!--[if !IE]>sidebar-heading2<![endif]-->
                                        
                                    <!--[if !IE]> Visitors<![endif]--> 
                                            
                                           
                                        </div>
                           
                                <!--[if !IE]>vsitors-online<![endif]-->
                               
                        
                        </div>
                       
                    <!--[if !IE]>sidebar<![endif]-->
                    
                    	
                        
                        <!--[if !IE]>right<![endif]-->
                        	<div id=\"right\">
                                                        {if $smarty.get.view}
                            	<!--[if !IE]>directory-content<![endif]-->
                        				
                                        <div id=\"directory-content\">
                                        
                                       <h3> {$acp_page_view} / <span class=\"add-content\">{$acp_page_do} {$acp_page_view2}</span>{$acp_bar_data}<br />                
</h3>
                                        
                                        </div>
                                <!--[if !IE]>directory-content<![endif]-->
                                
                                {/if}
                                
                                	<!--[if !IE]>form<![endif]-->
                        				
                                        <div id=\"form\">', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'admin_footer', 'main', '</div>\r\n                                        \r\n                                    <!--[if !IE]>form<![endif]-->\r\n                            \r\n                            </div>\r\n                        <!--[if !IE]>right<![endif]-->    \r\n                   \r\n                        \r\n                        \r\n                         </div>\r\n                <!--[if !IE]>content-part<![endif]-->\r\n                \r\n                \r\n                	<!--[if !IE]>footer<![endif]-->\r\n                        	<div id=\"footer\">\r\n                           \r\n                           <ul>\r\n                           <li><a href=\"http://www.adaptcms.com/download\"><img src=\"http://www.adaptcms.com/version.php?version={$adaptcms_version}\" width=\"38\" height=\"32\" alt=\"icon\" border=\"0\" /></a></li>\r\n                           <li>You are running <span class=\"drop\">- AdaptCMS {$adaptcms_version}</span></li>\r\n                           </ul>\r\n                           \r\n                           		<p>Copyright 2006-2010 <a href=\'http://www.insanevisions.com\'>Insane Visions</a><p>\r\n                            \r\n                            </div>\r\n                        <!--[if !IE]>footer<![endif]-->	\r\n             \r\n             </div>\r\n             	\r\n		<!--[if !IE]>bg-pattern<![endif]-->\r\n\r\n\r\n\r\n\r\n</body>\r\n</html>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'page', 'main', '<title>Page - {$name}</title>\r\n\r\n<b>{$name}</b> by {$username} @ <i>{$date}</i><br />\r\n<p>{$content}</p>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'poll_results', 'main', '<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'75%\'><tr><td><b>{$question}</b></td></tr></table><br clear=\'all\'>\r\n\r\n<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'75%\'>\r\n\r\n{section name=sec loop=$options}\r\n<tr><td>{$options[sec]}</td><td>{$options_data[sec]}</td></tr>\r\n{/section}\r\n\r\n<tr><td><b>Votes:</b> {$vote_total}</td></tr></table><br clear=\'all\'>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'poll_vote', 'main', '{$poll_header}\r\n<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'200\'><tr><td><b>{$question}</b></td></tr></table><br>\r\n\r\n<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' align=\'center\' width=\'200\'>\r\n\r\n{section name=sec loop=$options}\r\n<tr><td>{$options_data[sec]}</td><td>{$options[sec]}</td></tr>\r\n{/section}\r\n\r\n<tr><td><br><br>{$submit}</td><td><a href=\"{$siteurl}poll-results\">Results</a></td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'homepage', 'main', '{php}\r\necho content(\'homepage_content\', \'{$home_section}\', 5, \'\', \'\');\r\n{/php}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'homepage_content', 'main', '				<h2>{$link}</h2>\r\n				<span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span><br />\r\n								<span class=\"what\">On:</span> {$date}<br /><br />\r\n						{$story}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'media_list', 'main', '<table cellpadding=\"5\" cellspacing=\"2\"><tr>\r\n{section name=med loop=$media}\r\n<td><a href=\'{$media_url[med]}\'>{$media_image[med]}</a><br />{$media_name[med]}</td>\r\n{if $smarty.section.med.iteration % 3 == 0}\r\n</tr><tr>\r\n{/if}\r\n{/section}\r\n</tr></table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'media_page', 'main', '<title>{$sitename} - {$media_name}</title>\r\n\r\n<h2>{$media_name}</h2>\r\n\r\n<table cellpadding=\"5\" cellspacing=\"1\"><tr>\r\n{section name=r loop=$file}\r\n<td><a href=\'{$file_view[r]}\'>{$file_code[r]}</a></td>\r\n{if $smarty.section.r.iteration % 3 == 0}\r\n</tr><tr>\r\n{/if}\r\n{/section}\r\n</tr></table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'file_view', 'main', '<title>{$sitename} - {$media_name}</title>\r\n\r\n<h2>{$media_name}</h2>\r\n\r\n<table cellpadding=\"3\"><tr><td align=\"center\">\r\n{$file_code}\r\n</td></tr><tr><td><i>{$file_caption}</i></td></tr></table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'latestnews', 'main', '<li>{$link}</li>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'section', 'main', '				<div class=\"cBoxHeader\"><h2>{$link}</h2></div>\r\n				<div class=\"cBoxBg\">\r\n					<div class=\"cBoxText\">\r\n						<div class=\"cBoxTextInfo\">\r\n							<ol>\r\n								<li><span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span></li>\r\n								<li><span class=\"what\">On:</span> {$date}</li>\r\n							</ol>\r\n						</div>\r\n						{$story}\r\n</div>\r\n					</div>\r\n				</div>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'latestmedia', 'main', '{section name=abc loop=$file}\r\n<a href=\'{$file_view[abc]}\' class=\'input\' alt=\'{$file_name[abc]}\'>{$file_code[abc]}</a><br /><br />\r\n{/section}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'admin_bar', 'main', '<p style=\'padding:10px\'>\r\nTesting stuff here!\r\n</p>\r\n<br style=\'clear: left\' />\r\n<img src=\'{$siteurl}images/cancel.png\' class=\'closepanel\'> <b class=\'closepanel\'>Close</b>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'view_profile', 'main', '<table cellpadding=\"3\" cellspacing=\"0\" width=\"90%\" align=\"center\">\r\n<tr><td>Group: {$group}</td></tr>\r\n<tr><td>Level: {$level}</td></tr>\r\n<tr><td>Last Login: {$last_login}</td></tr>\r\n</table>\r\n\r\n<div align=\"center\"><h3>Updates</h3></div>\r\n<table cellpadding=\"3\" cellspacing=\"2\" width=\"90%\" align=\"center\">\r\n\r\n{section name=r loop=$statuses}\r\n<tr><td><img src=\"{$status_avatar[r]}\" width=\"48\"></td><td><b>{$status_username[r]}</b> {$status_data[r]}<br /><small>{$status_date[r]}</td></tr>\r\n{/section}\r\n</table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'register', 'main', '<title>{$sitename} - Register</title><a href=\'index.php\'>Directory</a>  -  Register / Form<br /><br />\r\n{$form_start}<table><tr><td>Username</td><td>{$username_input}</td></tr><tr><td>Password</td><td>{$password_input}</td></tr><tr><td>Password Confirm</td><td>{$password_input2}</td></tr><tr><td>E-Mail</td><td>{$email_input}</td></tr><tr><td>Captcha</td><td>{$captcha}</td></tr><tr><td><input type=\'submit\' value=\'Register\' class=\'input\'></td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'login', 'main', '<title>{$sitename} - Login</title><a href=\'index.php\'>Directory</a>  -  Login / Form - <a href=\'{$register_link}\'>Signup</a><br /><br />\r\n{$form_start}<table><tr><td>Username</td><td>{$username_input}</td></tr><tr><td>Password</td><td>{$password_input}</td></tr><tr><td>Captcha</td><td>{$captcha}</td></tr><tr><td><input type=\'submit\' value=\'Login\' class=\'input\'></td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'edit_profile', 'main', '<title>{$sitename} - Edit Profile</title><a href=\'index.php\'>Directory</a>  -  Social / Edit Profile<br /><br />\r\n{$form_start}<table>\r\n<tr><td>Username</td><td>{$username_input}</td></tr>\r\n<tr><td>New Password</td><td>{$password_input}</td></tr>\r\n<tr><td>Password Confirm</td><td>{$password_input2}</td></tr>\r\n<tr><td>E-Mail</td><td>{$email_input}</td></tr>\r\n<tr><td>Skin</td><td>{$skin_input}</td></tr>\r\n<tr><td></td><td></td></tr>\r\n{section name=r loop=$fields}\r\n<tr><td>{$field_name[r]}</td><td>{$field_input[r]}</td><td>{$field_info[r]}</td></tr>\r\n{/section}\r\n<tr><td><input type=\'submit\' value=\'Update\' class=\'input\'></td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'message_view', 'main', '<table cellpadding=\'5\' cellspacing=\'0\' border=\'0\' width=\'100%\' align=\'center\' style=\'border: 2px solid #dddddd\'><tr style=\'background:url({$siteurl}inc/images/topbg.jpg) repeat-x;\'><td><b>Private Messages</b> - {$folder}</td></tr>\r\n<tr><td align=\"center\" class=\"light\"><b>{$folder}</b> - {$messages_num} messages with a total of {$max_messages} permitted. ({$messages_percent})<br />{$folder_dropdown}</td></tr></table><br>\r\n\r\n<table cellpadding=\'5\' cellspacing=\'0\' border=\'0\' width=\'100%\' align=\'center\' style=\'border: 2px solid #dddddd\'><tr style=\'background:url({$siteurl}inc/images/topbg.jpg) repeat-x;\'><td valign=\"top\" align=\"center\" width=\"150\" style=\"border-right: 1px solid #dddddd\"><i>{$date}</i><br><br><font size=\"3\"><b><u>{$poster_username}</u></b></font><br>{$poster_rank_image}<font size=\"1\"><br><br><img src=\"{$poster_avatar}\"><br><br>\r\n\r\n<table cellpadding=\'3\' cellspacing=\'5\' border=\'0\'><tr bgcolor=\"#DDDDDD\"><td><b>Joined:</b> {$poster_join_date}</td></tr><tr><td><b>Online Tag:</b> {$poster_online_tag}</td></tr><tr><td style=\"padding-top:5px\">{$poster_aim} {$poster_msn} {$poster_gtalk}</td></tr></table>\r\n\r\n<br><br>User is <b>{$poster_status}</b></td>\r\n\r\n<td style=\"padding-left:10pxborder-left:1px solid #dddddd\" valign=\"top\">\r\n\r\n<table cellpadding=\'0\' cellspacing=\'0\' border=\'0\' width=\'100%\'><tr><td> {$icon} <b>{$subject}</b></td><td align=\"right\">{$reply} {$forward} {$delete}</td></tr></table><br><br clear=\'all\'>\r\n{$message}\r\n<br><br>\r\n<center><hr color=\"#CCCCCC\" width=\"90%\"></center><br><p>\r\n{$poster_signature}\r\n</p></td></tr></table><br clear=\"all\">', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'message_list', 'main', '<table cellpadding=\'5\' cellspacing=\'0\' border=\'0\' width=\'100%\' align=\'center\' style=\'border: 2px solid #dddddd\'><tr style=\'background:url({$siteurl}inc/images/topbg.jpg) repeat-x;\'><td><b>Private Messages</b> - {$folder}</td><td align=\'right\'>{$send_message}</td></tr>\r\n<tr><td align=\"center\" class=\"light\"><b>{$folder}</b> - {$messages_num} messages with a total of {$max_messages} permitted. ({$messages_percent})<br />{$folder_dropdown}</td></tr></table><br>\r\n\r\n<table cellpadding=\'5\' cellspacing=\'0\' border=\'0\' width=\'100%\' align=\'center\' style=\'border: 2px solid #dddddd\'><tr style=\'background:url({$siteurl}inc/images/topbg.jpg) repeat-x;\'><td align=\'center\'><b>Icon</b></td><td><b>Subject</b></td><td align=\'center\' style=\'padding-right:10px\'><b>Options</b></td></tr>\r\n\r\n{section name=r loop=$messages}\r\n<tr{$class}><td align=\'center\'>{$icon[r]}</td><td>{$subject[r]}<br />From: {$sender[r]} @ {$date[r]}</td><td align=\'center\' style=\'padding-right:10px\'>{$options[r]}</td></tr>\r\n{/section}\r\n\r\n</table>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'message_send', 'main', '{$form_start}\r\n<table cellpadding=\'5\' cellspacing=\'2\' border=\'0\' width=\'90%\' align=\'left\' style=\'border: 2px solid #dddddd\'><tr><td><font size=\"4\"><b>Send Message</b></font></td><td> </td></tr>\r\n<tr class=\'light\'><td><b>Recipient(s)</b></td><td>{$receivers_input}</td></tr>\r\n<tr class=\'dark\'><td><b>Subject</b></td><td>{$subject_input}</td></tr>\r\n<tr class=\'light\'><td><b>Message</b></td><td>{$message_input}</td></tr>\r\n<tr class=\'dark\'><td><b>Captcha</b></td><td>{$captcha_input}</td></tr>\r\n<tr class=\'light\'><td></td><td>{$submit}</td></tr></table>\r\n{$form_end}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'social_header', 'main', '<table width=\'95%\' cellpadding=\'2\' cellspacing=\'0\' style=\'padding-left:10px\'><tr><td valign=\"top\">\r\n\r\n<table width=\'100%\' cellpadding=\'1\' cellspacing=\'0\'><tr><td width=\"28%\"><img src=\"{$avatar}\" width=\"100\"><br />{$status}<br>@ <i>{$status_time}</i></td><td width=\"72%\"><h2>{$username}</h2> {$status_update}\r\n\r\n<div id=\'js_menu\'>\r\n	<ul>\r\n	{if $username == $user_name}\r\n	<li><a href=\'{$edit_profile_url}\'>Edit</a></li>\r\n	{/if}\r\n		<li><a href=\'{$profile_url}\'>Profile</a>\r\n		<ul>\r\n				<li><a href=\'{$status_url}\'>Status Page</a></li>\r\n				</ul>	\r\n		</li>\r\n		<li><a href=\'{$friends_url}\'>Friends</a>			\r\n			<ul>\r\n						<li><a href=\'{$friends_url}\'>View all</a></li> \r\n						<li><a href=\'{$friends_url_req}\'>View Requests</a></li>\r\n			</ul>\r\n		</li>\r\n		<li><a href=\'{$blogs_url}\'>Blogs</a>\r\n			<ul>\r\n				<li><a href=\'{$blogs_url_add}\'>Add Blog</a></li>\r\n				<li><a href=\'{$blogs_url}\'>Manage Blogs</a></li>\r\n			</ul>	\r\n		</li>\r\n		<li><a href=\'{$messages_url}\'>Messages</a>\r\n	</ul>\r\n	</div>\r\n\r\n</td></tr></table>\r\n\r\n</td></tr><tr><td>\r\n\r\n<table width=\'100%\' cellpadding=\'2\' cellspacing=\'0\'><tr><td width=\"28%\">menu here</td><td width=\"72%\">', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'social_footer', 'main', '</td>\r\n\r\n</tr></table>\r\n\r\n</td></tr></table>	', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'social_friends', 'main', '{if $i == 0}\r\n<div align=\'center\'><h3>Friends List</h3></div>\r\n\r\n<table cellpadding=\'5\' cellspacing=\'2\' border=\'0\' width=\'90%\'>\r\n{/if}\r\n\r\n<tr><td>{$friend_username}</td><td>{$friend_last_login}</td><td>{$friend_status}</td></tr>\r\n\r\n{if $i == 0}\r\n</table><br />\r\n{/if}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'social_blogs_view', 'main', '<title>{$sitename} - {$title} Blog ({$username})</title>\r\n	<h2>{$link}</h2>\r\n	<span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span><br />\r\n	<span class=\"what\">On:</span> {$date}<br /><br />\r\n	{$blog}<br />\r\n\r\n{$comments_form}<br />\r\n\r\n<b>Comments</b><br />\r\n<div id=\"comments\">\r\n{section name=r loop=$comments}\r\n<table class=\"newstxt\" cellpadding=\"5\" cellspacing=\"2\" border=\"0\" style=\"border: 2px solid #868585\" width=\"100%\"><tr><td bgcolor=\"#868585\"> {$comments_username[r]}, {$comments_date[r]}</td></tr><tr><td>{$comments_comment[r]}</td></tr><tr><td bgcolor=\"#868585\"><b>Rating:</b> {$comments_rating[r]}, <b>Rate Comment:</b> {$comments_rating_form[r]}</td></tr></table><br />\r\n{/section}\r\n</div>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'social_blogs_list', 'main', '<h2>{$link}</h2>\r\n<span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span><br />\r\n								<span class=\"what\">On:</span> {$date}<br /><br />\r\n{$blog}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'social_blogs_add', 'main', '<title>{$sitename} - Blog</title><br />\r\n{$form_start}<table cellpadding=\'5\' cellspacing=\'5\' width=\'100%\'><tr><td><p>Title</p>{$title_input}</td></tr><tr><td><p>Blog</p>{$blog_input}</td></tr><tr><td><p>Captcha</p>{$captcha}</td></tr><tr><td>{$submit_button}</td></tr></table></form>', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'search', 'main', '<h2>{$link}</h2>\r\n<span class=\"what\">Posted By:</span> <span class=\"bold\">{$username}</span><br />\r\n	<span class=\"what\">On:</span> {$date}<br /><br />\r\n	{$story}', '".time()."');
-- --------------------------------------------------------
INSERT INTO ".$pre."77skins VALUES(null, 'admin_login', 'main', '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>AdaptCMS {$adaptcms_version} - {$acp_page}</title>
<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\" />
<link rel=\'stylesheet\' href=\'inc/js/menu.css\'>
<script type=\'text/javascript\' src=\'inc/js/menu.js\'></script>
<script type=\'text/javascript\' src=\'inc/js/sortmenu.js\'></script>

</head>
<body>

	<!--[if !IE]>main<![endif]-->
    	<div id=\"main\">
        
        	<!--[if !IE]>header-area<![endif]-->
            	
            	<div id=\"header-area\">
                    
                    <div id=\"search-bar\"><form action=\"admin.php?view=search\" method=\"get\">
                			
                           
                            
                            
                            </form>
                		</div>

                    	<!--[if !IE]>header<![endif]-->
            	
            				<div id=\"header\">
                				
                                
                                <!--[if !IE]>logo<![endif]-->
                                	
                                    <div id=\"logo\">
                                    	
                                        <a href=\"{$siteurl}\"><img src=\"images/logo.jpg\" width=\"263\" height=\"46\" alt=\"company name\" border=\"0\" align=\"middle\"/></a>
                                    
                                    </div>
                                <!--[if !IE]>logo<![endif]-->
                                
                                
                                	 
                                     <!--[if !IE]>right-header<![endif]-->
                                	
                                    	<div id=\"right-header\">
                                		<a href=\"http://www.adaptcms.com\"><img src=\"images/banner3.jpg\" width=\"464\" height=\"72\" alt=\"banner\" align=\"right\" border=\"0\"/></a>
      
                                		</div>
                                    
                                	 <!--[if !IE]>right-header<![endif]-->
                                
                			</div>
                
            			<!--[if !IE]>header<![endif]-->
                        
                        
                        	
                            <!--[if !IE]>navigation<![endif]-->
                        	
                            	<div id=\"navigation\">
                                
                                	<!--[if !IE]>button<![endif]-->
                        	
                            		
                                        
                                     <!--[if !IE]>button<![endif]-->
                                     
                                     
                                     	<!--[if !IE]>icon<![endif]-->
                        	
                            				<div id=\"icon\">
                                            	
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
                        	
             <div id=\"bg-pattern\">
             
             	<!--[if !IE]>content-part<![endif]-->
                	
                    <div id=\"content-part\">
                    
     
                
                	<!--[if !IE]>sidebar<![endif]-->
                	
                    	
                       
                    <!--[if !IE]>sidebar<![endif]-->
                    
                    	
                        
                        <!--[if !IE]>right<![endif]-->
                        	<div id=\"right\">
                                                        
                            	<!--[if !IE]>directory-content<![endif]-->
                        				
                                        <div id=\"directory-content\">
                                        
                                       <h3> Admin / <span class=\"add-content\">Login</span>
                                       <br />                
</h3>
                                        
                                        </div>
                                <!--[if !IE]>directory-content<![endif]-->
                                
                                
                                
                                	
                        				
                                        <div id=\"form\">

					<form action=\'admin.php?view=login&act=login\' method=\'post\'>
					<table cellpadding=\'5\' cellspacing=\'2\' border=\'0\' width=\'100%\' align=\'center\'><tr><td><p>Username</p><input type=\'text\' name=\'username\' class=\'addtitle\' size=\'16\' value=\'{$username}\'></td></tr><tr><td><p><span class=\'drop\'>Password</span></p><input type=\'password\' name=\'password\' class=\'addtitle\' size=\'16\'></td></tr><tr><td><p>Captcha</p>{$captcha}</td></tr><tr><td><br /><input type=\'submit\' value=\'Login\' class=\'addContent-button\'></td></tr></table></form>', '".time()."');";
}
// end sql data

if ($_GET['do'] == 2) {
require_once("inc/dbinfo.php");

if ($_POST['email']) {
$msg = "<html><head>
<title>Welcome to AdaptCMS ".$version."</title>
</head>
<body>
<div align='left'><a href='http://www.adaptcms.com'><img src='http://www.insanevisions.com/adaptcms-logo.png'></a></div><br /><br />
<h2>AdaptCMS @ ".$_POST['sitename']."</h2>

<p>Thank you for choosing AdaptCMS as your CMS. We hope you enjoy AdaptCMS and have fun in your experience. I won't bother you with any paragraphs on this product because you'll be using it shortly and I'm sure you've read up, however I would like to link you to a few useful resources to help you with your experience of AdaptCMS as well as just a few tips.</p>

<ul>
<li>First make sure to use the support function! If you have any questions or issues, I strongly urge you to use this feature as no question will be ignored and will be responded to very quickly. Best of all it is right inside your admin panel, head to 'yoursite.com/admin.php' and login then look for the link 'Support' up top. There you can see other support questions as well as submit your own.</li>
<li>File Releases. Right from the main admin panel, from there you can get the very latest files and sometimes will have bugs fixed.</li>
<li><a href='http://insanevisions.com/forums/23/AdaptCMS-20'>Insane Visions Forums</a> - There you can discuss AdaptCMS with others or even ask for help there if you wish, make suggestions, etc.</li>
<li><a href='http://www.insanevisions.com'>Insane Visions</a> - You can get the latest AdaptCMS news as well as blogs on upcoming features, pictures and the latest altogether related to the software</li>
</ul>

<p>Thank you and once again, enjoy AdaptCMS!</p>

<p>Sincerely,<br /><br />

Charlie Page<br />
Webmaster/Owner<br />
Insane Visions - www.insanevisions.com<br /><br />

Lead Programmer<br />
AdaptCMS - www.adaptcms.com</p>
</body>
</html>";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($_POST['email'], "Welcome to AdaptCMS ".$version, $msg, $headers);
}

echo file_get_contents("http://www.insanevisions.com/share/adaptcms2/check.php?sitename=".urlencode($_POST['sitename'])."&siteurl=".urlencode($siteurl)."&version=".urlencode($version)."&domain=".urlencode($domain));

echo $install_temp[0];

echo "<table cellpadding='5' cellspacing='0' border='0' width='100%' align='center'>";

//echo "<tr><td><p>Site Options</p></td><td>Not in yet, need more brainstorming</td></tr>";

echo "<tr><td><p>Welcome!</p></td><td>&nbsp;</td></tr><tr><td><a href='admin.php'>Admin CP</a></td><td><a href='index.php'>Website</a></td></tr></table>";

echo $install_temp[1];
}

if ($_GET['do'] == 1 && $_POST['dbhost'] && $_POST['dbuser'] && $_POST['dbname'] && $_POST['dbpass']) {
require_once("inc/dbinfo.php");

echo $install_temp[0];

echo "<table cellpadding='5' cellspacing='0' border='0' width='100%' align='center'><tr><td><p>MySQL Connection</p></td><td>";

if (@mysql_select_db($_POST['dbname'], @mysql_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'])) == TRUE) {
$new_salt = generateSalt();
$hi = file(getcwd()."/inc/dbinfo.php");
foreach ($hi as $line_num => $line) {
$code .= stripslashes($line);
}

if ($dbhost == "cms_dbhost") {
$pab[0] = "'cms_dbhost'";
$pab[1] = "'cms_dbuser'";
$pab[2] = "'cms_dbname'";
$pab[3] = "'cms_dbpass'";
$pab[4] = "'adaptcms_'";
$pab[5] = "'salt'";
} else {
$pab[0] = "'$dbhost'";
$pab[1] = "'$dbuser'";
$pab[2] = "'$dbname'";
$pab[3] = "'$dbpass'";
$pab[4] = "'$pre'";
$pab[5] = "'$new_salt'";
}

$rab[0] = "'".htmlentities($_POST['dbhost'])."'";
$rab[1] = "'".htmlentities($_POST['dbuser'])."'";
$rab[2] = "'".htmlentities($_POST['dbname'])."'";
$rab[3] = "'".htmlentities($_POST['dbpass'])."'";
$rab[4] = "'".htmlentities($_POST['pre'])."'";
$rab[5] = "'".$new_salt."'";

$code2 = str_replace($pab, $rab, $code);

$fp = fopen(getcwd()."/inc/dbinfo.php", "w");
fwrite($fp,stripslashes($code2));
fclose($fp);
require("inc/dbinfo.php");

echo "<font color='blue'>YES</font></td></tr>";

echo "<form action='install.php?do=install' method='post'><tr><td>&nbsp;</td><td><h2>Admin Account</h2></td></tr><tr><td><p>Username</p></td><td><input type='text' name='username' class='title'></td></tr><tr><td><p><span class='drop'>Password</span></p></td><td><input type='text' name='password' class='title'></td></tr><tr><td><p>E-Mail</p></td><td><input type='text' name='email' class='title'></td></tr>

<tr><td>&nbsp;</td><td><h2>Website</h2></td></tr><tr><td><p>Site <span class='drop'>Name</span></p></td><td><input type='text' name='sitename' class='title' value='AdaptCMS Website'></td></tr>

<tr><td><br /><input type='submit' name='submit' value='Install' class='addContent-button'>";
if (@mysql_num_rows(@mysql_query("SELECT * FROM ".$pre."users")) > 0) {
echo "&nbsp;&nbsp;<input type='submit' name='submit' value='Upgrade - 1.5' class='addContent-button'>&nbsp;&nbsp;<input type='submit' name='submit' value='Upgrade - 2.0' class='addContent-button'>";
}
echo "</td><td>&nbsp;</td></tr></table></form>";
} else {
echo "<font color='red'>NO</font></td></tr></table>";
}
echo $install_temp[1];
}


if ($_GET['do'] == "install") {
echo $install_temp[0];

echo "<table cellpadding='5' cellspacing='0' border='0' width='100%' align='center'>";
if ($_POST['submit'] == "Install") {
require("inc/dbinfo.php");
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

$content_ht = "RewriteEngine On
RewriteBase ".str_replace($apage, "", $_SERVER['PHP_SELF'])."
#RewriteCond %{HTTP_HOST} !^www\..* [NC]
#RewriteCond %{HTTP_HOST} !^[0-9]+\.[0-9]+\..+ [NC]
#RewriteRule ^([^/]+) http://www.%{HTTP_HOST}/$1 [R=301,L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

ErrorDocument 404 ".str_replace($apage, "", $_SERVER['PHP_SELF'])."index.php?view=error&type=404

# Mod_Rewrite for AdaptCMS 2.0+

RewriteRule ^profile/?$ index.php?view=social
RewriteRule ^profile/edit/?$ index.php?view=social&do=edit
RewriteRule ^profile/edit2/?$ index.php?view=social&do=edit2
RewriteRule ^pages/?$ index.php?view=pages
RewriteRule ^rss/?$ index.php?view=rss

RewriteRule ^article/([0-9]+)/([^/]+)/?$ index.php?view=content&id=$1
RewriteRule ^article/([0-9]+)/([^/]+)/([^/]+)/?$ index.php?view=content&id=$1
RewriteRule ^content/([0-9]+)/([^/]+)/?$ index.php?view=content&id=$1
RewriteRule ^content/([^/]+)/([0-9]+)/([^/]+)/?$ index.php?view=content&id=$2
RewriteRule ^file/([0-9]+)/([^/]+)/?$ index.php?view=media&do=file&id=$1
RewriteRule ^media/([0-9]+)/([^/]+)/?$ index.php?view=media&do=gallery&id=$1
RewriteRule ^page/([0-9]+)/([^/]+)/?$ index.php?view=pages&id=$1
RewriteRule ^poll/([0-9]+)/?$ index.php?view=polls&id=$1
RewriteRule ^section/([^/]+)/?$ index.php?view=section&section=$1
RewriteRule ^tag/([^/]+)/?$ index.php?view=search&q=$1
RewriteRule ^profile/status/([0-9]+)/?$ index.php?view=social&do=status&id=$1
RewriteRule ^profile/blogs/add/?$ index.php?view=social&do=blogs&go=add
RewriteRule ^profile/blogs/([0-9]+)/([^/]+)/?$ index.php?view=social&do=blogs&id=$1
RewriteRule ^profile/([^/]+)/?$ index.php?view=social&username=$1
RewriteRule ^profile/([^/]+)/blogs/?$ index.php?view=social&do=blogs&username=$1
RewriteRule ^profile/([^/]+)/friends/?$ index.php?view=social&do=friends&username=$1
RewriteRule ^profile/([^/]+)/status/?$ index.php?view=social&do=status&username=$1

RewriteRule ^rss/([^/]+)/([^/]+)-([^/]+)/?$ index.php?view=rss&section=$1&field=$2&data=$3
RewriteRule ^rss/([^/]+)-([^/]+)/?$ index.php?view=rss&field=$1&data=$2
RewriteRule ^rss/([^/]+)/?$ index.php?view=rss&section=$1

RewriteRule ^friends/?$ index.php?view=social&do=friends
RewriteRule ^gallery/?$ index.php?view=media
RewriteRule ^login/?$ index.php?view=login
RewriteRule ^logout/?$ index.php?view=logout
RewriteRule ^media/?$ index.php?view=media
RewriteRule ^messages/?$ index.php?view=social&do=messages
RewriteRule ^poll-results/?$ index.php?results=yes
RewriteRule ^polls/?$ index.php?view=polls
RewriteRule ^register/?$ index.php?view=register
RewriteRule ^search/?$ index.php?view=search

RewriteRule ^archive/?$ index.php?view=content";

$handle = fopen($sitepath.".htaccess", 'w');
fwrite($handle, $content_ht);
fclose($handle);

$ex = explode("-- --------------------------------------------------------", $sqldata["tables"]);
while (list($k, $i) = each ($ex)) {
if ($k) {
unset($l, $n, $c);
$count1 = 0;
$count2 = 0;
echo "<tr><td><p>SQL Table <span class='drop'>".$pre.$tables[$k]."</span></p></td><td>";
if (mysql_query($i) == TRUE) {
echo "<font color='blue'>SUCCESS</font></td></tr><tr><td><p>SQL Queries <span class='drop'>".$pre.$tables[$k]."</span></p></td><td> ";
$l = $tables[$k];
$ex2 = explode("-- --------------------------------------------------------", $sqldata[$l]);
while (list($c, $n) = each ($ex2)) {
if ($n) {
if (mysql_query($n) == TRUE) {
$count1++;
} else {
$count2++;
}
}
}
echo $count1." <font color='blue'>successful</font>, ".$count2." <font color='red'>failed</font></td></tr>";
} else {
echo "<font color='red'>FAIL</font></td></tr>";
die;
}
}
}

// plugin install, NOT SQL QUERIES

$sqls = mysql_query("SELECT * FROM ".$pre."skins WHERE skin = '' ORDER BY `date` DESC");
while($row = mysql_fetch_array($sqls)) {
$query5 = mysql_query("INSERT INTO ".$pre."skins VALUES (null, 'plugin_affiliates', '".$row[name]."', '{$affiliate}<br>', '".time()."')");

$fh = fopen($sitepath."templates/".$row[name]."/plugin_affiliates.tpl", 'w') or die("can't open file");
fwrite($fh, '{$affiliate}<br>');
fclose($fh);
}


// END

} elseif ($_POST['submit'] == "Upgrade - 1.5") {

$content_ht = "RewriteEngine On
RewriteBase ".str_replace($apage, "", $_SERVER['PHP_SELF'])."
#RewriteCond %{HTTP_HOST} !^www\..* [NC]
#RewriteCond %{HTTP_HOST} !^[0-9]+\.[0-9]+\..+ [NC]
#RewriteRule ^([^/]+) http://www.%{HTTP_HOST}/$1 [R=301,L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

ErrorDocument 404 ".str_replace($apage, "", $_SERVER['PHP_SELF'])."index.php?view=error&type=404

# Mod_Rewrite for AdaptCMS 2.0+

RewriteRule ^profile/?$ index.php?view=social
RewriteRule ^profile/edit/?$ index.php?view=social&do=edit
RewriteRule ^profile/edit2/?$ index.php?view=social&do=edit2
RewriteRule ^pages/?$ index.php?view=pages
RewriteRule ^rss/?$ index.php?view=rss

RewriteRule ^article/([0-9]+)/([^/]+)/?$ index.php?view=content&id=$1
RewriteRule ^article/([0-9]+)/([^/]+)/([^/]+)/?$ index.php?view=content&id=$1
RewriteRule ^content/([0-9]+)/([^/]+)/?$ index.php?view=content&id=$1
RewriteRule ^content/([^/]+)/([0-9]+)/([^/]+)/?$ index.php?view=content&id=$2
RewriteRule ^file/([0-9]+)/([^/]+)/?$ index.php?view=media&do=file&id=$1
RewriteRule ^media/([0-9]+)/([^/]+)/?$ index.php?view=media&do=gallery&id=$1
RewriteRule ^page/([0-9]+)/([^/]+)/?$ index.php?view=pages&id=$1
RewriteRule ^poll/([0-9]+)/?$ index.php?view=polls&id=$1
RewriteRule ^section/([^/]+)/?$ index.php?view=section&section=$1
RewriteRule ^tag/([^/]+)/?$ index.php?view=search&q=$1
RewriteRule ^profile/status/([0-9]+)/?$ index.php?view=social&do=status&id=$1
RewriteRule ^profile/blogs/add/?$ index.php?view=social&do=blogs&go=add
RewriteRule ^profile/blogs/([0-9]+)/([^/]+)/?$ index.php?view=social&do=blogs&id=$1
RewriteRule ^profile/([^/]+)/?$ index.php?view=social&username=$1
RewriteRule ^profile/([^/]+)/blogs/?$ index.php?view=social&do=blogs&username=$1
RewriteRule ^profile/([^/]+)/friends/?$ index.php?view=social&do=friends&username=$1
RewriteRule ^profile/([^/]+)/status/?$ index.php?view=social&do=status&username=$1

RewriteRule ^rss/([^/]+)/([^/]+)-([^/]+)/?$ index.php?view=rss&section=$1&field=$2&data=$3
RewriteRule ^rss/([^/]+)-([^/]+)/?$ index.php?view=rss&field=$1&data=$2
RewriteRule ^rss/([^/]+)/?$ index.php?view=rss&section=$1

RewriteRule ^friends/?$ index.php?view=social&do=friends
RewriteRule ^gallery/?$ index.php?view=media
RewriteRule ^login/?$ index.php?view=login
RewriteRule ^logout/?$ index.php?view=logout
RewriteRule ^media/?$ index.php?view=media
RewriteRule ^messages/?$ index.php?view=social&do=messages
RewriteRule ^poll-results/?$ index.php?results=yes
RewriteRule ^polls/?$ index.php?view=polls
RewriteRule ^register/?$ index.php?view=register
RewriteRule ^search/?$ index.php?view=search

RewriteRule ^archive/?$ index.php?view=content";

$handle = fopen($sitepath.".htaccess", 'w');
fwrite($handle, $content_ht);
fclose($handle);

$ex = explode("-- --------------------------------------------------------", str_replace("CREATE TABLE IF NOT EXISTS ".$pre, "CREATE TABLE IF NOT EXISTS ".$pre."77", $sqldata["tables"]));
while (list($k, $i) = each ($ex)) {
if ($k && $tables[$k] != "plugins") {
unset($l, $n, $c);
$count1 = 0;
$count2 = 0;
echo "<tr><td><p>SQL Table <span class='drop'>".$pre.$tables[$k]."</span>";
if ($tables[$k] == "levels" OR $tables[$k] == "messages") {
echo " Created";
} else {
echo " Updated";
}
echo "</p></td><td>";
if (mysql_query($i) == TRUE) {
echo "<font color='blue'>SUCCESS</font></td></tr>";
}

}
}
$ex2 = explode("-- --------------------------------------------------------", $sqldata["1.5"]);
while (list($c, $n) = each ($ex2)) {
if ($n) {
mysql_query($n);
}
}

while (list($a, $b) = each ($tables)) {
if ($b && $b != "plugins") {
mysql_query("DROP TABLE `".$pre.$b."`");
mysql_query("RENAME TABLE `".$pre."77".$b."` TO `".$pre.$b."`");
}
}

mysql_query("DROP TABLE `".$pre."articles`, `".$pre."gallery`, `".$pre."templates`, `".$pre."fielddata`, `".$pre."ranks`, `".$pre."relations`");

} elseif ($_POST['submit'] == "Upgrade - 2.0") {
$content_ht = "RewriteEngine On
RewriteBase ".str_replace($apage, "", $_SERVER['PHP_SELF'])."
#RewriteCond %{HTTP_HOST} !^www\..* [NC]
#RewriteCond %{HTTP_HOST} !^[0-9]+\.[0-9]+\..+ [NC]
#RewriteRule ^([^/]+) http://www.%{HTTP_HOST}/$1 [R=301,L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

ErrorDocument 404 ".str_replace($apage, "", $_SERVER['PHP_SELF'])."index.php?view=error&type=404

# Mod_Rewrite for AdaptCMS 2.0+

RewriteRule ^profile/?$ index.php?view=social
RewriteRule ^profile/edit/?$ index.php?view=social&do=edit
RewriteRule ^profile/edit2/?$ index.php?view=social&do=edit2
RewriteRule ^pages/?$ index.php?view=pages
RewriteRule ^rss/?$ index.php?view=rss

RewriteRule ^article/([0-9]+)/([^/]+)/?$ index.php?view=content&id=$1
RewriteRule ^article/([0-9]+)/([^/]+)/([^/]+)/?$ index.php?view=content&id=$1
RewriteRule ^content/([0-9]+)/([^/]+)/?$ index.php?view=content&id=$1
RewriteRule ^content/([^/]+)/([0-9]+)/([^/]+)/?$ index.php?view=content&id=$2
RewriteRule ^file/([0-9]+)/([^/]+)/?$ index.php?view=media&do=file&id=$1
RewriteRule ^media/([0-9]+)/([^/]+)/?$ index.php?view=media&do=gallery&id=$1
RewriteRule ^page/([0-9]+)/([^/]+)/?$ index.php?view=pages&id=$1
RewriteRule ^poll/([0-9]+)/?$ index.php?view=polls&id=$1
RewriteRule ^section/([^/]+)/?$ index.php?view=section&section=$1
RewriteRule ^tag/([^/]+)/?$ index.php?view=search&q=$1
RewriteRule ^profile/status/([0-9]+)/?$ index.php?view=social&do=status&id=$1
RewriteRule ^profile/blogs/add/?$ index.php?view=social&do=blogs&go=add
RewriteRule ^profile/blogs/([0-9]+)/([^/]+)/?$ index.php?view=social&do=blogs&id=$1
RewriteRule ^profile/([^/]+)/?$ index.php?view=social&username=$1
RewriteRule ^profile/([^/]+)/blogs/?$ index.php?view=social&do=blogs&username=$1
RewriteRule ^profile/([^/]+)/friends/?$ index.php?view=social&do=friends&username=$1
RewriteRule ^profile/([^/]+)/status/?$ index.php?view=social&do=status&username=$1

RewriteRule ^rss/([^/]+)/([^/]+)-([^/]+)/?$ index.php?view=rss&section=$1&field=$2&data=$3
RewriteRule ^rss/([^/]+)-([^/]+)/?$ index.php?view=rss&field=$1&data=$2
RewriteRule ^rss/([^/]+)/?$ index.php?view=rss&section=$1

RewriteRule ^friends/?$ index.php?view=social&do=friends
RewriteRule ^gallery/?$ index.php?view=media
RewriteRule ^login/?$ index.php?view=login
RewriteRule ^logout/?$ index.php?view=logout
RewriteRule ^media/?$ index.php?view=media
RewriteRule ^messages/?$ index.php?view=social&do=messages
RewriteRule ^poll-results/?$ index.php?results=yes
RewriteRule ^polls/?$ index.php?view=polls
RewriteRule ^register/?$ index.php?view=register
RewriteRule ^search/?$ index.php?view=search

RewriteRule ^archive/?$ index.php?view=content";

$handle = fopen($sitepath.".htaccess", 'w');
fwrite($handle, $content_ht);
fclose($handle);

$ex2 = explode("-- --------------------------------------------------------", $sqldata["2.0"]);
while (list($c, $n) = each ($ex2)) {
if ($n) {
if (mysql_query($n) == TRUE) {
echo "MySQL Query #".$c." Successful<br />";
}
}
}

}
echo "</table><br /><form action='".str_replace($apage, "", $_SERVER['PHP_SELF'])."install.php?do=2' method='post'><input type='hidden' name='email' value='".$_POST['email']."'><input type='hidden' name='sitename' value='".$_POST['sitename']."'><input type='submit' value='Last Step' class='addContent-button'></form>";
echo $install_temp[1];
}

if (!$_GET['do']) {
require("inc/dbinfo.php");

echo $install_temp[0];

if ($dbhost != "cms_dbhost") {
$form[0] = " value='".$dbhost."'";
}
if ($dbname != "cms_dbname") {
$form[1] = " value='".$dbname."'";
}
if ($dbuser != "cms_dbuser") {
$form[2] = " value='".$dbuser."'";
}
if ($dbpass != "cms_dbpass") {
$form[3] = " value='".$dbpass."'";
}

echo "<table cellpadding='5' cellspacing='0' border='0' width='100%' align='center'><tr><td>&nbsp;</td><td><h2>Server Check</h2></td></tr><tr><td><p>PHP >= 4.3</p></td><td>";
if (@phpversion() >= "4.3") {
echo "<font color='blue'>YES</font>";
$var .= "Step2=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step2=false,";
}

echo "</td></tr><tr><td><p>MySQL Enabled</p></td><td>";
if (@function_exists("mysql_connect")) {
echo "<font color='blue'>YES</font>";
$var .= "Step3=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step3=false,";
}

echo "</td></tr><tr><td><p>GD Enabled</p></td><td>";
if (@function_exists("imagecreatefromjpeg")) {
echo "<font color='blue'>YES</font>";
$var .= "Step4=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step4=false,";
}

echo "</td></tr><tr><td><p>Magic Quotes GPC</p></td><td>";
if (@get_magic_quotes_gpc()) {
echo "<font color='blue'>YES</font>";
$var .= "=true,";
$var2 = $var2 + 1;
} else {
echo "<font color='red'>NO</font>";
$var .= "Step5=false,";
}
$ex = @explode(",", $var);

echo "</td></tr>";

if ($ex[1] == "Step2=false") {
echo "Your PHP is only version <b>".@phpversion()."</b> which is out of date. If you are running anything below 4.3 you may have many problems running AdaptCMS<br><br>";
}

if ($ex[2] == "Step3=false") {
echo "Sorry but MySQL appears to not be enabled on your server, you cannot use AdaptCMS without MySQL. Please ask your host to add MySQL<br><br>";
die;
}

if ($ex[3] == "Step4=false") {
echo "GD is not enabled but dont fret, you can use AdaptCMS except the thumbnailer<br><br>";
}

if ($ex[4] == "Step5=false") {
echo "Magic Quotes GPC is not enabled but it is not a problem, infact it does not matter if it is on or off<br><br>";
}

echo "<form action='install.php?do=1' method='post'><tr><td>&nbsp;</td><td><h2>MySQL Information</h2></td></tr>";

echo "<tr><td><p>Table <span class='drop'>Prefix</span></p></td><td><input type='text' name='pre' value='".$pre."' class='title'></td></tr><tr><td><p>Database <span class='drop'>Host</span></p></td><td><input type='text' name='dbhost'".$form[0]." value='localhost' class='title'></td></tr><tr><td><p>Database <span class='drop'>Name</span></p></td><td><input type='text' name='dbname'".$form[1]." class='title'></td></tr><tr><td><p>Database <span class='drop'>Username</p></span></td><td><input type='text' name='dbuser'".$form[2]." class='title'></td></tr><tr><td><p>Database <span class='drop'>Password</span></p></td><td><input type='text' name='dbpass'".$form[3]." class='title'></td></tr>

<tr><td><br /><input type='submit' value='Continue' class='addContent-button'></td><td>&nbsp;</td></tr></table></form>";

echo $install_temp[1];
}
?>