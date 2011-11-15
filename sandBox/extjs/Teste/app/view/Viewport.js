Ext.define('EB.view.Viewport', {
	extend : 'Ext.container.Viewport',

	layout : 'border',
	padding : 5,

	items : [ {
		xtype : 'container',
		html : 'ExtBlog by Bruno Tavares',
		region : 'north',
		height : 40
	}, {
		xtype : 'tabpanel',
		region : 'center',
		items : [ {
			xtype: 'postlist'
		}, {
			title : 'Post 1',
			html : '...post 1...'
		} ]
	}, {
		xtype : 'panel',
		region : 'east',
		html : '...here goes additional resources...',
		split : true,
		width : 400
	} ]
});