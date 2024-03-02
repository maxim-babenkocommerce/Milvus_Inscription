<?php

declare(strict_types=1);

namespace Milvus\Inscription\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Configuration Model
 */
class Config
{
    private const ENABLE_LOG = 'catalog/inscriptions/logging';

    private ScopeConfigInterface $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns true when the Inscription log is enabled.
     *
     * @return bool
     */
    public function isLogEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::ENABLE_LOG);
    }
}
