<?php

declare(strict_types=1);

namespace Magedia\DemoSales\Processor\InstallData;

use Magedia\DemoSales\Processor\MagentoSampleData\Invoice\InvoicesData as MagentoInvoices;
use Magedia\DemoSales\Processor\MagentoSampleData\CreditMemo\CreditMemosData as MagentoCreditMemos;
use Magedia\DemoSales\Processor\MagentoSampleData\Shipment\ShipmentsData as MagentoShipments;
use Magedia\DemoSales\Processor\MagentoSampleData\Inventory\InventoryData as MagentoInventory;
use Magedia\DemoSales\Processor\MagentoSampleData\Order\OrdersData as MagentoOrders;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class InstallSampleData
{
    /** Inventory fixture service. */
    private MagentoInventory $magentoInventory;

    /**
     * @var MagentoOrders
     */
    private MagentoOrders $magentoOrders;
    private MagentoInvoices $magentoInvoices;
    private MagentoCreditMemos $magentoCreditMemos;
    private MagentoShipments $magentoShipments;

    public function __construct(
        MagentoInventory $magentoInventory,
        MagentoOrders $magentoOrders,
        MagentoInvoices $magentoInvoices,
        MagentoCreditMemos $magentoCreditMemos,
        MagentoShipments $magentoShipments
    ) {
        $this->magentoInventory = $magentoInventory;
        $this->magentoOrders = $magentoOrders;
        $this->magentoInvoices = $magentoInvoices;
        $this->magentoCreditMemos = $magentoCreditMemos;
        $this->magentoShipments = $magentoShipments;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function setUpData(): void
    {
        $this->magentoInventory->restore();
        $this->magentoOrders->createOrders();
        $this->magentoInvoices->createInvoices();
        $this->magentoCreditMemos->createCreditMemos();
        $this->magentoShipments->createShipments();
    }

}
