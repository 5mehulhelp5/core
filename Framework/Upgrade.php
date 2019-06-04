<?php
namespace Df\Framework;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\DB\Adapter\AdapterInterface as IAdapter;
use Magento\Framework\DB\Adapter\Pdo\Mysql as Adapter;
use Magento\Framework\Module\Setup as FSetup;
use Magento\Framework\Setup\ModuleContextInterface as IModuleContext;
use Magento\Setup\Model\ModuleContext;
use Magento\Setup\Module\Setup as SSetup;
/**
 * 2016-12-08
 * Нам достаточно реализовывать только классы Upgrade,
 * а классы Install можно не делать, потому что при обновлении ядро вызывает только классы Upgrade,
 * а при первичной установке — оба класса (Install и Upgrade),
 * причём оба являются факультативными:
 *		$installer = $this->getSchemaDataHandler($moduleName, $installType);
 *		if ($installer) {
 *			$this->log->logInline("Installing $type... ");
 *			$installer->install($setup, $moduleContextList[$moduleName]);
 *		}
 *		$upgrader = $this->getSchemaDataHandler($moduleName, $upgradeType);
 *		if ($upgrader) {
 *			$this->log->logInline("Upgrading $type... ");
 *			$upgrader->upgrade($setup, $moduleContextList[$moduleName]);
 *		}
 * https://github.com/magento/magento2/blob/2.1.2/setup/src/Magento/Setup/Model/Installer.php#L840-L850
 * @see \Df\Framework\Upgrade\Data
 * @see \Df\Framework\Upgrade\Schema
 */
abstract class Upgrade {
	/**
	 * 2016-12-08
	 * @used-by process()
	 * @see \Df\API\Setup\UpgradeSchema::_process()
	 * @see \Df\Customer\Setup\UpgradeSchema::_process()
	 * @see \Df\OAuth\Setup\UpgradeSchema::_process()
	 * @see \Df\Sales\Setup\UpgradeSchema::_process()
	 * @see \Df\Sso\Upgrade\Data::_process()
	 * @see \Df\Sso\Upgrade\Schema::_process()
	 * @see \Dfe\IPay88\Setup\UpgradeData::_process()
	 * @see \Dfe\Markdown\Setup\UpgradeSchema::_process()
	 * @see \Inkifi\Pwinty\Setup\UpgradeSchema::_process()
	 * @see \KingPalm\B2B\Setup\UpgradeData::_process()
	 * @see \KingPalm\B2B\Setup\UpgradeSchema::_process()
	 * @see \Verdepieno\Core\Setup\UpgradeData::_process()
	 * @see \Verdepieno\Core\Setup\UpgradeSchema::_process()
	 */
	abstract protected function _process();

	/**
	 * 2016-12-08
	 * @used-by column()
	 * @used-by \Dfe\Markdown\Setup\UpgradeSchema::createTableEav()
	 * @return Adapter|IAdapter
	 */
	final protected function c() {return $this->_setup->getConnection();}

	/**
	 * 2017-08-01
	 * 2019-04-03
	 * 1) How to add an integer column:
	 * https://github.com/inkifi/pwinty/blob/0.0.2/Setup/UpgradeSchema.php#L15
	 * 2) How to add a custom column:
	 * https://github.com/Inkifi-Connect/Media-Clip-Inkifi/blob/2019-04-03/Setup/UpgradeSchema.php#L668-L675
	 * @used-by columnCAE()
	 * @used-by columnCE()
	 * @used-by \Df\Customer\Setup\UpgradeSchema::_process()
	 * @used-by \Dfe\Markdown\Setup\UpgradeSchema::_process()
	 * @used-by \Inkifi\Pwinty\Setup\UpgradeSchema::_process()
	 * @used-by \Verdepieno\Core\Setup\UpgradeSchema::_process()
	 * @see df_db_column_add()
	 * @param string $t
	 * @param string $name
	 * @param string $dfn [optional]
	 * @param string $comm [optional]
	 */
	final protected function column($t, $name, $dfn = '', $comm = '') {df_db_column_add($t, $name, $dfn, $comm);}

	/**
	 * 2019-03-06
	 * @see columnCE()
	 * @used-by \Verdepieno\Core\Setup\UpgradeSchema::_process()
	 * @param string $name
	 * @param string $dfn [optional]
	 * @param string $comm [optional]
	 */
	final protected function columnCAE($name, $dfn = '', $comm = '') {$this->column(
		'customer_address_entity', $name, $dfn, $comm
	);}

	/**
	 * 2016-06-05
	 * 2016-08-22
	 * Помимо добавления поля в таблицу «customer_entity» надо ещё добавить атрибут
	 * что мы делаем методом @see \Df\Sso\Upgrade\Data::attribute()
	 * иначе данные не будут сохраняться: https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Eav/Model/Entity/AbstractEntity.php#L1262-L1265
	 * @see columnCAE()
	 * @used-by \Df\Sso\Upgrade\Schema::_process()
	 * @used-by \Dfe\FacebookLogin\Setup\UpgradeSchema::_process()
	 * @param string $name
	 * @param string $dfn [optional]
	 * @param string $comm [optional]
	 * 2016-11-04 У нас теперь также есть функция @see df_db_column_add()
	 */
	final protected function columnCE($name, $dfn = '', $comm = '') {$this->column(
		'customer_entity', $name, $dfn, $comm
	);}

