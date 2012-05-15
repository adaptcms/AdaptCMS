<?php
$smarty->display($skin.'/admin_header.tpl');

if ($_GET['do'] == "") {
echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Name</b></td><td><b>Templates</b></td><td><b>Last Modified</b></td><td><b>Actions</b></td></tr>";

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."skins WHERE template = 'yes|' AND skin = '' OR template = 'yes|yes' AND skin = '' OR template = '|' AND skin = '' OR template = '|yes' AND skin = '' ORDER BY `date` DESC");
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td>".ucwords($r[name])."</td><td>";
if ($p[1]) {
echo "<select name='skin' onChange=\"jump('parent',this,0)\" class='input'><option value='' selected>- Select a Template -</option>";
$sql2 = mysql_query("SELECT * FROM ".$pre."skins WHERE skin = '".$r[name]."' ORDER BY `name` ASC");
while($row = mysql_fetch_array($sql2)) {
if ($row[name] != $r[name]) {
echo "<option value='admin.php?view=skins&do=edit_template&id=".$row[id]."'>".ucwords($row[name])."</option>";
}
}
echo "</select>";
}
echo "</td><td>".date($setting["date_format"], $r[date])."</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=skins&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=skins&do=delete&id=".$r[id]."&sname=".urlencode($r[name])."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</table><br clear='all'>";

echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'>
<colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
		<col id='col1_4'></col>
		<col id='col1_5'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Name</b></td><td><b>Skin</b></td><td><b>Last Modified</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$var1 = "template != '|' AND template != 'yes|' AND template != '|yes' AND template != 'yes|yes'";
$sql3 = mysql_query("SELECT * FROM ".$pre."skins WHERE ".$var1." ORDER BY `date` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql3)) {
if ($r[template] != "skin") {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td>".ucwords($r[name])."</td><td>";
$skinid = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."skins WHERE name = '".$r[skin]."' AND skin = ''"));
if ($skinid[0]) {
if ($p[1]) {
echo "<a href='admin.php?view=skins&do=edit&id=".$skinid[0]."'>";
}
echo $r[skin];
if ($p[1]) {
echo "</a>";
}
} else {
echo "N/A";
}
echo "</td><td>".date($setting["date_format"], $r[date])."</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=skins&do=edit_template&id=".$r[id]."'><img src='images/edit.png' border='0'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=skins&do=delete&id=".$r[id]."&name=".urlencode($r[name])."&skin=".urlencode($r[skin])."' onclick='return confirmDelete();'><img src='images/delete.png' border='0'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','N', 'N', 'S'));
</script><br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."skins WHERE ".$var1),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=skins&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=skins&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=skins&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "add_template") {
echo "<script language='javascript' type='text/javascript' src='inc/js/edit_area/edit_area_full.js'></script>
<script language='Javascript' type='text/javascript'>
		editAreaLoader.init({
			id: 'template'	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: 'both'
			,allow_toggle: true
			,toolbar: 'search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, highlight, reset_highlight, |, help'
			,syntax_selection_allow: 'css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas'
			,word_wrap: true
			,language: 'en'
			,syntax: 'html'	
		});
</script>

<form action='admin.php?view=skins&do=add_template2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='left'><tr><td><p>Template <span class='drop'>Name</span></p><input type='text' name='name' size='12' class='addtitle'></td></tr><tr><td><p>Skin</p><select name='skin' class='select'><option value=''></option>";
$sql = mysql_query("SELECT * FROM ".$pre."skins WHERE skin = '' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
$show = "";
if ($r[name] == $skin) {
$show = " selected";
}
echo "<option value='".$r[name]."'".$show.">".ucwords($r[name])."</option>";
}
echo "</select></td></tr><tr><td><p><span class='drop'>Template</span></p><textarea id='template' name='template' style='height: 300px; width: 100%' class='textarea'></textarea></td></tr><tr><td><br /><input type='submit' value='Add Template' class='addContent-button'></td></tr></table></form><br clear='all'>";
}

