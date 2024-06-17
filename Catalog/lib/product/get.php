<?php
use Magento\Catalog\Model\Product as P;
use Magento\Framework\Exception\NoSuchEntityException as NSE;
use Magento\Quote\Model\Quote\Item as QI;
use Magento\Sales\Model\Order\Item as OI;
use Magento\Store\Api\Data\StoreInterface as IStore;

/**
 * 2019-02-26
 * 2019-05-15 I have added the $s parameter: https://magento.stackexchange.com/a/177164
 * 2019-09-20
 * I tried to support SKU as $p using the following way:
 *	call_user_func(
 *		[df_product_r(), ctype_digit($p) || df_is_oi($p) ? 'getById' : 'get']
 *		,df_is_oi($p) ? $p->getProductId() : $p
 *		...
 *	)
 * https://github.com/mage2pro/core/commit/01d4fbbf83
 * It was wrong because SKU can be numeric, so the method become ambiguous.
 * Use @see \Magento\Catalog\Model\ProductRepository::get() directly to load a product by SKU, e.g.:
 * 		df_product_r()->get('your SKU')
 * @see df_category()
 * @see df_product_load()
 * @used-by df_category_names()
 * @used-by ikf_product_printer()
 * @used-by \Dfe\Sift\Payload\OQI::p()
 * @used-by \Inkifi\Mediaclip\API\Entity\Order\Item::product()
 * @used-by \Inkifi\Mediaclip\Event::product()
 * @used-by \Inkifi\Mediaclip\H\AvailableForDownload\Pureprint::pOI()
 * @used-by \Inkifi\Mediaclip\T\CaseT\Product::t02()
 * @used-by \Mangoit\MediaclipHub\Controller\Index\GetPriceEndpoint::execute()
 * @param int|string|P|OI|QI $p
 * @param int|string|null|bool|IStore $s [optional]
 * @throws NSE
 */
function df_product($p, $s = false):P {return df_is_p($p) ? $p : df_product_r()->getById(
	/**
	 * 2020-02-05
	 * 1) I do not use @see \Magento\Sales\Model\Order\Item::getProduct()
	 * and @see \Magento\Quote\Model\Quote\Item\AbstractItem::getProduct() here,
	 * because they return `null` for an empty product ID, but df_product() should throw @see NSE in such cases.
	 * 2) Also, my implementation allows to specify a custom $s.
	 */
	df_is_oqi($p) ? $p->getProductId() : $p
	,false
	,false === $s ? null : df_store_id(true === $s ? null : $s)
	,true === $s
);}