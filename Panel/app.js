// URL prefix initialization
// comment/delete if not required
Ext.require('Ext.Ajax');

Ext.onReady( function() {       
    Ext.Ajax.on("beforerequest", function(conn, options, eOpts){
        options.url = URLPrefix + options.url;
    });
});

var Env = null;

Ext.application({
    requires: ['Ext.container.Viewport'],
    name: 'NB',

    appFolder: 'app',
    
    controllers: [
        'Header',
        'Category',
        'Bill',
        'Cyclic',
        'Stats'
    ],

    launch: function() {
        var self = this;
        Ext.Ajax.request({
            url: '/Service/user',
            method: 'POST',
            params: {
                command: 'getLogin'
            },
            success: function(response) {
                var resp = Ext.JSON.decode(response.responseText, true);
                if (!resp) {
                    Ext.Msg.alert('Błąd', "Brak odpowiedzi z serwera!");
                    return;
                }
                if (resp.success === false)
                {
                    return;
                }
                Env = {
                    login: resp.login
                }
                self.createViewport();
                self.getController('Header').initEnv();
            }
        })
        
    },
    
    createViewport: function() {
        Ext.create('Ext.container.Viewport', {
            layout: {
                type: 'vbox',
                align: 'stretch',
                pack: 'start'
            },
            items: [
                {
                    xtype: 'headerbar',
                    id: 'headerbarinstance'
                },
                {
                    xtype: 'tabpanel',
                    listeners: {
                        tabchange: function(panel, value) {
                            if (typeof panel.getName === 'function') {
                                value.getStore().load();
                            }
                        }
                    },
                    items: [
                        {
                            xtype: 'billlist',
                            icon: '../Images/Icons/table.png'
                        },
                        {
                            xtype: 'cycliclist',
                            icon: '../Images/Icons/clock.png'
                        },
                        {
                            xtype: 'categorylist',
                            icon: '../Images/Icons/tag_blue.png'
                        },
                        {
                            xtype: 'statsmain',
                            icon: '../Images/Icons/chart_pie.png'
                        }
                    ]
                }
            ]
        });
    }
});