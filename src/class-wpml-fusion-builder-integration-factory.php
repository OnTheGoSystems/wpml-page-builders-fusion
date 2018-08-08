<?php

class WPML_Fusion_Builder_Integration_Factory {

	/**
	 * @return IWPML_Action[]
	 */
	public function create() {
		global $sitepress;

		return array(
			new WPML_Fusion_Builder_Global_Element_Hooks(
				$sitepress,
				new WPML_Translation_Element_Factory( $sitepress )
			),
		);
	}
}