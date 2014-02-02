Ext.define('NB.model.Cyclic', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_cyclic', type: 'auto'}, 
        {name: 'description', type: 'string'}, 
        {name: 'value', type: 'float'},
        {name: 'category', type: 'int'},
        {name: 'when', type: 'date'},
        {name: 'from', type: 'date'},
        {name: 'to', type: 'date'}
    ]
});