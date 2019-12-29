<?php

namespace App\Controller;

use App\DiagramGenerator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiController extends AbstractController {

	/**
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
		$source = $request->get( 'source' );
		if ( !$source ) {
			return new JsonResponse( [
				'status' => 'error',
				'error' => 'no-source',
			] );
		}
		$diagramGenerator->setSource( $source );
		try {
			$diagramGenerator->render();
		} catch ( Exception $exception ) {
			return new JsonResponse( [
				'status' => 'error',
				'error' => $exception->getCode(),
				'message' => $exception->getMessage(),
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
	 * @param Request $request
	 * @param DiagramGenerator $diagramGenerator
	 * @return Response
	 */
	public function diagramView( Request $request, DiagramGenerator $diagramGenerator ) {
		// Delete out-of-date files.
		$diagramGenerator->deleteOld();
		// See if the requested file exists.
		$diagramGenerator->setHash( $request->get( 'hash' ) );
		$type = $request->get( 'type' );
		$filename = $diagramGenerator->getOutputFilename( $type );
		if ( !file_exists( $filename ) ) {
			// If it doesn't, return an error.
			return new JsonResponse( [
				'error' => [
					'status' => 'error',
					'error' => 'not-found',
					'message' => 'No diagram found for given hash.'
				]
			] );
		}
		// Touch the file, to refresh its cache time.
		touch( $filename );
		// Return the file data.
		return new BinaryFileResponse( $filename );
	}

}
