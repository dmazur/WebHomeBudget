Ext.define('NB.view.cyclic.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.cyclicedit',

    title: 'Edycja rachunku cyklicznego',
    layout: 'fit',
    autoShow: true,
    width: 400,
    height: 250,
    modal: true,
    method: null,

    initComponent: function() {
        this.items = [{
            bodyPadding: 10,
            xtype: 'form',
            id: 'cycliceditform',
            items: [
                {
                    xtype: 'hidden',
                    name: 'id_cyclic'
                },
                {
                    labelWidth: 50,
                    width: 350,
                    maxLength: 32,
                    allowBlank: false,
                    xtype: 'textfield',
                    name : 'description',
                    fieldLabel: 'Opis'
                },
                {
                    labelWidth: 50,
                    width: 350,
                    maxLength: 32,
                    allowBlank: false,
                    xtype: 'textfield',
                    name : 'value',
                    fieldLabel: 'Wartość'
                },
                {
                    allowBlank: false,
                    xtype: 'combobox',
                    name : 'category',
                    model: 'NB.model.Category',
                    fieldLabel: 'Kateogria',
                    store: 'Category',
                    queryMode:'local',
                    valueField: 'id_category',
                    displayField: 'name'

                },
                {
                    xtype: 'datefield',
                    name: 'when',
                    fieldLabel: 'Pierwsze odpalenie',
                    allowBlank: false
                },
                {
                    xtype: 'datefield',
                    name: 'from',
                    fieldLabel: 'Od kiedy',
                    allowBlank: false
                },
                {
                    xtype: 'datefield',
                    name: 'to',
                    fieldLabel: 'Do kiedy',
                    allowBlank: false
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