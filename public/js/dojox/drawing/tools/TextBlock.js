define(["dojo", "dijit/registry", "../util/oo", "../manager/_registry", "../stencil/Text"],
function(dojo, dijit, oo, registry, StencilText){

	var conEdit;
	dojo.addOnLoad(function(){
		//		In order to use VML in IE, it's necessary to remove the
		//		DOCTYPE. But this has the side effect that causes a bug
		//		where contenteditable divs cannot be made dynamically.
		//		The solution is to include one in the main document
		//		that can be appended and removed as necessary:
		//		<div id="conEdit" contenteditable="true"></div>

		// console.log("Removing conedit");
		conEdit = dojo.byId("conEdit");
		if(!conEdit){
			console.error("A contenteditable div is missing from the main document. See 'dojox.drawing.tools.TextBlock'")
		}else{
			conEdit.parentNode.removeChild(conEdit);
		}
	});
	
	var TextBlock = oo.declare(
		// TODO - disable zoom while showing?

		// FIXME:
		//		Handles width: auto, align:middle, etc. but for
		//		display only, edit is out of whack

		StencilText,
		function(options){
			// summary:
			//		constructor

			if(options.data){
				var d = options.data;
				var text = d.text ? this.typesetter(d.text) : d.text;
				var w = !d.width ? this.style.text.minWidth : d.width=="auto" ? "auto" : Math.max(d.width, this.style.text.minWidth);
				var h = this._lineHeight;
				
				if(text && w=="auto"){
					var o = this.measureText(this.cleanText(text, false), w);
					w = o.w;
					h = o.h;
				}else{
					// w = this.style.text.minWidth;
					this._text = "";
				}
				
				this.points = [
					{x:d.x, y:d.y},
					{x:d.x+w, y:d.y},
					{x:d.x+w, y:d.y+h},
					{x:d.x, y:d.y+h}
				];
				
				if(d.showEmpty || text){
					this.editMode = true;
					
				
					dojo.disconnect(this._postRenderCon);
					this._postRenderCon = null;
					this.connect(this, "render", this, "onRender", true);
					
					if(d.showEmpty){
						this._text = text || "";
						this.edit();
					}else if(text && d.editMode){
						this._text = "";
						this.edit();
					}else if(text){
						this.render(text);
					}
					setTimeout(dojo.hitch(this, function(){
						this.editMode = false;
					}),100)
					
				}else{
					// Why make it if it won't render...
					this.render();
				}
				
			}else{
				this.connectMouse();
				this._postRenderCon = dojo.connect(this, "render", this, "_onPostRender");
			}
			//console.log("TextBlock:", this.id)
		},
		{
			// summary:
			//		A tool to create text fields on a canvas.
			// description:
			//		Extends stencil.Text by adding an HTML layer that
			//		can be dragged out to a certain size, and accept
			//		a text entry. Will wrap text to the width of the
			//		html field.
			//		When created programmtically, use 'auto' to shrink
			//		the width to the size of the text. Use line breaks
			//		( \n ) to create new lines.

			draws:true,
			baseRender:false,
			type:"dojox.drawing.tools.TextBlock",
			_caretStart: 0,
			_caretEnd: 0,
			_blockExec: false,
			
/*=====
StencilData: {
	// summary:
	//		The data used to create the dojox.gfx Text
	// x: Number
	//		Left point x
	// y: Number
	//		Top point y
	// width: Number|String?
	//		Optional width of Text. Not required but recommended.
	//		for auto-sizing, use 'auto'
	// height: Number?
	//		Optional height of Text. If not provided, _lineHeight is used.
	// text: String
	//		The string content. If not provided, may auto-delete depending on defaults.
},
=====*/
			
			// selectOnExec: Boolean
			//		Whether the Stencil is selected when the text field
			//		is executed or not
			selectOnExec:true,

			// showEmpty: Boolean
			//		If true and there is no text in the data, the TextBlock
			//		Is displayed and focused and awaits input.
			showEmpty: false,
			
			onDrag: function(/*EventObject*/obj){
				if(!this.parentNode){
					this.showParent(obj);
				}
				var s = this._startdrag, e = obj.page;
				this._box.left = (s.x < e.x ? s.x : e.x);
				this._box.top = s.y;
				this._box.width = (s.x < e.x ? e.x-s.x : s.x-e.x) + this.style.text.pad;
				
				dojo.style(this.parentNode, this._box.toPx());
			},
			
			onUp: function(/*EventObject*/obj){
				if(!this._downOnCanvas){ return; }
				this._downOnCanvas = false;
				
				var c = dojo.connect(this, "render", this, function(){
					dojo.disconnect(c);
					this.onRender(this);
					
				});
				this.editMode = true;
				this.showParent(obj);
				this.created = true;
				this.createTextField();
				this.connectTextField();
			},
			
			showParent: function(/*EventObject*/obj){
				// summary:
				//		Internal. Builds the parent node for the
				//		contenteditable HTML node.

				if(this.parentNode){ return; }
				var x = obj.pageX || 10;
				var y = obj.pageY || 10;
				this.parentNode = dojo.doc.createElement("div");
				this.parentNode.id = this.id;
				var d = this.style.textMode.create;
				this._box = {
					left:x,
					top:y,
					width:obj.width || 1,
					height:obj.height && obj.height>8 ? obj.height : this._lineHeight,
					border:d.width+"px "+d.style+" "+d.color,
					position:"absolute",
					zIndex:500,
					toPx: function(){
						var o = {};
						for(var nm in this){
							o[nm] = typeof(this[nm])=="number" && nm!="zIndex" ? this[nm] + "px" : this[nm];
						}
						return o;
					}
				};
				
				dojo.style(this.parentNode, this._box);
				
				document.body.appendChild(this.parentNode);
			},
			createTextField: function(/*String*/txt){
				// summary:
				//		Internal. Inserts the contenteditable HTML node
				//		into its parent node, and styles it.

				// style parent
				var d = this.style.textMode.edit;
				this._box.border = d.width+"px "+d.style+" "+d.color;
				this._box.height = "auto";
				this._box.width = Math.max(this._box.width, this.style.text.minWidth*this.mouse.zoom);
				dojo.style(this.parentNode, this._box.toPx());
				// style input
				this.parentNode.appendChild(conEdit);
				dojo.style(conEdit, {
					height: txt ? "auto" : this._lineHeight+"px",
					fontSize:(this.textSize/this.mouse.zoom)+"px",
					fontFamily:this.style.text.family
				});
				// FIXME:
				// In Safari, if the txt ends with '&' it gets stripped
				conEdit.innerHTML = txt || "";
				
				return conEdit; //HTMLNode
			},
			connectTextField: function(){
				// summary:
				//		Internal. Creates the connections to the
				//		contenteditable HTML node.

				if(this._textConnected){ return; } // good ol' IE and its double events
				// FIXME:
				// Ouch-getting greekPalette by id.  At the minimum this should
				// be from the plugin manager
				var greekPalette = dijit.byId("greekPalette");
				var greekHelp = greekPalette==undefined ? false : true;
				if(greekHelp){
					//set it up
					dojo.mixin(greekPalette,{
						_pushChangeTo: conEdit,
						_textBlock: this
					});
				};
				
				this._textConnected = true;
				this._dropMode = false;
				this.mouse.setEventMode("TEXT");
				this.keys.editMode(true);
				var kc1, kc2, kc3, kc4, self = this, _autoSet = false,
					exec = function(){
						if(self._dropMode){ return; }
						dojo.forEach([kc1,kc2,kc3,kc4], function(c){
							dojo.disconnect(c)
						});
						self._textConnected = false;
						self.keys.editMode(false);
						self.mouse.setEventMode();
						self.execText();
					};
					
				kc1 = dojo.connect(conEdit, "keyup", this, function(evt){
					// 	if text is empty, we need a height so the field's height
					//	doesn't collapse
					if(dojo.trim(conEdit.innerHTML) && !_autoSet){
						dojo.style(conEdit, "height", "auto"); _autoSet = true;
					}else if(dojo.trim(conEdit.innerHTML).length<2 && _autoSet){
						dojo.style(conEdit, "height", this._lineHeight+"px"); _autoSet = false;
					}
					
					if(!this._blockExec){
						if(evt.keyCode==13 || evt.keyCode==27){
							dojo.stopEvent(evt);
							exec();
						}
					} else {
						if(evt.keyCode==dojo.keys.SPACE){
							dojo.stopEvent(evt);
							greekHelp && greekPalette.onCancel();
						}
					}
				});
				kc2 = dojo.connect(conEdit, "keydown", this, function(evt){
					if(evt.keyCode==13 || evt.keyCode==27){ // TODO: make escape an option
						dojo.stopEvent(evt);
					}
					// if backslash, user is inputting a special character
					// This g