if ($_GET['do'] == "add_template2") {
$autobr = html_entity_decode($_POST['template']);
$_POST['name'] = strtolower(str_replace(" ", "_", addslashes($_POST["name"])));
if ($_POST['skin']) {
$folder = $_POST['skin']."/";
}

$fh = fopen($sitepath."templates/".$folder.$_POST['name'].".tpl", 'w') or die("can't open file");
fwrite($fh, stripslashes($_POST['template']));
fclose($fh);

if ($fh == TRUE && mysql_query("INSERT INTO ".$pre."skins VALUES (null, '".addslashes($_POST["name"])."', '".addslashes($_POST['skin'])."', '".$autobr."', '".time()."')") == TRUE) {
mysql_query("UPDATE ".$pre."skins SET date = '".time()."' WHERE skin = '".$_POST['skin']."' AND skin = ''");
echo re_direct("1500", "admin.php?view=skins");
echo "The template <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=skins'>Return</a>";
} else {
echo reporterror($siteurl.$cpage, mysql_error(@mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The template could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}


if ($_GET['do'] == "add") {
echo "<form action='admin.php?view=skins&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Skin <span class='drop'>Name</span></p><input type='text' name='name' size='12' class='addtitle'></td></tr><tr><td><p>Default <span class='drop'>Skin</span></p><input type='checkbox' name='default' value='yes'></td></tr><tr><td><p>Private <span class='drop'>Skin</span></p><input type='checkbox' name='private' value='yes'></td></tr><tr><td><br /><input type='submit' value='Add Skin' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "add2") {
$make = @mkdir($sitepath."templates/".$_POST["name"]);
if (mysql_query("INSERT INTO ".$pre."skins VALUES (null, '".addslashes($_POST["name"])."', '', '".$_POST['default']."|".$_POST['private']."', '".time()."')") == TRUE && $make == TRUE) {
$acp_header = '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
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
            	
            			<div id=\"search-bar\"><form action=\"index.php\" method=\"get\">
                			
                           <input class=\"search-button\" type=\"submit\" value=\"Search\"/> <input type=\"hidden\" name=\"view\" value=\"search\">

                            
                            <input class=\"search-box\" type=\"text\" name=\"q\" size=\"15\"  height=\"3\" onblur=\"if (this.value == \'\') {ldelim}this.value = \'Lets Search Here ...\';{rdelim}\" onfocus=\"if (this.value == \'Lets Search Here ...\') {ldelim}this.value = \'\';{rdelim}\" id=\"ls\"  value=\"Lets Search Here ...\"  /> 
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
					<li><a href=\"admin.php?view=social\"> Social </a></li>
           
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
                        				
                                        <div id=\"form\">';
$acp_footer = '</div>
                                        
                                    <!--[if !IE]>form<![endif]-->
                            
                            </div>
                        <!--[if !IE]>right<![endif]-->    
                   
                        
                        
                         </div>
                <!--[if !IE]>content-part<![endif]-->
                
                
                	<!--[if !IE]>footer<![endif]-->
                        	<div id=\"footer\">
                           
                           <ul>
                           <li><a href=\"http://www.adaptcms.com/download\"><img src=\"http://www.adaptcms.com/version.php?version={$adaptcms_version}\" width=\"38\" height=\"32\" alt=\"icon\" border=\"0\" /></a></li>
                           <li>You are running <span class=\"drop\">- AdaptCMS {$adaptcms_version}</span></li>
                           </ul>
                           
                           		<p>Copyright 2006-2010 <a href=\'http://www.insanevisions.com\'>Insane Visions</a><p>
                            
                            </div>
                        <!--[if !IE]>footer<![endif]-->	
             
             </div>
             	
		<!--[if !IE]>bg-pattern<![endif]-->

</body>
</html>';

mysql_query("INSERT INTO ".$pre."skins VALUES (null, 'admin_header', '".addslashes($_POST["name"])."', '".$acp_header."', '".time()."')");
mysql_query("INSERT INTO ".$pre."skins VALUES (null, 'admin_footer', '".addslashes($_POST["name"])."', '".$acp_footer."', '".time()."')");

$fh = fopen($sitepath."templates/".$_POST['name']."/admin_header.tpl", 'w') or die("can't open file");
fwrite($fh, stripslashes($acp_header));
fclose($fh);

$fh2 = fopen($sitepath."templates/".$_POST['name']."/admin_footer.tpl", 'w') or die("can't open file");
fwrite($fh2, stripslashes($acp_footer));
fclose($fh2);

echo re_direct("1500", "admin.php?view=skins");
echo "The skin <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=skins'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The skin could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT name,template FROM ".$pre."skins WHERE id = '".$_GET['id']."'"));
if ($r[1]) {
$ex = explode("|", $r[1]);
}
echo "<form action='admin.php?view=skins&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' value='".$r[0]."' size='12' class='addtitle'><input type='hidden' name='old_name' value='".$r[0]."'></td></tr><tr><td><p>Default <span class='drop'>Skin</span></p><input type='checkbox' name='default' value='yes'";
if ($ex[0]) {
echo " checked";
}
echo "></td></tr><tr><td><p>Private <span class='drop'>Skin</span></p><input type='checkbox' name='private' value='yes'";
if ($ex[1]) {
echo " checked";
}
echo "></td></tr><tr><td><br /><input type='submit' value='Update Skin' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2") {
$query = mysql_query("UPDATE ".$pre."skins SET name = '".addslashes($_POST["name"])."', template = '".$_POST['default']."|".$_POST['private']."', date = '".time()."' WHERE id = '".$_GET['id']."'");
mysql_query("UPDATE ".$pre."skins SET skin = '".$_POST['name']."' WHERE skin = '".$_POST['oldname']."' AND id != '".$_GET['id']."'");
mysql_query("UPDATE ".$pre."users SET skin = '".$_POST['name']."' WHERE skin = '".$_POST['oldname']."'");

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=skins");
echo "The skin <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=skins'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The skin could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($_GET['do'] == "edit_template") {
$r = mysql_fetch_row(mysql_query("SELECT name,template,skin FROM ".$pre."skins WHERE id = '".$_GET['id']."'"));
$handle = @fopen($sitepath."templates/".$r[2]."/".strtolower($r[0]).".tpl", "r");
$contents = @fread($handle, filesize($sitepath."templates/".$r[2]."/".strtolower($r[0]).".tpl"));
@fclose($handle);
if (!$contents) {
$contents = $r[1];
}

echo "<script language='javascript' type='text/javascript' src='inc/js/edit_area/edit_area_full.js'></script>
<script language='Javascript' type='text/javascript'>
		editAreaLoader.init({
			id: 'template'	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: 'both'
			,allow_toggle: true
			,toolbar: 'search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, highlight, reset_highlight, |, help'
			,syntax_selection_allow: 'css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas'
			,word_wrap: true
			,language: 'en'
			,syntax: 'html'	
		});
</script>

<form action='admin.php?view=skins&do=edit_template2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Template <span class='drop'>Name</span></p><input type='text' name='name' value='".$r[0]."' size='12' class='addtitle'></td></tr><tr><td><p>Skin</p><select name='skin' class='select'><option value=''></option>";
$sql = mysql_query("SELECT * FROM ".$pre."skins WHERE skin = '' ORDER BY `name` ASC");
while($row = mysql_fetch_array($sql)) {
if ($row[name] == $r[2]) {
echo "<option value='".$row[name]."' selected>- ".ucwords($row[name])." -</option>";
} else {
echo "<option value='".$row[name]."'>".ucwords($row[name])."</option>";
}
}
echo "</select></td></tr><tr><td><p><span class='drop'>Template</span></p><textarea id='template' name='template' style='height: 300px; width: 100%' class='textarea'>".htmlentities(stripslashes($contents))."</textarea></td></tr><tr><td><br /><input type='submit' value='Update' class='addContent-button'></td></tr></table></form><br clear='all'>";
}

if ($_GET['do'] == "edit_template2") {
$autobr = html_entity_decode($_POST['template']);

$_POST['name'] = strtolower(str_replace(" ", "_", addslashes($_POST["name"])));
if ($_POST['skin']) {
$folder = $_POST['skin']."/";
}

$fh = fopen($sitepath."templates/".$folder.$_POST['name'].".tpl", 'w') or die("can't open file");
fwrite($fh, stripslashes($_POST['template']));
fclose($fh);

if ($fh == TRUE && mysql_query("UPDATE ".$pre."skins SET template = '".addslashes($autobr)."', name = '".addslashes($_POST['name'])."', skin = '".addslashes($_POST['skin'])."', date = '".time()."' WHERE id = '".$_GET['id']."'") == TRUE) {

if ($handle = @opendir("inc/smarty/templates_c/")) {
while (false !== ($file = @readdir($handle))) {
if (stristr($file, ".".$_POST['name'].".tpl.php")) {
unlink($sitepath."inc/smarty/templates_c/".$file);
}
}
}

mysql_query("UPDATE ".$pre."skins SET date = '".time()."' WHERE skin = '".$_POST['skin']."' AND skin = ''");
echo re_direct("1500", "admin.php?view=skins");
echo "The template <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=skins'>Return</a>";
} else {
echo reporterror($siteurl.$cpage, mysql_error(@mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The template could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

}

if ($p[2]) {
if ($_GET['do'] == "delete") {
if ($_GET['name'] == "admin_header") {
$newtemp = '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
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
            	
            			<div id=\"search-bar\"><form action=\"index.php\" method=\"get\">
                			
                           <input class=\"search-button\" type=\"submit\" value=\"Search\"/> <input type=\"hidden\" name=\"view\" value=\"search\">
                            
                            <input class=\"search-box\" type=\"text\" name=\"q\" size=\"15\"  height=\"3\" onblur=\"if (this.value == \'\') {ldelim}this.value = \'Lets Search Here ...\';{rdelim}\" onfocus=\"if (this.value == \'Lets Search Here ...\') {ldelim}this.value = \'\';{rdelim}\" id=\"ls\"  value=\"Lets Search Here ...\"  /> 
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
					<li><a href=\"admin.php?view=social\"> Social </a></li>
           
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
                        				
                                        <div id=\"form\">';
} elseif ($_GET['name'] == "admin_footer") {
$newtemp = '</div>
                                        
                                    <!--[if !IE]>form<![endif]-->
                            
                            </div>
                        <!--[if !IE]>right<![endif]-->    
                   
                        
                        
                         </div>
                <!--[if !IE]>content-part<![endif]-->
                
                
                	<!--[if !IE]>footer<![endif]-->
                        	<div id=\"footer\">
                           
                           <ul>
                           <li><a href=\"http://www.adaptcms.com/download\"><img src=\"http://www.adaptcms.com/version.php?version={$adaptcms_version}\" width=\"38\" height=\"32\" alt=\"icon\" border=\"0\" /></a></li>
                           <li>You are running <span class=\"drop\">- AdaptCMS {$adaptcms_version}</span></li>
                           </ul>
                           
                           		<p>Copyright 2006-2010 <a href=\'http://www.insanevisions.com\'>Insane Visions</a><p>
                            
                            </div>
                        <!--[if !IE]>footer<![endif]-->	
             
             </div>
             	
		<!--[if !IE]>bg-pattern<![endif]-->

</body>
</html>';
} elseif ($_GET['name'] == "header") {
$newtemp = '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">

<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<title>{$sitename}</title>
	<link rel=\"stylesheet\" href=\"{$siteurl}style.css\" />
	<script type=\'text/javascript\' src=\'{$siteurl}style2.js\'></script>
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
            	
            			<div id=\"search-bar\"><form action=\"{$siteurl}index.php\" method=\"get\">
                			
                           <input class=\"search-button\" type=\"submit\" value=\"Search\"/> <input type=\"hidden\" name=\"view\" value=\"search\">
                            
                            <input class=\"search-box\" name=\"q\" type=\"text\" size=\"15\" maxlength=\"25\" />
                            </form>
                		</div>
                
            		<!--[if !IE]>search-bar<![endif]-->
                    
                    
                    	<!--[if !IE]>header<![endif]-->
            	
            				<div id=\"header\">
                				
                                <!--[if !IE]>logo<![endif]-->
                                	
                                    <div id=\"logo\">
                                    	
                                        <a href=\"{$siteurl}\"><img src=\"{$siteurl}images/banner1.jpg\" width=\"284\" height=\"45\" alt=\"{$sitename}\" border=\"0\" align=\"middle\"/></a>
                                    
                                    </div>
                                <!--[if !IE]>logo<![endif]-->
                                
                                
                                	 <!--[if !IE]>right-header<![endif]-->
                                	
                                    	<div id=\"right-header\">
                                		<img src=\"{$siteurl}images/banner2.jpg\" width=\"468\" height=\"60\" alt=\"banner\" align=\"right\" border=\"0\"/>
                                		</div>
                                    
                                	 <!--[if !IE]>right-header<![endif]-->
                                
                			</div>
                
            			<!--[if !IE]>header<![endif]-->
                        
                        
                        	<!--[if !IE]>navigation<![endif]-->
                        	
                            	<div id=\"navigation\">
                                
                                	<!--[if !IE]>button<![endif]-->
                        	
                            			<div id=\"button\">
                            	
                                            <ul>
			<li><a href=\"{$siteurl}\">Home</a></li>
			<li><a href=\"{$siteurl}section/News\">News</a></li>
			<li><a href=\"{$siteurl}section/reviews\">Reviews</a></li>
			<li><a href=\"{$siteurl}media\">Media</a></li>
			<li><a href=\"{$siteurl}page/contact-us\">Contact Us</a></li>
                                            </ul>
                                
                            			</div>
                                        
                                     <!--[if !IE]>button<![endif]-->
                                     
                                     
                                     	<!--[if !IE]>icon<![endif]-->
                        	
                            				<div id=\"icon\">
                                            	
                                                <a href=\"{$siteurl}rss\"><img src=\"{$siteurl}images/rss.png\" width=\"42\" height=\"44\" alt=\"icon\" border=\"0\" align=\"right\"/></a>
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
                    <span onClick=\"if(document.getElementById(\'text6\').style.display==\'block\'){ldelim}document.getElementById(\'text6\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text6\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text6\').style=\'hand\';\"><img src=\"{$siteurl}images/arrow-down2.jpg\" width=\"23\" height=\"48\" onclick=\"toggle(this,\'{$siteurl}images/arrow-down2.jpg\',\'{$siteurl}images/arrow-up.jpg\',\'id5\');\" id=\'id5\'  />
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
                            
                            
                            	<!--[if !IE]>sidebar2<![endif]-->
                	
                    			<div id=\"sidebar2\" class=\"menu\">
                        
                                     
                                    
                                    <!--[if !IE]> basic menu<![endif]-->  
                                        <!--[if !IE]>sidebar-heading<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\"> Latest News</div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text7\').style.display==\'block\'){ldelim}document.getElementById(\'text7\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text7\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text7\').style=\'hand\';\"><img src=\"{$siteurl}images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle(this,\'{$siteurl}images/arrow-down2.jpg\',\'{$siteurl}images/arrow-up.jpg\',\'id6\');\" id=\'id6\'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id=\"text7\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">                      	
                  <ul class=\"side-menu2\">
                   {php}
echo content(\"latestnews\", \"News\", 5);
{/php}
                 </ul>

                   
                     
            	</div> 
            
            	<!--[if !IE]>sidebar-heading<![endif]-->
                
          	<!--[if !IE]> basic menu<![endif]-->
                                
                        		</div>
                       <br /><br />
                    		<!--[if !IE]>sidebar3<![endif]-->
                    		<div id=\"sidebar3\" class=\"menu\">
                        
                                     
                                    
                                    <!--[if !IE]> basic menu<![endif]-->  
                                        <!--[if !IE]>sidebar-heading<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\">Poll</div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text8\').style.display==\'block\'){ldelim}document.getElementById(\'text8\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text8\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text8\').style=\'hand\';\"><img src=\"{$siteurl}images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle(this,\'{$siteurl}images/arrow-down2.jpg\',\'{$siteurl}images/arrow-up.jpg\',\'id7\');\" id=\'id7\'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id=\"text8\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">      
                        {php}
                            echo poll(1);
                            {/php}
                            </div>
                            
                            </div>
                            <br />
                            <!--[if !IE]>sidebar4<![endif]-->
                    		<div id=\"sidebar4\" class=\"menu\">
                        
                                     
                                    
                                    <!--[if !IE]> basic menu<![endif]-->  
                                        <!--[if !IE]>sidebar-heading<![endif]-->   
            								
                                            <div class=\"sidebar-heading2\">
                
                			
                            <div class=\"heading-text1\">Media</div>
                
                				<div class=\"arrow2\">
                    
                    <span onClick=\"if(document.getElementById(\'text9\').style.display==\'block\'){ldelim}document.getElementById(\'text9\').style.display=\'none\'; {rdelim}else{ldelim}document.getElementById(\'text9\').style.display=\'block\'; {rdelim}\" class=\"view\" onmouseover=\"document.getElementById(\'text9\').style=\'hand\';\"><img src=\"{$siteurl}images/arrow-down2.jpg\" border=\"none\" width=\"23\" height=\"48\" onclick=\"toggle(this,\'{$siteurl}images/arrow-down2.jpg\',\'{$siteurl}images/arrow-up.jpg\',\'id8\');\" id=\'id8\'  />
                    
                    </span>

                    </div>
                    


            	</div>
        	<!--[if !IE]>sidebar-heading<![endif]-->     
                               
                                 
              <div id=\"text9\" style=\"display:block; width: 218px; margin:0 0 0 0; float:left;\">    
              
              <div align=\"center\">     	
              {php}
echo media(\"media_page\", \"latestmedia\", 3);
{/php}
</div>
              </div></div>
                            
                            <!--[if !IE]>endmenu<![endif]-->
                        </div>
                       
                    <!--[if !IE]>sidebar<![endif]-->
                    
                    	<!--[if !IE]>right<![endif]-->
                        	<div id=\"right\">
                            
                            	<!--[if !IE]>banner<![endif]-->
                        			<div id=\"banner\">
                            	
                                		<img src=\"{$siteurl}images/banner.jpg\" width=\"724\" height=\"209\" alt=\"banner\" />
                            		</div>
                        		<!--[if !IE]>banner<![endif]-->
                                
                                
                                	<!--[if !IE]>content<![endif]-->
                        				
                                        <div id=\"content\"><p>';
} elseif ($_GET['name'] == "footer") {
$newtemp = "                                   	</div>
                                        
                                    <!--[if !IE]>content<![endif]-->
                            
                            </div>
                        <!--[if !IE]>right<![endif]-->    
                   
                        
                        
                         </div>
                <!--[if !IE]>content-part<![endif]-->
                
                
                	<!--[if !IE]>footer<![endif]-->
                        	<div id=\"footer\">
                        	<a href=\"http://www.adaptcms.com\"><img src=\"http://www.adaptcms.com/button.png\" align=\"left\" style=\"padding-left:5px;padding-top:6px\"></a>
                           <p>Copyright 2006-2010 - <a href=\"http://www.insanevisions.com\">Insane Visions</a><p>
                            
                            </div>
                        <!--[if !IE]>footer<![endif]-->	
             
             </div>
             	
		<!--[if !IE]>bg-pattern<![endif]-->

</body>
</html>";
}

if ($newtemp) {
$query = mysql_query("UPDATE ".$pre."skins SET template = '".addslashes($newtemp)."', date = '".time()."' WHERE id = '".$_GET['id']."'");

$_GET['name'] = strtolower(str_replace(" ", "_", addslashes($_GET["name"])));
if ($_GET['skin']) {
$folder = $_GET['skin']."/";
}

$fh = fopen($sitepath."templates/".$folder.$_GET['name'].".tpl", 'w') or die("can't open file");
fwrite($fh, stripslashes($newtemp));
fclose($fh);

if ($handle = @opendir("inc/smarty/templates_c/")) {
while (false !== ($file = @readdir($handle))) {
if (stristr($file, ".".$_GET['name'].".tpl.php")) {
unlink($sitepath."inc/smarty/templates_c/".$file);
}
}
}

echo re_direct("1500", "admin.php?view=skins");
echo "The template has been reset to default. <a href='admin.php?view=skins'>Return</a>";
} else {
if (mysql_query("DELETE FROM ".$pre."skins WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
mysql_query("DELETE FROM ".$pre."fields WHERE skin = '".addslashes($_GET["name"])."'");

if ($handle = @opendir("inc/smarty/templates_c/")) {
while (false !== ($file = @readdir($handle))) {
if (stristr($file, ".".$_GET['name'].".tpl.php")) {
unlink($sitepath."inc/smarty/templates_c/".$file);
}
}
}

if ($_GET['sname']) {
mysql_query("DELETE FROM ".$pre."skins WHERE skin = '".addslashes($_GET["sname"])."'");
rmdir($sitepath."templates/".addslashes($_GET["sname"]));
} else {
if ($_GET['skin']) {
unlink($sitepath."templates/".$_GET['skin']."/".$_GET['name'].".tpl");
} else {
unlink($sitepath."templates/".$_GET['name'].".tpl");
}
}

echo re_direct("1500", "admin.php?view=skins");
echo "The skin/template has been deleted. <a href='admin.php?view=skins'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The skin/template could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
}
?>