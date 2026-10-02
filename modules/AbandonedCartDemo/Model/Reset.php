<?php
declare(strict_types=1);
namespace Magedia\AbandonedCartDemo\Model;
use Magedia\Demo\Api\ResetHandlerInterface;
use Magedia\DemoSales\Model\Fixtures;
final class Reset implements ResetHandlerInterface
{
    public function __construct(private Fixtures $fixtures, private \Magento\Framework\App\ResourceConnection $resource) {}
    public function reset(): void
    {
        $file = BP . '/var/demo/abandoned-cart-rules.json';
        if (!is_file($file)) throw new \RuntimeException('Capture the synthetic demo rule baseline before enabling reset.');
        $rules = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($rules)) throw new \RuntimeException('Invalid demo rule baseline.');
        $this->fixtures->clear('magedia_acart%');
        if ($rules) $this->resource->getConnection()->insertMultiple($this->resource->getTableName('magedia_acart_rule'), $rules);
        $this->fixtures->install();
    }
}
