Ext.define('NB.view.user.Options', {
    extend: 'Ext.window.Window',
    alias: 'widget.useroptions',

    title: 'User Options',
    layout: 'fit',
    autoShow: true,
    modal: true,

    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                id: 'useroptionsform',
                bodyPadding: 10,
                items: [
                    {
                        name: 'id_user', 
                        xtype: 'hidden'
                    }, 
                    {
                        fieldLabel: 'Login',
                        name: 'login', 
                        xtype: 'textfield',
                        minLength: 6,
                        maxLength: 255,
                        maskRe: /^[0-9a-z_.]+$/i,
                        regex: /^[0-9a-z_.]+$/i,
                        regexText: 'Login can be only alphadigits, dots or underscore',
                        allowBlank: false
                    },
                    {
                        fieldLabel: 'E-mail',
                        name: 'email', 
                        xtype: 'textfield',
                        allowBlank: false
                    }
                ]
            }
        ];

        this.buttons = [
            {
                text: 'Save',
                action: 'save',
                icons: 'Images/Icons/disk.png'
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
