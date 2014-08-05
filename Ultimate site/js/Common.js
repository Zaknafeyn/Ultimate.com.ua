/**
 * Common functions
 * -----------------------------------------------
 * @author        Filatov Dmitry <alpha@design.ru>
 * @version       0.12, 11.04.2007 
 */

var Common = {

	// Common classes methods
	
	Class : {
		
		match : function(
			oElement,
			sClassName
			) {
	
			return (oElement.className && oElement.className.match(new RegExp('(^|\\s+)(' + sClassName + ')($|\\s+)')))? true : false;	
		
		},
		
		add : function(
			oElement,
			sClassName
			) {

			if(!Common.Class.match(oElement, sClassName)) {
				oElement.className += ' ' + sClassName;
			}
			
		},
		
		replace : function(
			oElement,
			sClassNameFrom,
			sClassNameTo
			) {
			
			if(Common.Class.match(oElement, sClassNameFrom)) {
				oElement.className = (oElement.className.replace( new RegExp("(^|\\s+)(" + sClassNameFrom + "|" + sClassNameTo + ")($|\\s+)", "g"), "$1" ) + ' ' + sClassNameTo ).replace( /^\s+/, '');
			}
			else {
				Common.Class.add(oElement, sClassNameTo);
			}			
			
		},
		
		remove : function(
			oElement,
			sClassName
			) {
	
			oElement.className = oElement.className.replace(new RegExp('(.*)(^|\\s+)(' + sClassName + ')($|\\s+)(.*)'), '$1$4$5').replace(/(^)\s/, '$1');	
			
		}
		
	},
		
	
	// Common event's methods
	
	Event : {
	
		aObservers : [],

		add : function(
			mElement,
			mEventType,
			fEventFunction
			) {
						
			if(mElement.length && mElement.sort) {
			
				for(var i = 0; i < mElement.length; i++) {
					this.add(mElement[i], mEventType, fEventFunction);
				}
				
				return;
				
			}
			
			if(!mEventType.match && mEventType.length) {
				
				for(var i = 0; i < mEventType.length; i++) {
					this.add(mElement, mEventType[i], fEventFunction);
				}
								
				return;				
				
			}			
			
			if(mElement.addEventListener) {							
				mElement.addEventListener(
					mEventType,
					fEventFunction,
					false		
					);
			}
			else if(mElement.attachEvent) {				
							
				if(this.aObservers.length == 0) {
					
					this.aObservers.push([mElement, mEventType, fEventFunction]);
									
					this.add(
						window,
						'unload',
						Common.Event.detachObservers
						);
					
				}
				else {
					this.aObservers.push([mElement, mEventType, fEventFunction]);
				}
					
				mElement.attachEvent('on' + mEventType, fEventFunction);				
				
				
			}
	
		},	

		remove : function(
			mElement,
			mEventType,
			fEventFunction
			) {
			
			if(mElement.length && mElement.sort) {
				
				for(var i = 0; i < mElement.length; i++) {
					this.remove(mElement[i], mEventType, fEventFunction);
				}				
				
				return;				
				
			}
			
			if(!mEventType.match && mEventType.length) {
				
				for(var i = 0; i < mEventType.length; i++) {
					this.remove(mElement, mEventType[i], fEventFunction);
				}				
				
				return;				
				
			}			
		
			if(mElement.removeEventListener) {
				mElement.removeEventListener(
					mEventType,
					fEventFunction,
					false
					);
			}
			else if(mElement.detachEvent) {			
				mElement.detachEvent(
					'on' + mEventType,
					fEventFunction
					);									
			}
			
		},
		
		detachObservers : function() {			
			
			for(var i = 0, iLength = Common.Event.aObservers.length; i < iLength; i++) {	
				
				Common.Event.remove(
					Common.Event.aObservers[i][0],
					Common.Event.aObservers[i][1],
					Common.Event.aObservers[i][2]
					);
					
				Common.Event.aObservers[i][0] = null;
					
			}					
			
			Common.Event.aObservers.length = 0;
			
		},
		
		cancel : function(oEvent) {
					
			var oEvent = oEvent? oEvent : window.event;
			
			oEvent.cancelBubble = true;
			oEvent.returnValue = false;
			
			if(oEvent.cancelable){
				
				oEvent.preventDefault();
				oEvent.stopPropagation();
				
			}
			
			return false;
			
		},
		
		normalize : function(oEvent) {
						
			var oEvent = oEvent? oEvent : window.event;
			
			if(oEvent && oEvent.srcElement && !window.opera) {									
				oEvent.target = oEvent.srcElement;				
			}
			
			if(oEvent){
				
				oEvent.iKeyCode = oEvent.keyCode?
					oEvent.keyCode :
					(oEvent.which? oEvent.which : null)
					;
							
				if(oEvent.wheelDelta) {
									
					oEvent.iMouseWheelDelta = oEvent.wheelDelta / 120;
					
					if(window.opera) {
						oEvent.iMouseWheelDelta *= -1;
					}
					
				}
				else if(oEvent.detail) {
					oEvent.iMouseWheelDelta = -oEvent.detail / 3;
				}
				
			}
			
			return oEvent;
		
		},
		
		getAbsoluteCoords : function(oEvent) {

			var
				oEvent = oEvent? oEvent : window.event,
				oResult = {
					iLeft : 0,
					iTop  : 0
					}
				;

			if(oEvent.pageX || oEvent.pageY) {
  
				oResult.iLeft = oEvent.pageX;
				oResult.iTop = oEvent.pageY;
				
			}
			else if(oEvent.clientX || oEvent.clientY) {
	
				oResult.iLeft = oEvent.clientX + document.body.scrollLeft - document.body.clientLeft;
				oResult.iTop = oEvent.clientY + document.body.scrollTop - document.body.clientTop;

				if(document.body.parentElement && document.body.parentElement.clientLeft) {
		
					var oBodyParent = document.body.parentElement;
		
					oResult.iLeft += oBodyParent.scrollLeft - oBodyParent.clientLeft;
					oResult.iTop += oBodyParent.scrollTop - oBodyParent.clientTop;  
			
				}
		
			}
			
			return oResult;
			
		}
		
	},	
	
	
	// Common DOM's methods
	
	Dom : {
			
		NODE_TYPE_ELEMENT : 1,
		NODE_TYPE_TEXT    : 3,
		
		getAbsoluteCoords : function(oElement) {
			
			var oResult = {
				iTop  : 0,
				iLeft : 0
				};
	
			while(oElement) {
	
				oResult.iTop += oElement.offsetTop;
				oResult.iLeft += oElement.offsetLeft;
		
				oElement = oElement.offsetParent;
		
			}
		
			return oResult;
			
		},				
				
		getAttribute : function(
			oElement,
			sName
			) {			
				
			if(oElement.attributes) {			
				for(var i = 0, iLength = oElement.attributes.length; i < iLength; i++){
					if(oElement.attributes[i].nodeName == sName) {
						return oElement.attributes[i].nodeValue;
					}				
				}				
			}
			
			return oElement.getAttribute(sName);

		},
		
		getElementsByClassName : function(
			oElement,
			sClassName,
			sTagName,
			bRecursion
			) {
				
			var
				aResult = [],
				sTagName = sTagName || '*';
				;
			
			if(!bRecursion) {
			
				if(document.evaluate) {				
					
					oQueryResult = document.evaluate(
						'.//' + sTagName + '[contains(concat(\' \', @class, \' \'), \' ' + sClassName + ' \')]',
						oElement,
						null,
						XPathResult.ORDERED_NODE_SNAPSHOT_TYPE,
						null
						);
				
					for(var i = 0, iLength = oQueryResult.snapshotLength; i < iLength; i++) {
						aResult.push(oQueryResult.snapshotItem(i));
					}
				 
				 	return aResult;
				
				}												
			
				var aDescendants = oElement.getElementsByTagName(sTagName);
			
				if(aDescendants.length > 0 ||
					sTagName != '*'
					) {
					
					for(var i = 0, iLength = aDescendants.length, oDescendant; i < iLength; i++) {
					
						oDescendant = aDescendants[i];
					
						if(Common.Class.match(oDescendant, sClassName)) {
							aResult.push(oDescendant);						
						}
							
					}
				
					return aResult;
					
				}
				
			}
			
			for(var i = 0, iLength = oElement.childNodes.length, oChild; i < iLength; i++) {
				
				oChild = oElement.childNodes[i];
				
				if(oChild.nodeType != Common.Dom.NODE_TYPE_ELEMENT) {
					continue;
				}
				
				if(Common.Class.match(oChild, sClassName)) {
					aResult.push(oChild);						
				}
				
				aResult = aResult.concat(
					this.getElementsByClassName(
						oChild,
						sClassName,
						sTagName,			
						true
						)
					);
				
			}
			
			return aResult;
				
		}
		
	},
		
	// Common cookie's methods
	
	Cookie : {
	
		set : function(sName, sValue, sExpire, sPath) {
		
			document.cookie = sName + '=' + (window.encodeURI? encodeURI(sValue) : escape(sValue)) +
				((sExpire == null)? '' : ('; expires=' + sExpire.toGMTString())) +
				((sPath == null)? '' : ('; path=' + sPath));
		
		},
		
		get : function(sName) {
		
			var sSearch = sName + '=';
			
			if(document.cookie.length > 0) {
			
				var iOffset = document.cookie.indexOf(sSearch);
				
				if(iOffset != -1) {
				
					iOffset += sSearch.length;
			
					var iEnd = document.cookie.indexOf(';', iOffset);
			
					if(iEnd == -1) {
						iEnd = document.cookie.length;
					}
					
					return window.decodeURI?
						decodeURI(document.cookie.substring(iOffset, iEnd)) :
						unescape(document.cookie.substring(iOffset, iEnd))
						;
					
				}
			}
			
			return '';			
		
		}
	
	},
	
	
	// Common object's methods

	Object : {
				
		extend : function(			
			oDestination,
			oSource,
			bReplace
			) {				
		
			for(var i in oSource) {
				if(bReplace || !oDestination[i]) {
					oDestination[i] = oSource[i];
				}
			}
		
			return oDestination;
		
		}
		
	},
	

	Utils : {
	
		oPopupDefaults : {	
	
			iWidth      : 540,
			iHeight     : 600,
			sToolbar    : 'no',
			sMenubar    : 'no',
			sResizeable : 'yes',
			sScrollbars : 'yes',
			sStatus     : 'yes'
	
		},
	
		popup : function(
			sUrl,
			sName,
			oOptions,
			bReplace
			) {
		
			oOptions = Common.Object.extend(
				Common.Utils.oPopupDefaults,
				oOptions,
				true
				);
		
			var iLeftOffset = screen.availWidth / 2 - oOptions.iWidth / 2;
			var iTopOffset = screen.availHeight / 2 - oOptions.iHeight / 2;			
		
			oNewWindow = window.open(
				sUrl,
				sName,
				'left=' + iLeftOffset + ', ' +
				'top = ' + iTopOffset + ', ' +
				'width=' + oOptions.iWidth + ', ' +
				'height=' + oOptions.iHeight + ', ' +
				'menubar=' + oOptions.sMenubar + ', ' +
				'toolbar=' + oOptions.sToolbar + ', ' +
				'resizable=' + oOptions.sResizeable + ', ' +
				'scrollbars=' + oOptions.sScrollbars + ', ' +
				'status=' + oOptions.sStatus
				);
			
			if(sUrl.match(/\.(gif|jpe?g|png)$/i)) {
		
				oNewWindow.document.open();
			
				oNewWindow.document.write('<html><head></head><body style="background: #FFF; margin: 0px; padding: 0px;">' +
					'<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"><tr><td align="center">' + 
					'<img src="' + sUrl + '" />' +
					'</td></tr></table></body></html>'
					);
				
				oNewWindow.document.close();
		
			}
			
			oNewWindow.focus();				

			return false;
			
		},
		
		aNavigationLinks : [
			{										
				sRel     : 'next',
				iKeyCode : 0x25,	
				sHref    : ''
			},
			{
				sRel     : 'prev',
				iKeyCode : 0x27,	
				sHref    : ''
			},
			{
				sRel     : 'up',
				iKeyCode : 0x26,	
				sHref    : ''
			},
			{
				sRel     : 'down',			
				iKeyCode : 0x28,
				sHref    : ''
			},
			{
				sRel     : 'home',			
				iKeyCode : 0x24,	
				sHref    : '/'
			}
		],
		
		keyNavigationInit : function() {
			
			var aLinkElements = document.getElementsByTagName('link');						
			
			for(var i = 0, sRel; i < aLinkElements.length; i++) {							
				
				sRel = aLinkElements[i].rel;
				
				for(var j = 0; j < this.aNavigationLinks.length; j++) {
					if(this.aNavigationLinks[j].sRel == sRel) {
											
						this.aNavigationLinks[j].sHref = aLinkElements[i].href;
						break;
						
					}
				}												
				
			}
			
			Common.Event.add(
				document,
				'keydown',
				function(oEvent) {					
					
					var oEvent = Common.Event.normalize(oEvent);
					
					if(!oEvent.ctrlKey) {
						return true;
					}
					
					var aLinks = Common.Utils.aNavigationLinks;
					
					for(var i = 0; i < aLinks.length; i++) {
						if(aLinks[i].iKeyCode == oEvent.iKeyCode &&
							aLinks[i].sHref != ''
							) {								
							document.location = aLinks[i].sHref;
						}
					}
					
				}
				);
			
		}
		
	}

}


