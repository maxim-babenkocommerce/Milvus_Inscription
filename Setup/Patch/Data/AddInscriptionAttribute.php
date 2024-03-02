<?php

declare(strict_types=1);

namespace Milvus\Inscription\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Zend_Validate_Exception;

/**
 *  Adds Add the Inscription product attribute
 */
class AddInscriptionAttribute implements DataPatchInterface
{
    private const ATTRIBUTE_CODE = 'inscription';
    private const ATTRIBUTE_LABEL = 'Inscription Text';
    private const ATTRIBUTE_NOTE = 'Add inscription to the product';

    private ModuleDataSetupInterface $moduleDataSetup;

    private EavSetupFactory $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheriDoc
     *
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply(): AddInscriptionAttribute
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'text',
                'input' => 'text',
                'label' => self::ATTRIBUTE_LABEL,
                'note' => self::ATTRIBUTE_NOTE,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'user_defined' => true,
                'apply_to' => implode(',', [Type::TYPE_SIMPLE, Configurable::TYPE_CODE]),
            ]
        );

        $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
        $allAttributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

        foreach ($allAttributeSetIds as $attributeSetId) {
            $groupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, 'General');
            $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $groupId,self::ATTRIBUTE_CODE, 100);
        }

        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }
}
