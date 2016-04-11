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

use SparkPost\APIResponseException;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;
use Webmozart\Console\UI\Component\Table;

/**
 * Handles Calls to the sending-domains endpoint
 */
final class SendingDomainHandler extends AbstractSparkpostUnwrappedHandler
{
    const API_ENDPOINT = 'sending-domains';

    public function handleList(Args $args, IO $io)
    {
        try {
            $api = $this->connect($args);
            $response = $api->get();

            $table = new Table();
            $table->setHeaderRow(['Domain', 'Subaccount', 'ownership', 'SPF', 'DKIM']);
            $results = array_map(function ($sendingDomain) {
                return [
                    $sendingDomain['domain'],
                    $sendingDomain['subaccount_id'] ?? 'Master',
                    $sendingDomain['status']['ownership_verified'] ? '+' : '-',
                    $sendingDomain['status']['spf_status'] === 'valid' ? '+' : '-',
                    $sendingDomain['status']['dkim_status'] === 'valid' ? '+' : '-',
                ];
            }, $response['results']);

            $table->addRows($results);
            $table->render($io);
            return 0;
        } catch (APIResponseException $e) {
            $this->renderApiResponseException($e, $io);
            return 1;
        }
    }

    /**
     * Adds a Sending-Domain
     *
     * @param Args $args
     * @param IO $io
     */
    public function handleAdd(Args $args, IO $io)
    {
        $domain = $args->getArgument('domain');

        try {
            $io->writeLine("Creating: <b>{$domain}</b>");
            $api = $this->connect($args);
            $parameters = array_merge(['domain' => $domain], $this->config['sending-domains'] ?? []);
            $api->create($parameters);
            $io->writeLine("<b>{$domain}</b> was succesfully created");
        } catch (APIResponseException $e) {
            $this->renderApiResponseException($e, $io);
        }
    }

    /**
     * Deletes a Sending-Domain
     *
     * @param Args $args
     * @param IO $io
     */
    public function handleDelete(Args $args, IO $io)
    {
        $domain = $args->getArgument('domain');

        $question = new ConfirmationQuestion(
            "<warn>This will delete {$domain}. Are you sure? [yes|no]</warn> ",
            false
        );

        if (!$this->ask($question, $args, $io)) {
            $io->writeLine("Deletion cancelled");
            return;
        }

        try {
            $io->writeLine("Deleting: <b>{$domain}</b>");
            $api = $this->connect($args);
            $api->delete($domain);
            $io->writeLine("<b>{$domain}</b> was deleted");
        } catch (APIResponseException $e) {
            $this->renderApiResponseException($e, $io);
            $io->writeLine("<warn>Did you provide the subaccount the domain is tied too?</warn>");
        }
    }

    /**
     * Updates a Sending-Domain
     * @param Args $args
     * @param IO $io
     */
    public function handleUpdate(Args $args, IO $io)
    {
        try {
            $domain = $args->getArgument('domain');
            $io->writeLine("Updating: <b>{$domain}</b>");
            $api = $this->connect($args);
            $api->update($domain, $this->config['sending-domains'] ?? []);
            $io->writeLine("<b>{$domain}</b> was succesfully updated");
        } catch (APIResponseException $e) {
            $this->renderApiResponseException($e, $io);
        }
    }
}