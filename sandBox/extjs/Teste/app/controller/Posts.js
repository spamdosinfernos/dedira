Ext.define('EB.controller.Posts', {
	extend : 'Ext.app.Controller',
	views : [ 'post.List' ],
	models : [ 'Post' ],
	stores : [ 'Posts' ]
});