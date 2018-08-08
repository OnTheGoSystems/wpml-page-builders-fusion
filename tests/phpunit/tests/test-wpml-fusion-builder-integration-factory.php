<?php

class Test_WPML_Fusion_Builder_Integration_Factory extends OTGS_TestCase {

	/**
	 * @test
	 */
	public function it_should_create_and_return_hooks_instances() {
		global $sitepress;

		$sitepress = $this->getMockBuilder( 'IWPML_Current_Language' )->getMock();
		$this->getMockBuilder( 'WPML_Translation_Element_Factory' )->getMock();

		$subject = new WPML_Fusion_Builder_Integration_Factory();

		$hooks = $subject->create();

		$this->assertCount( 1, $hooks );
		$this->assertInstanceOf( 'WPML_Fusion_Builder_Global_Element_Hooks', $hooks[0] );
	}
}
