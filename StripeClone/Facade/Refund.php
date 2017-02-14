<?php
namespace Df\StripeClone\Facade;
use Df\StripeClone\Method as M;
/**
 * 2017-02-10
 * @see \Dfe\Omise\Facade\Refund
 * @see \Dfe\Paymill\Facade\Refund
 * @see \Dfe\Stripe\Facade\Refund   
 * @method static Refund s(M $m)
 */
abstract class Refund extends \Df\StripeClone\Facade {
	/**
	 * 2017-02-10
	 * Метод должен вернуть идентификатор операции (не платежа!) в платёжной системе.
	 * Мы записываем его в БД и затем при обработке оповещений от платёжной системы
	 * смотрим, не было ли это оповещение инициировано нашей же операцией,
	 * и если было, то не обрабатываем его повторно.
	 * 2017-02-14
	 * Этот же идентификатор должен возвращать @see \Df\StripeClone\Webhook\IRefund::eTransId()
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @see \Dfe\Omise\Facade\Charge::transId()
	 * @see \Dfe\Paymill\Facade\Charge::transId()
	 * @see \Dfe\Stripe\Facade\Charge::transId()
	 * @param object $r
	 * @return string
	 */
	abstract function transId($r);
}