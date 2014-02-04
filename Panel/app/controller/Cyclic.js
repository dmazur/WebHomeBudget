Ext.define('NB.controller.Cyclic', {
    extend: 'Ext.app.Controller',
    
    views: [
        'cyclic.List',
        'cyclic.Edit'
    ],
    
    stores: [
        'Cyclic'
    ],
    
    models: [
        'Cyclic'
    ],

    init: function() {
        this.control({
            'cycliclist': {
                itemdblclick: this.editCyclicGrid
            },
            'cycliclist button[action=add]': {
                click: this.addCyclic
            },
            'cycliclist button[action=edit]': {
                click: this.editCyclic
            },
            'cycliclist button[action=del]': {
                click: this.deleteCyclic
            },
            'cyclicedit button[action=save]': {
                click: this.updateCyclic
            }
        });
    },

    editCyclicGrid: function(grid, record) {
        var view = Ext.widget('cyclicedit');
        view.setTitle('Edytuj rachunek cykliczny');
        view.method = 'post';
        view.down('form').loadRecord(record);
    },
    
    editCyclic: function(button) {
        var grid = Ext.getCmp('cycliclistgrid');
        var record = grid.getSelectionModel().getSelection()[0];
        if (!record) {
            Ext.Msg.alert('Błąd', 'Zaznacz najpierw rachunek cykliczny do edycji');
            return;
        }
        var view = Ext.widget('cyclicedit');
        view.setTitle('Edytuj rachunek cykliczny');
        view.method = 'post';
        view.down('form').loadRecord(record);
    },
    
    deleteCyclic: function(button) {
        var grid = Ext.getCmp('cycliclistgrid');
        var record = grid.getSelectionModel().getSelection()[0];
        var store = this.getStore('Cyclic');
        if (!record) {
            Ext.Msg.alert('Błąd', 'Zaznacz najpierw rachunek cykliczny do usunięcia');
            return;
        }
        Ext.Msg.confirm('Pytanie', 'Czy na pewno chcesz usunąć ten rachunek cykliczny?', function(button) {
            if (button === 'yes') {
                var id = record.get('id_cyclic');
                Ext.Ajax.request({
                    url: '/Service/cyclic',
                    method: 'delete',
                    params: {
                        id_cyclic: id
                    },
                    success: function(response) {
                        var resp = Ext.JSON.decode(response.responseText, true);
                        if (!resp.success) {
                            Ext.Msg.alert('Błąd', resp.msg);
                            return;
                        }
                        store.load();
                        var com = 'Poprawnie usunięto rachunek cykliczny, Yea!'
                        Ext.ux.Toast.msg('Komunikat', com);
                    },
                    failure: function(response) {
                        var resp = Ext.JSON.decode(response.responseText, true);
                        if (!resp) {
                            Ext.Msg.alert('Błąd', "Brak odpowiedzi z serwera! :(");
                            return;
                        }
                        Ext.Msg.alert('Błąd', resp.msg);
                        return;
                    }
                })
            } 
            else {
                return;
            }
        });
    },
    
    addCyclic: function(button) {
        var view = Ext.widget('cyclicedit');
        view.method = 'put';
        view.setTitle('Dodaj rachunek cykliczny');
        view.show();
    },
    
    updateCyclic: function(button) {
        var form = Ext.getCmp('cycliceditform');
        var method = form.up('window').method;
        var store = this.getStore('Cyclic');
        if(form.getForm().isValid()) {
            form.getForm().submit({
                url: '/Service/cyclic',
                method: method,
                success: function(form, action) {
                    button.up('window').close();
                    store.load();
                    var com = 'Poprawnie '+((method === 'put')?'dodano':'zedytowano')+' rachunek cykliczny.'
                    Ext.ux.Toast.msg('Komunikat', com);
                },
                failure: function(form, action) {
                    var resp = Ext.JSON.decode(action.response.responseText, true);
                    if (!resp) {
                        Ext.Msg.alert('Błąd', "Brak odpowiedzi z serwera!");
                        return;
                    }
                    Ext.Msg.alert('Błąd', resp.msg);
                    return;
                }
            })
        }
    }
});