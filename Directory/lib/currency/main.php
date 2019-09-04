<?php
use Df\Directory\Model\Country;
use Magento\Directory\Model\Currency as C;
use Magento\Framework\App\Config\Data as ConfigData;
use Magento\Framework\App\Config\DataInterface as IConfigData;
use Magento\Framework\App\ScopeInterface as ScopeA;
use Magento\Framework\Locale\Bundle\CurrencyBundle;
use Magento\Quote\Model\Quote as Q;
use Magento\Sales\Model\Order as O;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use NumberFormatter as NF;

/**
 * 2015-12-28
 * @see df_countries_options()
 * @param string[] $filter [optional]
 * @param int|string|null|bool|StoreInterface $s [optional]
 * @return array(array(string => string))
 */
function df_currencies_options(array $filter = [], $s = null) {return dfcf(function(array $filter = [], $s = null) {
	$all = df_currencies_ctn($s); /** @var array(string => string) $all */
	return df_map_to_options(!$filter ? $all : dfa_select_ordered($all, $filter));
}, func_get_args());}

/**
 * 2016-07-04
 * «How to load a currency by its ISO code?» https://mage2.pro/t/1840
 * @param C|string|null $c [optional]
 * @return C
 */
function df_currency($c = null) {/** @var C $r */
	if (!$c) {
		$r = df_currency_base();
	}
	elseif ($c instanceof C) {
		$r = $c;
	}
	else {
		static $cache; /** @var array(string => Currency) $cache */
		if (!isset($cache[$c])) {
			$cache[$c] = df_new_om(C::class)->load($c);
		}
		$r = $cache[$c];
	}
	return $r;
}

/**
 * 2016-07-04
 * «How to programmatically get the base currency's ISO code for a store?» https://mage2.pro/t/1841
 *
 * 2016-12-15
 * Добавил возможность передачи в качестве $scope массива из 2-х элементов: [Scope Type, Scope Code].
 * Это стало ответом на удаление из ядра класса \Magento\Framework\App\Config\ScopePool
 * в Magento CE 2.1.3: https://github.com/magento/magento2/commit/3660d012
 * @used-by \Df\Payment\Currency::rateToPayment()
 * @used-by \Df\Payment\Currency::toBase()
 * @param ScopeA|Store|ConfigData|IConfigData|O|Q|array(int|string)|null|string|int $s [optional]
 * @return C
 */
function df_currency_base($s = null) {return df_currency(df_assert_sne(df_cfg(
	C::XML_PATH_CURRENCY_BASE, df_is_oq($s) ? $s->getStore() : $s
)));}

/**
 * 2017-01-29
 * «How to get the currency code for a country with PHP?» https://mage2.pro/t/2552
 * http://stackoverflow.com/a/31755693
 * @used-by \Dfe\Klarna\Api\Checkout\V2\Charge::currency()
 * @used-by \Dfe\Stripe\FE\Currency::currency()
 * @param string|Country $c
 * @return string
 */
function df_currency_by_country_c($c) {return dfcf(function($c) {return
	(new NF(df_locale_by_country($c), NF::CURRENCY))->getTextAttribute(NF::CURRENCY_CODE)
;}, [df_currency_code($c)]);}

/**
 * 2016-08-08
 * http://magento.stackexchange.com/a/108013
 * В отличие от @see df_currency_base() здесь мы вынуждены использовать не $scope, а $store,
 * потому что учётную валюту можно просто считать из настроек,
 * а текущая валюта может меняться динамически (в том числе посетителем магазина и сессией).
 * @param int|string|null|bool|StoreInterface $s [optional]
 * @return C
 */
function df_currency_current($s = null) {return df_store($s)->getCurrentCurrency();}

/**
 * 2016-06-30
 * «How to programmatically get a currency's name by its ISO code?» https://mage2.pro/t/1833
 * @used-by \Df\Payment\ConfigProvider::config()
 * @used-by \Dfe\AlphaCommerceHub\W\Event::currencyName()
 * @param string|C|string[]|C[]|null $c [optional]
 * @return string|string[]
 */
function df_currency_name($c = null) {/** @var string|string[] $r */
	if (is_array($c)) {
		$r = array_map(__FUNCTION__, $c);
	}
	else {
		static $rb; /** @var \ResourceBundle $rb */
		if (!isset($rb))  {
			$rb = (new CurrencyBundle)->get(df_locale())['Currencies'];
		}
		$code = is_string($c) ? $c : df_currency_code($c); /** @var string $code */
		$r = $rb[$code][1] ?: $code;
	}
	return $r;
}