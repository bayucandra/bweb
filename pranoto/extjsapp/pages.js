Ext.Loader.setPath('Ext.ux', 'extjsinc/js/ux/');
Ext.require([
	'Ext.form.field.File',
    'Ext.form.field.Number',
    'Ext.form.Panel',
	'Ext.tab.*',
	'Ext.ux.*',
	'Ext.window.MessageBox',
    'Ext.tip.*'
]);
Ext.onReady(function(){
	var form_pages;
	var pageModel= Ext.define('pageModel',{
		extend: 'Ext.data.Model',
		fields:[
			{name:'idpages'},{name:'post_date'},{name:'modified_date'}
			,{name:'content'},{name:'title'},{name:'username'}
			,{name:'seo_title'},{name:'seo_keywords'},{name:'seo_description'}
		]
	});
	var page_store=Ext.create('Ext.data.Store',{
		model: 'pageModel',
		proxy:{
			type: 'ajax',
			url: 'extjsinc/php/act.php?act=page-list',
			reader:{
				type: 'json',
				root: 'pages'
			}
		}
	});
    var tabs = Ext.widget('tabpanel', {
        renderTo: 'pages-tab',
        width: '100%',
	minHeight:950,
        activeTab: 0,
        defaults :{
            bodyPadding: 10
        }
    });

	var page_content_tpl=new Ext.XTemplate(
		"{content}"
	);

	function submitUpdatePage(btn){
		var session_iduser=json_session.session_data.iduser;
		var form=btn.up('form').getForm();
		var frm_page_title_val=form.getValues(false,false,false,true).frm_page_title;
		var editor_value=CKEDITOR.instances['ckeditor'+frm_page_title_val].getData();
		form.setValues({frm_page_content:editor_value,frm_page_iduser:session_iduser});

		if(form.isValid()){
			form.submit({
				url:'extjsinc/php/act.php?act=page-update',
				waitMsg:'Updating Pages...',
				success: function(fp, o) {
					Ext.Msg.alert('Done', "Page updated successfully");
				},
				failure:function(){
					Ext.Msg.alert("Error",Ext.JSON.decode(this.response.responseText).message);
				}
			});
		}
	}

	page_store.load(function(records){
		var items=[];
		Ext.each(records,function(record){
			items.push({
				title: record.get('title'),
				html : '<div class="ckeditor" id="ckeditor'+record.get('title')+'">'+record.get('content')+'</div>',
				items : Ext.create('Ext.form.Panel',{
					id:'frm_'+record.get('title'),
					border:0,
					frame:false,
					bodyPadding:'10 10 0',
					defaults:{
						anchor: '100%',
						labelWidth: 70
					},
					items:[{
							xtype:'fieldset',
							collapsed:false,
							title:'SEO (Search Engine Optimization)',
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
								maxLength:60,
								value:record.get('seo_title')
							},{
								fieldLabel:'Keywords',
								name:'seo_keywords',
								enforceMaxLength:true,
								maxLength:255,
								value:record.get('seo_keywords')
							},{
								fieldLabel:'Description',
								name:'seo_description',
								enforceMaxLength:true,
								maxLength:255,
								value:record.get('seo_description')
							}]
						},{
							xtype:'hidden',
							name:'frm_page_title',
							value: record.get('title')
						},{
							xtype:'hidden',
							name:'frm_page_content'
						},{
							xtype:'hidden',
							name:'frm_page_iduser'
						}
					],
					buttons:[{
							text:'Save',
							handler:submitUpdatePage
						}
					]
				})
			});
			
		});
		tabs.add(items);
		tabs.on("added",tabs_added());
	});
	function tabs_added(){
		tabs.items.each(function(it,idx,ln){
			var item=tabs.getComponent(idx);
			tabs.setActiveTab(idx);
			CKEDITOR.replace('ckeditor'+item.title,{height:670});
		});
		tabs.setActiveTab(0);
	}
});