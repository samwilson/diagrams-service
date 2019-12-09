<?php

namespace Test;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase {

	protected static function getKernelClass() {
		return Kernel::class;
	}

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
