<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Waaz\SystemPayPlugin\Form\Type;

use Waaz\SystemPayPlugin\Legacy\Mercanet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class SystemPayGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('environment', ChoiceType::class, [
                'choices' => [
                    'waaz.system_pay.production' => 'PRODUCTION',
                    'waaz.system_pay.test' => 'TEST',
                ],
                'label' => 'waaz.system_pay.environment',
            ])
            ->add('secret_key', TextType::class, [
                'label' => 'waaz.system_pay.secure_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'waaz.system_pay.secure_key.not_blank',
                        'groups' => ['sylius']
                    ])
                ],
            ])
            ->add('merchant_id', TextType::class, [
                'label' => 'waaz.system_pay.merchant_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'waaz.system_pay.merchant_id.not_blank',
                        'groups' => ['sylius']
                    ])
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();
                $data['payum.http_client'] = '@waaz.system_pay.bridge.system_pay_bridge';
                $event->setData($data);
            })
        ;
    }
}
