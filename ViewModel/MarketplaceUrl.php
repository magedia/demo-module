<?php

declare(strict_types=1);

namespace Magedia\Demo\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * "Back to Magedia store" target for the demo banner.
 *
 * Each demo points at its own extension on the marketplace, which used to mean
 * an edit held permanently in the working tree of one install. Config keeps
 * both installs on identical code:
 *
 *   bin/magento config:set magedia_demo/general/marketplace_url <url>
 */
class MarketplaceUrl implements ArgumentInterface
{
    public const XML_PATH_MARKETPLACE_URL = 'magedia_demo/general/marketplace_url';

    private const FALLBACK_URL = 'https://store.magedia.com/';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Marketplace URL for this demo, never empty so the banner cannot render a dead link.
     *
     * @return string
     */
    public function bannerDisabled(): bool
    {
        return $this->scopeConfig->isSetFlag('magedia_demo/general/banner_disabled');
    }

    public function get(): string
    {
        $url = (string)$this->scopeConfig->getValue(
            self::XML_PATH_MARKETPLACE_URL,
            ScopeInterface::SCOPE_STORE
        );

        return trim($url) !== '' ? $url : self::FALLBACK_URL;
    }
}