/* Extensions */

Common.Object.extend(
	Function.prototype,
	{
		
		call : function() {
		
			var
				oObject = arguments[0],
				aArguments = [],
				oResult
				;	
		
			oObject.fFunction = this;
			
			for(var i = 1; i < arguments.length; i++) {
				aArguments[aArguments.length] = 'arguments[' + i + ']';		
			}
		
			eval('oResult = oObject.fFunction(' + aArguments.join(',') + ')');
		
			oObject.fFunction = null;
		
			return oResult;
			
		},

		apply : function() {
    
			var
				oContext,
				oOldProperty,
				aArguments = [],
				oResult			
				;
				    	
    		oContext = arguments.length == 0? window : arguments[0];    	
    	
    		oOldProperty = oContext.__temp;
    	
    		oContext.__temp = this;
    
    		for(var i = 0; i < arguments[1].length; i++) {
    			aArguments[aArguments.length] = 'arguments[1][' + i + ']';
    		}
    	
    		oResult = eval('oContext.__temp(' + aArguments.join(',') + ')');
    	
    		oContext.__temp = oOldProperty;
    	
    		return oResult;
    
		},

		inheritFrom : function(
			BaseClass,
			oOverride
			) {
		
			var Inheritance = function() {};
			
			Inheritance.prototype = BaseClass.prototype;

			this.prototype = new Inheritance();
			this.prototype.constructor = this;
			this.baseConstructor = BaseClass;
			this.superClass = BaseClass.prototype;
			
			if(oOverride) {
				Common.Object.extend(
					this.prototype,
					oOverride,
					true
					);
			}
			
		}
	
	}
	);
		
