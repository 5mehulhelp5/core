<?php
use Magento\Catalog\Model\Product as P;
use Magento\Wishlist\Model\Item as I;
/**
 * 2018-09-02
 * 1) I have implemented it by analogy with:
 * 1.1) @see \Magento\Wishlist\Model\Item::addToCart():
 *		$buyRequest = $this->getBuyRequest();
 * 		$cart->addProduct($product, $buyRequest);  
 * https://github.com/magento/magento2/blob/2.2.5/app/code/Magento/Wishlist/Model/Item.php#L434-L436
 * @see \Magento\Checkout\Model\Cart::addProduct():
 * 1.2) \Magento\Quote\Model\Quote::addProduct():
 * 		$cartCandidates = $product->getTypeInstance()->prepareForCartAdvanced($request, $product, $processMode);
 * https://github.com/magento/magento2/blob/2.2.5/app/code/Magento/Quote/Model/Quote.php#L1606
 * 2) If the wishlist item product is configurable, then
 * @uses \Magento\ConfigurableProduct\Model\Product\Type\Configurable::_prepareProduct()
 * includes the configurable product as the first element of the result's array.
 * We do not need it, so we filter it out.
 * @used-by frugue/core/view/frontend/templates/wishlist/item/column/image.phtml
 * @param I $i
 * @return P[]
 */
function df_wishlist_item_candidates(I $i) {
	/** @var P[]|string $pp */
	$pp = $i->getProduct()->getTypeInstance()->prepareForCartAdvanced($i->getBuyRequest(), $i->getProduct());
	/**
	 * 2018-09-02
	 * If the customer has not chosen all required options for the wishlist item,
	 * the @uses \Magento\Catalog\Model\Product\Type\AbstractType::prepareForCartAdvanced() method
	 * returns the «You need to choose options for your item» string.
	 */
	return !is_array($pp) ? [] : df_not_configurable($pp);
}