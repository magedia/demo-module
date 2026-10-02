<?php
declare(strict_types=1);
namespace Magedia\Demo\Observer;
use Magedia\Demo\Model\Reset\Runner;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
class ResetObserver implements ObserverInterface
{
    public function __construct(private Runner $runner) {}
    public function execute(Observer $observer): void { $this->runner->run(); }
}
