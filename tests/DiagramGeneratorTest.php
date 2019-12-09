<?php

namespace Test;

use App\DiagramGenerator;
use PHPUnit\Framework\TestCase;

class DiagramGeneratorTest extends TestCase {

	protected $diagramGenerator;

	protected $dir;

	public function setUp() : void {
		$this->dir = dirname( __DIR__ ) . '/var/tests/';
		$this->diagramGenerator = new DiagramGenerator( $this->dir, '' );
	}

	/**
	 * @covers DiagramGenerator::getTypesRequested()
	 */
	public function testTypesRequested() {
		static::assertEquals( [ 'png' ], $this->diagramGenerator->getTypesRequested() );
		$this->diagramGenerator->setTypesRequested( [ 'ismap' ] );
		static::assertEquals( [ 'ismap' ], $this->diagramGenerator->getTypesRequested() );
		$this->diagramGenerator->setTypesRequested( [ 'invalid' ] );
		static::assertEquals( [ 'png' ], $this->diagramGenerator->getTypesRequested() );
		$this->diagramGenerator->setTypesRequested( [ 'svg', 'pdf' ] );
		static::assertEquals( [ 'svg', 'pdf' ], $this->diagramGenerator->getTypesRequested() );
	}

	/**
	 * @covers \App\DiagramGenerator::render()
	 */
	public function testRender() {
		$this->diagramGenerator->setSource( 'digraph G { A -> B; }' );
		$this->diagramGenerator->render();
		$baseFilename = $this->dir . '1ef495c56c337fdff61b605159a4b62f_';
		static::assertEquals( $baseFilename . 'input.dot', $this->diagramGenerator->getInputFilename() );
		static::assertEquals(
			$baseFilename . 'output.png',
			$this->diagramGenerator->getOutputFilename( 'png' )
		);
	}
}
