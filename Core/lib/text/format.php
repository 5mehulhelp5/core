<?php
/**  
 * 2020-02-04
 * @used-by dfp_card_format_last4()
 * @used-by dfp_methods()
 * @used-by \Df\Config\Source\LetterCase::map()
 * @used-by \Df\GoogleFont\Font\Variant\Preview::text()
 * @used-by \Df\ZohoBI\Source\Organization::fetch()
 * @used-by \Dfe\AllPay\Block\Info\BankCard::eci()
 * @used-by \Dfe\AllPay\W\Event\Offline::expirationS()
 * @used-by \Dfe\CheckoutCom\Response::messageC()
 * @used-by \Dfe\Sift\Payload\Promotion\Discount::desc()
 * @used-by \Dfe\Stripe\Facade\Charge::refundAdjustments()
 * @param string $s1
 * @param string $s2
 */
function df_desc($s1, $s2):string {return df_es($s1) ? $s2 : (df_es($s2) || $s2 === $s1 ? $s1 : "$s1 ($s2)");}

/**
 * @used-by df_checkout_error()
 * @used-by df_error_create()
 * @used-by \Df\Core\Exception::comment()
 * @used-by \Df\Payment\W\Exception::__construct()
 * @param mixed ...$args
 */
function df_format(...$args):string { /** @var string $r */
	$args = df_args($args);
	$r = null;
	switch (count($args)) {
		case 0:
			$r = '';
			break;
		case 1:
			$r = $args[0];
			break;
		case 2:
			if (is_array($args[1])) {
				$r = strtr($args[0], $args[1]);
			}
			break;
	}
	return !is_null($r) ? $r : df_sprintf($args);
}

/**
 * 2017-07-09
 * @used-by df_api_rr_failed()
 * @used-by \Df\API\Client::p()
 * @used-by \Df\Qa\Failure\Error::main()
 * @param array(string => string) $a
 * @param int|null $pad [optional]
 */
function df_kv(array $a, $pad = null):string {return df_cc_n(df_map_k(df_clean($a), function($k, $v) use($pad) {return
	(!$pad ? "$k: " : df_pad("$k:", $pad))
	.(is_array($v) || (is_object($v) && !method_exists($v, '__toString')) ? "\n" . df_json_encode($v) : $v)
;}));}

/**
 * 2019-06-13   
 * @used-by \KingPalm\B2B\Observer\RegisterSuccess::execute()
 * @param array(string => string) $a
 */
function df_kv_table(array $a):string {return df_tag('table', [], df_map_k(
	df_clean($a), function($k, $v) {return
		df_tag('tr', [], [
			df_tag('td', [], $k)
			,df_tag('td', [],
				is_array($v) || (is_object($v) && !method_exists($v, '__toString'))
					? "\n" . df_json_encode($v) : $v					
			)
		])
	;}
));}

/**
 * @used-by df_format()
 * @param string|mixed[] $s
 * @throws Exception
 */
function df_sprintf($s):string {/** @var string $r */ /** @var mixed[] $args */
	# 2020-03-02, 2022-10-31
	# 1) Symmetric array destructuring requires PHP ≥ 7.1:
	#		[$a, $b] = [1, 2];
	# https://github.com/mage2pro/core/issues/96#issuecomment-593392100
	# We should support PHP 7.0.
	# https://3v4l.org/3O92j
	# https://www.php.net/manual/migration71.new-features.php#migration71.new-features.symmetric-array-destructuring
	# https://stackoverflow.com/a/28233499
	list($s, $args) = is_array($s) ? [df_first($s), $s] : [$s, func_get_args()];
	try {$r = df_sprintf_strict($args);}
	catch (Exception $e) {$r = $s;}
	return $r;
}

/**
 * @used-by df_sprintf()
 * @param string|mixed[] $s
 * @throws Exception
 */
function df_sprintf_strict($s):string {/** @var string $r */ /** @var mixed[] $args */
	# 2020-03-02, 2022-10-31
	# 1) Symmetric array destructuring requires PHP ≥ 7.1:
	#		[$a, $b] = [1, 2];
	# https://github.com/mage2pro/core/issues/96#issuecomment-593392100
	# We should support PHP 7.0.
	# https://3v4l.org/3O92j
	# https://www.php.net/manual/migration71.new-features.php#migration71.new-features.symmetric-array-destructuring
	# https://stackoverflow.com/a/28233499
	list($s, $args) = is_array($s) ? [df_first($s), $s] : [$s, func_get_args()];
	if (1 === count($args)) {
		$r = $s;
	}
	else {
		try {$r = vsprintf($s, df_tail($args));}
		catch (Exception $e) {
			static $inProcess = false; /** @var bool $inProcess */
			if (!$inProcess) {
				$inProcess = true;
				df_error(
					'df_sprintf_strict failed: «{message}».'
					. "\nPattern: {$s}."
					. "\nParameters:\n{params}."
					,['{message}' => df_ets($e), '{params}' => print_r(df_tail($args), true)]
				);
			}
		}
	}
	return $r;
}

/**
 * 2016-03-09 Замещает переменные в тексте.
 * 2016-08-07 Сегодня разработал аналогичные функции для JavaScript: df.string.template() и df.t().
 * @used-by df_file_name()
 * @used-by \Df\GingerPaymentsBase\Block\Info::btInstructions()
 * @used-by \Df\Payment\Charge::text()
 * @used-by \Df\Payment\Settings::messageFailure()
 * @used-by \Dfe\SalesSequence\Plugin\Model\Manager::affix()
 * @param string $s
 * @param array(string => string) $variables
 * @param string|callable|null $onUnknown
 */
function df_var($s, array $variables, $onUnknown = null):string {return preg_replace_callback(
	'#\{([^\}]*)\}#ui', function($m) use($variables, $onUnknown) {return
		dfa($variables, dfa($m, 1, ''), $onUnknown)
	;}, $s
);}