<?php
/*
 * This file is part of the sparkpost-api-cli package.
 *
 * (c) Roman Sachse <r.sachse@ipark-media.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ipm\SparkpostApi\Config;

use Ipm\SparkpostApi\Handler\SendingDomainHandler;
use Ipm\SparkpostApi\Handler\SubaccountHandler;
use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;
use Webmozart\Console\Config\DefaultApplicationConfig;

class SparkpostApplicationConfig extends DefaultApplicationConfig
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('sparkpost-api-cli')
            ->setVersion('0.0.1')

            ->beginCommand('sending-domains')
                ->setDescription('Handle sending domains')
                ->setHandler(function () {
                    return new SendingDomainHandler();
                })
                ->addOption('key', null, Option::REQUIRED_VALUE, 'the Api-Key for the request')
                ->addOption('config', null, Option::REQUIRED_VALUE, 'where to read the config from')
                ->beginSubCommand('list')
                    ->setHandlerMethod('handleList')
                    ->markDefault()
                ->end()
                ->beginSubCommand('add')
                    ->setHandlerMethod('handleAdd')
                    ->addArgument('domain', Argument::REQUIRED, 'the sending domain to create')
                    ->addArgument('subaccount', Argument::OPTIONAL, 'the subaccount id the sending domain is created for')
                ->end()
                ->beginSubCommand('update')
                    ->setHandlerMethod('handleUpdate')
                    ->addArgument('domain', Argument::REQUIRED, 'the sending domain to update')
                    ->addArgument('subaccount', Argument::OPTIONAL, 'the subaccount id the sending domain is updated for')
                ->end()
                ->beginSubCommand('delete')
                    ->setHandlerMethod('handleDelete')
                    ->addArgument('domain', Argument::REQUIRED, 'the sending domain to delete')
                    ->addArgument('subaccount', Argument::OPTIONAL, 'the subaccount id the sending domain is deleted for')
                ->end()
                ->beginSubCommand('verify')
                    ->setHandlerMethod('handleVerify')
                    ->addArgument('domain', Argument::REQUIRED, 'the sending domain to verify')
                    ->addArgument('subaccount', Argument::OPTIONAL, 'the subaccount id the sending domain is verified for')
                ->end()
            ->end()
            ->beginCommand('subaccounts')
                ->setDescription('Handle subaccounts')
                ->setHandler(function () {
                    return new SubaccountHandler();
                })
                ->addOption('key', null, Option::REQUIRED_VALUE, 'the Api-Key for the request')
                ->addOption('config', null, Option::REQUIRED_VALUE, 'where to read the config from')
                ->beginSubCommand('list')
                    ->setHandlerMethod('handleList')
                    ->addArgument('subaccountId', Argument::OPTIONAL, 'the subaccount id the info is requested for')
                    ->markDefault()
                ->end()
                ->beginSubCommand('add')
                    ->setHandlerMethod('handleAdd')
                    ->addArgument('name', Argument::REQUIRED, 'name of the subaccount to create')
                ->end()
                ->beginSubCommand('update')
                    ->setHandlerMethod('handleUpdate')
                    ->addArgument('subaccount', Argument::REQUIRED, 'the id of the subaccount to update')
                ->end()
                ->beginSubCommand('activate')
                    ->setHandlerMethod('handleActivate')
                    ->addArgument('subaccount', Argument::REQUIRED, 'the id of the subaccount to activate')
                ->end()
                ->beginSubCommand('suspend')
                    ->setHandlerMethod('handleSuspend')
                    ->addArgument('subaccount', Argument::REQUIRED, 'the id of the subaccount to suspend')
                ->end()
                ->beginSubCommand('terminate')
                    ->setHandlerMethod('handleTerminate')
                    ->addArgument('subaccount', Argument::REQUIRED, 'the id of the subaccount to terminate')
                ->end()
            ->end()
        ;
    }
}