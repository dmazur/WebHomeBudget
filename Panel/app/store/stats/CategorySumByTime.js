Ext.define('NB.store.stats.CategorySumByTime', {
    extend: 'Ext.data.Store',
    model: 'NB.model.stats.CategorySumByTime',
    autoLoad: false,

    proxy: {
        type: 'ajax',
        url: '/Service/stats/',
        method: 'get',
        extraParams: {
            command: 'getCategorySumByTime'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    }
});
