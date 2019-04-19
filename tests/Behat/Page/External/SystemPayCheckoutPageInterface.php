<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Tests\Waaz\SystemPayPlugin\Behat\Page\External;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Sylius\Behat\Page\PageInterface;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
interface SystemPayCheckoutPageInterface extends PageInterface
{
    /**
     * @throws UnsupportedDriverActionException
     * @throws DriverException
     */
    public function pay();

    /**
     * @throws UnsupportedDriverActionException
     * @throws DriverException
     */
    public function cancel();
}