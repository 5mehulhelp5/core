<?php
/**
 * 2017-04-26
 * @used-by df_ci_get()
 * @used-by df_fe_fc()
 * @used-by df_oi_add()
 * @used-by df_oi_get()
 * @used-by df_primary_key()
 * @used-by df_trd()
 * @used-by ikf_oi_pid()
 * @used-by \Df\API\Facade::p()
 * @used-by \Df\Config\Backend\Serialized::valueUnserialize()
 * @used-by \Df\Config\Settings::json()
 * @used-by \Df\Customer\Plugin\Block\Form\Register::afterGetFormData()
 * @used-by \Df\Directory\FE\Country::getValues()
 * @used-by \Df\Xml\Parser\Collection::findByNameAll()
 * @used-by \Df\Xml\X::importString()
 * @used-by \Df\Zf\Validate\ArrayT::filter()
 * @used-by \Dfe\AllPay\Total\Quote::iiAdd()
 * @used-by \Dfe\AllPay\Total\Quote::iiGet()
 * @used-by \Dfe\CheckoutCom\Method::disableEvent()
 * @used-by \Dfe\Color\Plugin\Swatches\Block\Adminhtml\Attribute\Edit\Options\Visual::afterGetJsonConfig()
 * @used-by \Dfe\Color\Plugin\Swatches\Model\Swatch::beforeBeforeSave()
 * @used-by \Dfe\Markdown\Plugin\Ui\Component\Form\Element\Wysiwyg::beforePrepare()
 * @used-by \Dfe\Square\Facade\Customer::cardsData()
 * @used-by \KingPalm\Core\Plugin\Aitoc\OrdersExportImport\Model\Processor\Config\ExportConfigMapper::aroundToConfig()
 * @param mixed|null $v
 * @return mixed[]
 */
function df_eta($v) {
	if (!is_array($v)) {
		df_assert(empty($v));
		$v = [];
	}
	return $v;
}

/**
 * 2020-01-29
 * @used-by df_countries_options()
 * @used-by df_currencies_options()
 * @used-by df_customer_session_id()
 * @used-by df_img_resize()
 * @param mixed $v
 * @return mixed|null
 */
function df_etn($v) {return $v ?: null;}

/**
 * @used-by df_fetch_one()
 * @used-by df_parent_name()
 * @used-by \Df\Core\O::cacheLoadProperty()
 * @used-by \Df\Xml\X::descend()
 * @used-by \Dfe\Stripe\Init\Action::need3DS()
 * @used-by \Dfe\Stripe\Init\Action::redirectUrl()
 * @param mixed|false $v
 * @return mixed|null
 */
function df_ftn($v) {return (false === $v) ? null : $v;}

/**
 * 2016-08-04
 * @used-by dfa_deep()
 * @used-by \Df\Payment\Block\Info::si()
 * @used-by \Df\Xml\Parser\Entity::descendWithCast()
 * @param mixed $v
 * @return bool
 */
function df_nes($v) {return is_null($v) || '' === $v;}

/**
 * @used-by sift_prefix()
 * @used-by \Df\Qa\State::className()
 * @used-by \Df\Qa\State::functionName()
 * @used-by \Dfe\Sift\Payload\Payment\PayPal::p()
 * @used-by \Dfe\Sift\Test\CaseT\PayPal::t01()
 * @param mixed|null $v
 * @return mixed
 */
function df_nts($v) {return !is_null($v) ? $v : '';}