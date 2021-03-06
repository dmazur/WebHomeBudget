Ext.define('NB.view.bill.List' ,{
    extend: 'Ext.grid.Panel',
    alias: 'widget.billlist',
    id: 'billlistgrid',

    title: 'Rachunki',
    
    store: 'Bill',

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
            {header: 'Data', dataIndex: 'when', flex: 1, renderer: Ext.util.Format.dateRenderer('Y-m-d')}
        ];
        
        this.method = 'put';

        this.callParent(arguments);
    },
    
    tbar: [
        {
            text: 'Dodaj',
            action: 'add',
            icon: '../Images/Icons/table_add.png'
        },
        {
            text: 'Edytuj',
            action: 'edit',
            icon: '../Images/Icons/table_edit.png'
        },
        {
            text: 'Usuń',
            action: 'del',
            icon: '../Images/Icons/table_delete.png'
        }
    ]
});