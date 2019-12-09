<?php

namespace Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase {

	/**
	 * @covers \App\Controller\ApiController::
	 */
	public function testGenerate() {
		$client = static::createClient();
		$client->request( 'POST', '/render', [
			'source' => 'digraph G { A -> B; }',
			'types' => [ 'png', 'imap' ],
		] );
		$response = $client->getResponse();
		static::assertEquals( 200, $response->getStatusCode() );
		static::assertJson( $response->getContent() );
	}
}
