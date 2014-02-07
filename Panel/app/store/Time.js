Ext.define('NB.store.Time', {
    extend: 'Ext.data.Store',
    model: 'NB.model.Year',
    autoLoad: true,

    proxy: {
        type: 'ajax',
        url: '/Service/time/',
        method: 'get',
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    }
});
