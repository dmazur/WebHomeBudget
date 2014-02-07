Ext.define('NB.view.bill.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.billedit',

    title: 'Edycja rachunku',
    layout: 'fit',
    autoShow: true,
    width: 400,
    height: 200,
    modal: true,
    method: null,

    initComponent: function() {
        this.items = [{
            bodyPadding: 10,
            xtype: 'form',
            id: 'billeditform',
            items: [
                {
                    xtype: 'hidden',
                    name: 'id_bill'
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
                    xtype: 'numberfield',
                    decimalPrecision: 2,
                    decimalSeparator: Ext.util.Format.decimalSeparator,
                    minValue: 0,
                    name : 'value',
                    fieldLabel: 'Wartość'
                },
                {
                    allowBlank: false,
                    xtype: 'combobox',
                    name : 'category',
                    model: 'NB.model.Category',
                    fieldLabel: 'Kategoria',
                    store: 'Category',
                    queryMode:'local',
                    valueField: 'id_category',
                    displayField: 'name'
                },
                {
                    allowBlank: false,
                    name: 'when',
                    fieldLabel: 'Data',
                    xtype: 'datefield'
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