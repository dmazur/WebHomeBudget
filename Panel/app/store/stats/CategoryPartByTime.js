Ext.define('NB.store.stats.CategoryPartByTime', {
    extend: 'Ext.data.Store',
    model: 'NB.model.stats.CategoryPartByTime',
    autoLoad: false,

    proxy: {
        type: 'ajax',
        url: '/Service/stats/',
        method: 'get',
        extraParams: {
            command: 'getCategoryPartByTime'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    }
});
