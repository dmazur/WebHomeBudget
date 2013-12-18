Ext.define('NB.view.category.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.categoryedit',

    title: 'Edycja kategorii',
    layout: 'fit',
    autoShow: true,
    width: 400,
    height: 100,
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
                    fieldLabel: 'Nazwa'
                }
            ]
        }];

        this.buttons = [
            {
                text: 'Zapisz',
                action: 'save',
                icon: '../Images/Icons/disk.png'
            },
            {
                text: 'Anuluj',
                scope: this,
                handler: this.close,
                icon: '../Images/Icons/cancel.png'
            }
        ];

        this.callParent(arguments);
    }
});