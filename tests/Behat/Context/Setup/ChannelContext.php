<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Tests\Waaz\SystemPayPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Component\Core\Test\Services\DefaultChannelFactory;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class ChannelContext implements Context
{
    /**
     * @var DefaultChannelFactory
     */
    private $defaultChannelFactory;

    /**
     * @param DefaultChannelFactory $defaultChannelFactory
     */
    public function __construct(DefaultChannelFactory $defaultChannelFactory)
    {
        $this->defaultChannelFactory = $defaultChannelFactory;
    }

    /**
     * @Given adding a new channel in :arg1
     */
    public function addingANewChannelIn()
    {
        $this->defaultChannelFactory->create('FR', 'France', 'EUR');
    }
}