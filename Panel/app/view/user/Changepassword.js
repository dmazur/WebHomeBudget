Ext.define('NB.view.user.Changepassword', {
    extend: 'Ext.window.Window',
    alias: 'widget.userchangepassword',

    title: 'Change Password',
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
                        fieldLabel: 'Old Password',
                        inputType: 'password',
                        allowBlank: false
                    },
                    {
                        xtype: 'textfield',
                        name : 'newPassword1',
                        fieldLabel: 'New Password',
                        inputType: 'password',
                        allowBlank: false
                    },
                    {
                        xtype: 'textfield',
                        name : 'newPassword2',
                        fieldLabel: 'New Password Confirmation',
                        inputType: 'password',
                        allowBlank: false
                    }
                ]
            }
        ];

        this.buttons = [
            {
                text: 'Save',
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
