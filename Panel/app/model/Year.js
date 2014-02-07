Ext.define('NB.model.Year', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'number', type: 'int'}
    ],
    hasMany: [
    	{model: 'NB.model.Month', name: 'months', associationKey: 'months'}
    ]
});