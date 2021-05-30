<?php
namespace Df\Qa;
use Df\Qa\Trace\Frame;
use Df\Zf\Validate\ArrayT as VArray;
use Df\Zf\Validate\Boolean as VBoolean;
use Df\Zf\Validate\FloatT as VFloat;
use Df\Zf\Validate\IntT as VInt;
use Df\Zf\Validate\StringT as VString;
use Df\Zf\Validate\StringT\Iso2 as VIso2;
use Exception as E;
use ReflectionParameter as RP;
use Zend_Validate_Interface as Vd;
final class Method {
	/**
	 * @param array $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return array
	 * @throws E
	 */
	static function assertParamIsArray($v, $ord, $sl = 0) {return self::vp(VArray::s(), $v, $ord, ++$sl);}

	/**
	 * @param bool $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return bool
	 * @throws E
	 */
	static function assertParamIsBoolean($v, $ord, $sl = 0) {return self::vp(VBoolean::s(), $v, $ord, ++$sl);}

	/**
	 * @param int $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return int
	 * @throws E
	 */
	static function assertParamIsInteger($v, $ord, $sl = 0) {return self::vp(VInt::s(), $v, $ord, ++$sl);}

	/**
	 * @used-by df_param_iso2()
	 * @param string $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	static function assertParamIsIso2($v, $ord, $sl = 0) {return self::vp(VIso2::s(), $v, $ord, ++$sl);}

	/**
	 * @param string $v
	 * @param int $ord
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	static function assertParamIsString($v, $ord, $sl = 0) {return self::vp(VString::s(), $v, $ord, ++$sl);}

	/**
	 * @param array $v
	 * @param int $sl [optional]
	 * @return array
	 * @throws E
	 */
	static function assertResultIsArray($v, $sl = 0) {return self::vr(VArray::s(), $v, ++$sl);}

	/**
	 * @param bool $v
	 * @param int $sl [optional]
	 * @return bool
	 * @throws E
	 */
	static function assertResultIsBoolean($v, $sl = 0) {return self::vr(VBoolean::s(), $v, ++$sl);}

	/**
	 * @param int $v
	 * @param int $sl [optional]
	 * @return int
	 * @throws E
	 */
	static function assertResultIsInteger($v, $sl = 0) {return self::vr(VInt::s(), $v, ++$sl);}

	/**
	 * @param string $v
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	static function assertResultIsString($v, $sl = 0) {return self::vr(VString::s(), $v, ++$sl);}

	/**
	 * @param array $v
	 * @param int $sl [optional]
	 * @return array
	 * @throws E
	 */
	static function assertValueIsArray($v, $sl = 0) {return self::vv(VArray::s(), $v, ++$sl);}

	/**
	 * @param bool $v
	 * @param int $sl [optional]
	 * @return bool
	 * @throws E
	 */
	static function assertValueIsBoolean($v, $sl = 0) {return self::vr(VBoolean::s(), $v, ++$sl);}

	/**
	 * @param float $v
	 * @param int $sl [optional]
	 * @return float
	 * @throws E
	 */
	static function assertValueIsFloat($v, $sl = 0) {return self::vv(VFloat::s(), $v, ++$sl);}

	/**
	 * 2021-05-30 @deprecated It is unused.
	 * @used-by df_assert_iso2()
	 * @param string $v
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	static function assertValueIsIso2($v, $sl = 0) {return self::vv(VIso2::s(), $v, ++$sl);}

	/**
	 * @param string $v
	 * @param int $sl [optional]
	 * @return string
	 * @throws E
	 */
	static function assertValueIsString($v, $sl = 0) {return self::vv(VString::s(), $v, ++$sl);}

	/**
	 * @used-by df_param_sne()
	 * @used-by vp()
	 * @param string $method
	 * @param array $messages
	 * @param int $ord  zero-based
	 * @param int $sl
	 * @throws E
	 */
	static function raiseErrorParam($method, array $messages, $ord, $sl = 1) {
		$frame = self::caller($sl); /** @var Frame $frame */
		$name = 'unknown'; /** @var string $name */
		if (!is_null($ord) && $frame->method()) {/** @var RP $param */
			$name = $frame->methodParameter($ord)->getName();
		}
		$messagesS = df_cc_n($messages); /** @var string $messagesS */
		self::throwException(
			"[{$frame->methodName()}]"
			."\nThe argument «{$name}» is rejected by the «{$method}» validator."
			."\nThe diagnostic message:\n{$messagesS}\n\n"
			,$sl
		);
	}

