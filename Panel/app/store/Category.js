Ext.define('NB.store.Category', {
    extend: 'Ext.data.Store',
    model: 'NB.model.Category',
    autoLoad: true,

    proxy: {
        type: 'ajax',
        url: '/Service/category/',
        method: 'get',
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    }
});
