<?php

namespace App\Command;

use App\Services\PaymentCheckService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'payments:check',
    description: 'Проверка платежей в статусе "process"',
)]
class PaymentsCheckCommand extends Command
{
    public function __construct(private PaymentCheckService $paymentCheckService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $paymentsCount = $this->paymentCheckService->paymentsCheck();

        $io->success('Проверено ' . $paymentsCount . ' платежей в статусе process.');

        return Command::SUCCESS;
    }
}
