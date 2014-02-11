Ext.define('NB.model.stats.CategoryPartByTime', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'category', type: 'int'},
        {name: 'name', type: 'string'},
        {name: 'sum', type: 'float'}
    ]
});