
Ext.Loader.setPath('Ext.ux.DataView', 'extjsinc/js/ux/DataView/');

Ext.require([
	'Ext.form.field.File',
	'Ext.form.field.Number',
	'Ext.form.Panel',
	'Ext.container.Viewport',
	'Ext.window.*',
	'Ext.data.*',
	'Ext.util.*',
	'Ext.view.View',
	'Ext.ux.DataView.DragSelector',
	'Ext.window.MessageBox',
	'Ext.tip.*',
	'Ext.toolbar.Paging'
]);

Ext.onReady(function(){
	var win_product_group_input,product_group_form,selected_product_group;
	var tb_products,cmb_product_group,panel_products,win_product_input,product_form,product_view,selected_product,paging_product;

	var product_group_model= Ext.define('productGroupModel', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'idproduct_group'},
			{name: 'description'},
			{name: 'parent'},
			{name: 'img_rand'},
			{name: 'seo_title'},
			{name: 'seo_keywords'},
			{name: 'seo_description'},
			
		]
	});
	var product_group_store = Ext.create('Ext.data.Store', {
		model: 'productGroupModel',
		proxy: {
			type: 'ajax',
			url: 'extjsinc/php/act.php?act=product-group',
			reader: {
				type: 'json',
				root: 'product_group'
			}
		}
	});
	product_group_store.load();
	var tb_products_group=Ext.create('Ext.toolbar.Toolbar',{
		id:'tb_products_group'
	});
	tb_products_group.add([{
			xtype:'button',
			id:'product_group_new',
			text:'New',
			iconCls:'slider_tb_btn_new',
			handler:onProductGroup
		},{
			xtype:'button',
			id:'product_group_edit',
			text:'Edit',
			iconCls:'slider_tb_btn_edit',
			handler:onProductGroup
		},{
			xtype:'button',
			id:'product_group_delete',
			text:'Delete',
			iconCls:'slider_tb_btn_delete',
			handler: function(btn){
				if((!selected_product_group)||(selected_product_group==null)||(typeof(selected_product_group)=='undefined')||(selected_product_group=='')){
					Ext.Msg.show({
						title:"Error delete Product Group",
						msg:"Please select <b>Product Group</b> to delete.",
						icon:Ext.Msg.ERROR,
						buttons:Ext.Msg.OK
					});
				}else{
					Ext.Msg.confirm("Confirm","Are you sure to delete selected group?",onProductGroup);
				}
			}
	}]);

	var view_product_group=Ext.create('Ext.view.View',{
		id: 'view_product_group',
		store:product_group_store,
		tpl:[
			'<tpl for=".">',
				'<div class="thumb-wrap" id="{idproduct_group}">',
					'<div class="thumb"><img src="../imagep.php?gname={description}&&pname=_icon&&sz=150&&rand={img_rand}" title="{description:htmlEncode}"></div>',
					'<div class="label">',
						'<div><b>Description:</b> {shortName:htmlEncode}</div>',
					'</div>',
				'</div>',
			'</tpl>',
			'<div class="x-clear"></div>'
		],
		multiSelect: false,
		trackOver: true,
		overItemCls: 'x-item-over',
		itemSelector: 'div.thumb-wrap',
		emptyText: 'No images to display',
		plugins: [
			Ext.create('Ext.ux.DataView.DragSelector', {}),
			Ext.create('Ext.ux.DataView.LabelEditor', {dataIndex: 'name'})
		],
		prepareData: function(data) {
			Ext.apply(data, {
				shortName: Ext.util.Format.ellipsis(data.description, 23)
			});
			return data;
		},
		listeners: {
			select: function(dv, nodes ){
				selected_product_group=nodes;
			}
		}
	});
	var panel_product_group=Ext.create('Ext.panel.Panel',{
		id:'panel_product_group',
		title:'Product Group Admin',
		overflowY:'auto',
		region:'west',
		flex:2,
		height:500,
		split:true,
		collapsible:true,
		tbar:tb_products_group,
		items:[view_product_group]
	});
	
	cmb_product_group=Ext.create('Ext.form.field.ComboBox',{
		store:product_group_store,
		fieldLabel:'Product Group',
		displayField:'description',
		valueField:'idproduct_group',
		emptyText:'Select Group',
		listeners: {
			change:function(cmb,nval,oval){
				var is_nval_undefined=typeof(nval)==='undefined';
				var filter_id_pg;
				if((nval!="")&&(nval!=null)&&(is_nval_undefined!=true)){
					//product_store.filter('idproduct_group',nval);
					filter_id_pg="&&id_pg="+nval;
				}else{
					var filter_id_pg="";
				}
				product_store.setProxy({
					type: 'ajax',
					url: 'extjsinc/php/act.php?act=product-list'+filter_id_pg,
					reader: {
						type: 'json',
						root: 'product_list',
						totalProperty: 'totalCount'
					},
					simpleSortMode:true
				});
// 				product_store.reload();
				product_view.getStore().reload({ params: { start: 0} });
				product_store.loadPage(1);
			}
		}
	});
	tb_products=Ext.create('Ext.toolbar.Toolbar');
	tb_products.add({
			xtype: 'buttongroup',
			title: 'Products Menu',
			column:2,
			items:[
				{
					id:'product_new',
					text:'New Product',
					iconCls:'slider_tb_btn_new',
					handler:onProduct
				},{
					id:'product_edit',
					text:'Edit Product',
					iconCls:'slider_tb_btn_edit',
					handler:onProduct
				},{
					id:'product_delete',
					text:'Delete Product',
					iconCls:'slider_tb_btn_delete',
					handler:function(btn){
						if((!selected_product)||(selected_product==null)||(typeof(selected_product)=='undefined')||(selected_product=='')){
							Ext.Msg.show({
								title:"Error delete product",
								msg:"Please select <b>Product</b> to delete.",
								buttons:Ext.Msg.OK,
								icon:Ext.Msg.ERROR
							});
						}else{
							Ext.Msg.confirm("Confirm delete","Are you sure to delete selected product?",onProduct);
						}
					}
				}
			]
		},{
			xtype: 'buttongroup',
			title: 'Filter',
			column:2,
			items:[
				cmb_product_group
// 				,{
// 					id:'product_group_filter',
// 					text:'Filter'
// 				}
			]
		}
	);
	var product_model= Ext.define('productModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'idproduct'},
				{name: 'code'},
				{name: 'name'},
				{name: 'idproduct_group'},
				{name: 'group_name'},
				{name: 'img_rand'},
				{name: 'seo_title'},
				{name: 'seo_keywords'},
				{name: 'seo_description'}
			]
		});
	var product_store = Ext.create('Ext.data.Store', {
		pageSize: 9,
		model: 'productModel',
		remoteSort:true,
		proxy: {
			type: 'ajax',
			url: 'extjsinc/php/act.php?act=product-list',
			reader: {
				type: 'json',
				root: 'product_list',
				totalProperty: 'totalCount'
			},
			simpleSortMode:true
		}
	});
	/*
	product_store.on('beforeload',function(ob,op,o){
		product_store.loadPage(1);
	});*/
	product_store.load();
	var product_view=Ext.create('Ext.view.View',{
		id: 'view_product',
		store:product_store,
		tpl:[
			'<tpl for=".">',
				'<div class="thumb-wrap" id="{idproduct}">',
					'<div class="thumb"><img src="../imagep.php?gname={group_name}&&pname={name}&&sz=150&&rand={img_rand}" title="{name:htmlEncode}"></div>',
					'<div class="label">',
						'<div><b>Code:</b> {code}</div> <div><b>Name:</b> {shortName:htmlEncode}</div><div><b>Group:</b> {group_name}</div>',
					'</div>',
				'</div>',
			'</tpl>',
			'<div class="x-clear"></div>'
		],
		multiSelect: false,
		trackOver: true,
		overItemCls: 'x-item-over',
		itemSelector: 'div.thumb-wrap',
		emptyText: 'No images to display',
		plugins: [
			Ext.create('Ext.ux.DataView.DragSelector', {}),
			Ext.create('Ext.ux.DataView.LabelEditor', {dataIndex: 'name'})
		],
		prepareData: function(data) {
			Ext.apply(data, {
				shortName: Ext.util.Format.ellipsis(data.name, 23)
			});
			return data;
		},
		listeners: {
			select: function(dv, nodes ){
						selected_product=nodes;
			}
		}
	});
	paging_product=Ext.create('Ext.PagingToolbar',{
			id:'paging-product',
			store:product_store,
			displayInfo:true,
			displayMsg: 'Display Products {0} - {1} of {2}',
			emptyMsg: "No products to display"
		});
	panel_products=Ext.create('Ext.Panel',{
		id:'panel_product',
		overflowY:'auto',
		region:'center',
		flex:3,
		height:500,
		title: 'Products Admin',
		tbar:tb_products,
		bbar: paging_product,
		items:[product_view]
	});
	var panel_product_wrapper=Ext.create('Ext.Panel',{
		id:'panel_product_wrapper',
		layout:'border',
		renderTo:'panel-products',
		width:'100%',
		height:500,
		items:[panel_product_group,panel_products]
	});
