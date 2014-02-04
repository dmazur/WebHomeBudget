Ext.define('NB.store.Cyclic', {
    extend: 'Ext.data.Store',
    model: 'NB.model.Cyclic',

    proxy: {
        type: 'ajax',
        url: '/Service/cyclic/',
        method: 'get',
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        }
    } 
});
