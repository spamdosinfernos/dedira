Ext.onReady(function(){
	
	Ext.grid.ColumnModel.prototype.defaults = {sortable: true};
	
	var usuario = Ext.extend(Ext.util.Observable, {
		
		constructor: function(){
			with (this){
				initStores();
				initPrincipal();
			}
		},
		
		initStores: function(){
			this.dsCategorias = new Ext.data.JsonStore({
				url: 'php/categorias.php',
				root: 'data',
				idProperty: 'cat_id',
				fields: [
					'cat_id',
					'cat_descricao'
				],
				baseParams: {
					_action: 'select'
				}
			})
			
			this.dsUsuarios = new Ext.data.JsonStore({
				url: 'php/usuarios.php',
				root: 'data',
				totalProperty: 'total', //Aqui definimos a propriedade que contém a quantidade de registros no banco, necessário para a paginação
				fields: [
					'usu_login',
					'usu_nome',
					'usu_senha',
					'usu_email',
					{name: 'usu_data_nascimento', type: 'date', dateFormat: 'Y-m-d g:i:s'},
					{name: 'cat_id', type: 'int'},
					{name: 'inserted', type: 'bool'}
				],
				baseParams: {
					_action: 'selectLimited', //Aqui mudamos o método que queremos executar para um novo
					start  : 0, //Temos que passar também em qual registro queremos iniciar
					limit  : 6  //e aqui passamos quantos registros queremos trazer
				}
			})
		},
		
		initPrincipal: function(){
			this.grid = new Ext.grid.GridPanel({
				store     : this.dsUsuarios,
				loadMask  : true,
				border    : false,
				stripeRows: true,
				columns : [{
					dataIndex: 'usu_nome', 
					header   : 'Usuário',
					width    : 170
				},{
					dataIndex: 'usu_login', 
					header   : 'Login',
					width    : 50
				},{
					dataIndex: 'usu_senha', 
					header   : 'Senha',
					width    : 50
				},{
					dataIndex: 'usu_email', 
					header   : 'E-mail',
					width    : 180
				},{
					dataIndex: 'usu_data_nascimento', 
					header   : 'Dt. Nasc.',
					width    : 70,
					xtype    : 'datecolumn'
				},{
					dataIndex: 'cat_id',
					header   : 'Categoria',
					width    : 80,
					renderer: {
						scope: this,
						fn   : function(value, metaData, record, rowIndex, colIndex, store){
							if(this.dsCategorias.getById(value)){
								return this.dsCategorias.getById(value).data.cat_descricao;
							}
						}
					}
				}],
				viewConfig: {
					forceFit: true,
					emptyText: '<center>Sem registros para exibir</center>'
				}
			});
			
			//Aqui criamos nosso PagingToolbar
			this.ptb = new Ext.PagingToolbar({
				//Passamos o store que queremos paginar
				store: this.dsUsuarios,
				//Aqui passamos a quantidade de registros que queremos por página
				//Note que pego isso diretamente daquele valor que definimos como limit
				//no nosso store, isso facilita na hora de mudar a quantidade, mudando 
				//apenas no store
				pageSize: this.dsUsuarios.baseParams.limit,
				//Aqui definimos que queremos que o pagingToolBar mostre algumas informações
				//a direita dos seus botões, exemplo "1 á 6 de 7 registros"
				displayInfo: true
			})
			
			this.winGrid = new Ext.Window({
				title : '<center>CRUD Completo - Parte 2</center>',
				height: 240,
				width : 620,
				layout: 'fit',
				items : this.grid,
				listeners: {
					show: function(){
						this.dsCategorias.load({
							callback: function(){
								this.dsUsuarios.load();
							},
							scope: this
						});
					},
					scope: this
				},
				//Aqui atribuimos o pagingToolBar a barra de baixo da janela
				bbar: this.ptb
			})
			
			this.winGrid.show();
		}
	});
	var cadUsuario = new usuario;
})