Ext.define('NB.controller.Stats', {
    extend: 'Ext.app.Controller',
    
    views: [
        'stats.Main'
    ],
    
    stores: [
        'Time',
        'stats.CategoryPartByTime',
        'stats.CategorySumByTime',
        'stats.SumByTime',
        'Category'
    ],
    
    models: [
        'Year',
        'Month',
        'stats.CategoryPartByTime',
        'stats.CategorySumByTime',
        'stats.SumByTime',
        'Category'
    ],

    init: function() {

        this.control({
        });
    }
});