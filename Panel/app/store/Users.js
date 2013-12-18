Ext.define('NB.store.Users', {
    extend: 'Ext.data.Store',
    model: 'NB.model.User',
    autoLoad: true,

    proxy: {
        type: 'ajax',
        url: '/Service/user/',
        extraParams: {
            command: 'getOtherUsers'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    }
});
