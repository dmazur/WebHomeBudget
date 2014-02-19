Ext.define('NB.view.bill.List' ,{
    extend: 'Ext.grid.Panel',
    alias: 'widget.billlist',
    id: 'billlistgrid',

    title: 'Bills',
    
    store: 'Bill',

    initComponent: function() {
        this.columns = [
            {dataIndex: 'category', width: 5, 
                renderer: Ext.ux.StoreColorRenderer(Ext.getStore('Category'), 'id_category', 'color')
            },
            {header: 'Description',  dataIndex: 'description',  flex: 1},
            {header: 'Value',  dataIndex: 'value',  flex: 1, 
                renderer: function (val) {return Ext.util.Format.currency(val);}
            },
            {header: 'Category',  dataIndex: 'category',  flex: 1, 
                renderer: Ext.ux.StoreRenderer(Ext.getStore('Category'), 'id_category', 'name')
            },
            {header: 'Data', dataIndex: 'when', flex: 1, renderer: Ext.util.Format.dateRenderer('Y-m-d')}
        ];
        
        this.method = 'put';

        this.callParent(arguments);
    },
    
    tbar: [
        {
            text: 'Add',
            action: 'add',
            icon: '../Images/Icons/tag_blue_add.png'
        },
        {
            text: 'Edit',
            action: 'edit',
            icon: '../Images/Icons/tag_blue_edit.png'
        },
        {
            text: 'Delete',
            action: 'del',
            icon: '../Images/Icons/tag_blue_delete.png'
        }
    ]
});
