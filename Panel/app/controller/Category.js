Ext.define('NB.controller.Category', {
    extend: 'Ext.app.Controller',
    
    views: [
        'category.List',
        'category.Edit'
    ],
    
    stores: [
        'Category'
    ],
    
    models: [
        'Category'
    ],

    init: function() {
        this.control({
            'categorylist': {
                itemdblclick: this.editCategoryGrid
            },
            'categorylist button[action=add]': {
                click: this.addCategory
            },
            'categorylist button[action=edit]': {
                click: this.editCategory
            },
            'categorylist button[action=del]': {
                click: this.deleteCategory
            },
            'categoryedit button[action=save]': {
                click: this.updateCategory
            }
        });
    },
    
    editCategoryGrid: function(grid, record) {
        var view = Ext.widget('categoryedit');
        view.setTitle('Edit category');
        view.method = 'post';
        view.down('form').loadRecord(record);
    },
    
    editCategory: function(button) {
        var grid = Ext.getCmp('categorylistgrid');
        var record = grid.getSelectionModel().getSelection()[0];
        if (!record) {
            Ext.Msg.alert('Error', 'Choose category first');
            return;
        }
        var view = Ext.widget('categoryedit');
        view.setTitle('Edit category');
        view.method = 'post';
        view.down('form').loadRecord(record);
    },
    
    deleteCategory: function(button) {
        var grid = Ext.getCmp('categorylistgrid');
        var record = grid.getSelectionModel().getSelection()[0];
        var store = this.getStore('Category');
        if (!record) {
            Ext.Msg.alert('Error', 'Choose first category to delete');
            return;
        }
        Ext.Msg.confirm('Question', 'Are you sure?', function(button) {
            if (button === 'yes') {
                var id = record.get('id_category');
                Ext.Ajax.request({
                    url: '/Service/category',
                    method: 'delete',
                    params: {
                        id_category: id
                    },
                    success: function(response) {
                        var resp = Ext.JSON.decode(response.responseText, true);
                        if (!resp.success) {
                            Ext.Msg.alert('Error', resp.msg);
                            return;
                        }
                        store.load();
                        var com = 'Category deleted successfully.'
                        Ext.ux.Toast.msg('Message', com);
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
    
    addCategory: function(button) {
        var view = Ext.widget('categoryedit');
        view.method = 'put';
        view.setTitle('Add category');
        view.show();
    },
    
    updateCategory: function(button) {
        var form = Ext.getCmp('categoryeditform');
        var method = form.up('window').method;
        var store = this.getStore('Category');
        if(form.getForm().isValid()) {
            form.getForm().submit({
                url: '/Service/category',
                method: method,
                success: function(form, action) {
                    button.up('window').close();
                    store.load();
                    var com = 'Successfully '+((method === 'put')?'added':'edited')+' category.'
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
