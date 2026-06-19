<?php
/**
 * Save trait proxy for conditional validation tests.
 *
 * @package Pedalcms\CassetteCmf\Tests\Unit
 */

use Pedalcms\CassetteCmf\Core\Traits\Field_Saving_Trait;
use Pedalcms\CassetteCmf\Field\Field_Interface;

/**
 * Test Field Saving Proxy
 */
class Test_Field_Saving_Proxy {
	use Field_Saving_Trait;

	/**
	 * Proxy sanitize/validate call.
	 *
	 * @param Field_Interface $field Field instance.
	 * @param mixed           $value Raw value.
	 * @return array{value: mixed, valid: bool, errors: array}
	 */
	public function run( Field_Interface $field, $value ): array {
		return $this->sanitize_and_validate( $field, $value );
	}
}
