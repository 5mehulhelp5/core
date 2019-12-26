<?php
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface as IAction;
/**
 * 2017-05-04
 * @param string $c
 * @return IAction
 */
function df_action_create($c) {
	/** @var ActionFactory $f */
	$f = df_o(ActionFactory::class);
	return $f->create($c);
}

/**
 * 2017-03-16
 * @see df_url_path_contains()
 * @used-by \Dfe\AllPay\W\Event\Offline::ttCurrent()
 * @param string $s
 * @return bool
 */
function df_action_has($s) {return df_contains(df_action_name(), $s);}

/**
 * 2016-01-07
 * @see df_url_path_contains()
 * @used-by \Dfe\Markdown\Modifier::modifyData()
 * @used-by \Inkifi\Consolidation\Plugin\Backend\Block\Widget\Button\Toolbar::beforePushButtons()
 * @used-by \SayItWithAGift\Core\Plugin\Newsletter\Model\Subscriber::beforePrepare()
 * @used-by \Wolf\Filter\Observer\ControllerActionPredispatch::execute()
 * @used-by vendor/wolfautoparts.com/filter/view/frontend/templates/sidebar.phtml
 * @param string[] ...$names
 * @return bool
 */
function df_action_is(...$names) {return ($a = df_action_name()) && in_array($a, dfa_flatten($names));}

/**
 * 2015-09-02
 * 2017-03-15
 * Случай запуска Magento с командной строки надо обрабатывать отдельно, потому что иначе
 * @uses \Magento\Framework\App\Request\Http::getFullActionName() вернёт строку «__».
 * @used-by df_action_has()
 * @used-by df_action_is()
 * @used-by df_sentry()
 * @used-by \Dfe\Markdown\CatalogAction::entityType()
 * @used-by \Dfe\Markdown\FormElement::config()
 * @used-by \Justuno\M2\Block\Js::_toHtml()
 * @return string|null
 */
function df_action_name() {return df_is_cli() ? null : df_request_o()->getFullActionName();}

/**
 * 2017-08-28
 * @used-by df_is_checkout()
 * @used-by df_is_checkout_multishipping()
 * @used-by df_is_system_config()
 * @param string|string[] $p
 * @return bool
 */
function df_action_prefix($p) {return df_starts_with(df_action_name(), $p);}

/**
 * 2019-12-26
 * @see \Magento\Store\App\Response\Redirect::getRefererUrl():
 * 		df_response_redirect()->getRefererUrl()
 * @used-by df_referer_ends_with() 
 * @used-by https://github.com/royalwholesalecandy/core/issues/58
 * @return string
 */
function df_referer() {return dfa($_SERVER, 'HTTP_REFERER');}

/**
 * 2019-11-04
 * @see df_redirect_back()
 * @used-by \PPCs\Core\Plugin\Amazon\Payment\Observer\AddAmazonButton::aroundExecute()
 * @used-by \PPCs\Core\Plugin\Quote\Model\QuoteRepository::aroundGetActiveForCustomer()
 * @param string $s
 * @return bool
 */
function df_referer_ends_with($s) {return df_ends_with(df_referer(), $s);}