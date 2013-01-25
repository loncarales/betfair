Ext.define('Betfair.view.west.List', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.marketlist',
	
	id: 'app-markelist',
	title:'Markets',
    width:225,
    minSize:225,
    maxSize:300,
    animCollapse: true,
    collapsible:true,
    layout:'accordion',
    layoutConfig:{
        animate: true
    },
    items: [{
        title:'Favourites',
        autoScroll: true,
        border: false,
        iconCls: 'nav'
    },{
        title:'Sports',
        border: false,
        autoScroll: true,
        iconCls: 'settings'
    }],
    
	initComponent: function() { 
        this.callParent(arguments);
    }
});