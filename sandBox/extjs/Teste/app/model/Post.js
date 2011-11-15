Ext.define('EB.model.Post', {
	extend : 'Ext.data.Model',
	fields : [ {
		name : 'id',
		type : 'int'
	}, {
		name : 'title',
		type : 'string'
	}, {
		name : 'content',
		type : 'string'
	} ],
	proxy : {
		type : 'ajax',
		url : 'data/posts.json',
		reader : {
			type : 'json',
			root : 'posts',
			idProperty : 'id'
		}
	}
});