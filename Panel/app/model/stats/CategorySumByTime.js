Ext.define('NB.model.stats.CategorySumByTime', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'month', type: 'int'},
        {name: 'sum', type: 'float'}
    ]
});