<?php
use ReflectionClass as RC;

/**
 * 2017-01-11 http://stackoverflow.com/a/666701
 * @used-by \Df\Payment\W\F::i()
 */
function df_class_check_abstract(string $c):bool {df_param_sne($c, 0); return (new RC(df_ctr($c)))->isAbstract();}

/**
 * 2016-05-06
 * By analogy with https://github.com/magento/magento2/blob/135f967/lib/internal/Magento/Framework/ObjectManager/TMap.php#L97-L99
 * 2016-05-23
 * Намеренно не объединяем строки в единное выражение, чтобы собака @ не подавляла сбои первой строки.
 * Такие сбои могут произойти при синтаксических ошибках в проверяемом классе
 * (похоже, getInstanceType как-то загружает код класса).
 * @used-by dfpm_c()
 * @used-by \Df\Payment\Block\Info::checkoutSuccess()
 */
function df_class_exists(string $c):bool {$c = df_ctr($c); return @class_exists($c);}