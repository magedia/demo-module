<?php
declare(strict_types=1);
namespace Magedia\DemoSales\Model;
use Magedia\DemoSales\Model\Reset\DataRemover;
use Magedia\DemoSales\Model\Reset\DatabaseTables\Magento\Order\SalesTables;
use Magedia\DemoSales\Model\Reset\DatabaseTables\Magedia\CustomTables;
use Magedia\DemoSales\Processor\InstallData\InstallSampleData;
final class Fixtures
{
    public function __construct(private DataRemover $remover, private SalesTables $sales, private CustomTables $custom, private InstallSampleData $installer) {}
    public function clear(string $pattern): void
    {
        if ($pattern === '' || $pattern === '%') throw new \InvalidArgumentException('An extension table pattern is required.');
        $this->remover->truncateTables([array_merge($this->sales->getSalesOrderTables(), $this->custom->getCustomTableNames($pattern))]);
    }
    public function install(): void { $this->installer->setUpData(); }
}
