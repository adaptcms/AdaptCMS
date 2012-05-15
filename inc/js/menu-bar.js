 /************************************************************************************************************ 
   (C) www.dhtmlgoodies.com, October 2005 
    
   This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.    
    
   Terms of use: 
   You are free to use this script as long as the copyright message is kept intact. However, you may not 
   redistribute, sell or repost it without our permission. 
    
   Thank you! 
    
   www.dhtmlgoodies.com 
   Alf Magne Kalleland 
    
   ************************************************************************************************************/    
        
   var js_menuObj;   // Reference to the menu div 
   var currentZIndex = 1000; 
   var liIndex = 0; 
   var visibleMenus = new Array(); 
   var activeMenuItem = false; 
   var timeBeforeAutoHide = 1200; // Microseconds from mouse leaves menu to auto hide. 
   var js_menu_arrow = 'http://www.insanevisions.com/share/adaptcms2/arrow.gif'; 
    
   var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false; 
   var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox')>=0?true:false; 
   var navigatorVersion = navigator.appVersion.replace(/.*?MSIE ([0-9]\.[0-9]).*/g,'$1')/1; 
   var menuBlockArray = new Array(); 
   var menuParentOffsetLeft = false;    


    // {{{ getStyle() 
   /** 
   * Return specific style attribute for an element 
   * 
   * @param Object el = Reference to HTML element 
   * @param String property = Css property 
   * @private 
   */        
   function getStyle(el,property) 
   {        

      if (document.defaultView && document.defaultView.getComputedStyle) { 

         var retVal = null;              
         var comp = document.defaultView.getComputedStyle(el, ''); 
         if (comp){ 
            retVal = comp[property]; 
              
            if(!retVal){ 
               var comp = document.defaultView.getComputedStyle(el, null); 
               retVal = comp.getPropertyCSSValue(property); 
            }          
         }    

         if(retVal==null)retVal=''; 
          
         return el.style[property] || retVal; 
      } 
      if (document.documentElement.currentStyle && MSIE){    
         var value = el.currentStyle ? el.currentStyle[property] : null; 
         return ( el.style[property] || value ); 
                                              
      } 
      return el.style[property];              
   } 
      
   function getTopPos(inputObj) 
   { 
   	var origInputObj = inputObj;
 
     var returnValue = inputObj.offsetTop; 
     if(inputObj.tagName=='LI' && inputObj.parentNode.className=='menuBlock1'){    
        var aTag = inputObj.getElementsByTagName('A')[0]; 
        if(aTag)returnValue += aTag.parentNode.offsetHeight; 
     } 
     var topOfMenuReached = false; 
     while((inputObj = inputObj.offsetParent) != null){ 
        if(inputObj.parentNode.id=='js_menu')topOfMenuReached=true; 
        if(topOfMenuReached && !inputObj.className.match(/menuBlock/gi) || (!MSIE && origInputObj.parentNode.className=='menuBlock1')){ 
           var style = getStyle(inputObj,'position'); 
           if(style=='absolute' || style=='relative'){                
              return returnValue;            
           } 
        } 
          
        returnValue += inputObj.offsetTop;          
     } 

     return returnValue; 
   } 
    
   function getLeftPos(inputObj) 
   { 
     var returnValue = inputObj.offsetLeft; 
      
     var topOfMenuReached = false; 
     while((inputObj = inputObj.offsetParent) != null){ 
       if(inputObj.parentNode.id=='js_menu')topOfMenuReached=true; 
        if(topOfMenuReached && !inputObj.className.match(/menuBlock/gi)){ 
           var style = getStyle(inputObj,'position'); 
           if(style=='absolute' || style=='relative')return returnValue; 
        } 
      
        returnValue += inputObj.offsetLeft; 
     } 
     return returnValue; 
   } 


    
   function showHideSub() 
   { 

      var attr = this.parentNode.getAttribute('currentDepth'); 
      if(navigator.userAgent.indexOf('Opera')>=0){ 
         attr = this.parentNode.currentDepth; 
      } 
        
      this.className = 'currentDepth' + attr + 'over'; 
        
      if(activeMenuItem && activeMenuItem!=this){ 
         activeMenuItem.className=activeMenuItem.className.replace(/over/,''); 
      } 
      activeMenuItem = this; 
    
      var numericIdThis = this.id.replace(/[^0-9]/g,''); 
      var exceptionArray = new Array(); 
      // Showing sub item of this LI 
      var sub = document.getElementById('subOf' + numericIdThis); 
      if(sub){ 
         visibleMenus.push(sub); 
         sub.style.display=''; 
         sub.parentNode.className = sub.parentNode.className + 'over'; 
         exceptionArray[sub.id] = true; 
      }    
        
      // Showing parent items of this one 
        
      var parent = this.parentNode; 
      while(parent && parent.id && parent.tagName=='UL'){ 
         visibleMenus.push(parent); 
         exceptionArray[parent.id] = true; 
         parent.style.display=''; 
          
         var li = document.getElementById('dhtmlgoodies_listItem' + parent.id.replace(/[^0-9]/g,'')); 
         if(li.className.indexOf('over')<0)li.className = li.className + 'over'; 
         parent = li.parentNode; 
          
      } 

          
      hideMenuItems(exceptionArray); 



   } 

   function hideMenuItems(exceptionArray) 
   { 
      /* 
      Hiding visible menu items 
      */ 
      var newVisibleMenuArray = new Array(); 
      for(var no=0;no<visibleMenus.length;no++){ 
         if(visibleMenus[no].className!='menuBlock1' && visibleMenus[no].id){ 
            if(!exceptionArray[visibleMenus[no].id]){ 
               var el = visibleMenus[no].getElementsByTagName('A')[0]; 
               visibleMenus[no].style.display = 'none'; 
               var li = document.getElementById('dhtmlgoodies_listItem' + visibleMenus[no].id.replace(/[^0-9]/g,'')); 
               if(li.className.indexOf('over')>0)li.className = li.className.replace(/over/,''); 
            }else{              
               newVisibleMenuArray.push(visibleMenus[no]); 
            } 
         } 
      }        
      visibleMenus = newVisibleMenuArray;        
   } 
    
    
    
   var menuActive = true; 
   var hideTimer = 0; 
   function mouseOverMenu() 
   { 
      menuActive = true;        
   } 
    
   function mouseOutMenu() 
   { 
      menuActive = false; 
      timerAutoHide();    
   } 
    
   function timerAutoHide() 
   { 
      if(menuActive){ 
         hideTimer = 0; 
         return; 
      } 
        
      if(hideTimer<timeBeforeAutoHide){ 
         hideTimer+=100; 
         setTimeout('timerAutoHide()',99); 
      }else{ 
         hideTimer = 0; 
         autohideMenuItems();    
      } 
   } 
    
   function autohideMenuItems() 
   { 
      if(!menuActive){ 
         hideMenuItems(new Array());    
         if(activeMenuItem)activeMenuItem.className=activeMenuItem.className.replace(/over/,'');        
      } 
   } 
    
    
   function initSubMenus(inputObj,initOffsetLeft,currentDepth) 
   {    
      var subUl = inputObj.getElementsByTagName('UL'); 
      if(subUl.length>0){ 
         var ul = subUl[0]; 
          
         ul.id = 'subOf' + inputObj.id.replace(/[^0-9]/g,''); 
         ul.setAttribute('currentDepth' ,currentDepth); 
         ul.currentDepth = currentDepth; 
         ul.className='menuBlock' + currentDepth; 
         ul.onmouseover = mouseOverMenu; 
         ul.onmouseout = mouseOutMenu; 
         currentZIndex+=1; 
         ul.style.zIndex = currentZIndex; 
         menuBlockArray.push(ul); 
         ul = js_menuObj.appendChild(ul); 
         var topPos = getTopPos(inputObj); 
         var leftPos = getLeftPos(inputObj)/1 + initOffsetLeft/1;          
         
         ul.style.position = 'absolute'; 
         ul.style.left = leftPos + 'px'; 
         ul.style.top = topPos + 'px'; 
         var li = ul.getElementsByTagName('LI')[0]; 
         while(li){ 
            if(li.tagName=='LI'){    
               li.className='currentDepth' + currentDepth;                
               li.id = 'dhtmlgoodies_listItem' + liIndex; 
               liIndex++;              
               var uls = li.getElementsByTagName('UL'); 
               li.onmouseover = showHideSub; 

               if(uls.length>0){ 
                  var offsetToFunction = li.getElementsByTagName('A')[0].offsetWidth+2; 
                  if(navigatorVersion<6 && MSIE)offsetToFunction+=15;   // MSIE 5.x fix 
                  initSubMenus(li,offsetToFunction,(currentDepth+1)); 
               }    
               if(MSIE){ 
                  var a = li.getElementsByTagName('A')[0]; 
                  a.style.width=li.offsetWidth+'px'; 
                  a.style.display='block'; 
               }                
            } 
            li = li.nextSibling; 
         } 
         ul.style.display = 'none';    
         if(!document.all){ 
            //js_menuObj.appendChild(ul); 
         } 
      }    
   } 


   function resizeMenu() 
   { 
      var offsetParent = getLeftPos(js_menuObj); 
        
      for(var no=0;no<menuBlockArray.length;no++){ 
         var leftPos = menuBlockArray[no].style.left.replace('px','')/1; 
         menuBlockArray[no].style.left = leftPos + offsetParent - menuParentOffsetLeft + 'px'; 
      } 
      menuParentOffsetLeft = offsetParent; 
   } 
    
   /* 
   Initializing menu 
   */ 
   function initDhtmlGoodiesMenu() 
   { 
      js_menuObj = document.getElementById('js_menu'); 
        
        
      var aTags = js_menuObj.getElementsByTagName('A'); 
      for(var no=0;no<aTags.length;no++){          

         var subUl = aTags[no].parentNode.getElementsByTagName('UL'); 
         if(subUl.length>0 && aTags[no].parentNode.parentNode.parentNode.id != 'js_menu'){ 
            var img = document.createElement('IMG'); 
            img.src = js_menu_arrow; 
            aTags[no].appendChild(img);              

         } 

      } 
              
      var mainMenu = js_menuObj.getElementsByTagName('UL')[0]; 
      mainMenu.className='menuBlock1'; 
      mainMenu.style.zIndex = currentZIndex; 
      mainMenu.setAttribute('currentDepth' ,1); 
      mainMenu.currentDepth = '1'; 
      mainMenu.onmouseover = mouseOverMenu; 
      mainMenu.onmouseout = mouseOutMenu;        

      var mainMenuItemsArray = new Array(); 
      var mainMenuItem = mainMenu.getElementsByTagName('LI')[0]; 
      mainMenu.style.height = mainMenuItem.offsetHeight + 2 + 'px'; 
      while(mainMenuItem){ 
          
         mainMenuItem.className='currentDepth1'; 
         mainMenuItem.id = 'dhtmlgoodies_listItem' + liIndex; 
         mainMenuItem.onmouseover = showHideSub; 
         liIndex++;              
         if(mainMenuItem.tagName=='LI'){ 
            mainMenuItem.style.cssText = 'float:left;';    
            mainMenuItem.style.styleFloat = 'left'; 
            mainMenuItemsArray[mainMenuItemsArray.length] = mainMenuItem; 
            initSubMenus(mainMenuItem,0,2); 
         }          
          
         mainMenuItem = mainMenuItem.nextSibling; 
          
      } 

      for(var no=0;no<mainMenuItemsArray.length;no++){ 
         initSubMenus(mainMenuItemsArray[no],0,2);          
      } 
        
      menuParentOffsetLeft = getLeftPos(js_menuObj);    
      window.onresize = resizeMenu;    
      js_menuObj.style.visibility = 'visible';    
   } 
	window.onload = initDhtmlGoodiesMenu;