#!/usr/bin/perl

#Dados da venda
my $codigoDoCupomDeVenda   = "09682020";
my $dataDaVenda            = "2010-02-03 14:58:00";
my $precoDoProdutoVendido  = "1.00";
my $codigoDoProdutoVendido = "TT01";
my $status                 = "CA";

#Dados do cliente
my $tipoDeCliente              = 2;
my $tipoDePessoa               = "F";
my $tipoDeInterface            = "CLI";
my $nomeOuRazaoSocialDoCliente = "Vinicius";
my $sobrenomeDoCliente         = "";
my $nomeFantasiaOuDoCliente    = "Vinicius";

#Dados de contato com o cliente
my $ddd            = "11";
my $fax            = "";
my $foneDoCliente  = "92468892";
my $emailDoCliente = "tatupheba@gmail.com.br";

#Identificação do cliente
my $rg                 = "427390400";
my $cpfOuCnpjDoCliente = "30007769822";
my $inscricaoEstadual  = "";
my $inscricaoMunicipal = "";

#Endereço do cliente
my $cepDoCliente                   = "03040010";
my $ufDoCliente                    = "SP";
my $cidadeDoCliente                = "São Paulo";
my $bairroDoCliente                = "Brás";
my $enderecoDoCliente              = "Rua Carneiro Leão";
my $numeroDeEnderecoDoCliente      = "290";
my $complementoDeEnderecoDoCliente = "apto 84, bloco 2";


#$codigoDoCupomDeVenda,       $dataDaVenda,
#$precoDoProdutoVendido,      $codigoDoProdutoVendido,
#$status,                     $tipoDeCliente,
#$tipoDePessoa,               $tipoDeInterface,
#$nomeOuRazaoSocialDoCliente, $sobrenomeDoCliente,
#$nomeFantasiaOuDoCliente,    $ddd,
#$fax,                        $foneDoCliente,
#$emailDoCliente,             $rg,
#$cpfOuCnpjDoCliente,         $inscricaoEstadual,
#inscricaoMunicipal,         $cepDoCliente,
#$ufDoCliente,                $cidadeDoCliente,
#$bairroDoCliente,            $enderecoDoCliente,
#$numeroDeEnderecoDoCliente,  $complementoDeEnderecoDoCliente
sub inserirVenda() {

	use SOAP::Lite;

	my $wsClient =
	  SOAP::Lite->service('http://172.16.100.31/ws_integracao/server.wsdl');

	return $wsClient->inserir(
		$_[0],  $_[1],  $_[2],  $_[3],  $_[4],  $_[5],  $_[6],
		$_[7],  $_[8],  $_[9],  $_[10], $_[11], $_[12], $_[13],
		$_[14], $_[15], $_[16], $_[17], $_[18], $_[19], $_[20],
		$_[21], $_[22], $_[23], $_[24], $_[25]
	);
}

$teste = &inserirVenda(
	$codigoDoCupomDeVenda,       $dataDaVenda,
	$precoDoProdutoVendido,      $codigoDoProdutoVendido,
	$status,                     $tipoDeCliente,
	$tipoDePessoa,               $tipoDeInterface,
	$nomeOuRazaoSocialDoCliente, $sobrenomeDoCliente,
	$nomeFantasiaOuDoCliente,    $ddd,
	$fax,                        $foneDoCliente,
	$emailDoCliente,             $rg,
	$cpfOuCnpjDoCliente,         $inscricaoEstadual,
	$inscricaoMunicipal,         $cepDoCliente,
	$ufDoCliente,                $cidadeDoCliente,
	$bairroDoCliente,            $enderecoDoCliente,
	$numeroDeEnderecoDoCliente,  $complementoDeEnderecoDoCliente
);

print $teste->{cartao};
print "\n";
print $teste->{codigoDeAtivacao};
print "\n";
print $teste->{codigosDaMensagem};
print "\n";
print $teste->{mensagem};
print "\n";
print $teste->{rps};
print "\n";

