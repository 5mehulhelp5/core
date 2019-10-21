<?php
namespace Df\Config\Plugin\Model\Config;
use Magento\Config\Model\Config\SourceFactory as Sb;
// 2019-10-21
final class PathValidator {
	/**
	 * 2019-10-21
	 * 1) https://magento.stackexchange.com/a/276025
	 * 2) The Magento\Config\Model\Config\PathValidator class is absent in Magento < 2.2:
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Config/Model/Config/PathValidator.php
	 * But it does not break the compilation process, I have checked it in Magento 2.1.15.
	 * @see \Magento\Config\Model\Config\PathValidator::validate()
	 * @param Sb $sb
	 * @param \Closure $f
	 * @param string $path
	 * @return \Magento\Framework\Option\ArrayInterface|mixed
	 */
	function aroundValidate(Sb $sb, \Closure $f, $path) {return true;}
}