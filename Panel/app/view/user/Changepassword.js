Ext.define('NB.view.user.Changepassword', {
    extend: 'Ext.window.Window',
    alias: 'widget.userchangepassword',

    title: 'Zmień hasło',
    layout: 'fit',
    autoShow: true,
    modal: true,

    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                id: 'userchangepasswordform',
                bodyPadding: 10,
                items: [
                    {
                        xtype: 'textfield',
                        name : 'oldPassword',
                        fieldLabel: 'Stare hasło',
                        inputType: 'password',
                        allowBlank: false
                    },
                    {
                        xtype: 'textfield',
                        name : 'newPassword1',
                        fieldLabel: 'Nowe hasło',
                        inputType: 'password',
                        allowBlank: false
                    },
                    {
                        xtype: 'textfield',
                        name : 'newPassword2',
                        fieldLabel: 'Powtórz nowe hasło',
                        inputType: 'password',
                        allowBlank: false
                    }
                ]
            }
        ];

        this.buttons = [
            {
                text: 'Zapisz',
                action: 'save',
                icon: '../Images/Icons/disk.png'
            },
            {
                text: 'Cancel',
                scope: this,
                handler: this.close,
                icon: '../Images/Icons/cancel.png'
            }
        ];

        this.callParent(arguments);
    }
});