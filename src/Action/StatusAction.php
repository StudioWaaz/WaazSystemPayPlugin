<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Waaz\SystemPayPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class StatusAction implements ActionInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritDoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $requestCurrent = $this->requestStack->getCurrentRequest();

        $transactionReference = isset($model['transactionReference']) ? $model['transactionReference'] : null;

        $status = isset($model['status']) ? $model['status'] : null;

        if ((null === $transactionReference) && !$requestCurrent->isMethod('POST')) {

            $request->markNew();

            return;
        }

        if ($status === PaymentInterface::STATE_CANCELLED) {

            $request->markCanceled();

            return;
        }
        if ($status === PaymentInterface::STATE_COMPLETED) {

            $request->markCaptured();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
