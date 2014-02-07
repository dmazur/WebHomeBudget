Ext.define('NB.model.Month', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'name', type: 'string'}, 
        {name: 'number', type: 'int'}
    ],
    belongsTo: 'NB.model.Year'
});