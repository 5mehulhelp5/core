<?php
namespace Df\Customer\Plugin\Js;
use Magento\Customer\CustomerData\Customer as Sb;
/**
 * 2020-01-25
 * This plugin is intentionally disabled by default in vendor/mage2pro/core/Customer/etc/frontend/di.xml.
 * If you need the current quote ID in JavaScript,
 * then enable the plugin in the `etc/frontend/di.xml` file of your module:
 *	<type name='Magento\Customer\CustomerData\Customer'>
 *		<plugin disabled='false' name='Df\Customer\Js\QuoteId' />
 *	</type>
 * 2020-01-26
 * Currently, it is never used.
 * A previous usage:
 * https://github.com/mage2pro/sift/blob/0.0.4/etc/frontend/di.xml#L9
 * https://github.com/mage2pro/sift/blob/0.0.4/view/frontend/web/main.js#L7
 */
final class QuoteId {
	/**
	 * 2020-01-25
	 * @see \Magento\Customer\CustomerData\Customer::getSectionData()
	 * @param Sb $sb
	 * @param array(string => mixed) $r
	 * @return array(string => mixed)
	 */
	function afterGetSectionData(Sb $sb, array $r) {return ['quoteId' => df_quote_id()] + $r;}
}