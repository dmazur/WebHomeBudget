Ext.define('NB.controller.Bill', {
    extend: 'Ext.app.Controller',
    
    views: [
        'bill.List',
        'bill.Edit'
    ],
    
    stores: [
        'Bill'
    ],
    
    models: [
        'Bill'
    ],

    init: function() {
        this.control({
            'billlist': {
                itemdblclick: this.editBillGrid
            },
            'billlist button[action=add]': {
                click: this.addBill
            },
            'billlist button[action=edit]': {
                click: this.editBill
            },
            'billlist button[action=del]': {
                click: this.deleteBill
            },
            'billedit button[action=save]': {
                click: this.updateBill
            }
        });
    },

    editBillGrid: function(grid, record) {
        var view = Ext.widget('billedit');
        view.setTitle('Edytuj rachunek');
        view.method = 'post';
        view.down('form').loadRecord(record);
    },
    
    editBill: function(button) {
        var grid = Ext.getCmp('libblistgrid');
        var record = grid.getSelectionModel().getSelection()[0];
        if (!record) {
            Ext.Msg.alert('Błąd', 'Zaznacz najpierw rachunek do edycji');
            return;
        }
        var view = Ext.widget('billedit');
        view.setTitle('Edytuj rachunek');
        view.method = 'post';
        view.down('form').loadRecord(record);
    },
    
    deleteBill: function(button) {
        var grid = Ext.getCmp('billlistgrid');
        var record = grid.getSelectionModel().getSelection()[0];
        var store = this.getStore('Bill');
        if (!record) {
            Ext.Msg.alert('Błąd', 'Zaznacz najpierw rachunek do usunięcia');
            return;
        }
        Ext.Msg.confirm('Pytanie', 'Czy na pewno chcesz usunąć ten rachunek?', function(button) {
            if (button === 'yes') {
                var id = record.get('id_bill');
                Ext.Ajax.request({
                    url: '/Service/bill',
                    method: 'delete',
                    params: {
                        id_bill: id
                    },
                    success: function(response) {
                        var resp = Ext.JSON.decode(response.responseText, true);
                        if (!resp.success) {
                            Ext.Msg.alert('Błąd', resp.msg);
                            return;
                        }
                        store.load();
                        var com = 'Poprawnie usunięto rachunek.'
                        Ext.ux.Toast.msg('Komunikat', com);
                    },
                    failure: function(response) {
                        var resp = Ext.JSON.decode(response.responseText, true);
                        if (!resp) {
                            Ext.Msg.alert('Błąd', "Brak odpowiedzi z serwera!");
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
    
    addBill: function(button) {
        var view = Ext.widget('billedit');
        view.method = 'put';
        view.setTitle('Dodaj rachunek');
        view.show();
    },
    
    updateBill: function(button) {
        var form = Ext.getCmp('billeditform');
        var method = form.up('window').method;
        var store = this.getStore('Bill');
        if(form.getForm().isValid()) {
            form.getForm().submit({
                url: '/Service/bill',
                method: method,
                success: function(form, action) {
                    button.up('window').close();
                    store.load();
                    var com = 'Poprawnie '+((method === 'put')?'dodano':'zedytowano')+' rachunek.'
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