<?php

namespace Milvus\Inscription\Model\Api;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Milvus\Inscription\Api\InscriptionInterface;
use Milvus\Inscription\Setup\Patch\Data\AddInscriptionAttribute;

/**
 * Model for managing the Inscription product attribute
 */
class Inscription implements InscriptionInterface
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritDoc
     */
    public function setValue(int $productId, string $value): string
    {
        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return __('There is no product with provided ID.')->getText();
        }

        if (!in_array($product->getTypeId(), [Type::TYPE_SIMPLE, Configurable::TYPE_CODE])) {
            return __('There is no simple or configurable product with provided ID.')->getText();
        }

        $product->setData(AddInscriptionAttribute::ATTRIBUTE_CODE, $value);

        try {
            $this->productRepository->save($product);
        } catch (LocalizedException $e) {
            return __('We can\'t save the product attribute.')->getText();
        }

        return __('Product attribute has been saved.')->getText();
    }
}
