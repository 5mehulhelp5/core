<?php
namespace Df\Payment\W\Strategy;
use Df\Payment\Method as M;
use Df\Payment\W\Handler;
use Df\Payment\W\IRefund;
// 2017-01-07
final class Refund extends \Df\Payment\W\Strategy {
	/**
	 * 2017-01-07
	 * 2017-01-18
	 * Переводить здесь размер платежа из копеек (формата платёжной системы)
	 * в рубли (формат Magento) не нужно: это делает dfp_refund().
	 * 2017-08-16
	 * Previously, I had the following code here:
	 *	$this->resultSet((dfp_container_has($this->op(), M::II_TRANS, $h->eTransId()) ? null :
	 *		dfp_refund($this->op(), $h->nav()->pid(), $h->amount())
	 *	) ?: 'skipped');
	 * This code is not correct, because PayPal clones require a spicific response on success:
	 * @see \Dfe\AllPay\W\Handler::result()
	 * @see \Dfe\Dragonpay\W\Handler::result()
	 * @see \Dfe\IPay88\W\Handler::result()
	 * @see \Dfe\Robokassa\W\Handler::result()
	 * @override
	 * @see \Df\Payment\W\Strategy::_handle()
	 * @used-by \Df\Payment\W\Strategy::::handle()
	 */
	protected function _handle() {
		$h = df_ar($this->h(), IRefund::class); /** @var Handler|IRefund $h */
		if (!dfp_container_has($this->op(), M::II_TRANS, $h->eTransId())) {
			dfp_refund($this->op(), $h->nav()->pid(), $h->amount());
		}
	}
}