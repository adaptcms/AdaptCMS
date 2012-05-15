/**
*	akeditable-  in place editor
*	author: Amit Kumar Singh 
* 	project url : http://amiworks.co.in/talk/akeditable-jquery-inplace-editor/ 
 * 	Based on jeditable by Mika Tuupola, Dylan Verheul
 *	http://www.appelsiini.net/projects/jeditable
**/
/**
  * Version 1.1.0
  *  @param String  url             POST URL  to send edited content to.
  *  @param String  element_id      Element ID of the element that you want to convert to editable area.
  *  @param Hash  settings	option to be used. options are type:(  textarea, text), name:(name of the textare to be used  it has to be same as element_id) ,width,height, submit:( name of the submit button to show)
  *   
  **/
function akedit(url,element_id,settings)
{
	if(document.getElementById('frm'+element_id))
		return false; //form is already thier so no need to do it again. 10:40 PM 1/4/2008 amit singh
    
	if(document.getElementById('akebutton'))
		reset(old_content,objtoedit);//another button is already their so reset the content as new 'Edit' has been clicked.
    objtoedit=document.getElementById(element_id);
    old_content=objtoedit.innerHTML.replace("\r\n","");
    objtoedit.innerHTML='';
    //create textbox or textarea
            /* create the form object */
        var f = document.createElement('form');
		  f.id='frm'+element_id;
        /*  main input element */
        var i;
        if ('textarea' == settings.type) {
            i = document.createElement('textarea');
            if (settings.rows) {
                i.rows = settings.rows;
            } else {
               i.rows =4;
            }
            if (settings.cols) {
                i.cols = settings.cols;
            } else {
                i.cols=30;
            }

        } else {
            i = document.createElement('input');
            i.type  = settings.type;
            /* https://bugzilla.mozilla.org/show_bug.cgi?id=236791 */
            i.setAttribute('autocomplete','off');
        }
        i.name  = settings.name;
		i.id	= 'txt'+element_id;	
        i.value=old_content;
    
        f.appendChild(i);
        f.appendChild(document.createElement('br'));
        if (settings.submit) {
            var b = document.createElement('input');
            b.type = 'submit';
            b.value = settings.submit;
            b.style.backgroundColor='#ff6666';
            b.style.color='#ffffff';
            f.appendChild(b);
        }
        var b = document.createElement('input');
            b.type = 'button';
            b.id = 'akebutton';
            b.value = 'cancel';
            b.style.backgroundColor='#dddddd';
            b.style.color='#6E6E6E';
            b.style.marginLeft='5px';
            b.onclick =function(e){	
				reset(old_content,objtoedit);	
				if (!e)
					window.event.cancelBubble = true
				else
					e.stopPropagation();
            }
            f.appendChild(b);
            f.onsubmit=function(e) {
                /* do no submit */
                if (!e)
                    window.event.returnValue = false;
                else
                    e.preventDefault(); 
                /* add edited content and id of edited element to POST */           

                var p = {};
                p[i.name] = $(i).val();
                p['id'] = element_id;

                /* show the saving indicator */
				objtoedit = document.getElementById(element_id);
                objtoedit.innerHTML="Saving.....";
                $.post(url, p, function(str) {
                   objtoedit.innerHTML=str;
                });				
            }
			
        objtoedit.appendChild(f);
		

        i.focus();
        i.onkeydown=function(e){
              if (!e)
             {
                var e = window.event;
                e.preventDefault = function() { window.event.returnValue = false }
             }
             if (e.keyCode == 27) {
                e.preventDefault();
                reset(old_content,objtoedit);
            }
        };
}

function reset(oldContent,objtoreset) {
            objtoreset.innerHTML = oldContent;
}