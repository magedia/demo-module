<?php
declare(strict_types=1);
namespace Magedia\Demo\Model\Reset;
use Magedia\Demo\Api\ResetHandlerInterface;
use Magento\Framework\Lock\LockManagerInterface;
final class Runner
{
    public function __construct(private SetResetTime $time, private LockManagerInterface $locks, private array $handlers = []) {}
    public function run(): bool
    {
        if (!$this->handlers || !$this->locks->lock('magedia_demo_reset', 0)) return false;
        try {
            foreach ($this->handlers as $handler) {
                if (!$handler instanceof ResetHandlerInterface) throw new \LogicException('Invalid demo reset handler.');
                $handler->reset();
            }
            $this->time->setLastResetTime();
            return true;
        } finally {
            $this->locks->unlock('magedia_demo_reset');
        }
    }
}
