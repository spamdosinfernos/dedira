<?php
require_once __DIR__ . '/../BaseDeDados/CBaseDeDados.php';

/**
 * Responsável pelas operações de autenticação no sistema
 * @author andre
 */
class CAutenticador{

	/**
	 * Realiza a autenticação de um usuário no sistema
	 * @return null - Falha na autenticação | string - Id do usuário
	 */
	public function __construct(){
		
		$realm = 'Área restrita, caia fora!';
		$users = array('tatupheba' => 'tatu7', 'guest' => 'guest');

		//Verifica se os dados de autenticação foram enviados.
		if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
			//Caso os dados de autenticação não sejam informados, faz uma requisição http de autenticação
			header('HTTP/1.1 401 Unauthorized');
			header('WWW-Authenticate: Digest realm="' . $realm . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
			die("sa");
		}

		//Trata os dados enviados pela autenticação
		$data = $this->httpDigestParse($_SERVER['PHP_AUTH_DIGEST']);
		
		//Se nenhum usuário foi informado sai emitindo valor nulo
		if(!isset($users[$data['username']])){
			return null;
		}

		//Verifica se uma resposta correta foi gerada
		$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
		$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
		$respostaValida = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

		if ($data['response'] != $respostaValida){
			return null;
		}
		
		//TODO retornar o id do usuário
		//Está tudo ok!!
		return $data['username'];
	}

	// function to parse the http auth header
	private function httpDigestParse($txt){
		// protect against missing data
		$needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
		$data = array();
		$keys = implode('|', array_keys($needed_parts));

		preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

		foreach ($matches as $m) {
			$data[$m[1]] = $m[3] ? $m[3] : $m[4];
			unset($needed_parts[$m[1]]);
		}

		return $needed_parts ? false : $data;
	}
}

?>