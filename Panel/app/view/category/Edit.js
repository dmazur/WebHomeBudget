Ext.define('NB.view.category.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.categoryedit',

    title: 'Category edit',
    layout: 'fit',
    autoShow: true,
    width: 400,
    height: 210,
    modal: true,
    method: null,

    initComponent: function() {
        this.items = [{
            bodyPadding: 10,
            xtype: 'form',
            id: 'categoryeditform',
            items: [
                {
                    xtype: 'hidden',
                    name: 'id_category'
                },
                {
                    labelWidth: 50,
                    width: 350,
                    maxLength: 32,
                    allowBlank: false,
                    xtype: 'textfield',
                    name : 'name',
                    fieldLabel: 'Name'
                },
                {
                    xtype: 'label',
                    text: 'Color:'
                },
                {
                    xtype: 'colorpicker',
                    value: '993300', //initial value
                    id: 'category_edit_colorpicker',
                    listeners: {
                        select: function(picker, color) {
                            Ext.getCmp('category_edit_color').setValue(color);
                        }
                    }
                },
                {
                    xtype: 'hidden',
                    name: 'color',
                    value: '993300', //initial value
                    id: 'category_edit_color',
                    listeners: {
                        change: function(field, value) {
                            Ext.getCmp('category_edit_colorpicker').select(value);
                        }
                    }
                }
            ]
        }];

        this.buttons = [
            {
                text: 'Save',
                action: 'save',
                icon: '../Images/Icons/disk.png'
            },
            {
                text: 'Cancel',
                scope: this,
                handler: this.close,
                icon: '../Images/Icons/cancel.png'
            }
        ];

        this.callParent(arguments);
    }
});
