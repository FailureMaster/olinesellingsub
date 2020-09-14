<?php
namespace Auxin\Plugin\Pro\Elementor\Modules;

use Elementor\Plugin;

class Templates_Types_Manager {
	private $docs_types = [];

	public function __construct() {
		if( ! defined( 'ELEMENTOR_PRO_VERSION' ) ){
			define( 'AUXPRO_ELEMENTOR_TEMPLATE', true );
			add_action( 'elementor/documents/register', [ $this, 'register_documents' ] );
		}
	}

	public function register_documents() {
		$this->docs_types = [
			'single'  => Documents\Single::get_class_full_name(),
			// 'archive' => Documents\Archive::get_class_full_name(),
		];

		foreach ( $this->docs_types as $type => $class_name ) {
			Plugin::$instance->documents->register_document_type( $type, $class_name );
		}
	}

}