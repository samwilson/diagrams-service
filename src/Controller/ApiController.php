<?php

namespace App\Controller;

use App\DiagramGenerator;
use Exception;
use finfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiController extends AbstractController {

	/**
	 * @Route("/render", name="render")
	 * @param Request $request
	 * @param DiagramGenerator $diagramGenerator
	 * @return Response
	 */
	public function diagramRender( Request $request, DiagramGenerator $diagramGenerator ) {
		$diagramGenerator->setGenerator( $request->get( 'generator' ) );
		$types = $request->get( 'types' );
		if ( !is_array( $types ) ) {
			$types = [ $types ];
		}
		$diagramGenerator->setTypesRequested( $types );
		$diagramGenerator->setSource( $request->get( 'source' ) );
		try {
			$diagramGenerator->render();
		} catch ( Exception $exception ) {
			return new JsonResponse( [
				'status' => 'error',
				'error' => $exception->getMessage(),
			] );
		}

		$out = [
			'status' => 'ok',
			'hash' => $diagramGenerator->getHash(),
			'types' => $diagramGenerator->getTypesRequested(),
			'diagrams' => [],
		];
		foreach ( $diagramGenerator->getTypesRequested() as $type ) {
			$url = $this->generateUrl(
				'view',
				[ 'hash' => $diagramGenerator->getHash(), 'type' => $type ],
				UrlGeneratorInterface::ABSOLUTE_URL
			);
			$out['diagrams'][ $type ] = [
				'url' => $url,
			];
			// For text result types, also include the text.
			$filename = $diagramGenerator->getOutputFilename( $type );
			if ( mime_content_type( $filename ) === 'text/plain' ) {
				$out['diagrams'][ $type ]['text'] = true;
				$out['diagrams'][ $type ]['contents'] = file_get_contents( $filename );
			}
		}
		return new JsonResponse( $out );
	}

	/**
	 * @Route("/view/{hash}.{type}", name="view")
	 * @param Request $request
	 * @param DiagramGenerator $diagramGenerator
	 * @return Response
	 */
	public function diagramView( Request $request, DiagramGenerator $diagramGenerator ) {
		$diagramGenerator->setHash( $request->get( 'hash' ) );
		$type = $request->get( 'type' );
		if ( !file_exists( $diagramGenerator->getOutputFilename( $type ) ) ) {
			return new JsonResponse( [
				'error' => [
					'status' => 'error',
					'error' => 'not-found',
					'message' => 'No diagram found for given hash.'
				]
			] );
		}
		return new BinaryFileResponse( $diagramGenerator->getOutputFilename( $type ) );
	}

}
