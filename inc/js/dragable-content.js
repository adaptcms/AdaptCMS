	/************************************************************************************************************
	(C) www.dhtmlgoodies.com, January 2006
	
	This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.	
	
	Terms of use:
	You are free to use this script as long as the copyright message is kept intact. However, you may not
	redistribute, sell or repost it without our permission.
	
	Thank you!
	
	www.dhtmlgoodies.com
	Alf Magne Kalleland
	
	************************************************************************************************************/	

	
	if(!window.rememberPositionedInCookie)var rememberPositionedInCookie = false;
	if(!window.rememberPosition_cookieName)var rememberPosition_cookieName = 'demo';

	
	var dragObjArray = new Array();
	var dragObjCloneArray = new Array();
	var numericIdToBeDragged = false;
	var dragDropTimer = -1;
	
	var mouse_x;
	var mouse_y;
	
	var el_x;
	var el_y;
	
	var currentZIndex = 10000;
	var dragableElementMoved = new Array();
	
	var dragableContent_cookieString;
	var dragableContent_cookieItems = new Array();
	
	
	/*
	These cookie functions are downloaded from 
	http://www.mach5.com/support/analyzer/manual/html/General/CookiesJavaScript.htm
	*/	
	function Get_Cookie(name) { 
	   var start = document.cookie.indexOf(name+"="); 
	   var len = start+name.length+1; 
	   if ((!start) && (name != document.cookie.substring(0,name.length))) return null; 
	   if (start == -1) return null; 
	   var end = document.cookie.indexOf(";",len); 
	   if (end == -1) end = document.cookie.length; 
	   return unescape(document.cookie.substring(len,end)); 
	} 
	// This function has been slightly modified
	function Set_Cookie(name,value,expires,path,domain,secure) { 
		expires = expires * 60*60*24*1000;
		var today = new Date();
		var expires_date = new Date( today.getTime() + (expires) );
	    var cookieString = name + "=" +escape(value) + 
	       ( (expires) ? ";expires=" + expires_date.toGMTString() : "") + 
	       ( (path) ? ";path=" + path : "") + 
	       ( (domain) ? ";domain=" + domain : "") + 
	       ( (secure) ? ";secure" : ""); 
	    document.cookie = cookieString; 
	} 

	
	function getTopPos(inputObj)
	{		
	  var returnValue = inputObj.offsetTop;
	  while((inputObj = inputObj.offsetParent) != null){
	  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetTop;
	  }
	  return returnValue;
	}
	
	function getLeftPos(inputObj)
	{
	  var returnValue = inputObj.offsetLeft;
	  while((inputObj = inputObj.offsetParent) != null){
	  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetLeft;
	  }
	  return returnValue;
	}
		
	function initDragDropElement(e)
	{
		if(document.all)e = event;
		
		if(document.all)e = event;
		if (e.target) source = e.target;
			else if (e.srcElement) source = e.srcElement;
			if (source.nodeType == 3) // defeat Safari bug
				source = source.parentNode;	
		if(source.tagName.toLowerCase()=='input' || source.tagName.toLowerCase()=='textarea')return false;	

			
		numericIdToBeDragged = this.className.replace(/[^0-9]/g,'');
		dragDropTimer=0;
		mouse_x = e.clientX;
		mouse_y = e.clientY;
		
		currentZIndex = currentZIndex + 1;
		
		dragObjCloneArray[numericIdToBeDragged].style.zIndex = currentZIndex;
		
		if(!dragableElementMoved[numericIdToBeDragged]){
			dragObjCloneArray[numericIdToBeDragged].style.top = getTopPos(dragObjArray[numericIdToBeDragged]) + 'px';
			dragObjCloneArray[numericIdToBeDragged].style.left = getLeftPos(dragObjArray[numericIdToBeDragged]) + 'px';
		}				
		el_x = dragObjCloneArray[numericIdToBeDragged].style.left.replace('px','')/1;
		el_y = dragObjCloneArray[numericIdToBeDragged].style.top.replace('px','')/1;

		
		timerDragDropElement();
		return false;
	}
	
	function timerDragDropElement()
	{
		if(dragDropTimer>=0 && dragDropTimer<10){
			dragDropTimer = dragDropTimer + 1;
			setTimeout('timerDragDropElement()',5);
			return;			
		}
		if(dragDropTimer>=10){
			if(dragObjCloneArray[numericIdToBeDragged].style.display=='none'){
				dragObjArray[numericIdToBeDragged].style.visibility = 'hidden';
				dragObjCloneArray[numericIdToBeDragged].style.display = 'block';
				dragObjCloneArray[numericIdToBeDragged].style.visibility = 'visible';
				dragObjCloneArray[numericIdToBeDragged].style.top = getTopPos(dragObjArray[numericIdToBeDragged]) + 'px';
				dragObjCloneArray[numericIdToBeDragged].style.left = getLeftPos(dragObjArray[numericIdToBeDragged]) + 'px';
				dragableElementMoved[numericIdToBeDragged] = true;	
			}
		}		
	}
	
	function cancelEvent()
	{
		return false;
	}
	
	function cancelSelectionEvent()
	{
		if(dragDropTimer>=0)return false;
		return true;
	}
	
	function moveDragableElement(e)
	{
		if(document.all)e = event;		
		if(dragDropTimer<10)return;	
		dragObjCloneArray[numericIdToBeDragged].style.left = (e.clientX - mouse_x + el_x) + 'px'; 
		dragObjCloneArray[numericIdToBeDragged].style.top = (e.clientY - mouse_y + el_y) + 'px'; 
	}
	
	function stop_dragDropElement()
	{
		dragDropTimer = -1;
		
		if(rememberPositionedInCookie && dragObjCloneArray[numericIdToBeDragged]){		
			dragableContent_cookieItems['dragableElementClone' + numericIdToBeDragged] = [dragObjCloneArray[numericIdToBeDragged].style.left,dragObjCloneArray[numericIdToBeDragged].style.top,dragObjCloneArray[numericIdToBeDragged].style.zIndex]; 
		}
		if(rememberPositionedInCookie)createCookieString();
		numericIdToBeDragged = false;
	}
	
	function createCookieString()
	{
		var stringToSave = '';
		for(var prop in dragableContent_cookieItems){
			if(stringToSave)stringToSave = stringToSave + '###';
			stringToSave = stringToSave + prop + ',' + dragableContent_cookieItems[prop][0] + ',' +  dragableContent_cookieItems[prop][1] + ',' +  dragableContent_cookieItems[prop][2];
		}	
		Set_Cookie(rememberPosition_cookieName,stringToSave,60000000);
	}
	
	
	function initdragableElements()
	{
		var dragableContent_cookieString = false;
		if(rememberPositionedInCookie){
			dragableContent_cookieString = Get_Cookie(rememberPosition_cookieName);
		}
		var tmpElements = new Array();
		var allObjects = document.getElementsByTagName('*');
		for(var no=0;no<allObjects.length;no++){
			if(allObjects[no].className=='dragableElement'){
				allObjects[no].style.cursor = 'move';
				tmpElements[tmpElements.length] = allObjects[no];
			}
		}
		
		for(var no=0;no<tmpElements.length;no++){
			var el = tmpElements[no].cloneNode(true);
			tmpElements[no].className='dragableElement' + no;
			el.onmousedown = initDragDropElement;
			el.className='dragableElementClone' + no;			

			el.style.position='absolute';
			el.style.display='none';
			el.style.visibility='hidden';
			
			el.style.top = getTopPos(tmpElements[no]) + 'px';
			el.style.left = getLeftPos(tmpElements[no]) + 'px';
			tmpElements[no].parentNode.insertBefore(el,tmpElements[no]);
			tmpElements[no].onmousedown = initDragDropElement;
			
			dragObjArray[no] = tmpElements[no]; 
			dragObjCloneArray[no] = el; 
		}
		
		document.body.onmousemove = moveDragableElement;
		document.body.onmouseup = stop_dragDropElement;
		document.body.onselectstart = cancelSelectionEvent;
		document.body.ondragstart = cancelEvent;
		
		// Position cookie elements
		if(dragableContent_cookieString){
		
			var items = dragableContent_cookieString.split('###');
			for(var no=0;no<items.length;no++){
				var tokens = items[no].split(',');
				dragableContent_cookieItems[tokens[0]] = [tokens[1] ,tokens[2],tokens[3]];				
			}	
			positionItemsFromCookie();	
		}
	}
	
	function positionItemsFromCookie()
	{
		for(var prop in dragableContent_cookieItems){
			for(var no=0;no<dragObjCloneArray.length;no++){
				if(dragObjCloneArray[no].className==prop){
					dragableElementMoved[no] = true;
					dragObjCloneArray[no].style.display='block';
					dragObjArray[no].style.visibility = 'hidden';
					dragObjCloneArray[no].style.visibility = 'visible';
					dragObjCloneArray[no].style.left = dragableContent_cookieItems[prop][0];
					dragObjCloneArray[no].style.top = dragableContent_cookieItems[prop][1];
					dragObjCloneArray[no].style.zIndex = dragableContent_cookieItems[prop][2];
					currentZIndex = Math.max(currentZIndex,dragableContent_cookieItems[prop][2]/1 + 1);
					
				}
			}
		}
	}
	
	window.onload = initdragableElements;