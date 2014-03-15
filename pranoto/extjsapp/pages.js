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
			{name:'idpages'},{name:'post_date'},{name:'modified_date'},
			{name:'content'},{name:'title'},{name:'username'}
		]
	});
	var page_store=Ext.create('Ext.data.Store',{
		model: 'pageModel',
		proxy:{
			type: 'ajax',
			url: 'extjsinc/php/pages.php',
			reader:{
				type: 'json',
				root: 'pages'
			}
		}
	});
    var tabs = Ext.widget('tabpanel', {
        renderTo: 'pages-tab',
        width: 890,
		height:950,
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
				url:'extjsinc/php/page-update.php',
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
							text:'Edit',
							handler:function(){
								CKEDITOR.replace('ckeditor'+record.get('title'),{height:670});
							}
						},{
							text:'Save',
							handler:submitUpdatePage
						}
					]
				})
			});
			
		});
		tabs.add(items);
		tabs.setActiveTab(0);
	});

});