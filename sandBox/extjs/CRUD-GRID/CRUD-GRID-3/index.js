Ext.onReady(function(){
	
	Ext.grid.ColumnModel.prototype.defaults = {sortable: true};
	
	//Aqui faço algumas definições padrão para alguns componentes:
	//Todos os campos ficam com mensagem de erro ao lado, quando tiverem
	Ext.form.Field.prototype.msgTarget      = 'side';
	//Espaçamento ao redor do formulário
	Ext.form.FormPanel.prototype.bodyStyle  = 'padding:5px';
	//Alinhamento dos labels a direita
	Ext.form.FormPanel.prototype.labelAlign = 'right';
	//Aqui iniciamos o QuickTips para que quando o usuário passar
	//o mouse sobre o icone de erro de um campo seja mostrado
	//uma dica com o erro
	Ext.QuickTips.init();
	
	var usuario = Ext.extend(Ext.util.Observable, {
		//Aqui separa o titulo da janela do formulário pq irei muda-lo
		//de acordo com a ação insert ou update
		formTitle: 'Cadastro de Usuários ',
		
		constructor: function(){
			with (this){
				initStores();
				//Adiciono mais uma função de inicialização
				initForm();
				initPrincipal();
			}
		},
		
		//Função que será chamada ao clicar no botão de adicionar da janela principal
		adicionar: function(){
			//Defino uma propriedade na janela do form pra indicar que estamos abrindo-a
			//para editar um registro e assim poder definir o que fazer na hora de salvar
			this.winForm.update = false;
			//Aqui altero o titulo pegando aquele titulo salvo acima e adicionando um texto
			//indicando que ação estamos executando
			this.winForm.setTitle(this.formTitle+'[Inserindo]');
			//Mostramos a janela
			this.winForm.show();
			//Procuro o campo usu_login e defino que ele não é somente leitura podendo ser editado
			this.form.getForm().findField('usu_login').setReadOnly(false);
			//Limpo o formulário para iniciar a inserção de um novo registro
			this.form.getForm().reset();
		},
		
		//Função a ser chamada quando clicar no botão de editar da janela principal
		editar: function(){
			//Para editar precisamos que o usuário tenha selecionado um registro então
			//verificamos se existe alguama seleção no nosso grid.
			if(this.grid.getSelectionModel().hasSelection()){
				//Mudamos nossa propriedade para indicar que a janela está em modo de atualização
				this.winForm.update = true;
				//Mudamos o titulo para indicar ao usuário a ação que está sendo executada
				this.winForm.setTitle(this.formTitle+'[Alterando]');
				//mostramos a janela
				this.winForm.show();
				//Colocamos o campo usu_login como somente leitura, afinal ele é nossa chave primaria
				//e não pode ser alterada porque será usada em nossa clausula WHERE no PHP
				this.form.getForm().findField('usu_login').setReadOnly(true);
				//Carregamos o registro selecionado no grid para o nosso formulário
				//Lembrando que se tivermos os campos do form com os seus nomes iguais aos campos
				//do store basta fazer como abaixo
				this.form.getForm().loadRecord(this.grid.getSelectionModel().getSelected());
			}else{
				//Caso não tenhamos nenhuma linha selecionada avisamos ao usuário
				Ext.Msg.alert('Atenção', 'Selecione um registro');
			}
		},
		
		//Função a ser chamada quando clicar no botão de deletar da tela principal
		deletar: function(){
			//Novamente verificamos se o usuário selecionou alguma linha
			if(this.grid.getSelectionModel().hasSelection()){
				//Separamos o registro selecionado para uma variavel para evitar de
				//chamar estas funções com frequencia ja que usarei este registro mais
				//de uma vez abaixo
				var record = this.grid.getSelectionModel().getSelected();
				//Perguntamos ao usuário se ele realmente deseja excluir o registro
				Ext.Msg.confirm('Atenção', 'Você está prestes a excluir o usuário <b>'+record.data.usu_nome+'</b>. Deseja continuar?', function(btn){
					//Testamos qual botão ele clicou
					if(btn == 'yes'){
						//Se ele aceitou blz, criamos um AJAX passando o registro que queremos deletar
						Ext.Ajax.request({
							//Aqui o arquivo php que interage com nosso banco
							url: 'php/usuarios.php',
							//Paramentros que passaremos por POST
							params: {
								//Ação a ser executada
								_action: 'delete',
								//E passamos o login do cara que queremos deletar, pq só o login?
								//Pq o login é nossa chave primária, só preciamos dela pra fazer um delete
								usu_login: record.data.usu_login
							},
							//Função chamada quando não houver nenhum erro de pagina como 404, 500
							success: function(r){
								//Se tudo OK, pegamos a resposta que é um JSON e decodificamos para um objeto
								var obj = Ext.decode(r.responseText);
								//Verificamos se obtivemos sucesso na ação
								if(obj.success){
									//Se sim removemos o registro do nosso store, menos trabalhoso que efetuar um reload
									this.dsUsuarios.remove(record);
								}else{
									//Caso tenha acontecido um erro mostra uma mensagem ao usuário com um texto vindo do servidor
									Ext.Msg.alert('Erro', obj.msg);
								}
							},
							//Função executada se tivermos um erro de arquivo n encontrado ou coisa do tipo, 404, 500, etc
							failure: function(){
								//Mostramos uma mensagem ao usuário pedindo para contatar o administrador
								Ext.Msg.alert('Erro', 'Ocorreu um erro ao se comunicar com o servidor, tente novamente. Se o erro persistir entre em contato com o adiministrador do sistema')
							},
							scope: this
						})
					}
				}, this)
			}else{
				//Se não tivermos uma linha selecionada no grid avisa ao usuário
				Ext.Msg.alert('Atenção', 'Selecione um registro');
			}
		},
		
		//Função a ser chamada quando clicar no botão salvar do formulário
		salvar: function(){
			//Verificamos se o formulário está valido de acordo com cada campo
			if(this.form.getForm().isValid()){
				//Se sim colocamos uma mascara de "Salvando" na janela do form
				//Impedindo que o usuário fique funçando na tela
				this.winForm.el.mask('Salvando', 'x-mask-loading');
				//Usamos a função do form para salvar os dados, submit
				//os dados vão por AJAX em POST
				this.form.getForm().submit({
					//Arquivo que faz a interação com o banco
					url: 'php/usuarios.php',
					params: {
						//Aqui temos if compacto para verificar qual a ação que estava
						//sendo executada no form
						_action: this.winForm.update ? 'update' : 'insert'
					},
					//Função chamada se retornado success:true
					success: function(){
						//Então se tudo ok retiramos a mascara de 'Salvando'
						this.winForm.el.unmask();
						//E fechamos a janela
						this.winForm.hide();
						//Recarregamos o grid para visualizarmos as mudanças
						this.dsUsuarios.reload();
					},
					//Função chamada se retornado success:false
					failure: function(form, action){
						//Se tivemos problemas tiramos a mascara
						this.winForm.el.unmask();
						//E mostramos uma mensagem ao usuário informando o erro
						//vindo do servidor
						Ext.Msg.alert('Erro', action.result.msg);
					},
					scope: this
				})
			}else{
				//Se temos algum campo inválido avisamos ao usuário
				Ext.Msg.alert('Atenção', 'Exixtem campos inválidos');
			}
		},
		
		//Função chamada ao clicar no botão de cancelar do formulário
		cancelar: function(){
			//Apenas fechamos a janela
			this.winForm.hide();
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
				totalProperty: 'total',
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
					_action: 'selectLimited',
					start  : 0,
					limit  : 6
				}
			})
		},
		
		//Aqui nossa função que irá criar nosso form
		initForm: function(){
			//Criamos o formulário
			this.form = new Ext.form.FormPanel({
				border: false, //Tiramos a borda azul
				labelWidth: 70, //Definimos a largura dos labels
				items: [{
					xtype     : 'textfield', //Tupo do campo
					name      : 'usu_nome', //Nome a ser enviado pro server e para ser carregado do store
					fieldLabel: 'Nome', //Nome visível ao usuário
					width     : 300, //Largura do campo
					allowBlank: false //Não permite campo em branco
				},{
					xtype     : 'textfield',
					name      : 'usu_login',
					fieldLabel: 'Login',
					width     : 100,
					allowBlank: false
				},{
					xtype     : 'textfield',
					name      : 'usu_senha',
					fieldLabel: 'Senha',
					width     : 100,
					allowBlank: false,
					col       : true //Criamos uma coluna, uso aqui meu override do formpanel
				},{
					xtype     : 'textfield',
					name      : 'usu_email',
					fieldLabel: 'E-mail',
					width     : 300,
					allowBlank: true
				},{
					xtype     : 'datefield',
					name      : 'usu_data_nascimento',
					fieldLabel: 'Data Nasc.',
					width     : 100,
					allowBlank: false
				},{
					xtype     : 'combo',
					name      : 'cat_id',
					fieldLabel: 'Categoria',
					width     : 100,
					allowBlank: false,
					col       : true,
					//Store de onde o combo pegara sua lista de dados
					store        : this.dsCategorias,
					//Campo que será usado como valor
					valueField   : 'cat_id',
					//Campo que será mostrado na lista
					displayField : 'cat_descricao',
					//Nome do parametro que será enviado ao php com o valor
					hiddenName   : 'cat_id',
					//necessário para o combo buscar os dados do store
					triggerAction: 'all'
				}]
			})
			
			//Aqui criamos a janela que conterá nosso form
			this.winForm = new Ext.Window({
				title      : this.formTitle,
				height     : 180,
				width      : 430,
				modal      : true, //Bloqueia o resto da aplicação forçando o usuário a terminar a ação que começou nesta tela
				closeAction: 'hide', //Quando fechada a janela é apenas escondida para não precisar ser criada novamente
				layout     : 'fit', //Aqui definimos que o filho desta tela irá ocupar toda a área disponivel na janela
				items      : this.form,
				buttonAlign: 'center', //Alinhamos os botões no meio horizontalmente
				buttons    : [{
					text   : 'Salvar',
					iconCls: 'btn-save',
					scope  : this,
					handler: this.salvar
				},{
					text   : 'Cancelar',
					iconCls: 'btn-cancel',
					scope  : this,
					handler: this.cancelar
				}]
			})
		},
		
		initPrincipal: function(){
			this.grid = new Ext.grid.GridPanel({
				store     : this.dsUsuarios,
				loadMask  : true,
				border    : false,
				stripeRows: true,
				columns: [{
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
			
			this.ptb = new Ext.PagingToolbar({
				store: this.dsUsuarios,
				pageSize: this.dsUsuarios.baseParams.limit,
				displayInfo: true
			})
			
			this.winGrid = new Ext.Window({
				title : '<center>CRUD Completo - Parte 3</center>',
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
				bbar: this.ptb,
				//Aqui apenas adicionamos uma barra no topo com com os botões de adicionar, editar e deletar
				tbar: [{
					text   : 'Adicionar',
					iconCls: 'btn-add',
					scope  : this,
					handler: this.adicionar
				},{
					text   : 'Editar',
					iconCls: 'btn-edit',
					scope  : this,
					handler: this.editar
				},{
					text   : 'Deletar',
					iconCls: 'btn-delete',
					scope  : this,
					handler: this.deletar
				}]
			})
			
			this.winGrid.show();
		}
	});
	var cadUsuario = new usuario;
})