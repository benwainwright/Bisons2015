<?php

class Teamer {

	private static $teamerURL = 'https://teamer.net/';
	private $login;
	private $password;
	private $htmlBody;
	private $rawHTML;
	public $result;


	function __construct( $login, $password ) {

		$this->login = $login;
		$this->password = $password;
		$this->goutte = new Goutte\Client();

	}

	function request( $url = false, $method = 'GET', $postData = array() ) {

		$url = $url ? $url : self::$teamerURL;
		$this->result = $this->goutte->request('GET', $url);

	}

	function login( ) {

		$this->request();

		$form = $this->result->selectButton('Login')->form();
		$form['email'] = $this->login;
		$form['password'] = $this->password;

		$this->result = $this->goutte->submit($form);
		return $this->result->html();

	}

	function addPlayer() {

	}

	function removePlayer() {

	}

	function addFixture() {

	}

}


