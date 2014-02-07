Ext.define('NB.view.category.List' ,{
    extend: 'Ext.grid.Panel',
    alias: 'widget.categorylist',
    id: 'categorylistgrid',

    title: 'Kategorie',
    
    store: 'Category',

    initComponent: function() {
        this.columns = [
            {dataIndex: 'color', width: 5, renderer: Ext.ux.RendererColor},
            {header: 'Nazwa',  dataIndex: 'name',  flex: 1}
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