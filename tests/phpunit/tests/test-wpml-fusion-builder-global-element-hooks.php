<?php

/**
 * @group wpmlcore-5375
 */
class Test_WPML_Fusion_Builder_Global_Element_Hooks extends OTGS_TestCase {

	private $current_lang = 'fr';

	/**
	 * @test
	 */
	public function it_should_add_hooks() {
		$subject = $this->get_subject();

		\WP_Mock::expectFilterAdded(
			'content_edit_pre',
			array( $subject, 'translate_global_element_ids' ),
			WPML_Fusion_Builder_Global_Element_Hooks::BEFORE_ADD_GLOBAL_ELEMENTS_PRIORITY
		);

		$subject->add_hooks();
	}

	/**
	 * @test
	 */
	public function it_should_translate_global_element_ids() {
		$global_id_1 = mt_rand( 1, 100 );
		$global_id_2 = mt_rand( 101, 200 );
		$global_tr_id_1 = mt_rand( 1000, 1100 );
		$global_tr_id_2 = mt_rand( 1101, 1200 );

		$content =
			'[fusion_builder_container]
				[fusion_builder_row]
					[fusion_builder_column type="1_1"]
						[fusion_global id="' . $global_id_1 . '"]
						[fusion_text columns=""]Another text[/fusion_text]
						[fusion_global id="' . $global_id_2 . '"]
					[/fusion_builder_column]
				[/fusion_builder_row]
			[/fusion_builder_container]';

		$expected_content =
			'[fusion_builder_container]
				[fusion_builder_row]
					[fusion_builder_column type="1_1"]
						[fusion_global id="' . $global_tr_id_1 . '"]
						[fusion_text columns=""]Another text[/fusion_text]
						[fusion_global id="' . $global_tr_id_2 . '"]
					[/fusion_builder_column]
				[/fusion_builder_row]
			[/fusion_builder_container]';

		$element_1 = $this->get_element( $global_tr_id_1 );
		$element_2 = $this->get_element( $global_tr_id_2 );

		$element_factory = $this->get_element_factory();
		$element_factory->method( 'create' )->willReturnMap(
			array(
				array( $global_id_1, 'post', $element_1 ),
				array( $global_id_2, 'post', $element_2 ),
			)
		);

		$subject = $this->get_subject( $element_factory );

		$this->assertEquals(
			$expected_content,
			$subject->translate_global_element_ids( $content )
		);
	}

	private function get_subject( $element_factory = null ) {
		$element_factory = $element_factory ? $element_factory : $this->get_element_factory();
		return new WPML_Fusion_Builder_Global_Element_Hooks( $this->get_current_language(), $element_factory );
	}

	private function get_element_factory() {
		return $this->getMockBuilder( 'WPML_Translation_Element_Factory' )
		            ->setMethods( array( 'create' ) )->disableOriginalConstructor()->getMock();
	}

	private function get_element( $translation_id ) {
		$translation = $this->getMockBuilder( 'WPML_Post_Element' )
		                    ->setMethods( array( 'get_element_id' ) )->disableOriginalConstructor()->getMock();
		$translation->method( 'get_element_id' )->willReturn( $translation_id );

		$element = $this->getMockBuilder( 'WPML_Post_Element' )
		                ->setMethods( array( 'get_translation' ) )->disableOriginalConstructor()->getMock();
		$element->method( 'get_translation' )->willReturn( $translation );

		return $element;
	}

	private function get_current_language() {
		$current_language = $this->getMockBuilder( 'IWPML_Current_Language' )
		                         ->setMethods( array( 'get_current_language', 'get_default_language', 'get_admin_language' ) )->disableOriginalConstructor()->getMock();
		$current_language->method( 'get_current_language' )->willReturn( $this->current_lang );
		return $current_language;
	}
}
