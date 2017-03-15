<?php
use Df\Directory\Model\Country;
use Magento\Framework\App\ScopeInterface as IScope;
use Magento\Framework\UrlInterface as U;
use Magento\Store\Api\Data\StoreInterface as IStore;
use Magento\Store\Model\Information as Inf;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\StoreManagerInterface as IStoreManager;
/**
 * 2015-02-04
 * Обратите внимание, что вряд ли мы вправе кэшировать результат при парметре $store = null,
 * ведь текущий магазин может меняться.
 * @param int|string|null|bool|IStore $store [optional]
 * @return IStore|Store
 * @throws \Magento\Framework\Exception\NoSuchEntityException|Exception
 * https://github.com/magento/magento2/issues/2222
 */
function df_store($store = null) {
	/** @var IStore $result */
	$result = $store;
	if (is_null($result)) {
		/**
		 * 2015-11-04
		 * По аналогии с @see \Magento\Store\Model\StoreResolver::getCurrentStoreId()
		 * https://github.com/magento/magento2/blob/f578e54e093c31378ca981cfe336f7e651194585/app/code/Magento/Store/Model/StoreResolver.php#L82
		 */
		/** @var string|null $storeCode */
		$storeCode = df_request(\Magento\Store\Model\StoreResolver::PARAM_NAME);
		if (is_null($storeCode)) {
			$storeCode = df_store_cookie_m()->getStoreCodeFromCookie();
		}
		if (is_null($storeCode)) {
			$storeCode = df_request('store-view');
		}
		/**
		 * 2015-08-10
		 * Доработал алгоритм.
		 * Сначала мы смотрим, не находимся ли мы в административной части,
		 * и нельзя ли при этом узнать текущий магазин из веб-адреса.
		 * По аналогии с @see Mage_Adminhtml_Block_Catalog_Product_Grid::_getStore()
		 *
		 * 2015-09-20
		 * При единственном магазине
		 * вызываемый ниже метод метод @uses \Df\Core\State::getStoreProcessed()
		 * возвратит витрину default, однако при нахождении в административной части
		 * нам нужно вернуть витрину «admin».
		 * Например, это нужно, чтобы правильно работала функция @used-by df_is_backend()
		 * Переменная $coreCurrentStore в данной точке содержит витрину «admin».
		 *
		 * 2015-11-04
		 * При нахождении в административном интерфейсе
		 * и при отсутствии в веб-адресе идентификатора магазина
		 * этот метод вернёт витрину по-умолчанию, а не витрину «admin».
		 *
		 * Не знаю, правильно ли это, то так делает этот метод в Российской сборке для Magento 1.x,
		 * поэтому решил пока не менять поведение.
		 *
		 * В Magento 2 же стандартный метод \Magento\Store\Model\StoreManager::getStore()
		 * при вызове без параметров возвращает именно витрину по умолчанию, а не витрину «admin»:
		 * https://github.com/magento/magento2/issues/2254
		 * «The call for \Magento\Store\Model\StoreManager::getStore() without parameters
		 * inside the backend returns the default frontend store, not the «admin» store,
		 * which is inconsistent with Magento 1.x behaviour and I think it will lead to developer mistakes.»
		 */
		if (is_null($storeCode) && df_is_backend()) {
			$storeCode = df_request('store', 'admin');
		}
		if (!is_null($storeCode)) {
			$result = df_store_m()->getStore($storeCode);
		}
	}
	return is_object($result) ? $result : df_store_m()->getStore($result);
}

/**
 * 2016-01-30
 * @used-by df_sentry()
 * @param null|string|int|IScope $store [optional]
 * @return string
 */
function df_store_code($store = null) {return df_scope_code($store);}

/**            
 * 2017-01-21
 * «How to get the store's country?» https://mage2.pro/t/2509
 * @param null|string|int|IStore $store [optional] 
 * @return Country
 */
function df_store_country($store = null) {return df_country(df_store($store)->getConfig(
	Inf::XML_PATH_STORE_INFO_COUNTRY_CODE
));}

/**
 * 2016-01-11
 * @param int|string|null|bool|IStore $store [optional]
 * @return int
 */
function df_store_id($store = null) {return df_store($store)->getId();}

/**
 * 2016-01-11
 * @param bool $withDefault [optional]
 * @return int[]
 */
function df_store_ids($withDefault = false) {return array_keys(df_stores($withDefault));}

/**
 * 2017-02-07
 * @return IStoreManager|StoreManager
 */
function df_store_m() {return df_o(IStoreManager::class);}

/**
 * 2016-01-11
 * @used-by \Dfe\SalesSequence\Config\Next\Element::rows()
 * @param bool $withDefault [optional]
 * @param bool $codeKey [optional]
 * @return string[]
 */
function df_store_names($withDefault = false, $codeKey = false) {return
	array_map(function(IStore $store) {return
		$store->getName()
	;}, df_stores($withDefault, $codeKey))
;}

/**
 * 2017-03-15
 * Returns an empty string if the store's root URL is absent in the Magento database.
 * @used-by df_store_url_link()
 * @used-by df_store_url_web()
 * @param int|string|null|bool|IStore $s
 * @param string $type
 * @return string
 */
function df_store_url($s, $type) {return df_store($s)->getBaseUrl($type);}

/**
 * 2017-03-15
 * Returns an empty string if the store's root URL is absent in the Magento database.
 * @used-by \Df\Payment\Metadata::vars()
 * @param int|string|null|bool|IStore $s [optional]
 * @return string
 */
function df_store_url_link($s = null) {return df_store_url($s, U::URL_TYPE_LINK);}

/**
 * 2017-03-15
 * Returns an empty string if the store's root URL is absent in the Magento database.
 * @used-by df_domain()
 * @param int|string|null|bool|IStore $s [optional]
 * @return string
 */
function df_store_url_web($s = null) {return df_store_url($s, U::URL_TYPE_WEB);}

/**
 * 2016-01-11
 * 2016-01-29
 * Добави @uses df_ksort(), потому что иначе порядок элементов различается
 * в зависимости от того, загружается ли страница из кэша или нет.
 * Для модуля Dfe\SalesSequence нам нужен фиксированный порядок.
 * @param bool $withDefault [optional]
 * @param bool $codeKey [optional]
 * @return array|IStore[]
 */
function df_stores($withDefault = false, $codeKey = false) {return
	df_ksort(df_store_m()->getStores($withDefault, $codeKey))
;}