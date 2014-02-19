Ext.define('NB.controller.Header', {
    extend: 'Ext.app.Controller',
    
    views: [
        'header.Bar',
        'user.Options',
        'user.Changepassword'
    ],
    
    stores: [
        'User'
    ],
    
    models: [
        'User'
    ],
    
    initEnv: function() {
        this.getStore('User').getProxy().setExtraParam('login', Env.login);
        this.getStore('User').load();
        var headerBar = Ext.getCmp('headerbarinstance');
        headerBar.down('#loginlabel').setText(Env.login);
    },

    init: function() {
        
        this.control({
            'headerbar button[action=logout]': {
                click: this.logout
            },
            'headerbar button[action=options]': {
                click: this.showOptions
            },
            'headerbar button[action=changepassword]': {
                click: this.showChangePassword
            },
            'useroptions button[action=save]': {
                click: this.saveOptions
            },
            'userchangepassword button[action=save]': {
                click: this.changePassword
            }
        });
    },
    
    logout: function() {
        Ext.Ajax.request({
            url: '/Service/user',
            method: 'post',
            params: {
                command: 'logout'
            },
            success: function(response) {
                var resp = Ext.JSON.decode(response.responseText, true);
                if (!resp) {
                    Ext.Msg.alert('Error', "No response from server!");
                    return;
                }
                location.href = '';
            }
        });
    },
    
    showOptions: function() {
        var view = Ext.widget('useroptions');
        var store = this.getStore('User');
        view.down('form').loadRecord(store.getAt(0));
    },
    
    saveOptions: function(button) {
        var form = Ext.getCmp('useroptionsform');
        var store = this.getStore('User');
        if(form.getForm().isValid()) {
            form.getForm().submit({
                url: '/Service/user',
                method: 'put',
                success: function(form, action) {
                    button.up('window').close();
                    store.load();
                    Ext.ux.Toast.msg('Message', 'Successfully update your information.');
                }
            })
        }
    },
    
    showChangePassword: function(button) {
        var view = Ext.widget('userchangepassword');
        view.show();
    },
    
    changePassword: function(button) {
        var form = Ext.getCmp('userchangepasswordform');
        var values = form.getValues();
        if (values.newPassword1 !== values.newPassword2) {
            var fields = form.getForm().getFields();
            var fieldIndex = fields.findIndex('name', 'newPassword2');
            var invalidField = fields.getAt(fieldIndex);
            invalidField.markInvalid("Passwords don't match");
            return;
        }
        if(form.getForm().isValid()) {
            form.getForm().submit({
                url: '/Service/user',
                method: 'post',
                params: {
                    command: 'changePassword',
                    login: Env.login
                },
                success: function(form, action) {
                    var resp = Ext.JSON.decode(action.response.responseText, true);
                    if (!resp) {
                        Ext.Msg.alert('Error', "No response from server!");
                        return;
                    }
                    if (resp.success == false)
                    {
                        Ext.Msg.alert('Error', resp.msg);
                        return;
                    }
                    Ext.ux.Toast.msg('Message', 'Successfully changed password.');
                    button.up('window').close();
                },
                failure: function(form, action) {
                    var resp = Ext.JSON.decode(action.response.responseText, true);
                    if (!resp) {
                        Ext.Msg.alert('Error', "No response from server!");
                        return;
                    }
                    Ext.Msg.alert('Error', resp.msg);
                    return;
                }
            })
        }
    }
});
