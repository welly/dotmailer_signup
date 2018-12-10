<?php

namespace Drupal\dotmailer_signup_form_block;

class DotmailerAPI {

	protected $username;
	protected $password;
	protected $api_endpoint;

	public function __construct($username, $password, $api_endpoint) {
		$this->username = $username;
		$this->password = $password;
		$this->api_endpoint = $api_endpoint;
	}

	public function addContact($id, $contact) {
		$url = $this->api_endpoint . '/v2/address-books/'.$id.'/contacts';
		return $this->connect($url,$contact);
	}

	public function removeContact($id, $email) {
		$url = $this->api_endpoint . '/v2/contacts/'.$email;
		$user = $this->connect($url);

		if ($user->id) {
			$url = $this->api_endpoint . '/v2/contacts/'.$user->id;
			return $this->connect($url, null, 'DELETE');
		}

		return false;
	}

	public function unsubscribeContact($id, $contact) {
		$url = $this->api_endpoint . '/v2/address-books/'.$id.'/contacts/unsubscribe';
		$request = $this->connect($url,$contact);
		return $request;
	}

	protected function connect($url, $data = null, $method = 'POST') {
		$client = \Drupal::httpClient();

		$request = $client->request($method, $url, [
			'auth' => [$this->username, $this->password],
			'json' => $data,
		]);

		return $request;
	}
}
