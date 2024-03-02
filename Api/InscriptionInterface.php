<?php

namespace Milvus\Inscription\Api;

/**
 * Interface for managing the Inscription product attribute
 */
interface InscriptionInterface
{
    /**
     * Set the attribute value by the product ID
     *
     * @param int $productId
     * @param string $value
     * @return string
     */
    public function setValue(int $productId, string $value): string;
}
