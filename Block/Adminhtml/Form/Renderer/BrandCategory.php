<?php
/**
 * Mavenbird
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mavenbird.com license that is
 * available through the world-wide-web at this URL:
 * https://www.Mavenbird.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mavenbird
 * @package     Mavenbird_Shopbybrand
 * @copyright   Copyright (c) Mavenbird (https://www.Mavenbird.com/)
 * @license     https://www.Mavenbird.com/LICENSE.txt
 */

namespace Mavenbird\Shopbybrand\Block\Adminhtml\Form\Renderer;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use Mavenbird\Shopbybrand\Helper\Data as BrandHelper;
use Mavenbird\Shopbybrand\Model\BrandFactory;
use Mavenbird\Shopbybrand\Model\CategoryFactory;

/**
 * Class RenderDefaultAttributes
 * @package Mavenbird\LayeredNavigationUltimate\Block\Adminhtml\Form\Renderer
 */
class BrandCategory extends Element
{
    /** @var string Template */
    protected $_template = 'Mavenbird_Shopbybrand::category/brands.phtml';

    /**
     * @var BrandHelper
     */
    protected $helperData;

    /**
     * @var BrandFactory
     */
    protected $brandFactory;

    /**
     * @var CategoryFactory
     */
    protected $brandCategoryFactory;

    /**
     * @var Store
     */
    protected $systemStore;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * BrandCategory constructor.
     *
     * @param BrandHelper $helperData
     * @param BrandFactory $brandFactory
     * @param CategoryFactory $brandCategoryFactory
     * @param Store $systemStore
     * @param Registry $coreRegistry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        BrandHelper $helperData,
        BrandFactory $brandFactory,
        CategoryFactory $brandCategoryFactory,
        Store $systemStore,
        Registry $coreRegistry,
        Context $context,
        array $data = []
    ) {
        $this->helperData           = $helperData;
        $this->brandFactory         = $brandFactory;
        $this->coreRegistry         = $coreRegistry;
        $this->systemStore          = $systemStore;
        $this->brandCategoryFactory = $brandCategoryFactory;

        parent::__construct($context, $data);
    }

    /**
     * @return Collection
     */
    public function getBrands()
    {
        return $this->brandFactory->create()->getBrandCollection();
    }

    /**
     * get all store as array
     * @return array
     */
    public function getStoreViews()
    {
        return $this->systemStore->getStoreValuesForForm();
    }

    /**
     * check is single store
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return $this->_storeManager->isSingleStoreMode();
    }

    /**
     * @return array
     */
    public function getSelectedBrands()
    {
        $optionIds = [];
        $model     = $this->coreRegistry->registry('current_brand_category');
        if ($model->getId()) {
            $collection = $this->brandCategoryFactory->create()->getCollection();
            $collection->getSelect()
                ->joinInner(
                    ['at' => $collection->getTable('Mavenbird_shopbybrand_brand_category')],
                    'main_table.cat_id = at.cat_id'
                );

            foreach ($collection->getData() as $item) {
                if ($item['cat_id'] === $model->getId()) {
                    $optionIds[] = $item['option_id'];
                }
            }
        }

        return $optionIds;
    }
}
