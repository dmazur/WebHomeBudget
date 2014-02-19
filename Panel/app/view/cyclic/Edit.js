Ext.define('NB.view.cyclic.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.cyclicedit',

    title: 'Edit Cyclic',
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
                    fieldLabel: 'Description'
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
                    fieldLabel: 'Value'
                },
                {
                    allowBlank: false,
                    xtype: 'combobox',
                    name : 'category',
                    model: 'NB.model.Category',
                    fieldLabel: 'Category',
                    store: 'Category',
                    queryMode:'local',
                    valueField: 'id_category',
                    displayField: 'name'

                },
                {
                    xtype: 'datefield',
                    name: 'when',
                    fieldLabel: 'First run',
                    allowBlank: false,
                    validator: function(value){
                        from_value = this.up("form").getForm().findField("from").getValue();
                        to_value = this.up("form").getForm().findField("to").getValue();
                        my_value = this.getValue();
                        if ((my_value >= from_value) && (my_value <= to_value))
                            return true
                        else
                            return "First run must be between from and to values"  
                    }
                },
                {
                    xtype: 'datefield',
                    name: 'from',
                    fieldLabel: 'From',
                    allowBlank: false,
                    validator: function(value){
                        to_value = this.up("form").getForm().findField("to").getValue();
                        my_value = this.getValue();
                        if (my_value < to_value)
                            return true
                        else
                            return "From value cant me gather than to value."  
                    }
                },
                {
                    xtype: 'datefield',
                    name: 'to',
                    fieldLabel: 'To',
                    allowBlank: false
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
