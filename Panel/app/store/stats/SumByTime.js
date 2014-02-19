Ext.define('NB.store.stats.SumByTime', {
    extend: 'Ext.data.Store',
    model: 'NB.model.stats.SumByTime',
    autoLoad: false,

    proxy: {
        type: 'ajax',
        url: '/Service/stats/',
        method: 'get',
        extraParams: {
            command: 'getAllSumByTime'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    }
});
