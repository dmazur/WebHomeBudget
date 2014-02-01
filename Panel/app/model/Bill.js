Ext.define('NB.model.Bill', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_bill', type: 'auto'}, 
        {name: 'description', type: 'string'}, 
        {name: 'value', type: 'float'}
    ]
});