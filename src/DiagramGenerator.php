<?php

namespace App;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class DiagramGenerator {

	/** @var string Data directory. */
	protected $directory;

	/** @var string Generator name. */
	protected $generator = 'dot';

	/** @var string[][] */
	protected $generators = [
		'graphviz' => [ 'dot', 'neato', 'twopi', 'circo', 'fdp', 'sfdp', 'patchwork', 'osage' ],
		'mscgen' => [ 'mscgen' ],
		'plantuml' => [ 'plantuml' ]
	];

	/** @var string[] */
	protected $typesRequested = [ 'png' ];

	/** @var string[][] Allowed output types. */
	protected $typesAllowed = [
		'graphviz' => [
			'dot', 'xdot', 'ps', 'pdf', 'svg', 'fig', 'png', 'gif', 'jpg', 'json', 'imap', 'cmapx'
		],
		'mscgen' => [
			'png', 'eps', 'svg', 'ismap'
		],
		'plantuml' => [
			'png', 'svg', 'eps', 'pdf', 'vdx', 'xmi', 'scxml', 'html', 'txt', 'utxt',
			'latex', 'latex:nopreamble'
		],
	];

	/** @var string Graph source code. */
	protected $source;

	/** @var string */
	protected $sourceHash;

	/** @var string */
	protected $plantumlPath;

	/**
	 * @param string $directory
	 * @param string $plantumlPath
	 */
	public function __construct( string $directory, string $plantumlPath ) {
		$this->setDirectory( $directory );
		$this->plantumlPath = $plantumlPath;
	}

	/**
	 * @param string|null $generator
	 * @return bool True if the new generator was successfully set; false otherwise.
	 */
	public function setGenerator( string $generator = null ): bool {
		if ( !$generator ) {
			return false;
		}
		foreach ( $this->generators as $gType => $generators ) {
			if ( in_array( $generator, $generators ) ) {
				$this->generator = $generator;
				return true;
			}
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function getGenerator() {
		return $this->generator;
	}

	/**
	 * @return string[]
	 */
	public function getGenerators(): array {
		return $this->generators;
	}

	/**
	 * @param string[] $typesRequested
	 */
	public function setTypesRequested( array $typesRequested = [] ): void {
		$this->typesRequested = [];
		foreach ( $typesRequested as $type ) {
			foreach ( $this->typesAllowed as $typesAllowed ) {
				if ( in_array( $type, $typesAllowed ) && !in_array( $type, $this->typesRequested ) ) {
					// Prevent duplicates, because some types are in multiple generator groups.
					$this->typesRequested[] = $type;
				}
			}
		}
		// Make sure there's always at least one type.
		if ( empty( $this->typesRequested ) ) {
			$this->typesRequested = [ 'png' ];
		}
	}

	/**
	 * @return string[]
	 */
	public function getTypesRequested(): array {
		return $this->typesRequested;
	}

	/**
	 * @return string[]
	 */
	public function getTypesAllowed(): array {
		return $this->typesAllowed;
	}

	/**
	 * @return string
	 */
	public function getSource(): string {
		return $this->source;
	}

	/**
	 * @param string $source
	 */
	public function setSource( string $source ): void {
		$this->source = trim( $source );
		$this->sourceHash = md5( $this->source );
	}

	/**
	 * @param string $hash
	 */
	public function setHash( $hash ) {
		$this->sourceHash = $hash;
		$this->source = null;
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return $this->sourceHash;
	}

	/**
	 * @return string
	 */
	public function getInputFilename(): string {
		return $this->getDirectory() . $this->sourceHash . '_input.' . $this->getGenerator();
	}

	/**
	 * @param string $type
	 * @return string
	 */
	public function getOutputFilename( $type ): string {
		return $this->getDirectory() . $this->sourceHash . '_output.' . $type;
	}

	/**
	 * @param string $directory
	 */
	public function setDirectory( string $directory ): void {
		$this->directory = $directory;
	}

	/**
	 * @return string The full path to the data directory, with trailing slash.
	 */
	public function getDirectory() {
		$dir = $this->directory;
		$filesystem = new Filesystem();
		if ( !$filesystem->exists( $dir ) ) {
			$filesystem->mkdir( $dir );
		}
		if ( !is_writable( $dir ) ) {
			throw new Exception( 'Unable to write to directory ' . $dir );
		}
		return rtrim( realpath( $dir ), '/' ) . '/';
	}

	public function render() {
		foreach ( $this->getTypesRequested() as $type ) {
			$this->renderOneType( $type );
		}
	}

	/**
	 * @param string $type
	 */
	protected function renderOneType( string $type ) {
		// Dump the source to a file.
		$filesystem = new Filesystem();
		$filesystem->dumpFile( $this->getInputFilename(), $this->getSource() );

		if ( $this->getGenerator() === 'plantuml' ) {
			// PlantUML.
			$process = Process::fromShellCommandline( 'cat $IN | java -jar $JAR -pipe > $OUT' );
			$args = [
				'IN' => $this->getInputFilename(),
				'OUT' => $this->getOutputFilename( $type ),
				'JAR' => $this->plantumlPath,
			];
			$process->mustRun( null, $args );
		} else {
			// Graphviz and Mscgen.
			$process = new Process( [
				$this->getGenerator(),
				'-T',
				$type,
				'-o',
				$this->getOutputFilename( $type ),
				$this->getInputFilename(),
			] );
			$process->mustRun();
		}
	}
}
