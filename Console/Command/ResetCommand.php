<?php
declare(strict_types=1);

namespace Magedia\Demo\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends Command
{
    private EventManager $eventManager;
    private State $state;

    public function __construct(
        EventManager $eventManager,
        State $state,
        ?string $name = null
    ) {
        $this->eventManager = $eventManager;
        $this->state = $state;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('magedia:demo:reset')
            ->setDescription('Reset demo data manually');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting demo reset...');

        try {
            $this->state->setAreaCode(Area::AREA_ADMINHTML);
            $this->eventManager->dispatch('reset_sample_data');
            $output->writeln('<info>Demo reset completed successfully!</info>');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
