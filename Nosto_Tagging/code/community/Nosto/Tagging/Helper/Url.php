<?php
/**
* Magento
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@magentocommerce.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to http://www.magentocommerce.com for more information.
*
* @category    design
* @package     base_default
* @copyright   Copyright (c) 2013 Nosto Solutions Ltd (http://www.nosto.com)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

/**
 * Helper class for building urls.
 * Includes getters for all preview urls for the Nosto account configuration iframe.
 *
 * @category    Nosto
 * @package     Nosto_Tagging
 * @author      Nosto Solutions Ltd
 */
class Nosto_Tagging_Helper_Url extends Mage_Core_Helper_Abstract
{
	/**
	 * Gets the absolute preview URL to the current store view product page.
	 * The product is the first one found in the database for the store.
	 * The preview url includes "nostodebug=true" parameter.
	 *
	 * @return string the url.
	 */
	public function getPreviewUrlProduct()
	{
		$collection = Mage::getModel('catalog/product')
			->getCollection()
			->addStoreFilter(Mage::app()->getStore()->getId())
			->addAttributeToSelect('*')
			->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
		 	->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
			->setPageSize(1)
			->setCurPage(1);
		foreach ($collection as $product) {
			/** @var Mage_Catalog_Model_Product $product */
			$url = $product->getProductUrl();
			$url = NostoHttpRequest::replaceQueryParamInUrl('___store', Mage::app()->getStore()->getCode(), $url);
			return NostoHttpRequest::replaceQueryParamInUrl('nostodebug', 'true', $url);
		}
		return '';
	}

	/**
	 * Gets the absolute preview URL to the current store view category page.
	 * The category is the first one found in the database for the store.
	 * The preview url includes "nostodebug=true" parameter.
	 *
	 * @return string the url.
	 */
	public function getPreviewUrlCategory()
	{
		$rootCategoryId = (int)Mage::app()->getStore()->getRootCategoryId();
		$collection = Mage::getModel('catalog/category')
			->getCollection()
			->addFieldToFilter('is_active', 1)
			->addFieldToFilter('path', array('like' => "1/$rootCategoryId/%"))
			->addAttributeToSelect('*')
			->setPageSize(1)
			->setCurPage(1);
		foreach ($collection as $category) {
			/** @var Mage_Catalog_Model_Category $category */
			$url = $category->getUrl();
			$url = NostoHttpRequest::replaceQueryParamInUrl('___store', Mage::app()->getStore()->getCode(), $url);
			return NostoHttpRequest::replaceQueryParamInUrl('nostodebug', 'true', $url);
		}
		return '';
	}

	/**
	 * Gets the absolute preview URL to the current store view search page.
	 * The search query in the URL is "q=nosto".
	 * The preview url includes "nostodebug=true" parameter.
	 *
	 * @return string the url.
	 */
	public function getPreviewUrlSearch()
	{
		$url = Mage::getUrl('catalogsearch/result', array('_store' => Mage::app()->getStore()->getId(), '_store_to_url' => true));
		$url = NostoHttpRequest::replaceQueryParamInUrl('q', 'nosto', $url);
		return NostoHttpRequest::replaceQueryParamInUrl('nostodebug', 'true', $url);
	}

	/**
	 * Gets the absolute preview URL to the current store view cart page.
	 * The preview url includes "nostodebug=true" parameter.
	 *
	 * @return string the url.
	 */
	public function getPreviewUrlCart()
	{
		$url = Mage::getUrl('checkout/cart', array('_store' => Mage::app()->getStore()->getId(), '_store_to_url' => true));
		return NostoHttpRequest::replaceQueryParamInUrl('nostodebug', 'true', $url);
	}

	/**
	 * Gets the absolute preview URL to the current store view front page.
	 * The preview url includes "nostodebug=true" parameter.
	 *
	 * @return string the url.
	 */
	public function getPreviewUrlFront()
	{
		$url = Mage::getUrl('', array('_store' => Mage::app()->getStore()->getId(), '_store_to_url' => true));
		return NostoHttpRequest::replaceQueryParamInUrl('nostodebug', 'true', $url);
	}
}
