Ext.define('NB.store.Bill', {
    extend: 'Ext.data.Store',
    model: 'NB.model.Bill',
    autoLoad: true,

    proxy: {
        type: 'ajax',
        url: '/Service/bill/',
        method: 'get',
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    } 
});
