Ext.define('NB.view.stats.Main' ,{
    extend: 'Ext.panel.Panel',
    alias: 'widget.statsmain',
    id: 'statsmainpanel',
    layout: 'fit',

    title: 'Statistics',

    initComponent: function() {
        this.callParent(arguments);
    },

    items: [
        {
            xtype: 'panel',
            layout: 'hbox',
            autoScroll: true,
            items: [
                {
                    xtype: 'combo',
                    store: 'Time',
                    displayField: 'number',
                    valueField: 'number',
                    fieldLabel: 'Rok',
                    margin: 5,
                    labelWidth: 55,
                    id: 'stats_main_year_combo',
                    editable: false,
                    listeners: {
                        select: function(combo, record) {
                            Ext.getCmp('stats_main_month_combo').clearValue();
                            Ext.getCmp('stats_main_month_combo').bindStore(record[0].months());
                            Ext.getCmp('stats_main_chart_pie').getStore().load({params: {
                                year: combo.getValue()
                            }});
                            Ext.getCmp('stats_main_chart_line').getStore().load({params: {
                                year:  Ext.getCmp('stats_main_year_combo').getValue()
                            }});
                        }
                    }
                },
                {
                    id: 'stats_main_month_combo',
                    xtype: 'combo',
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'number',
                    fieldLabel: 'Miesiąc',
                    labelWidth: 55,
                    margin: 5,
                    editable: false,
                    listeners: {
                        select: function(combo, record) {
                            Ext.getCmp('stats_main_chart_pie').getStore().load({params: {
                                year: Ext.getCmp('stats_main_year_combo').getValue(),
                                month:  combo.getValue()
                            }});
                        }
                    }
                },
                {
                    allowBlank: false,
                    xtype: 'combo',
                    name : 'category',
                    fieldLabel: 'Category',
                    labelWidth: 55,
                    margin: 5,
                    store: 'Category',
                    queryMode:'local',
                    valueField: 'id_category',
                    editable: false,
                    displayField: 'name',
                    listeners: {
                        select: function(combo, record) {
                            Ext.getCmp('stats_main_chart_column').getStore().load({params: {
                                category_id: combo.getValue(),
                                year:  Ext.getCmp('stats_main_year_combo').getValue()
                            }});
                        }
                    }
                }
            ]
        },
        {
            xtype: 'panel',
            layout: 'fit',
            // overflowY: 'scroll',
            // height: 700,
            items: [
                {
                    
                    xtype: 'panel',
                    id: 'teafsad',
                    height: 550,
                    layout: {
                        type: 'vbox',
                        align: 'center'
                    },
                     overflowY: 'scroll',
                    // resizable: true,
                    items: [
                        {
                            width: 500,
                            height: 300,
                            animate: true,
                            store: 'stats.SumByTime',
                            id: 'stats_main_chart_line',
                            xtype: 'chart',
                            title: 'Wszystkie wydatki w roku',
                            items: [{
                                type  : 'text',
                                text  : 'Wszystkie wydatki w roku',
                                font  : '14px Arial',
                                width : 100,
                                height: 30,
                                x : 180, //the sprite x position
                                y : 10  //the sprite y position
                            }],
                            axes: [
                                {
                                    type: 'Numeric',
                                    position: 'left',
                                    fields: ['sum'],
                                    label: {
                                        renderer: Ext.util.Format.numberRenderer('0,0')
                                    },
                                    title: '',
                                    grid: true,
                                    minimum: 0
                                },
                                {
                                    type: 'Category',
                                    position: 'bottom',
                                    fields: ['month'],
                                    title: 'Miesiące'
                                }
                            ],
                            series: [
                                {
                                    type: 'column',
                                    axis: 'left',
                                    highlight: true,
                                    tips: {
                                      trackMouse: true,
                                      width: 140,
                                      height: 28,
                                      renderer: function(storeItem, item) {
                                        this.setTitle(storeItem.get('month') + ': ' + storeItem.get('sum'));
                                      }
                                    },
                                    label: {
                                      display: 'insideEnd',
                                      'text-anchor': 'middle',
                                        field: 'sum',
                                        renderer: Ext.util.Format.numberRenderer('0'),
                                        orientation: 'vertical',
                                        color: '#333'
                                    },
                                    xField: 'month',
                                    yField: 'sum'
                                }
                            ]
                        },
                        {
                            width: 500,
                            height: 350,
                            animate: true,
                            store: 'stats.CategoryPartByTime',
                            theme: 'Base:gradients',
                            id: 'stats_main_chart_pie',
                            xtype: 'chart',
                            series: [{
                                type: 'pie',
                                angleField: 'sum',
                                showInLegend: true,
                                tips: {
                                    trackMouse: true,
                                    width: 140,
                                    height: 28,
                                    renderer: function(storeItem, item) {
                                        // calculate and display percentage on hover
                                        var total = 0;
                                        storeItem.store.each(function(rec) {
                                            total += rec.get('sum');
                                        });
                                        this.setTitle(storeItem.get('name') + ': ' + Math.round(storeItem.get('sum') / total * 100) + '%');
                                    }
                                },
                                highlight: {
                                    segment: {
                                        margin: 20
                                    }
                                },
                                label: {
                                    field: 'name',
                                    display: 'rotate',
                                    contrast: true,
                                    font: '18px Arial'
                                }
                            }]
                        },
                        {
                            width: 500,
                            height: 300,
                            animate: true,
                            store: 'stats.CategorySumByTime',
                            id: 'stats_main_chart_column',
                            xtype: 'chart',
                            axes: [
                                {
                                    type: 'Numeric',
                                    position: 'left',
                                    fields: ['sum'],
                                    label: {
                                        renderer: Ext.util.Format.numberRenderer('0,0')
                                    },
                                    title: '',
                                    grid: true,
                                    minimum: 0
                                },
                                {
                                    type: 'Category',
                                    position: 'bottom',
                                    fields: ['month'],
                                    title: 'Miesiące'
                                }
                            ],
                            items: [{
                                type  : 'text',
                                text  : 'Wydatki wg kategorii',
                                font  : '14px Arial',
                                width : 100,
                                height: 30,
                                x : 180, //the sprite x position
                                y : 10  //the sprite y position
                            }],
                            series: [
                                {
                                    type: 'column',
                                    axis: 'left',
                                    highlight: true,
                                    tips: {
                                      trackMouse: true,
                                      width: 140,
                                      height: 28,
                                      renderer: function(storeItem, item) {
                                        this.setTitle(storeItem.get('month') + ': ' + storeItem.get('sum'));
                                      }
                                    },
                                    label: {
                                      display: 'insideEnd',
                                      'text-anchor': 'middle',
                                        field: 'sum',
                                        renderer: Ext.util.Format.numberRenderer('0'),
                                        orientation: 'vertical',
                                        color: '#333'
                                    },
                                    xField: 'month',
                                    yField: 'sum'
                                }
                            ]
                        }
                    ]
                }
            ]    
        }
    ]
});

