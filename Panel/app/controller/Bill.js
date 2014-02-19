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
        view.setTitle('Edit bill');
        view.method = 'post';
        view.down('form').loadRecord(record);
    },
    
    editBill: function(button) {
        var grid = Ext.getCmp('billlistgrid');
        var record = grid.getSelectionModel().getSelection()[0];
        if (!record) {
            Ext.Msg.alert('Error', 'Choose first bill to edit');
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
            Ext.Msg.alert('Error', 'Choose first bill to delete');
            return;
        }
        Ext.Msg.confirm('Question', 'Are you sure?', function(button) {
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
                            Ext.Msg.alert('Error', resp.msg);
                            return;
                        }
                        store.load();
                        var com = 'Bill deleted successfully.'
                        Ext.ux.Toast.msg('Komunikat', com);
                    },
                    failure: function(response) {
                        var resp = Ext.JSON.decode(response.responseText, true);
                        if (!resp) {
                            Ext.Msg.alert('Error', "No response from server!");
                            return;
                        }
                        Ext.Msg.alert('Error', resp.msg);
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
        view.setTitle('Add bill');
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
                    var com = 'Successfully '+((method === 'put')?'added':'edited')+' bill.'
                    Ext.ux.Toast.msg('Message', com);
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