	/**
	 * @used-by df_result_s()
	 * @used-by df_result_sne()
	 * @used-by vr()
	 * @param string $vd
	 * @param array $messages
	 * @param int $sl
	 * @throws E
	 */
	static function raiseErrorResult($vd, array $messages, $sl = 1) {
		$messagesS = df_cc_n($messages); /** @var string $messagesS */
		$method = self::caller($sl)->methodName(); /** @var string $method */
		self::throwException(
			"[{$method}]\nA result of this method is rejected by the «{$vd}» validator."
			."\nThe diagnostic message:\n{$messagesS}\n\n"
			, $sl
		);
	}

	/**
	 * @used-by df_assert_sne()
	 * @used-by vv()
	 * @param string $vd
	 * @param array $messages
	 * @param int $sl
	 * @throws E
	 */
	static function raiseErrorVariable($vd, array $messages, $sl = 1) {
		$messagesS = df_cc_n($messages); /** @var string $messagesS */
		$method = self::caller($sl)->methodName(); /** @var string $method */
		self::throwException(
			"[{$method}]\nThe validator «{$vd}» has catched a variable with an invalid value."
			."\nThe diagnostic message:\n{$messagesS}\n\n"
			, $sl
		);
	}

	/**
	 * 2017-01-12
	 * @used-by df_assert_sne()
	 * @used-by df_param_sne()
	 * @used-by df_result_sne()
	 */
	const NES = 'A non-empty string is required, but got an empty one.';

	/**
	 * 2017-04-22
	 * @used-by df_param_s()
	 */
	const S = 'A string is required.';

	/**
	 * Объект @see Frame конструируется на основе $sl + 2,
	 * потому что нам нужно вернуть название метода, который вызвал тот метод, который вызвал метод caller.
	 * @used-by raiseErrorParam()
	 * @used-by raiseErrorResult()
	 * @used-by raiseErrorVariable()
	 * @param int $offset [optional]
	 * @return Frame
	 */
	private static function caller($offset) {return Frame::i(
		debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3 + $offset)[2 + $offset]
	);}

	/**
	 * 2015-01-28
	 * Раньше тут стояло throw $e, что приводило к отображению на экране
	 * диагностического сообщения в неверной кодировке.
	 * @uses df_error() точнее: эта функция в режиме разработчика
	 * отсылает браузеру заголовок HTTP о требуемой кодировке.
	 * @param string $message
	 * @param int $sl [optional]
	 * @throws E
	 */
	private static function throwException($message, $sl = 0) {df_error(new E($message, ++$sl));}
	
	/**
	 * @param Vd $vd
	 * @param mixed $v
	 * @param int $ord
	 * @param int $sl
	 * @return mixed
	 * @throws E
	 */
	private static function vp(Vd $vd, $v, $ord, $sl = 1) {return $vd->isValid($v) ? $v : self::raiseErrorParam(
		get_class($vd), $vd->getMessages(), $ord, ++$sl
	);}

	/**
	 * @param Vd $vd
	 * @param mixed $v
	 * @param int $sl
	 * @return mixed
	 * @throws E
	 */
	private static function vr(Vd $vd, $v, $sl = 1) {return $vd->isValid($v) ? $v : self::raiseErrorResult(
		get_class($vd), $vd->getMessages(), ++$sl
	);}
	
	/**
	 * @used-by assertValueIsArray()
	 * @used-by assertValueIsFloat()
	 * @used-by assertValueIsIso2()
	 * @used-by assertValueIsString()
	 * @param Vd $vd
	 * @param mixed $v
	 * @param int $sl
	 * @return mixed
	 * @throws E
	 */
	private static function vv(Vd $vd, $v, $sl = 1) {return $vd->isValid($v) ? $v : self::raiseErrorVariable(
		get_class($vd), $vd->getMessages(), ++$sl
	);}
}