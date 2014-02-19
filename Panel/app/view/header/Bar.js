Ext.define('NB.view.header.Bar' ,{
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.headerbar',

    title: 'WebHomeBudget',

    initComponent: function() {
        this.items = [
            {
                xtype: 'label',
                text: 'WebHomeBudget'
            },
            '->',
            {
                xtype: 'label',
                text: '',
                id: 'loginlabel'
            },
            '-',
            {
                xtype: 'button',
                action: 'changepassword',
                text: 'Change password',
                icon: '../Images/Icons/key.png'
            },
            {
                xtype: 'button',
                action: 'options',
                text: 'Options',
                icon: '../Images/Icons/cog.png'
            },
            {
                xtype: 'button',
                action: 'logout',
                text: 'Log Out',
                icon: '../Images/Icons/door_out.png'
            }
        ];
        
        this.callParent(arguments);
    }
});
