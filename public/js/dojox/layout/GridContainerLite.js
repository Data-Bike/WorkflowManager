define([
	"dojo/_base/kernel",
	"dojo/text!./resources/GridContainer.html",
	"dojo/_base/declare", // declare
	"dojo/query",
	"dojo/_base/sniff",
	"dojo/dom-class",
	"dojo/dom-style",
	"dojo/dom-geometry",
	"dojo/dom-construct",
	"dojo/dom-attr", // domAttr.get
	"dojo/_base/array",
	"dojo/_base/lang",
	"dojo/_base/event",
	"dojo/keys", // keys
	"dojo/topic", // topic.publish()
	"dijit/registry",
	"dijit/focus",
	"dijit/_base/focus", // dijit.getFocus()
	"dijit/_WidgetBase",
	"dijit/_TemplatedMixin",
	"dijit/layout/_LayoutWidget",
	"dojo/_base/NodeList",
	"dojox/mdnd/AreaManager", "dojox/mdnd/DropIndicator",
	"dojox/mdnd/dropMode/OverDropMode","dojox/mdnd/AutoScroll"
],function(dojo, template, declare, query, has, domClass, domStyle, geom, domConstruct, domAttr, array, lang, events, keys, topic, registry, focus, baseFocus, _WidgetBase, _TemplatedMixin, _LayoutWidget, NodeList){

	var gcl = declare(
		"dojox.layout.GridContainerLite",
		[_LayoutWidget, _TemplatedMixin],
	{
		// summary:
		//		The GridContainerLite is a container of child elements that are placed in a kind of grid.
		//
		// description:
		//		GridContainerLite displays the child elements by column
		//		(ie: the children widths are fixed by the column width of the grid but
		//		the children heights are free).
		//		Each child is movable by drag and drop inside the GridContainer.
		//		The position of other children is automatically calculated when a child is moved.
		//
		// example:
		// 	|	<div dojoType="dojox.layout.GridContainerLite" nbZones="3" isAutoOrganized="true">
		// 	|		<div dojoType="dijit.layout.ContentPane">Content Pane 1 : Drag Me !</div>
		// 	|		<div dojoType="dijit.layout.ContentPane">Content Pane 2 : Drag Me !</div>
		// 	|		<div dojoType="dijit.layout.ContentPane">Content Pane 3 : Drag Me !</div>
		// 	|	</div>
		//
		// example:
		// 	|	dojo.ready(function(){
		// 	|		var cpane1 = new dijit.layout.ContentPane({
		//	|			title:"cpane1", content: "Content Pane 1 : Drag Me !"
		//	|		}),
		// 	|		cpane2 = new dijit.layout.ContentPane({
		//	|			title:"cpane2",
		//	|			content: "Content Pane 2 : Drag Me !"
		//	|		}),
		// 	|		cpane3 = new dijit.layout.ContentPane({
		//	|			title:"cpane3",
		//	|			content: "Content Pane 3 : Drag Me !"
		//	|		});
		// 	|
		// 	|		var widget = new dojox.layout.GridContainerLite({
		// 	|			nbZones: 3,
		// 	|			isAutoOrganized: true
		// 	|		}, dojo.byId("idNode"));
		// 	|		widget.addChild(cpane1, 0, 0);
		// 	|		widget.addChild(cpane2, 1, 0);
		// 	|		widget.addChild(cpane3, 2, 1);
		// 	|		widget.startup();
		// 	|	});

		// autoRefresh: Boolean
		//		Enable the refresh of registered areas on drag start.
		autoRefresh: true,


		// templateString: String
		//		template of gridContainer.
		templateString: template,

		// dragHandleClass: Array
		//		CSS class enabling a drag handle on a child.
		dragHandleClass: "dojoxDragHandle",

		// nbZones: Integer
		//		The number of dropped zones, by default 1.
		nbZones: 1,

		// doLayout: Boolean
		//		If true, change the size of my currently displayed child to match my size.
		doLayout: true,

		// isAutoOrganized: Boolean
		//		If true, widgets are organized automatically,
		//		else the attribute colum of child will define the right column.
		isAutoOrganized: true,

		// acceptTypes: Array
		//		The GridContainer will only accept the children that fit to the types.
		acceptTypes: [],

		// colWidths: String
		//		A comma separated list of column widths. If the column widths do not add up
		//		to 100, the remaining columns split the rest of the width evenly
		//		between them.
		colWidths: "",

		constructor: function(/*Object*/props, /*DOMNode*/node){
			this.acceptTypes = (props || {}).acceptTypes || ["text"];
			this._disabled = true;
		},

		postCreate: function(){
			//console.log("dojox.layout.GridContainerLite ::: postCreate");
			this.inherited(arguments);
			this._grid = [];

			this._createCells();

			// need to resize dragged child when it's dropped.
			this.subscribe("/dojox/mdnd/drop", "resizeChildAfterDrop");
			this.subscribe("/dojox/mdnd/drag/start", "resizeChildAfterDragStart");

			this._dragManager = dojox.mdnd.areaManager();
			// console.info("autorefresh ::: ", this.autoRefresh);
			this._dragManager.autoRefresh = this.autoRefresh;

			//	Add specific dragHandleClass to the manager.
			this._dragManager.dragHandleClass = this.dragHandleClass;

			if(this.doLayout){
				this._border = {
					h: has("ie") ? geom.getBorderExtents(this.gridContainerTable).h : 0,
					w: (has("ie") == 6) ? 1 : 0
				};
			}else{
				domStyle.set(this.domNode, "overflowY", "hidden");
				domStyle.set(this.gridContainerTable, "height", "auto");
			}
		},

		startup: function(){
			//console.log("dojox.layout.GridContainerLite ::: startup");
			if(this._started){ return; }

			if(this.isAutoOrganized){
				this._organizeChildren();
			}
			else{
				this._organizeChildrenManually();
			}

			// Need to call getChildren because getChildren return null
			// The children are not direct children because of _organizeChildren method
			array.forEach(this.getChildren(), function(child){
			  child.startup();
			});

			// Need to enable the Drag And Drop only if the GridContainer is visible.
			if(this._isShown()){
				this.enableDnd();
			}
			this.inherited(arguments);
		},

		resizeChildAfterDrop: function(/*Node*/node, /*Object*/targetArea, /*Integer*/indexChild){
			// summary:
			//		Resize the GridContainerLite inner table and the dropped widget.
			// description:
			//		These components are resized only if the targetArea.node is a
			//		child of this instance of gridContainerLite.
			//		To be resized, the dropped node must have also a method resize.
			// node:
			//		domNode of dropped widget.
			// targetArea:
			//		AreaManager Object containing information of targetArea
			// indexChild:
			//		Index where the dropped widget has been placed
			// returns:
			//		True if resized.

			//console.log("dojox.layout.GridContainerLite ::: resizeChildAfterDrop");
			if(this._disabled){
				return false;
			}
			if(registry.getEnclosingWidget(targetArea.node) == this){
				var widget = registry.byNode(node);
				if(widget.resize && lang.isFunction(widget.resize)){
					widget.resize();
				}

				// Update the column of the widget
				widget.set("column", node.parentNode.cellIndex);

				if(this.doLayout){
					var domNodeHeight = this._contentBox.h,
						divHeight = geom.getContentBox(this.gridContainerDiv).h;
					if(divHeight >= domNodeHeight){
						domStyle.set(this.gridContainerTable, "height",
								(domNodeHeight - this._border.h) + "px");
					}
				}
				return true;
			}
			return false;
		},

		resizeChildAfterDragStart: function(/*Node*/node, /*Object*/sourceArea, /*Integer*/indexChild){
			// summary:
			//		Resize the GridContainerLite inner table only if the drag source
			//		is a child of this gridContainer.
			// node:
			//		domNode of dragged widget.
			// sourceArea:
			//		AreaManager Object containing information of sourceArea
			// indexChild:
			//		Index where the dragged widget has been placed

			//console.log("dojox.layout.GridContainerLite ::: resizeChildAfterDragStart");
			if(this._disabled){
				return false;
			}
			if(registry.getEnclosingWidget(sourceArea.node) == this){
				this._draggedNode = node;
				if(this.doLayout){
					geom.setMarginBox(this.gridContainerTable, {
						h: geom.getContentBox(this.gridContainerDiv).h - this._border.h
					});
				}
				return true;
			}
			return false;
		},

		getChildren: function(){
			// summary:
			//		A specific method which returns children after they were placed in zones.
			// returns:
			//		A NodeList containing all children (widgets).
			// tags:
			//		protected

			var children = new NodeList();
			array.forEach(this._grid, function(dropZone){
				query("> [widgetId]", dropZone.node).map(registry.byNode).forEach(function(item){
				  children.push(item);
				});
			});
			return children;	// Array
		},

		_isShown: function(){
			// summary:
			//		Check if the domNode is visible or not.
			// returns:
			//		true if the content is currently shown
			// tags:
			//		protected

			//console.log("dojox.layout.GridContainerLite ::: _isShown");
			if("open" in this){		// for TitlePane, etc.
				return this.open;		// Boolean
			}
			else{
				var node = this.domNode;
				return (node.style.display != 'none') && (node.style.visibility != 'hidden') && !domClass.contains(node, "dijitHidden"); // Boolean
			}
		},

		layout: function(){
			// summary:
			//		Resize of each child

			//console.log("dojox.layout.GridContainerLite ::: layout");
			if(this.doLayout){
				var contentBox = this._contentBox;
				geom.setMarginBox(this.gridContainerTable, {
					h: contentBox.h - this._border.h
				});
				geom.setContentSize(this.domNode, {
					w: contentBox.w - this._border.w
				});
			}
			array.forEach(this.getChildren(), function(widget){
				if(widget.resize && lang.isFunction(widget.resize)){
					widget.resize();
				}
			});
		},

		onShow: function(){
			// summary:
			//		Enabled the Drag And Drop if it's necessary.

			//console.log("dojox.layout.GridContainerLite ::: onShow");
			if(this._disabled){
				this.enableDnd();
			}
		},

		onHide: function(){
			// summary:
			//		Disabled the Drag And Drop if it's necessary.

			//console.log("dojox.layout.GridContainerLite ::: onHide");
			if(!this._disabled){
				this.disableDnd();
			}
		},

		_createCells: function(){
			// summary:
			//		Create the columns of the GridContainer.
			// tags:
			//		protected

			//console.log("dojox.layout.GridContainerLite ::: _createCells");
			if(this.nbZones === 0){ this.nbZones = 1; }
			var accept = this.acceptTypes.join(","),
				i = 0;

			var widths = this._computeColWidth();

			while(i < this.nbZones){
				// Add the parameter accept in each zone used by AreaManager
				// (see method dojox.mdnd.AreaManager:registerByNode)
				this._grid.push({
					node: domConstruct.create("td", {
						'class': "gridContainerZone",
						accept: accept,
						id: this.id + "_dz" + i,
						style: {
							'width': widths[i] + "%"
						}
					}, this.gridNode)
				});
				i++;
			}
		},

		_getZonesAttr: function(){
			// summary:
			//		return array of zone (domNode)
			return query(".gridContainerZone",  this.containerNode);
		},

		enableDnd: function(){
			// summary:
			//		Enable the Drag And Drop for children of GridContainer.

			//console.log("dojox.layout.GridContainerLite ::: enableDnd");
			var m = this._dragManager;
			array.forEach(this._grid, function(dropZone){
				m.registerByNode(dropZone.node);
			});
			m._dropMode.updateAreas(m._areaList);
			this._disabled = false;
		},

		disableDnd: function(){
			// summary:
			//		Disable the Drag And Drop for children of GridContainer.

			//console.log("dojox.layout.GridContainerLite ::: disableDnd");
			var m = this._dragManager;
			array.forEach(this._grid, function(dropZone){
				m.unregister(dropZone.node);
			});
			m._dropMode.updateAreas(m._areaList);
			this._disabled = true;
		},

		_organizeChildren: function(){
			// summary:
			//		List all zones and insert child into columns.

			//console.log("dojox.layout.GridContainerLite ::: _organizeChildren");
			var children = dojox.layout.GridContainerLite.superclass.getChildren.call(this);
			var numZones = this.nbZones,
				numPerZone = Math.floor(children.length / numZones),
				mod = children.length % numZones,
				i = 0;
	//		console.log('numPerZone', numPerZone, ':: mod', mod);
			for(var z = 0; z < numZones; z++){
				for(var r = 0; r < numPerZone; r++){
					this._insertChild(children[i], z);
					i++;
				}
				if(mod > 0){
					try{
						this._insertChild(children[i], z);
						i++;
					}
					catch(e){
						console.error("Unable to insert child in GridContainer", e);
					}
					mod--;
				}
				else if(numPerZone === 0){
					break;	// Optimization
				}
			}
		},

		_organizeChildrenManually: function (){
			// summary:
			//		Organize children by column property of widget.

			//console.log("dojox.layout.GridContainerLite ::: _organizeChildrenManually");
			var children = dojox.layout.GridContainerLite.superclass.getChildren.call(this),
				length = children.length,
				child;
			for(va