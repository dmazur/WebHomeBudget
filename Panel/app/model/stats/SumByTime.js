Ext.define('NB.model.stats.SumByTime', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'month', type: 'int'},
        {name: 'sum', type: 'float'}
    ]
});
