Ext.define('NB.model.User', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_user', type: 'int'}, 
        {name: 'login', type: 'string'}, 
        {name: 'pass', type: 'string'}, 
        {name: 'email', type: 'string'}
    ]
});

Ext.define('NB.model.UserShort', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id_user', type: 'int'}, 
        {name: 'login', type: 'string'}
    ]
});