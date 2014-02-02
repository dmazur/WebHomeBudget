Ext.define('NB.view.bill.List' ,{
    extend: 'Ext.grid.Panel',
    alias: 'widget.billlist',
    id: 'billlistgrid',

    title: 'Rachunki',
    
    store: 'Bill',

    initComponent: function() {
        this.columns = [
            {header: 'Opis',  dataIndex: 'description',  flex: 1},
            {header: 'Wartość',  dataIndex: 'value',  flex: 1},
            {header: 'Kategoria',  dataIndex: 'category',  flex: 1}
        ];
        
        this.method = 'put';

        this.callParent(arguments);
    },
    
    tbar: [
        {
            text: 'Dodaj',
            action: 'add',
            icon: '../Images/Icons/tag_blue_add.png'
        },
        {
            text: 'Edytuj',
            action: 'edit',
            icon: '../Images/Icons/tag_blue_edit.png'
        },
        {
            text: 'Usuń',
            action: 'del',
            icon: '../Images/Icons/tag_blue_delete.png'
        }
    ]
});