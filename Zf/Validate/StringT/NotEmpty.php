<?php
namespace Df\Zf\Validate\StringT;
use Magento\Framework\Phrase;
final class NotEmpty extends \Df\Zf\Validate\Type {
	/**
	 * @override
	 * @see \Zend_Validate_Interface::isValid()
	 * @param mixed $v
	 * @return bool
	 */
	function isValid($v) {
		$this->prepareValidation($v);
		# 2015-02-16
		# Раньше здесь стояло `is_string($value) && ('' !== strval($value))`
		# Однако интерпретатор PHP способен неявно и вполне однозначно
		# (без двусмысленностей, как, скажем, с вещественными числами)
		# конвертировать целые числа в строки, поэтому пусть целые числа всегда проходят валидацию как непустые строки.
		# 2016-07-01 Добавил `|| $value instanceof Phrase`
		return is_int($v) || ((is_string($v) || ($v instanceof Phrase)) && ('' !== strval($v)));
	}

	/**
	 * @override
	 * @see \Df\Zf\Validate\Type::expected()
	 * @used-by \Df\Zf\Validate\Type::_message()
	 * @return string
	 */
	protected function expected() {return 'a non-empty string';}

	/**
	 * @return self
	 */
	static function s() {static $r; return $r ? $r : $r = new self;}
}