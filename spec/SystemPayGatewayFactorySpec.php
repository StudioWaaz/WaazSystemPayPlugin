<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace spec\Waaz\SystemPayPlugin;

use Waaz\SystemPayPlugin\SystemPayGatewayFactory;
use PhpSpec\ObjectBehavior;
use Payum\Core\GatewayFactory;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class SystemPayGatewayFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SystemPayGatewayFactory::class);
        $this->shouldHaveType(GatewayFactory::class);
    }

    function it_populateConfig_run()
    {
        $this->createConfig([]);
    }
}
