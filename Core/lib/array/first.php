<?php

/**
 * Функция возвращает null, если массив пуст.
 * Обратите внимание, что неверен код
 *	$result = reset($a);
 *	return (false === $result) ? null : $result;
 * потому что если @uses reset() вернуло false, это не всегда означает сбой метода:
 * ведь первый элемент массива может быть равен false.
 * @see df_last()
 * @see df_tail()
 * @used-by df_store_code_from_url()
 * @used-by dfa_group()
 * @used-by dfe_alphacommercehub_fix_amount_bug()
 * @used-by \Df\Customer\AddAttribute\Customer::p()
 * @used-by \Df\Payment\TM::response()
 * @used-by \Dfe\Color\Image::dist()
 * @used-by \Inkifi\Consolidation\Processor::pid()
 * @used-by \Inkifi\Mediaclip\API\Entity\Order\Item::mProduct()
 * @used-by \Inkifi\Mediaclip\H\AvailableForDownload\Pureprint::writeLocal()
 * @used-by \Inkifi\Mediaclip\T\CaseT\Order\Item::t01()
 * @used-by \Mangoit\MediaclipHub\Controller\Index\OrderStatusUpdateEndpoint::execute()
 * @used-by \Mineralair\Core\Controller\Modal\Index::execute()
 * @used-by frugue/core/view/frontend/templates/wishlist/item/column/image.phtml
 * @param array $a
 * @return mixed|null
 */
function df_first(array $a) {return !$a ? null : reset($a);}

/**
 * 2019-08-21 https://www.php.net/manual/en/function.array-key-first.php
 * @used-by \Dfe\Color\Observer\ProductSaveBefore::execute()
 * @param array(int|string => mixed) $a
 * @return string|int|null
 */
function df_first_key(array $a) {
	$r = null; /** @var int|string|null $r */
	foreach($a as $k => $v) { /** @var int|string $k */
		$r = $k;
		break;
	}
	return $r;
}

/**
 * 2015-03-13
 * Отсекает последний элемент массива и возвращает «голову» (массив оставшихся элементов).
 * Похожая системная функция @see array_pop() возвращает отсечённый последний элемент.
 * Противоположная системная функция @see df_tail() отсекает первый элемент массива.
 * @used-by \Df\Config\Comment::groupPath()
 * @used-by \Df\Config\Source::sibling()
 * @used-by \Mineralair\Core\Controller\Modal\Index::execute()
 * @param mixed[] $a
 * @return mixed[]|string[]
 */
function df_head(array $a) {return array_slice($a, 0, -1);}

/**
 * Функция возвращает null, если массив пуст.
 * Если использовать @see end() вместо @see df_last(),
 * то указатель массива после вызова end сместится к последнему элементу.
 * При использовании @see df_last() смещения указателя не происходит,
 * потому что в @see df_last() попадает лишь копия массива.
 *
 * Обратите внимание, что неверен код
 *	$result = end($array);
 *	return (false === $result) ? null : $result;
 * потому что если @uses end() вернуло false, это не всегда означает сбой метода:
 * ведь последний элемент массива может быть равен false.
 * http://www.php.net/manual/en/function.end.php#107733
 * @see df_first()
 * @see df_tail()
 * @used-by df_class_l()
 * @used-by df_fe_name_short()
 * @used-by df_package_name_l()
 * @used-by df_url_path()
 * @used-by df_url_staged()
 * @used-by ikf_eti()
 * @used-by \Df\Config\Backend::value()
 * @used-by \Df\Core\O::_prop()
 * @used-by \Df\Core\State::block()
 * @used-by \Df\Core\State::component()
 * @used-by \Df\Core\State::templateFile()
 * @used-by \Df\Core\Text\Regex::match()
 * @used-by \Df\Customer\Settings\BillingAddress::disabled()
 * @used-by \Df\Framework\Form\Element::uidSt()
 * @used-by \Df\Framework\Plugin\View\Page\Title::aroundGet()
 * @used-by \Df\Payment\Operation::customerNameL()
 * @used-by \Df\Payment\Source\API\Key\Testable::_test()
 * @used-by \Df\Payment\TM::response()
 * @used-by \Df\PaypalClone\Init\Action::redirectParams()
 * @used-by \Df\StripeClone\Payer::cardId()
 * @used-by \Dfe\AlphaCommerceHub\W\Event::providerRespL()
 * @used-by \Dfe\AmazonLogin\Customer::nameLast()
 * @used-by \Dfe\Omise\Facade\Customer::cardAdd()
 * @used-by \Dfe\Salesforce\T\Basic::t02_the_latest_version()
 * @used-by \Dfe\Stripe\W\Handler\Charge\Refunded::amount()
 * @used-by \Dfe\Stripe\W\Handler\Charge\Refunded::eTransId()
 * @used-by \Dfr\Core\Realtime\Dictionary\Entities::findByAttribute()
 * @used-by \KingPalm\Core\Plugin\Aitoc\OrdersExportImport\Model\Processor\Config\ExportConfigMapper::aroundToConfig()
 * @param mixed[] $array
 * @return mixed|null
 */
function df_last(array $array) {return !$array ? null : end($array);}

/**
 * Отсекает первый элемент массива и возвращает хвост (аналог CDR в Lisp).
 * Обратите внимание, что если исходный массив содержит меньше 2 элементов,
 * то функция вернёт пустой массив.
 * @see df_first()
 * @see df_last()
 * @used-by \Doormall\Shipping\Partner\Entity::locations()
 * @param mixed[] $a
 * @return mixed[]|string[]
 */
function df_tail(array $a) {return array_slice($a, 1);}