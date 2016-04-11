<?php
/*
 * This file is part of the sparkpost-api-cli package.
 *
 * (c) Roman Sachse <r.sachse@ipark-media.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ipm\SparkpostApi\Handler;

use GuzzleHttp\Client;
use Ivory\HttpAdapter\Guzzle6HttpAdapter;
use SparkPost\APIResponseException;
use SparkPost\SparkPost;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;
use Webmozart\Console\Adapter\ArgsInput;
use Webmozart\Console\Adapter\IOOutput;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;
use Webmozart\Console\UI\Component\Table;

class AbstractSparkpostUnwrappedHandler
{
    const API_ENDPOINT = '';

    /**
     * @var array
     */
    protected $config = [];

    public function __construct()
    {
        $this->config = include 'config.php';
    }

    /**
     * @param Args $args
     * @return \SparkPost\APIResource
     */
    protected function connect(Args $args)
    {
        $subaccount = false;
        if ($args->isArgumentDefined('subaccount')) {
            $subaccount = $args->getArgument('subaccount');
        }

        $parameters = [
            'key' => $args->getOption('key') ?? $this->config['key'],
        ];

        if ($subaccount) {
            $parameters['X-MSYS-SUBACCOUNT'] = $subaccount;
        }

        $httpAdapter = new Guzzle6HttpAdapter(new Client());
        $sparky = new SparkPost($httpAdapter, $parameters);
        return $sparky->setupUnwrapped(static::API_ENDPOINT);
    }

    protected function renderApiResponseException(APIResponseException $exception, IO $io)
    {
        $io->writeLine("");
        $io->writeLine("<error>Error:</error>");

        $table = new Table();
        $table->setHeaderRow(['Message', 'Code', 'Description']);

        if ($exception->getCode() === 404) {
            $table->addRow([$exception->getMessage(), $exception->getCode(), '']);
        } else {
            $table->addRow([$exception->getAPIMessage(), $exception->getAPICode(), $exception->getAPIDescription()]);
        }

        $table->render($io);
    }

    protected function ask(Question $question, Args $args, IO $io)
    {
        $helper = new QuestionHelper();
        return $helper->ask(new ArgsInput($args->getRawArgs(), $args), new IOOutput($io), $question);
    }
}