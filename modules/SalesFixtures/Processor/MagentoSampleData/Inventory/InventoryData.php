<?php

declare(strict_types=1);

namespace Magedia\DemoSales\Processor\MagentoSampleData\Inventory;

use Magedia\DemoSales\Api\Data\Magento\OrderInterface;
use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Magento\InventoryApi\Api\Data\SourceItemInterfaceFactory;
use Magento\InventoryApi\Api\SourceItemsSaveInterface;
use Magento\InventoryCatalogApi\Api\DefaultSourceProviderInterface;

class InventoryData
{
    private const SOURCE_QUANTITY = 100.0;

    /**
     * @var SourceItemInterfaceFactory
     */
    private SourceItemInterfaceFactory $sourceItemFactory;

    /**
     * @var SourceItemsSaveInterface
     */
    private SourceItemsSaveInterface $sourceItemsSave;

    /**
     * @var DefaultSourceProviderInterface
     */
    private DefaultSourceProviderInterface $defaultSourceProvider;

    /**
     * @param SourceItemInterfaceFactory $sourceItemFactory
     * @param SourceItemsSaveInterface $sourceItemsSave
     * @param DefaultSourceProviderInterface $defaultSourceProvider
     */
    public function __construct(
        SourceItemInterfaceFactory $sourceItemFactory,
        SourceItemsSaveInterface $sourceItemsSave,
        DefaultSourceProviderInterface $defaultSourceProvider
    ) {
        $this->sourceItemFactory = $sourceItemFactory;
        $this->sourceItemsSave = $sourceItemsSave;
        $this->defaultSourceProvider = $defaultSourceProvider;
    }

    /**
     * Restore stock consumed by the sample shipments before creating new orders.
     *
     * @return void
     */
    public function restore(): void
    {
        $sourceCode = $this->defaultSourceProvider->getCode();
        $sourceItems = [];

        foreach ($this->getSampleProductSkus() as $sku) {
            $sourceItem = $this->sourceItemFactory->create();
            $sourceItem->setSourceCode($sourceCode);
            $sourceItem->setSku($sku);
            $sourceItem->setQuantity(self::SOURCE_QUANTITY);
            $sourceItem->setStatus(SourceItemInterface::STATUS_IN_STOCK);
            $sourceItems[] = $sourceItem;
        }

        $this->sourceItemsSave->execute($sourceItems);
    }

    /**
     * Return the unique products used by the sample orders.
     *
     * @return string[]
     */
    private function getSampleProductSkus(): array
    {
        $skus = [];
        foreach (OrderInterface::ORDERS_SAMPLE_DATA as $order) {
            foreach ($order['items'] as $item) {
                $skus[$item['sku']] = $item['sku'];
            }
        }

        return array_values($skus);
    }
}

