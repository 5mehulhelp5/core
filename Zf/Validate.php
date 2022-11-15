<?php
namespace Df\Zf;
/** @see \Df\Zf\Validate\Type */
abstract class Validate implements \Zend_Validate_Interface {
	/**
	 * @used-by self::getMessage()
	 * @see \Df\Zf\Validate\Type::_message()
	 */
	abstract protected function _message():string;

	/**
	 * @used-by \Df\Zf\Validate\ArrayT::s()
	 * @used-by \Df\Zf\Validate\IntT::s()
	 * @used-by \Df\Zf\Validate\StringT::s()
	 * @used-by \Df\Zf\Validate\StringT\IntT::s()
	 * @used-by \Df\Zf\Validate\StringT\Iso2::s()
	 * @used-by \Df\Zf\Validate\StringT\FloatT::s()
	 * @param array(string => mixed) $p
	 */
	final function __construct(array $p = []) {$this->_params = $p;}

	/**
	 * @used-by df_float()
	 * @used-by df_int()
	 * @used-by self::getMessages()
	 */
	final function getMessage():string {
		if (!isset($this->_message)) {
			$this->_message = $this->_message();
			if ($this->getExplanation()) {
				$this->_message .= ("\n" . $this->getExplanation());
			}
		}
		return $this->_message;
	}

	/**
	 * @override
	 * @see \Zend_Validate_Interface::getMessages()
	 * @return array(string => string)
	 */
	final function getMessages():array {return [__CLASS__ => $this->getMessage()];}

	/**
	 * @param string $paramName
	 * @param mixed $d [optional]
	 * @return mixed
	 */
	final protected function cfg($paramName, $d = null) {return dfa($this->_params, $paramName, $d);}

	/** @return mixed */
	protected function getValue() {return $this->cfg(self::$PARAM__VALUE);}

	/**
	 * @param mixed $v
	 */
	protected function prepareValidation($v) {$this->setValue($v);}

	/** @used-by setValue() */
	protected function reset() {
		unset($this->_message);
		/**
		 * Раньше тут стоял код $this->_params = []
		 * который сбрасывает сразу все значения параметров.
		 * Однако этот код неверен!
		 * Негоже родительскому классу безапелляционно решать за потомков,
		 * какие данные им сбрасывать.
		 * Например, потомок @see \Df\Zf\Validate\Class
		 * хранит в параметре @see \Df\Zf\Validate\Class::$PARAM__CLASS
		 * требуемый класс результата,
		 * и сбрасывать это значение между разными валидациями не нужно!
		 * Вместо сброса значения между разными валидациями
		 * класс @see \Df\Zf\Validate\Class ведёт статический кэш своих экземпляров
		 * для каждого требуемого класса результата:
		 * @see \Df\Zf\Validate\Class::s().
		 * Сброс значения параметра @see \Df\Zf\Validate\Class::$PARAM__CLASS
		 * не только не нужен, но и приведёт к сбою!
		 * Пусть потомки сами решают
		 * посредством перекрытия метода @see \Df\Zf\Validate\Type::reset(),
		 * значения каких параметров им надо сбрасывать между разными валидациями.
		 */
		unset($this->_params[self::$PARAM__VALUE]);
	}

	/**
	 * @param string $message
	 */
	protected function setMessage($message) {$this->_message = $message;}

	/**
	 * @param mixed $value
	 */
	private function setValue($value) {
		$this->reset();
		$this->_params[self::$PARAM__VALUE] = $value;
	}

	/**
	 * @used-by self::getMessage()
	 * @used-by self::reset()
	 * @used-by self::setMessage()
	 * @var string
	 */
	private $_message;

	/**
	 * @used-by self::__construct()
	 * @used-by self::cfg()
	 * @used-by self::reset()
	 * @var array(string => mixed)
	 */
	private $_params = [];

	/** @var string */
	private static $PARAM__VALUE = 'value';
}