Common.Object.extend(
	Array.prototype,
	{
		
		push : function() {				
				
			for(var i = 0; i < arguments.length; i++) {			
				this[this.length] = arguments[i];
			}		
		
		},
	
		splice : function() {
						
			if(arguments[0] > this.length - 1) {			
				return;
			}
		
			var iRemovedCount = arguments[0] + arguments[1] > this.length?
				this.length - arguments[0] :
				arguments[1]
				;
		
			if(iRemovedCount > 0) {
					
				for(var i = arguments[0]; i < this.length; i++) {			
					this[i] = this[i + iRemovedCount];			
				}
		
				this.length = this.length - iRemovedCount;
			
			}
		
			var iNewCount = arguments.length - 2;
						
			if(iNewCount > 0) {
			
				var
					iLastIndex = this.length - 1,
					iNewLastIndex = iLastIndex + iNewCount
					;			
			
				for(var i = iLastIndex, j = 0; i >= arguments[0]; i--, j++) {				
					this[iNewLastIndex - j] = this[i];								
				}
			
				for(var i = 0; i < iNewCount; i++) {									
					this[arguments[0] + i] = arguments[i + 2];		
				}
			
			}				
		
		},

		remove : function(mElement) {
	
			for(var i = 0; i < this.length; i++) {
		
				if(this[i] == mElement){
			
					this.splice(i, 1);
					break;
					
				}
			
			}
		
		},

		indexOf : function(mElement) {
	
			var iFoundedIndex = -1;
		
			for(var i = 0; i < this.length; i++) {
		
				if(this[i] == mElement) {
				
					iFoundedIndex = i;
					break;
			
				}
		
			}
	
			return iFoundedIndex;
	
		},
		
		contains : function(mElement) {
	
			return this.indexOf(mElement) > -1;
	
		},

		intersect : function(aArrayWith) {
	
			var aResult = [];
	
			for(var i = 0; i < aArrayWith.length; i++) {
				if(this.indexOf(aArrayWith[i]) > -1) {
					aResult.push(aArrayWith[i]);
				}
			}
	
			return aResult;
			
		},

		union : function(aArrayWith) {
	
			var aResult = [].concat(this);
	
			for(var i = 0; i < aArrayWith.length; i++) {
				if(this.indexOf(aArrayWith[i]) == -1) {
					aResult.push(aArrayWith[i]);
				}
			}
	
			return aResult;
			
		},

		foreach : function(fFunction) {
	
			for(var mKey in this) {
				if(this.hasOwnProperty(mKey)) {
					fFunction(mKey, this[mKey]);
				}
			}
			
		}
	}
	);
	
Common.Object.extend(
	Object.prototype,
	{
  	
		hasOwnProperty : function(sPropertyName) {
  	
    		return !(
    			'undefined' == typeof this[sPropertyName] ||
    			this.constructor &&
    			this.constructor.prototype[sPropertyName] &&
    			this[sPropertyName] === this.constructor.prototype[sPropertyName]
    			);
    			
		}
		
	}
	);
	
Common.Event.add(
	window,
	'load',
	function() {
		
		Common.Utils.keyNavigationInit();
		
	}
	);