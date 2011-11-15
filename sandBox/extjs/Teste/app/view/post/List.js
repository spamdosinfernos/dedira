Ext.define('EB.view.post.List', {
	extend : 'Ext.view.View',
	alias : 'widget.postlist',

	// inits
	initComponent : function() {
		Ext.apply(this, {
			title : 'Posts',
			store : 'Posts',
			itemSelector : 'div.post-wrap',
			tpl : new Ext.XTemplate(
				'<tpl for=".">',
                    '<div style="margin-bottom: 10px;" class="post-wrap">',
                        '<h2>{title}</h2>',
                        '<p>{content}</p>',
                    '</div>',
                '</tpl>'
			)
		});

		this.callParent(arguments);
		this.store.load();
	}
});