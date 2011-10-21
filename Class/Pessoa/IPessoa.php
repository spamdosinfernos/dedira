<?php
interface IPessoa{
	
	public function autenticar($user, $password);

	public function getNome();

	public function setNome($nome);

	public function getSobrenome();

	public function setSobrenome($sobrenome);

	public function getDataDeNascimento();

	public function setDataDeNascimento($dataDeNascimento);

	public function getSexo();

	public function setSexo($sexo);

	public function getArrTelefone();

	public function setArrTelefone($arrTelefone);

	public function getNivelDeAcessoAoSitema();

	public function setNivelDeAcessoAoSitema($nivelDeAcessoAoSitema);
}
?>