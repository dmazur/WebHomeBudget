Ext.ux.Toast = function() {
    var msgCt;

    function createBox(t, s){
        return '<div class="msg"><h3>' + t + '</h3><p>' + s + '</p></div>';
    }

    return {
        msg : function(title, format){
            if(!msgCt){
                msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div',style:'position:absolute;z-index:10000'}, true);
            }
            var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1));
            var m = Ext.DomHelper.append(msgCt, {html:createBox(title, s)}, true);
            msgCt.alignTo(document, 't-t');
            m.slideIn('t').pause(2000).ghost("t", {remove:true});
        }
    }
}();

/**
 * Helps when rendering model name from id in grids
 */
Ext.ux.StoreRenderer = function(store, valueField, displayField) {
    return function(v) {
        var record = store.findRecord(valueField, v);
        if (record) {
            return record.get(displayField);
        }
        return '';
    };
};
