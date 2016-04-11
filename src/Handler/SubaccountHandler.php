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
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;
use Webmozart\Console\UI\Component\Table;

/**
 * Handles Calls to the subaccount endpoint
 */
final class SubaccountHandler extends AbstractSparkpostUnwrappedHandler
{
    const API_ENDPOINT = 'subaccounts';

    public function handleList(Args $args, IO $io)
    {
        try {
            $api = $this->connect($args);

            $subaccount = $args->getArgument('subaccountId');
            $use_subaccount = $subaccount !== null;

            $response = $api->get($subaccount);
            if ($use_subaccount) {
                // wrap results, when only one subaccount was requested to be able to use array_map
                $response = ['results' => $response];
            }

            $table = new Table();
            $table->setHeaderRow(['Name', 'Id', 'Status', 'Compliance Status']);
            $results = array_map(function ($subaccount) {
                return [
                    $subaccount['name'],
                    $subaccount['id'],
                    $subaccount['status'],
                    $subaccount['compliance_status'],
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
     * Adds a subaccount
     *
     * @param Args $args
     * @param IO $io
     * @return int
     */
    public function handleAdd(Args $args, IO $io)
    {
        $name = $args->getArgument('name');

        $parameters = [
            'name' => $name,
            'key_label' => "{$name} Api-Key",
        ];

        try {
            $io->writeLine("Creating: <b>{$name}</b>");
            $api = $this->connect($args);
            $parameters = array_merge($parameters, $this->config['subaccounts'] ?? []);
            $response = $api->create($parameters);
            $io->writeLine("The subbaccount with the name <b>{$name}</b> was succesfully created with id <b>{$response['results']['subaccount_id']}</b>");
            $io->writeLine("<warn>The API-key is: {$response['results']['key']} . Copy it, because you will never see it again</warn>");
            return 0;
        } catch (APIResponseException $e) {
            $this->renderApiResponseException($e, $io);
            return 1;
        }
    }

    /**
     * Updates a Sending-Domain
     * @param Args $args
     * @param IO $io
     */
    public function handleActivate(Args $args, IO $io)
    {
        try {
            $subaccount = $args->getArgument('subaccount');
            $io->writeLine("Activate subaccount: <b>{$subaccount}</b>");
            $api = $this->connect($args);
            $api->update($subaccount, ['status' => 'active']);
            $io->writeLine("<b>{$subaccount}</b> was succesfully activated");
        } catch (APIResponseException $e) {
            $this->renderApiResponseException($e, $io);
        }
    }

    /**
     * Updates a Sending-Domain
     * @param Args $args
     * @param IO $io
     */
    public function handleSuspend(Args $args, IO $io)
    {
        try {
            $subaccount = $args->getArgument('subaccount');
            $io->writeLine("Suspending subaccount: <b>{$subaccount}</b>");
            $api = $this->connect($args);
            $api->update($subaccount, ['status' => 'suspended']);
            $io->writeLine("<b>{$subaccount}</b> was succesfully suspended");
        } catch (APIResponseException $e) {
            $this->renderApiResponseException($e, $io);
        }
    }

    /**
     * Updates a Sending-Domain
     * @param Args $args
     * @param IO $io
     */
    public function handleTerminate(Args $args, IO $io)
    {
        try {
            $subaccount = $args->getArgument('subaccount');
            $io->writeLine("Terminate subaccount: <b>{$subaccount}</b>");
            $api = $this->connect($args);
            $api->update($subaccount, ['status' => 'terminated']);
            $io->writeLine("<b>{$subaccount}</b> was succesfully terminated");
        } catch (APIResponseException $e) {
            $this->renderApiResponseException($e, $io);
        }
    }
}