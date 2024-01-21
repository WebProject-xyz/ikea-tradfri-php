<?php

declare(strict_types=1);

/**
 * Copyright (c) 2024 Benjamin Fahl
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/WebProject-xyz/ikea-tradfri-php
 */

namespace IKEA\Tradfri\Command;

use IKEA\Tradfri\Command\Coap\Keys;
use IKEA\Tradfri\Exception\RuntimeException;
use IKEA\Tradfri\Helper\CommandRunner;
use IKEA\Tradfri\Helper\CommandRunnerInterface;

final readonly class GatewayHelperCommands
{
    /**
     * @throws \InvalidArgumentException
     * @throws RuntimeException
     */
    public function __construct(
        private \IKEA\Tradfri\Dto\CoapGatewayAuthConfigDto $authConfig,
        private CommandRunnerInterface $runner = new CommandRunner(),
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public function getSharedKeyFromGateway(): string
    {
        // get command to switch light
        $onCommand = $this->getPreSharedKeyCommand();

        // run command
        $result = $this->parseResult(
            $this->runner->execWithTimeout($onCommand, 2),
        );

        // verify result
        if (\is_string($result)) {
            return $result;
        }

        throw new RuntimeException('Could not get api key');
    }

    public function getPreSharedKeyCommand(): string
    {
        return \sprintf(
            Post::COAP_COMMAND,
            'Client_identity',
            $this->authConfig->getGatewaySecret(),
        )
        . ' -e \'{"' . Keys::ATTR_CLIENT_IDENTITY_PROPOSED . '":"' . $this->authConfig->getUsername() . '"}\' '
        . $this->_getRequestTypeCoapsUrl(
            Request::RootGateway->withTargetId(Keys::ATTR_AUTH),
        );
    }

    public function parseResult(array $result): false|string
    {
        $parsed = false;
        foreach ($result as $part) {
            if (!empty($part)
                &&   !\str_contains((string) $part, 'decrypt')
                &&   !\str_contains((string) $part, 'v:1')) {
                $parsed = (string) $part;

                break;
            }
        }

        return $parsed;
    }

    private function _getRequestTypeCoapsUrl(int|string $requestType): string
    {
        return \sprintf('"%s/%s"', $this->getUrl(), $requestType);
    }

    private function getUrl(): string
    {
        return $this->authConfig->getGatewayUrl();
    }
}
