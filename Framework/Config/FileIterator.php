<?php
namespace Df\Framework\Config;
use Magento\Framework\Config\FileIterator as I;
// 2017-07-26
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class FileIterator extends I {
	/**
	 * 2017-07-26
	 * @param I $i
	 * @return string[]
	 */
	static function pathsGet(I $i) {return $i->paths;}

	/**
	 * 2017-07-26
	 * @param I $i
	 * @param string[] $v
	 * @return I
	 */
	static function pathsSet(I $i, array $v) {$i->paths = $v; return $i;}
}