Ext.define('NB.view.cyclic.List' ,{
    extend: 'Ext.grid.Panel',
    alias: 'widget.cycliclist',
    id: 'cycliclistgrid',

    title: 'Rachunki Cykliczne',
    
    store: 'Cyclic',

    initComponent: function() {
        this.columns = [
            {dataIndex: 'category', width: 5, 
                renderer: Ext.ux.StoreColorRenderer(Ext.getStore('Category'), 'id_category', 'color')
            },
            {header: 'Opis',  dataIndex: 'description',  flex: 1},
            {header: 'Wartość',  dataIndex: 'value',  flex: 1, 
                renderer: function (val) {return Ext.util.Format.currency(val);}
            },
            {header: 'Kategoria',  dataIndex: 'category',  flex: 1,
                renderer: Ext.ux.StoreRenderer(Ext.getStore('Category'), 'id_category', 'name')
            },
            {header: 'Kiedy się odpala',  dataIndex: 'when',  flex: 1, renderer: Ext.util.Format.dateRenderer('Y-m-d')},
            {header: 'Od kiedy',  dataIndex: 'from',  flex: 1, renderer: Ext.util.Format.dateRenderer('Y-m-d')},
            {header: 'Do kiedy',  dataIndex: 'to',  flex: 1, renderer: Ext.util.Format.dateRenderer('Y-m-d')}
        ];
        
        this.method = 'put';

        this.callParent(arguments);
    },
    
    tbar: [
        {
            text: 'Dodaj',
            action: 'add',
            icon: '../Images/Icons/clock_add.png'
        },
        {
            text: 'Edytuj',
            action: 'edit',
            icon: '../Images/Icons/clock_edit.png'
        },
        {
            text: 'Usuń',
            action: 'del',
            icon: '../Images/Icons/clock_delete.png'
        }
    ]
});