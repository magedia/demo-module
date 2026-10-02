<?php
declare(strict_types=1);
namespace Magedia\PdfInvoiceDemo\Model;
use Magedia\Demo\Api\ResetHandlerInterface;
use Magedia\DemoSales\Model\Fixtures;
use Magedia\PdfInvoice\Setup\Patch\Data\InstallDefaultTemplatesPatch;
final class Reset implements ResetHandlerInterface
{
    public function __construct(private Fixtures $fixtures, private InstallDefaultTemplatesPatch $templates, private ConfigUpdater $config) {}
    public function reset(): void
    {
        $this->fixtures->clear('magedia_pdfinvoice%');
        $this->templates->apply();
        $this->fixtures->install();
        $this->config->reset();
    }
}