	/**
	 * 2016-12-02
	 * 2017-08-01
	 * Override this method if the initially installed module has not included this class.
	 * In this case provide here the number of the version when the class has been added.
	 * @used-by isInitial()
	 * @see \Dfe\BlackbaudNetCommunity\Setup\UpgradeData::initial()
	 * @see \Dfe\BlackbaudNetCommunity\Setup\UpgradeSchema::initial()
	 * @see \Dfe\IPay88\Setup\UpgradeData::initial()
	 * @return string
	 */
	protected function initial() {return '0.0.1';}

	/**
	 * 2016-12-02
	 * @used-by \Df\Sso\Upgrade\Data::_process()
	 * @used-by \Df\Sso\Upgrade\Schema::_process()
	 * @used-by \Dfe\FacebookLogin\Setup\UpgradeData::_process()
	 * @used-by \Dfe\FacebookLogin\Setup\UpgradeSchema::_process()
	 * @used-by \Dfe\Markdown\Setup\UpgradeSchema::_process()
	 * @used-by \KingPalm\B2B\Setup\UpgradeData::_process()
	 * @used-by \KingPalm\B2B\Setup\UpgradeSchema::_process()
	 * @used-by \Verdepieno\Core\Setup\UpgradeData::_process()
	 * @used-by \Verdepieno\Core\Setup\UpgradeSchema::_process()
	 * @return bool
	 */
	final protected function isInitial() {return $this->v($this->initial());}

	/**
	 * 2016-08-14
	 * @used-by \Df\Framework\Upgrade\Data::upgrade()
	 * @used-by \Df\Framework\Upgrade\Schema::upgrade()
	 * @param FSetup|SSetup $setup
	 * @param IModuleContext|ModuleContext $context
	 */
	final protected function process(FSetup $setup, IModuleContext $context) {
		$setup->startSetup();
		$this->_context = $context;
		$this->_setup = $setup;
		$this->_process();
		$setup->endSetup();
	}

	/**
	 * 2016-08-14
	 * @used-by \Df\Sales\Setup\Schema::sQuote()
	 * @used-by \Df\Sales\Setup\Schema::sSales()
	 * @param string $c [optional]
	 * @return EavSetup
	 */
	final protected function sEav($c = EavSetup::class) {return dfc($this, function($c) {return
		df_new_om($c, ['setup' => $this->_setup])
	;}, [$c]);}

	/**
	 * 2018-03-21
	 * @used-by \Dfe\Logo\Setup\UpgradeSchema::_process()
	 * @return FSetup|SSetup
	 */
	final protected function setup() {return $this->_setup;}

	/**
	 * 2016-12-08
	 * 2017-08-01 It does the same as @see df_table().
	 * The sole difference: $this->t() expression can be used inside PHP strings, but df_table can not.
	 * E.g.:
	 *		CREATE TABLE IF NOT EXISTS `{$this->t($name)}` (
	 *			`value_id` int(11) NOT NULL
	 *			,`{$f_MARKDOWN}` text
	 *			,PRIMARY KEY (`{$f_VALUE_ID}`)
	 *			,FOREIGN KEY (`{$f_VALUE_ID}`)
	 *				REFERENCES `{$this->t($master)}` (`value_id`)
	 *				ON DELETE CASCADE
	 *			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
	 *		");
	 * https://github.com/mage2pro/markdown/blob/1.0.24/Setup/UpgradeSchema.php#L74-L82
	 * @used-by \Dfe\Markdown\Setup\UpgradeSchema::createTableEav()
	 * @param string|array $t
	 * @return string
	 */
	final protected function t($t) {return $this->_setup->getTable($t);}

	/**
	 * 2016-08-21
	 * 2017-08-01 It checks whether the installed version of the current module is lower than $v.
	 * @used-by isInitial()
	 * @used-by \Df\API\Setup\UpgradeSchema::_process()
	 * @used-by \Df\Customer\Setup\UpgradeData::_process()
	 * @used-by \Df\Customer\Setup\UpgradeSchema::_process()
	 * @used-by \Df\OAuth\Setup\UpgradeSchema::_process()
	 * @used-by \Inkifi\Pwinty\Setup\UpgradeSchema::_process()
	 * @used-by \Verdepieno\Core\Setup\UpgradeSchema::_process()
	 * @param string $v
	 * @return bool
	 */
	final protected function v($v) {return -1 === version_compare($this->_context->getVersion(), $v);}

	/**
	 * 2016-12-02
	 * @used-by process()
	 * @used-by v()
	 * @var IModuleContext|ModuleContext
	 */
	private $_context;

	/**
	 * 2016-12-02
	 * @used-by c()
	 * @used-by process()
	 * @used-by sEav()
	 * @used-by setup()
	 * @used-by t()
	 * @var FSetup|SSetup
	 */
	private $_setup;
}