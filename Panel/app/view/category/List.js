Ext.define('NB.view.category.List' ,{
    extend: 'Ext.grid.Panel',
    alias: 'widget.categorylist',
    id: 'categorylistgrid',

    title: 'Category',
    
    store: 'Category',

    initComponent: function() {
        this.columns = [
            {dataIndex: 'color', width: 5, renderer: Ext.ux.RendererColor},
            {header: 'Name',  dataIndex: 'name',  flex: 1}
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
