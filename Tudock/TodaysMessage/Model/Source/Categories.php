<?php
/**
 * Copyright © 2021 Anastasiia Miednykh
 */

namespace Tudock\TodaysMessage\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Categories
 */
class Categories implements ArrayInterface
{
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_categoryHelper;
    /**
     * @var \Magento\Catalog\Api\CategoryManagementInterface
     */
    private \Magento\Catalog\Api\CategoryManagementInterface $categoryManagement;

    /**
     * Categories constructor.
     *
     * @param \Magento\Catalog\Helper\Category $catalogCategory
     * @param \Magento\Catalog\Api\CategoryManagementInterface $categoryManagement
     */
    public function __construct(
        \Magento\Catalog\Helper\Category $catalogCategory,
        \Magento\Catalog\Api\CategoryManagementInterface $categoryManagement
    ) {
        $this->_categoryHelper = $catalogCategory;
        $this->categoryManagement = $categoryManagement;
    }

    /*
     * Return categories helper
     */
    /**
     * @param false $sorted
     * @param false $asCollection
     * @param bool $toLoad
     *
     * @return array|\Magento\Framework\Data\Tree\Node\Collection
     */
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted, $asCollection, $toLoad);
    }

    /*
     * Option getter
     * @return array
     */
    public function toOptionArray()
    {
        $arr = $this->toArray();
        $ret = [];

        foreach ($arr as $key => $value) {

            $ret[] = [
                'value' => $key,
                'label' => $value,
            ];
        }

        return $ret;
    }

    /*
     * Get options in "key-value" format
     * @return array
     */
    public function toArray()
    {
        $categories = $this->getStoreCategories(false, true, true);

        $categoryList = [];
        foreach ($categories as $category) {
            $categoryList[$category->getEntityId()] = str_repeat("-", $category->getLevel()) . (__(
                    $category->getName()
                ));
        }

        return $categoryList;
    }

}
