//Ext.Loader.setConfig({enabled:true});
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
    'Ext.ux.DataView.LabelEditor',
	'Ext.window.MessageBox',
    'Ext.tip.*'
]);

Ext.onReady(function(){
    var slider_tpl_success = new Ext.XTemplate(
        'Slider Photo : {message}'
    );
	var win_slider_input,slider_panel_view,selected_slider,slider_view_images,slider_store,slider_form;
	function submitFormSlider(btn){
		//var form=this.up('form').getForm();
		var form=slider_form.getForm();
		if(form.isValid()){
			form.submit({
				url:'extjsinc/php/act.php?act=slider-upload',
				waitMsg:'Submitting Slider...',
				success: function(fp, o) {
					Ext.Msg.alert('Upload Success', slider_tpl_success.apply(o.result));
					slider_store.reload();
					slider_view_images.refresh();
					win_slider_input.hide();
				},
				failure:function(){
					Ext.Msg.alert("Error",Ext.JSON.decode(this.response.responseText).message);
				}
			});
		}
	}
	function onSliderSubmit(btn){
		if(!win_slider_input){//BEGIN CREATING WINDOW FORMS===========================================
			win_slider_input= Ext.create('Ext.window.Window', {
				closeAction:'hide',
				autoShow: false,
				title: '',
				width: 500,
				height: 300,
				minWidth: 300,
				minHeight: 200,
				layout: 'fit',
				plain:true,
				items: slider_form=Ext.create('Ext.form.Panel',{
					id:'slider-form-input',
					frame:false,
					bodyPadding:'10 10 0',
					defaults:{
						anchor: '100%',
						labelWidth: 70
					},
					items: [{
							xtype:'hidden',
							name:'input_mode'
						},
						{
							xtype:'hidden',
							name:'last_file_name'
						},
						{
							xtype:'textfield',
							name:'description',
							fieldLabel: 'Description'
						},{
							xtype:'filefield',
							fieldLabel:'Picture',
							emptyText: 'Select an image',
							fieldLabel: 'Picture',
							name: 'slider-image',
							buttonText: '',
							buttonConfig: {
								iconCls: 'upload-icon'
							}
						}
					],
					buttons: [{
							id:'slider-btn-submit',
							text: 'Submit',
							handler: submitFormSlider
						},{
							text: 'Reset',
							handler:function(){
								this.up('form').getForm().reset();
							}
						}
					]
				}),
			});
		}//END CREATING WINDOW AND FORM*************************************
		
		if(btn=='yes'){//If delete button command confirmed by click "Yes"
			win_slider_input.down('form').getForm().setValues({input_mode:'delete'});
			win_slider_input.down('form').getForm().setValues({last_file_name:selected_slider.get('name')});
			submitFormSlider(btn);
			//Ext.Msg.alert("test",win_slider_input.down('form').down('button#slider-btn-submit').getId());
			//win_slider_input.down('form').down('button#slider-btn-submit').dom.Click();
		}else{
			if((btn!='no')&&(btn!=null)&&(typeof(btn)!=='undefined')&&(btn!="")){//If not delete button click on "No" or btn not defined
				var btn_id=btn.getId();
				if(btn_id=="slide_new"){//If New Input
					win_slider_input.setTitle("New Slider");
					win_slider_input.down('form').getForm().reset();
					win_slider_input.down('form').getForm().setValues({input_mode:'new'});
					
					win_slider_input.center();
					win_slider_input.show();
				}
				if(btn_id=="slide_edit"){//If Edit Input
					if(!selected_slider){
						Ext.Msg.alert("Error!!!","Please select image to edit.");
					}
					win_slider_input.setTitle("Edit Slider");
					win_slider_input.down('form').getForm().setValues({input_mode:'edit'});
					win_slider_input.down('form').getForm().setValues({last_file_name:selected_slider.get('name')});
					win_slider_input.down('form').getForm().setValues({description:selected_slider.get('description')});
					
					win_slider_input.center();
					win_slider_input.show();
				}
			}
			//Ext.Msg.alert("Values:",win_slider_input.down('form').getForm().getValues(true));
				//Ext.MessageBox.prompt('Status', 'Changes saved successfully.');
		}
	}
    ImageModel = Ext.define('ImageModel', {
        extend: 'Ext.data.Model',
        fields: [
           {name: 'name'},
           {name: 'description'},
           {name: 'short_order'}
        ]
    });

    slider_store = Ext.create('Ext.data.Store', {
        model: 'ImageModel',
        proxy: {
            type: 'ajax',
            url: 'extjsinc/php/act.php?act=slider-list',
            reader: {
                type: 'json',
                root: 'images'
            }
        }
    });
    slider_store.load();

    slider_panel_view=Ext.create('Ext.Panel', {
        id: 'panel-view',
	overflowY:'auto',
        frame: true,
        collapsible: false,
        width: 600,
		height: 400,
        renderTo: 'dataview-slider',
        title: 'Slider Pictures',
		tbar:[{
			id:'slide_new',
			text:'New Slide',
			iconCls:'slider_tb_btn_new',
			handler: onSliderSubmit
			},{
			id:'slide_edit',
			text:'Edit Slide',
			iconCls:'slider_tb_btn_edit',
			handler: onSliderSubmit
			},{
			id:'slide_delete',
			text:'Delete Slide',
			iconCls:'slider_tb_btn_delete',
			handler: function(btn){
					if(!selected_slider){
						Ext.Msg.alert("Error","Please select slider to delete.");
					}else{
						Ext.Msg.confirm("Confirm","Are you confirm to delete selected slider???",onSliderSubmit);
					}
				}
			}
		],
        items: slider_view_images=Ext.create('Ext.view.View', {
			id: 'view-images',
            store: slider_store,
            tpl: [
                '<tpl for=".">',
                    '<div class="thumb-wrap" id="{name:stripTags}">',
                        '<div class="thumb"><img src="../images/image.php?path=../images/header-slider/{name}&&sz=150" title="{name:htmlEncode}"></div>',
                        '<span>{shortName:htmlEncode}</span>',
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
                    shortName: Ext.util.Format.ellipsis(data.name, 15),
                    sizeString: Ext.util.Format.fileSize(data.size),
                    dateString: Ext.util.Format.date(data.lastmod, "m/d/Y g:i a")
                });
                return data;
            },
            listeners: {
                select: function(dv, nodes ){
					//var data= this.up('view').getStore;
                    /*var l = nodes.length,
                        s = l !== 1 ? 's' : '';*/
                    //this.up('panel').setTitle('Slider Pictures' + nodes.get('description'));
					selected_slider=nodes;
                }
            }
        })
    });
});