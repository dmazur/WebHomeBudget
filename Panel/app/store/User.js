Ext.define('NB.store.User', {
    extend: 'Ext.data.Store',
    model: 'NB.model.User',

    proxy: {
        type: 'ajax',
        url: '/Service/user/',
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    }
});
