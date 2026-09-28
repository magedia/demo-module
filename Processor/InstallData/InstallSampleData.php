<?php

declare(strict_types=1);

namespace Magedia\Demo\Processor\InstallData;

use Exception;
use Magedia\Demo\Processor\MagediaSampleData\ModulesPatches;
use Magedia\Demo\Processor\MagentoSampleData\Invoice\InvoicesData as MagentoInvoices;
use Magedia\Demo\Processor\MagentoSampleData\CreditMemo\CreditMemosData as MagentoCreditMemos;
use Magedia\Demo\Processor\MagentoSampleData\Shipment\ShipmentsData as MagentoShipments;
use Magedia\Demo\Processor\MagentoSampleData\Inventory\InventoryData as MagentoInventory;
use Magedia\Demo\Processor\MagentoSampleData\Order\OrdersData as MagentoOrders;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;

class InstallSampleData
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $objectManager;

    /**
     * @var ModulesPatches
     */
    private ModulesPatches $modulesPatches;

    /**
     * @var MagentoInventory
     */
    private MagentoInventory $magentoInventory;

    /**
     * @var MagentoOrders
     */
    private MagentoOrders $magentoOrders;
    private MagentoInvoices $magentoInvoices;
    private MagentoCreditMemos $magentoCreditMemos;
    private MagentoShipments $magentoShipments;

    public function __construct(
        LoggerInterface $logger,
        ModulesPatches $modulesPatches,
        ObjectManagerInterface $objectManager,
        MagentoInventory $magentoInventory,
        MagentoOrders $magentoOrders,
        MagentoInvoices $magentoInvoices,
        MagentoCreditMemos $magentoCreditMemos,
        MagentoShipments $magentoShipments
    ) {
        $this->logger = $logger;
        $this->objectManager = $objectManager;
        $this->modulesPatches = $modulesPatches;
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
        $this->setupModulesPatches();
        $this->magentoInventory->restore();
        $this->magentoOrders->createOrders();
        $this->magentoInvoices->createInvoices();
        $this->magentoCreditMemos->createCreditMemos();
        $this->magentoShipments->createShipments();
    }

    /**
     * @return void
     */
    private function setupModulesPatches(): void
    {
        $patches = $this->modulesPatches->createInstallDataPath();
        foreach ($patches as $moduleName => $modulePatches) {
            foreach ($modulePatches as $patchName) {
                try {
                    $patch = $this->objectManager->get("\Magedia\\$moduleName\Setup\Patch\Data\\$patchName");
                    $patch->apply();
                    $this->logger->info(
                        'Successfully installed sample data patch {patch} for {module} module.',
                        ['patch' => $patchName, 'module' => $moduleName]
                    );
                } catch (Exception $e) {
                    $this->logger->info($e->getMessage());
                    continue;
                }
            }
        }
    }
}

