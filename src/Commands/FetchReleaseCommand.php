<?php
declare(strict_types=1);

namespace Mamau\Wkit\Commands;

use Mamau\Wkit\Services\GithubService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class FetchReleaseCommand
 * @package Mamau\Wkit\Commands
 */
class FetchReleaseCommand extends AbstractCommand
{
    protected static $defaultName = 'fetch';

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $github = new GithubService();

        $osId = $this->askOS($input, $output);
        $github->fetchBinary($osId);

        return self::SUCCESS;
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int
     */
    private function askOS(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Для какой ОС скачать бинарь? (по умолчанию Darwin)',
            GithubService::OS_LIST,
            0
        );
        $question->setErrorMessage('Нет такой оси');

        return (int)$helper->ask($input, $output, $question);
    }
}
