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
                    allowBlank: false,
                    validator: function(value){
                        from_value = this.up("form").getForm().findField("from").getValue();
                        to_value = this.up("form").getForm().findField("to").getValue();
                        my_value = this.getValue();
                        if ((my_value >= from_value) && (my_value <= to_value))
                            return true
                        else
                            return "Pierwsze odpalenie mysi znajdować się pomiędzy wartościami Od kiedy - Do kiedy"  
                    }
                },
                {
                    xtype: 'datefield',
                    name: 'from',
                    fieldLabel: 'Od kiedy',
                    allowBlank: false,
                    validator: function(value){
                        to_value = this.up("form").getForm().findField("to").getValue();
                        my_value = this.getValue();
                        if (my_value < to_value)
                            return true
                        else
                            return "Data Od kiedy nie może być większa od pola Do kiedy"  
                    }
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
