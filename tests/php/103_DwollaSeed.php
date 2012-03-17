<?php

require_once('tests/php/base.php');

class DwollaSeedTests extends UnitTestCase {
	private $dwolla_connection_id, $dwolla_username,$cash_user_id;
	
	function __construct() {
		// add a new admin user for this
		$user_add_request = new CASHRequest(
			array(
				'cash_request_type' => 'system',
				'cash_action'       => 'addlogin',
				'address'           => 'email@anothertest.com',
				'password'          => 'thiswillneverbeused',
				'is_admin'          => 1
			)
		);
		$this->cash_user_id = $user_add_request->response['payload'];
		
		// add a new connection 
		$this->dwolla_username = getTestEnv("dwolla_USERNAME");
		$c = new CASHConnection($this->cash_user_id); // the '1' sets a user id=1
		$this->dwolla_connection_id = $c->setSettings('Dwolla', 'com.dwolla',
			array(
				"username"  => $this->dwolla_username,
				"password"  => getTestEnv("dwolla_PASSWORD"),
				"signature" => getTestEnv("dwolla_SIGNATURE"),
				"sandboxed" => true
			) 
		);
	}

	function testMailchimpSeed(){
		if($this->dwolla_username) {
			$pp = new DwollaSeed($this->cash_user_id, $this->dwolla_connection_id);
			$this->assertIsa($pp, 'DwollaSeed');
		} else {
			fwrite(STDERR,"Dwolla credentials not found, skipping tests\n");
			return;
		}
	}

	function testSet(){
		if($this->dwolla_username) {
			$pp = new DwollaSeed($this->cash_user_id, $this->dwolla_connection_id);
			$redirect_url = $pp->setExpressCheckout(
				'13.26',
				'order_sku',
				'this is the best order ever',
				'http://localhost',
				'http://localhost'
			);
			$this->assertTrue($redirect_url);
			//$redirect = CASHSystem::redirectToUrl($redirect_url);
			//echo $redirect;
		} else {
			fwrite(STDERR,"Dwolla credentials not found, skipping tests\n");
			return;
		}
	}
}
