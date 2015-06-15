<?php

class Teamer {

	private static $teamerURL = 'https://teamer.net/';
	private $login;
	private $password;
	private $htmlBody;
	private $rawHTML;


	function __construct( $login, $password ) {

		$this->login = $login;
		$this->password = $password;

	}

	function request( $url = false, $method = 'GET', $postData = array() ) {

		$url = $url ? $url : $this::$teamerURL;



		$args = array(
			'method'      => $method,
			timeout       => 10,
			'user-agent'  => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
		);

		if ( count ($postData) > 0 ) {

			$args['body'] = $postData;
		}

		$response = wp_remote_request($url, $args);

		if ( $response instanceof WP_Error ) {
			new dBug($response);
		}

		else
		{
			$this->rawHTML = $response['body'];
			$this->htmlBody = str_get_html($this->rawHTML);

			return $this->htmlBody;

		}
	}

	function login( ) {

		$html = $this->request();

		$loginForm = $html->find('form#login_form')[0];

		$action = $loginForm->action;
		$authToken = $loginForm->find('input[name="authenticity_token"]')[0]->value;

		$data = array(
			'email'                 => $this->email,
			'password'              => $this->password,
			'authenticity_token'    => $authToken,
			'remember_me'           => 1
		);

		$this->request( $action, 'POST', $data);

		// fields email, password, authenticity_token, remember_me

		return $this->rawHTML;

	}

	function addPlayer() {

	}

	function removePlayer() {

	}

	function addFixture() {

	}

}


