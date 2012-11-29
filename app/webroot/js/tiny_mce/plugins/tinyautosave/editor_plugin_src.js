/*
	TinyAutoSave plug-in, by Speednet
	Adds auto-save capability to the TinyMCE text editor to rescue content inadvertently lost.
	Copyright © 2008-2009 Speednet Group LLC. All rights reserved.

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

(function() {

	// Load language pack
	tinymce.PluginManager.requireLangPack("tinyautosave");
	
	tinymce.create("tinymce.plugins.TinyAutoSavePlugin", {
		/// <summary>
		/// Automatically saves the editor contents periodically and just before leaving the current page.
		/// Allows the user to rescue the contents of the last autosave, in case they did not intend to
		/// navigate away from the current page or the browser window was closed before posting the content.
		/// </summary>
		/// <field name="onPreSave" type="String" mayBeNull="false">Name of a callback function that gets called
		/// before each auto-save is performed. The callback function must return a Boolean value of true if the
		/// auto-save is to proceed normally, or false if the auto-save is to be canceled. The editor instance
		/// is the context of the callback (assigned to 'this').</field>
		/// <field name="onPostSave" type="String" mayBeNull="false">Name of a callback function that gets called
		/// after each auto-save is performed. Any return value from the callback function is ignored. The editor
		/// instance is the context of the callback (assigned to 'this').</field>
		/// <field name="onSaveError" type="String" mayBeNull="false">Name of a callback function that gets called
		/// each time an auto-save fails in an error condition. The editor instance is the context of the callback
		/// (assigned to 'this').</field>
		/// <field name="onPreRestore" type="String" mayBeNull="false">Name of a callback function that gets called
		/// before a restore request is performed. The callback function must return a Boolean value of true if the
		/// restore is to proceed normally, or false if the restore is to be canceled. The editor instance is the
		/// context of the callback (assigned to 'this').</field>
		/// <field name="onPostRestore" type="String" mayBeNull="false">Name of a callback function that gets called
		/// after a restore request is performed. Any return value from the callback function is ignored. The editor
		/// instance is the context of the callback (assigned to 'this').</field>
		/// <field name="onRestoreError" type="String" mayBeNull="false">Name of a callback function that gets called
		/// each time a restore request fails in an error condition. The editor instance is the context of the
		/// callback (assigned to 'this').</field>
		/// <field name="showSaveProgress" type="Boolean" mayBeNull="false">Receives the Boolean value
		/// specified in the tinyautosave_showsaveprogress configuration option, or true if none is specified.
		/// This is a public read/write property, and the behavior of the toolbar button throbber/progress
		/// can be altered dynamically by changing this property.</field>
		/// <remarks>
		/// 
		/// CONFIGURATION OPTIONS:
		/// 
		/// tinyautosave_interval_seconds - (Number, default = 60) The number of seconds between automatic saves.
		/// When the editor is first displayed, an autosave will not occur for at least this amount of time.
		/// 
		/// tinyautosave_retention_minutes - (Number, default = 20) The number of minutes since the last autosave
		/// that content will remain in the rescue storage space before it is automatically expired.
		/// 
		/// tinyautosave_minlength - (Number, default = 50) The minimum number of characters that must be in the
		/// editor before an autosave will occur.  The character count includes all non-visible characters,
		/// such as HTML tags.  Although this can be set to 0 (zero), it is not recommended.  Doing so would
		/// open the possibility that if the user accidentally refreshes the page, the empty editor contents
		/// would overwrite the rescue content, effectively defeating the purpose of the plugin.
		/// 
		/// tinyautosave_showsaveprogress - (Boolean, default = true) When true, the toolbar button will show a
		/// brief animation every time an autosave occurs.
		/// 
		/// PUBLIC PROPERTIES:
		/// 
		/// Available public properties of the TinyAutoSave plugin are:
		/// 	onPreSave (String)
		/// 	onPostSave (String)
		/// 	onSaveError (String)
		/// 	onPreRestore (String)
		/// 	onPostRestore (String)
		/// 	onRestoreError (String)
		/// 	showSaveProgress (Boolean)
		/// 
		/// See <field /> definitions above for detailed descriptions of the public properties.
		/// 
		/// TECHNOLOGY DISCUSSION:
		/// 
		/// The plugin attempts to use the most advanced features available in the current browser to save
		/// as much content as possible.  There are a total of four different methods used to autosave the
		/// content.  In order of preference, they are:
		/// 
		/// 1. localStorage - A new feature of HTML 5, localStorage can store megabytes of data per domain
		/// on the client computer. Data stored in the localStorage area has no expiration date, so we must
		/// manage expiring the data ourselves.  localStorage is fully supported by IE8, and it is supposed
		/// to be working in Firefox 3 and Safari 3.2, but in reality is is flaky in those browsers.  As
		/// HTML 5 gets wider support, the TinyAutoSave plugin will use it automatically. In Windows Vista,
		/// localStorage is stored in the following folder:
		/// C:\Users\<username>\AppData\Local\Microsoft\Internet Explorer\DOMStore\<tempFolder>
		/// 
		/// 2. sessionStorage - A new feature of HTML 5, sessionStorage works similarly to localStorage,
		/// except it is designed to expire after a certain amount of time.  Because the specification
		/// around expiration date/time is very loosely-described, it is preferrable to use locaStorage and
		/// manage the expiration ourselves.  sessionStorage has similar storage characteristics to
		/// localStorage, although it seems to have better support by Firefox 3 at the moment.  (That will
		/// certainly change as Firefox continues getting better at HTML 5 adoption.)
		/// 
		/// 3. UserData - A very under-exploited feature of Microsoft Internet Explorer, UserData is a
		/// way to store up to 128K of data per "document", or up to 1MB of data per domain, on the client
		/// computer.  The feature is available for IE 5+, which makes it available for every version of IE
		/// supported by TinyMCE.  The content is persistent across browser restarts and expires on the
		/// date/time specified, just like a cookie.  However, the data is not cleared when the user clears
		/// cookies on the browser, which makes it well-suited for rescuing autosaved content.  UserData,
		/// like other Microsoft IE browser technologies, is implemented as a behavior attached to a
		/// specific DOM object, so in this case we attach the behavior to the same DOM element that the
		/// TinyMCE editor instance is attached to.
		/// 
		/// 4. Cookies - When none of the above methods is available, the autosave content is stored in a
		/// cookie.  This limits the total saved content to around 4,000 characters, but we use every bit
		/// of that space as we can.  To maximize space utilization, before saving the content, we remove
		/// all newlines and other control characters less than ASCII code 32, change &nbsp; instances to 
		/// a regular space character, and do some minor compression techniques.  (TO-DO: add more
		/// compressiion techniques.)  Unfortunately, because the data is stored in a cookie, we have to
		/// waste some space encoding certain characters to avoid server warnings about dangerous content
		/// (as well as overcoming some browser bugs in Safari).  Instead of using the built-in escape()
		/// function, we do a proprietary encoding that only encodes the bare minimum characters, and uses
		/// only two bytes per encoded character, rather than 3 bytes like escape() does.  escape() encodes
		/// most non-alpha-numeric characters because it is designed for encoding URLs, not for encoding
		/// cookies.  It is a huge space-waster in cookies, and in this case would have cut the amount
		/// of autosaved content by at least half.
		/// </remarks>
	
		// Public properties
		editor: null,
		url: "",
		onPreSave: null,
		onPostSave: null,
		onSaveError: null,
		onPreRestore: null,
		onPostRestore: null,
		onRestoreError: null,
		showSaveProgress: true,
		
		// Private properties
		__save: null,
		__saveFinal: null,
		__restore: null,
		__dispose: null,
		_cookieName: "tinyautosave",
		_restoreImage: "",
		_progressImage: "",
		_intervalSeconds: 60,
		_retentionMinutes: 20,
		_minLength: 50,
		_canRestore: false,
		_busy: false,
		_useLocalStorage: false,
		_useSessionStorage: false,
		_useUserData: false,
		_timer: null,
		
		// Initialization - called by TinyMCE
		init: function (ed, url) {
		
			function createDelegate(instance, method) {
				/// <summary>
				/// Returns a delegate function, used for callbacks. Ensures 'this' refers
				/// to the desired object.
				/// </summary>
				/// <param name="instance" type="Object" optional="false" mayBeNull="true">Object that will be 'this' within the callback function.</param>
				/// <param name="method" type="Function" optional="false" mayBeNull="false">Callback function</param>
				/// <returns type="Function"></returns>
				
				return function () {
					return method.apply(instance, arguments);
				};
			}

			var t = this;

			if (!(t._useLocalStorage = ((typeof(localStorage) === "object") && (!!localStorage.getItem) && (!!localStorage.setItem) && (!!localStorage.removeItem)))) {
				if (!(t._useSessionStorage = ((typeof(sessionStorage) === "object") && (!!sessionStorage.getItem) && (!!sessionStorage.setItem) && (!!sessionStorage.removeItem)))) {
					if (t._useUserData = tinymce.isIE) {
						ed.getElement().style.behavior = "url('#default#userData')";
					}
				}
			}
			
			t.editor = ed;
			t.url = url;
			t._cookieName = "tinyautosave_" + ed.id;
			t._restoreImage = url + "/images/restore." + (tinymce.isIE6? "gif" : "png");
			t._progressImage = url + "/images/progress.gif";
			t._intervalSeconds = Math.max(1, parseInt(ed.getParam("tinyautosave_interval_seconds", null) || ed.getParam("tinyautosave_interval", t._intervalSeconds))); // Default = 60 seconds; minimum is 1
			t._retentionMinutes = Math.max(1, parseInt(ed.getParam("tinyautosave_retention_minutes", null) || ed.getParam("tinyautosave_retention", t._retentionMinutes))); // Default = 20 minutes; minimum is 1
			t._minLength = Math.max(1, parseInt(ed.getParam("tinyautosave_minlength", t._minLength))); // Default = 50 characters; minimum is 1
			t.showSaveProgress = ed.getParam("tinyautosave_showsaveprogress", t.showSaveProgress);
			t._canRestore = t.hasSavedContent();
			
			t.__save = createDelegate(t, t._save);
			t.__saveFinal = createDelegate(t, t._saveFinal);
			t.__restore = createDelegate(t, t._restore);
			t.__dispose = createDelegate(t, t._dispose);
			
			// Register commands
			ed.addCommand("mceTinyAutoSave", t.__save);
			ed.addCommand("mceTinyAutoSaveRestore", t.__restore);

			// Register restore button
			ed.addButton("tinyautosave", {
				title: "tinyautosave.restore_content",
				cmd: "mceTinyAutoSaveRestore",
				image: t._restoreImage
			});
			
			// Set save interval
			t._timer = window.setInterval(t.__save, t._intervalSeconds * 1000);
			
			// Ensures content is autosaved before window closes or navigates to new page
			tinymce.dom.Event.add(window, "beforeunload", t.__saveFinal);

			// Save when editor is removed (may be different than window's onbeforeunload event, so we need to do both)
			ed.onRemove.add(t.__saveFinal);
			
			// Set initial state of restore button
			ed.onPostRender.add(function (ed, cm) {
				ed.controlManager.setDisabled('tinyautosave', !t._canRestore);
			});
		},
		
		getInfo: function() {
			return {
				longname: "TinyAutoSave",
				author: "Speednet",
				authorurl: "http://www.speednet.biz/",
				infourl: "http://tinyautosave.googlecode.com/",
				version: "1.1"
			};
		},

		clear: function () {
			/// <summary>
			/// Removes the autosave content from storage. Disables the 'tinyautosave' toolbar button.
			/// </summary>

			var ed = this.editor, now = new Date();
			
			if (this._useLocalStorage) {
				localStorage.removeItem("TinyAutoSave");
			}
			else if (this._useSessionStorage) {
				sessionStorage.removeItem("TinyAutoSave");
			}
			else if (this._useUserData) {
				this._removeUserData(ed);
			}
			else {
				tinymce.util.Cookie.remove(this._cookieName);
			}

			this._canRestore = false;
			ed.controlManager.setDisabled('tinyautosave', true);
		},
		
		hasSavedContent: function () {
			/// <summary>
			/// Returns true if there is unexpired autosave content available to be restored.
			/// </summary>
			/// <returns type="Boolean"></returns>

			if (this._useLocalStorage || this._useSessionStorage) {
				var now = new Date();
				var content = ((this._useLocalStorage? localStorage.getItem("TinyAutoSave") : sessionStorage.getItem("TinyAutoSave")) || "").toString();
				var i = content.indexOf(",");
				
				if ((i > 8) && (i < content.length - 1)) {
					
					if ((new Date(content.slice(0, i))) > now) {
						return true;
					}
					
					// Remove expired content
					if (this._useLocalStorage) {
						localStorage.removeItem("TinyAutoSave");
					}
					else {
						sessionStorage.removeItem("TinyAutoSave");
					}
				}
				
				return false;
			}
			else if (this._useUserData) {
				return ((this._getUserData(this.editor) || "").length > 0);
			}
			
			return ((tinymce.util.Cookie.get(this._cookieName) || "").length > 0);
		},
		
		//************************************************************************
		// Private methods and properties
		
		_saveFinal: function () {
			/// <summary>
			/// Called just before the current page is unloaded. Performs a final save, then
			/// cleans up memory to prevent leaks.
			/// </summary>

			this._save();
			this._dispose();
		},
		
		_save: function () {
			/// <summary>
			/// Performs a single, one-time autosave. Checks to be sure there is at least the
			/// specified minimum number of characters in the editor before saving. Briefly
			/// animates the toolbar button. Enables the 'tinyautosave' button to indicate
			/// autosave content is available.
			/// </summary>
			/// <returns type="Boolean">Returns true if content was saved or false if not.</returns>

			var t = this, ed = t.editor, is = tinymce.is, execCallback = ed.execCallback, saved = false, now = new Date();
			
			if ((ed) && (!t._busy)) {
				t._busy = true;
		
				if (is(t.onPreSave, "string")) {
					if (!execCallback(t.onPreSave)) {
						t._busy = false;
						return false;
					}
				}

				var content = ed.getContent();
				
				if (is(content, "string") && (content.length >= t._minLength)) {
					var exp = new Date(now.getTime() + (t._retentionMinutes * 60 * 1000));
					
					try {
						if (t._useLocalStorage) {
							localStorage.setItem("TinyAutoSave", exp.toString() + "," + t._encodeStorage(content)); // Uses local time for expiration
						}
						else if (t._useSessionStorage) {
							sessionStorage.setItem("TinyAutoSave", exp.toString() + "," + t._encodeStorage(content)); // Uses local time for expiration
						}
						else if (t._useUserData) {
							t._setUserData(ed, content, exp);
						}
						else {
							var a = t._cookieName + "=";
							var b = "; expires=" + exp.toUTCString();
							
							document.cookie = a + t._encodeCookie(content).slice(0, 4096 - a.length - b.length) + b;
						}
						
						saved = true;
					}
					catch (e) {
						if (is(t.onSaveError, "string")) {
							execCallback(t.onSaveError);
						}
					}
					
					if (saved) {
						var cm = ed.controlManager;
						t._canRestore = true;
						cm.setDisabled('tinyautosave', false);
						
						if (t.showSaveProgress) {
							var b = tinymce.DOM.get(cm.get('tinyautosave').id), restoreImage = t._restoreImage;
							b.children[0].src = t._progressImage;
							window.setTimeout(function () {b.children[0].src = restoreImage;}, 1200);
						}
		
						if (is(t.onPostSave, "string")) {
							execCallback(t.onPostSave);
						}
					}
				}
				
				t._busy = false;
			}
			
			return saved;
		},
		
		_cookieFilter: null,
		
		_restore: function () {
			/// <summary>
			/// Called when the user clicks the 'tinyautosave' button on the toolbar.
			/// Replaces the contents of the editor with the autosaved content. If the editor
			/// contains more than just whitespace, the user is warned and given the option
			/// to abort. The autosaved content remains in storage.
			/// </summary>
			/// <remarks>
			/// Depends on the existence of the _cookieFilter property, which is used to
			/// store a regular expression so it is only built and compiled once. Also, it
			/// is never built if cookies can be avoided.
			/// </remarks>

			var t = this, ed = t.editor, content = null, is = tinymce.is, execCallback = ed.execCallback;
			
			if ((ed) && (t._canRestore) && (!t._busy)) {
				t._busy = true;
				
				if (is(t.onPreRestore, "string")) {
					if (!execCallback(t.onPreRestore)) {
						t._busy = false;
						return;
					}
				}

				try {
					if (t._useLocalStorage || t._useSessionStorage) {
						content = ((t._useLocalStorage? localStorage.getItem("TinyAutoSave") : sessionStorage.getItem("TinyAutoSave")) || "").toString();
						var i = content.indexOf(",");
						
						if (i == -1) {
							content = null;
						}
						else {
							content = t._decodeStorage(content.slice(i + 1, content.length));
						}
					}
					else if (t._useUserData) {
						content = t._getUserData(ed);
					}
					else {
					
						if (t._cookieFilter == null) {
							t._cookieFilter = new RegExp("(?:^|;\\s*)" + t._cookieName + "=([^;]*)(?:;|$)", "i");
						}
						
						var m = t._cookieFilter.exec(document.cookie);
						
						if (m) {
							content = t._decodeCookie(m[1]);
						}
					}
					
					if (!is(content, "string")) {
						ed.windowManager.alert("tinyautosave.no_content");
					}
					else {
						
						// If current contents are empty or whitespace, the confirmation is unnecessary
						if (ed.getContent().replace(/\s|&nbsp;|<\/?p[^>]*>|<br[^>]*>/gi, "").length == 0) {
							ed.setContent(content);
				
							if (is(t.onPostRestore, "string")) {
								execCallback(t.onPostRestore);
							}
						}
						else {
							ed.windowManager.confirm("tinyautosave.warning_message", function (ok) {
								if (ok) {
									ed.setContent(content);
						
									if (is(t.onPostRestore, "string")) {
										execCallback(t.onPostRestore);
									}
								}
								t._busy = false;
							}, this);
						}
					}
				}
				catch (e) {
					if (is(t.onRestoreError, "string")) {
						execCallback(t.onRestoreError);
					}
				}
				
				t._busy = false;
			}
		},
		
		_setUserData: function (ed, str, exp) {
			/// <summary>
			/// IE browsers only. Saves a string to the 'UserData' storage area.
			/// </summary>
			/// <param name="ed" type="Object" optional="false" mayBeNull="false">TinyMCE Editor instance that is the target of the autosave</param>
			/// <param name="str" type="String" optional="false" mayBeNull="false">String value to save.</param>
			/// <param name="exp" type="Date" optional="false" mayBeNull="false">Date object specifying the expiration date of the content</param>
			/// <remarks>
			/// Maximum size of the autosave data is 128K for regular Internet Web sites or
			/// 512KB for intranet sites. Total size of all data for one domain is 1MB for
			/// Internet sites and 10MB for intranet sites.
			/// </remarks>

			var ta = ed.getElement();
			
			ta.setAttribute("tinyautosave", str);
			ta.expires = exp.toUTCString();
			ta.save("TinyMCE");
		},
 
		_getUserData: function (ed) {
			/// <summary>
			/// IE browsers only. Retrieves a string from the 'UserData' storage area.
			/// </summary>
			/// <param name="ed" type="Object" optional="false" mayBeNull="false">TinyMCE Editor instance that is the target of the autosave</param>
			/// <returns type="String"></returns>

			var ta = ed.getElement();
			
			ta.load("TinyMCE");
			return ta.getAttribute("tinyautosave");
		},
		
		_removeUserData: function (ed) {
			/// <summary>
			/// IE browsers only. Removes a string from the 'UserData' storage area.
			/// </summary>
			/// <param name="ed" type="Object" optional="false" mayBeNull="false">TinyMCE Editor instance that is the target of the autosave</param>
			
			ed.getElement().removeAttribute("tinyautosave", str);
		},
 
		_encodeKey: {"%": "%1", "&": "%2", ";": "%3", "=": "%4", "<": "%5"},
		_decodeKey: {"%1": "%", "%2": "&", "%3": ";", "%4": "=", "%5": "<"},
		
		_encodeCookie: function (str) {
			/// <summary>
			/// Encodes a string value intended for storage in a cookie. Used instead of escape()
			/// to be more space-efficient and to apply some minor compression.
			/// </summary>
			/// <param name="str" type="String" optional="false" mayBeNull="false">String to encode for cookie storage</param>
			/// <returns type="String"></returns>
			/// <remarks>
			/// Depends on the existence of the _encodeKey property. Used as a lookup table.
			/// TO DO: Implement additional compression techniques.
			/// </remarks>

			var k = this._encodeKey;
			
			return str.replace(/[\x00-\x1f]+|&nbsp;|&#160;/gi, " ")
				.replace(/(.)\1{5,}|[%&;=<]/g,
					function (c) {
						if (c.length > 1) {
							return ("%0" + c.charAt(0) + c.length.toString() + "%");
						}
						return k[c];
					});
		},
		
		_decodeCookie: function (str) {
			/// <summary>
			/// Decodes a string value that was previously encoded with _encodeCookie().
			/// </summary>
			/// <param name="str" type="String" optional="false" mayBeNull="false">String that was previously encoded with _encodeCookie()</param>
			/// <returns type="String"></returns>
			/// <remarks>
			/// Depends on the existence of the _decodeKey property. Used as a lookup table.
			/// TO DO: Implement additional compression techniques.
			/// </remarks>

			var k = this._decodeKey;
			
			return str.replace(/%[1-5]|%0(.)(\d+)%/g,
				function (c, m, d) {
					
					if (c.length == 2) {
						return k[c];
					}
					
					for (var a=[], i=0, l=parseInt(d); i<l; i++) {
						a.push(m);
					}
					
					return a.join("");
				});
		},
		
		_encodeStorage: function (str) {
			/// <summary>
			/// Encodes a string value intended for storage in either localStorage or sessionStorage.
			/// </summary>
			/// <param name="str" type="String" optional="false" mayBeNull="false">String to encode for localStorage or sessionStorage</param>
			/// <returns type="String"></returns>
			/// <remarks>
			/// Necessary because a bug in Safari truncates the string at the first comma.
			/// </remarks>

			return str.replace(/,/g, "&#44;");
		},
		
		_decodeStorage: function (str) {
			/// <summary>
			/// Decodes a string value that was previously encoded with _encodeStorage().
			/// </summary>
			/// <param name="str" type="String" optional="false" mayBeNull="false">String that was previously encoded with _encodeStorage()</param>
			/// <returns type="String"></returns>

			return str.replace(/&#44;/g, ",");
		},
		
		_dispose: function () {
			/// <summary>
			/// Called just before the current page unloads. Cleans up memory, releases timers and events.
			/// </summary>
		
			if (this._timer) {
				window.clearInterval(this._timer);
			}
			
			tinymce.dom.Event.remove(window, "beforeunload", this.__saveFinal);
			this.__save = this.__saveFinal = this.__restore = this.__dispose = this._timer = this._cookieFilter = this._encodeKey = this._decodeKey = null;
		}
	});

	// Register plugin
	tinymce.PluginManager.add("tinyautosave", tinymce.plugins.TinyAutoSavePlugin);
})();
