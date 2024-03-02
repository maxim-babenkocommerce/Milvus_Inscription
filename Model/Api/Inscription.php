<?php

declare(strict_types=1);

namespace Milvus\Inscription\Model\Api;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Milvus\Inscription\Api\InscriptionInterface;
use Milvus\Inscription\Model\Config;
use Milvus\Inscription\Setup\Patch\Data\AddInscriptionAttribute;
use Psr\Log\LoggerInterface;

/**
 * Model for managing the Inscription product attribute
 */
class Inscription implements InscriptionInterface
{
    private ProductRepositoryInterface $productRepository;

    private LoggerInterface $logger;

    private Config $config;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param LoggerInterface $logger
     * @param Config $config
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger,
        Config $config
    ) {
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        $this->config = $config;
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

        $oldValue = $product->getData(AddInscriptionAttribute::ATTRIBUTE_CODE);

        if ($oldValue === $value) {
            return __('There are no changes to the product.')->getText();
        }

        $product->setData(AddInscriptionAttribute::ATTRIBUTE_CODE, $value);

        try {
            $this->productRepository->save($product);
        } catch (LocalizedException $e) {
            return __('We can\'t save the product attribute.')->getText();
        }

        if ($this->config->isLogEnabled()) {
            $this->logger->info(
                __('Product %1. Old inscription: \'%2\'. New inscription: \'%3\'.', $productId, $oldValue, $value)
                    ->render()
            );
        }

        return __('Product attribute has been saved.')->getText();
    }
}
