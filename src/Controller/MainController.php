<?php

namespace App\Controller;

use App\DiagramGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainController extends AbstractController {

	/**
	 * @param Request $request
	 * @param DiagramGenerator $diagramGenerator
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index( Request $request, DiagramGenerator $diagramGenerator ) {
		$result = false;
		if ( $request->get( 'source' ) ) {
			$url = $this->generateUrl( 'render', [], UrlGeneratorInterface::ABSOLUTE_URL );
			$requestOptions = [
				'body' => [
					'generator' => $request->get( 'generator' ),
					'source' => $request->get( 'source' ),
					'types' => $request->get( 'types' ),
				],
			];
			$response = HttpClient::create()->request( 'POST', $url, $requestOptions );
			if ( $response->getStatusCode() === 200 ) {
				$result = json_decode( $response->getContent() );
			} else {
				$result = $response->getContent();
			}
		}
		return $this->render( 'index.html.twig', [
			'examples' => $this->getExamples(),
			'generators' => $diagramGenerator->getGenerators(),
			'generator' => $request->get( 'generator' ),
			'source' => $request->get( 'source' ),
			'types_allowed' => $diagramGenerator->getTypesAllowed(),
			'types_selected' => $request->get( 'types' ),
			'result' => $result,
		] );
	}

	/**
	 * Get information about the example files.
	 * @return string[]
	 */
	public function getExamples() {
		$out = [];
		$finder = new Finder();
		$finder->files()->in( dirname( __DIR__, 2 ) . '/templates/examples' );
		foreach ( $finder as $file ) {
			$out[] = [
				'generator' => $file->getExtension(),
				'name' => $file->getFilename(),
				'source' => file_get_contents( $file->getRealPath() ),
			];
		}
		return $out;
	}
}
