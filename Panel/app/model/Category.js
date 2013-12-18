Ext.define('NB.model.Category', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_category', type: 'auto'}, 
        {name: 'name', type: 'string'}, 
        {name: 'author', type: 'int'}
    ]
});