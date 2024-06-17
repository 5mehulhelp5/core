<?php
use Magento\Catalog\Model\Category as C;
use Magento\Store\Api\Data\StoreInterface as IStore;

/**
 * 2019-09-08
 * @see df_product()
 * @used-by df_category_ancestor_at_level()
 * @used-by df_category_children()
 * @used-by df_category_children_map()
 * @used-by \Wolf\Filter\Block\Navigation::hDropdowns()
 * @used-by \Wolf\Filter\Block\Navigation::selectedPath()
 * @used-by \Wolf\Filter\Controller\Index\Change::execute()
 * @used-by \CabinetsBay\Catalog\B\Category::l3p() (https://github.com/cabinetsbay/site/issues/98)
 * @param int|string|C $c
 * @param int|string|null|bool|IStore $s [optional]
 */
function df_category($c, $s = false):C {return $c instanceof C ? $c : df_category_r()->get(
	$c, false === $s ? null : df_store_id(true === $s ? null : $s)
);}

/**
 * 2021-11-30 @deprecated It is unused.
 * @see df_product_id()
 * @param C|int $c
 */
function df_category_id($c):int {return df_int($c instanceof C ? $c->getId() : $c);}