// 	panel_products.add(product_view);
	
	function onProduct(btn){
		if(!win_product_input){//BEGIN CREATING WINDOW FORMS===========================================
			win_product_input= Ext.create('Ext.window.Window', {
				id:'window_product',
				closeAction:'hide',
				autoShow: false,
				title: '',
				width: 550,
				minWidth: 300,
				minHeight: 200,
				layout: 'fit',
				plain:true,
				items: product_form=Ext.create('Ext.form.Panel',{
					id:'product_form',
					frame:false,
					bodyPadding:'10 10 0',
					defaults:{
						anchor: '100%',
						labelWidth: 90
					},
					items: [{
							xtype:'hidden',
							name:'input_mode'
						},{
							xtype:'hidden',
							name:'idproduct'
						},{
							xtype:'combobox',
							name:'idproduct_group',
							store:product_group_store,
							fieldLabel:'Product Group',
							displayField:'description',
							valueField:'idproduct_group',
							emptyText:'Select Group',
							allowBlank:false
						},
						{
							xtype:'textfield',
							name:'product_code',
							fieldLabel: 'Product Code'
						},
						{
							xtype:'textfield',
							name:'product_name',
							fieldLabel: 'Product Name',
							allowBlank:false
						},{
							xtype:'filefield',
							fieldLabel:'Picture',
							emptyText: 'Select an image',
							name: 'product_image',
							buttonText: '',
							buttonConfig: {
								iconCls: 'upload-icon'
							}
						},{
							xtype:'fieldset',
							id:'p-seo',
							name:'p-seo',
							checkboxToggle:true,
							collapsed:true,
							title:'SEO',
							defaultType:'textareafield',
							defaults:{
								anchor:'90%',
								labelWidth:70,
								labelStyle:'text-transform:capitalize',
								height:40
							},
							items:[{
									fieldLabel:'Title',
									name:'seo_title',
									enforceMaxLength:true,
									maxLength:60
								},{
									fieldLabel:'Keywords',
									name:'seo_keywords',
									enforceMaxLength:true,
									maxLength:255
								},{
									fieldLabel:'Description',
									name:'seo_description',
									enforceMaxLength:true,
									maxLength:255
								}
							]
						}
					],
					buttons: [{
							id:'product-btn-submit',
							text: 'Submit',
							handler:onProductSubmit
						},{
							text: 'Reset',
							handler:function(){
								this.up('form').getForm().reset();
							}
						}
					]
				})
			});
		}//END CREATING WINDOW AND FORM*************************************
		if(btn=='yes'){
			win_product_input.down('form').getForm().setValues({
				input_mode:'delete',
				idproduct_group:selected_product.get('idproduct_group'),
				idproduct:selected_product.get('idproduct'),
				product_name:selected_product.get('name')
			});
			onProductSubmit(btn);
			//Ext.Msg.alert("test",win_product_input.down('form').down('button#slider-btn-submit').getId());
			//win_product_input.down('form').down('button#slider-btn-submit').dom.Click();
		}else{
			if((btn!='no')&&(btn!=null)&&(btn!='')&&(typeof(btn)!=='undefined')){
				var btn_id=btn.getId();
				var form=win_product_input.down('form').getForm();
				if(btn_id=="product_new"){//If New Input
					win_product_input.setTitle("New Product");
					form.reset();
					form.setValues({input_mode:'new',idproduct:-1});
					
					win_product_input.center();
					win_product_input.show();
				}
				if(btn_id=="product_edit"){//If Edit Input
					if(!selected_product){
						Ext.Msg.show({
							title:"Error!!!",
							msg:"Please select product to edit.",
							buttons:Ext.Msg.OK,
							icon:Ext.Msg.ERROR
							
						});
					}else{
						form.reset();
						var seo_title=selected_product.get('seo_title');
						var seo_keywords=selected_product.get('seo_keywords');
						var seo_description=selected_product.get('seo_description');
						win_product_input.setTitle("Edit Product");
						form.setValues({
							input_mode:'edit',
							idproduct:selected_product.get('idproduct'),
							idproduct_group:selected_product.get('idproduct_group'),
							product_code:selected_product.get('code'),
							product_name:selected_product.get('name'),
							seo_title:seo_title,
							seo_keywords:seo_keywords,
							seo_description:seo_description
						});
						if((seo_title!="")||(seo_keywords!="")||(seo_description!="")){
							Ext.getCmp('p-seo').expand();
						}
						
						win_product_input.center();
						win_product_input.show();
					}
				}
				//Ext.Msg.alert("Values:",win_product_input.down('form').getForm().getValues(true));
					//Ext.MessageBox.prompt('Status', 'Changes saved successfully.');
			}
		}
	}//END function onProduct
	var product_tpl_success = new Ext.XTemplate(
		'Product Upload : {message}'
	);
	function onProductSubmit(btn){
// 		Ext.Msg.alert("tst","Test");
		//var form=this.up('form').getForm();
		var form=product_form.getForm();
		
		if(form.isValid()){
			form.submit({
				url:'extjsinc/php/act.php?act=product-upload',
				waitMsg:'Submitting Product...',
				success: function(fp, o) {
					Ext.Msg.alert('Upload Success', product_tpl_success.apply(o.result));
					if(o.result.input_mode=='new'){
						product_store.reload({params:{start:0}});
						product_store.loadPage(1);
					}else{
						product_store.reload();
					}
					product_view.refresh();
					win_product_input.hide();
				},
				failure:function(){
					Ext.Msg.alert("Error",Ext.JSON.decode(this.response.responseText).message);
				}
			});
		}else{
			Ext.Msg.show({
				title: "Error",
				msg:"Please fill field red marked.",
				buttons:Ext.Msg.OK,
				icon:Ext.Msg.ERROR
			});
		}
	}//END function onProductSubmit
	function onProductGroup(btn){
		if(!win_product_group_input){
			win_product_group_input=Ext.create('Ext.window.Window',{
				id:'window_product_group',
				closeAction:'hide',
				autoShow:false,
				title:'',
				width:450,
				minWidth:300,
				minHeight:140,
				layout:'fit',
				plain:true,
				items:product_group_form=Ext.create('Ext.form.Panel',{
					id:'product_group_form',
					bodyPadding:'10 10 0',
					defaults:{
						anchor: '100%',
						lebelWidth:'90'
					},
					items:[{
						xtype:'hidden',
						name:'input_mode'
					},{
						xtype:'hidden',
						name:'idproduct_group'
					},{
						xtype:'textfield',
						name:'description',
						fieldLabel:'Description',
						allowBlank:false
					},{
						xtype:'filefield',
						fieldLabel:'Picture',
						emptyText:'Select an image',
						name:'product_group_image',
						buttonText:'',
						buttonConfig:{
							iconCls:'upload-icon'
						}
					},{
							xtype:'fieldset',
							id:'pg-seo',
							name:'pg-seo',
							checkboxToggle:true,
							collapsed:true,
							title:'SEO',
							defaultType:'textareafield',
							defaults:{
								anchor:'95%',
								labelWidth:70,
								labelStyle:'text-transform:capitalize',
								height:40
							},
							items:[{
									fieldLabel:'Title',
									name:'seo_title',
									enforceMaxLength:true,
									maxLength:60
								},{
									fieldLabel:'Keywords',
									name:'seo_keywords',
									enforceMaxLength:true,
									maxLength:255
								},{
									fieldLabel:'Description',
									name:'seo_description',
									enforceMaxLength:true,
									maxLength:255
								}
							]
						}],
					buttons:[{
							id:'product_group_btn_submit',
							text: 'Submit',
							handler:onProductGroupSubmit
						},{
							text:'Reset',
							handler:function(){
								this.up('form').getForm().reset();
							}
					}]
				})
			});
		}
		var form=Ext.getCmp('product_group_form').getForm();

		if(btn=='yes'){
			form.setValues({input_mode:'delete',idproduct_group:selected_product_group.get('idproduct_group'),description:'-'});
			onProductGroupSubmit();
		}else{
			if((btn!='no')&&(btn!=null)&&(btn!='')&&(typeof(btn)!=='undefined')){
				var btn_id=btn.getId();
				if(btn_id=='product_group_new'){
					form.reset();
					form.setValues({input_mode:'new',idproduct_group:-1});

					win_product_group_input.setTitle("New Product group");
					win_product_group_input.center();
					win_product_group_input.show();
				}
				if(btn_id=='product_group_edit'){
					if((!selected_product_group)||(selected_product_group==null)||(typeof(selected_product_group)=='undefined')||(selected_product_group=='')){
						Ext.Msg.show({
							title:"Error edit Product Group",
							msg:"Please select product group row to edit",
							icon:Ext.Msg.ERROR,
							buttons:Ext.Msg.OK
						});
					}else{
						form.reset();
						var seo_title=selected_product_group.get('seo_title');
						var seo_keywords=selected_product_group.get('seo_keywords');
						var seo_description=selected_product_group.get('seo_description');
						if((seo_title!="")||(seo_keywords!="")||(seo_description!="")){
							Ext.getCmp('pg-seo').expand();
						}
						form.setValues({
							input_mode:'edit',
							idproduct_group:selected_product_group.get('idproduct_group'),
							description:selected_product_group.get('description'),
							seo_title:seo_title,
							seo_keywords:seo_keywords,
							seo_description:seo_description
						});
						
						win_product_group_input.setTitle('Edit Product Group ( '+selected_product_group.get('description')+' )');
						win_product_group_input.center();
						win_product_group_input.show();
					}
				}
			}
		}
	}//END onProductGroup
	function onProductGroupSubmit(){
		var form=Ext.getCmp('product_group_form').getForm();
		if(form.isValid()){
			form.submit({
				url:'extjsinc/php/act.php?act=product-group-input',
				waitMsg:'Submitting Product Group query...',
				success: function(fp, o){
					Ext.Msg.alert('Product group input success','Product group input:'+o.result.message);
					product_group_store.reload();
					win_product_group_input.hide();
				},
				failure:function(){
					Ext.Msg.show({
						title:"Error",
						msg:Ext.JSON.decode(this.response.responseText).message,
						icon:Ext.Msg.ERROR
					});
				}
			});
		}else{
			Ext.Msg.show({
				title:"Error",
				msg:"Input not valid, please also check if there are fields with red mark",
				icon:Ext.Msg.ERROR
			});
		}
	}//END onProductGroupSubmit
});