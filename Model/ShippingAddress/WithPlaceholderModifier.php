<?php

namespace Elgentos\HyvaCheckoutPlaceholdersModifier\Model\ShippingAddress;

use Hyva\Checkout\Model\Form\EntityField\EavAttributeField;
use Hyva\Checkout\Model\Form\EntityFormInterface;
use Hyva\Checkout\Model\Form\EntityFormModifierInterface;

class WithPlaceholderModifier implements EntityFormModifierInterface
{
    private const SHIPPING_ADDRESS_FIELDS = [
        'company',
        'postcode',
        'street',
        'city',
        'country_id',
        'telephone',
        'firstname',
        'lastname',
        'region',
        'prefix',
        'email'
    ];

    public function apply(EntityFormInterface $form): EntityFormInterface
    {
        $form->registerModificationListener(
            'applyPlaceholders',
            'form:build',
            [$this, 'applyPlaceholders']
        );

        return $form;
    }

    public function applyPlaceholders(EntityFormInterface $form): void
    {
        foreach (self::SHIPPING_ADDRESS_FIELDS as $fieldName => $placeholder) {
            /** @var EavAttributeField|null $field */
            $field = $form->getField($fieldName);

            if ($field?->hasRelatives()) {
                /** @var EavAttributeField $relative */
                foreach ($field->getRelatives() as $relative) {
                    $relative->setAttribute('placeholder', $relative->getLabel());
                }
            }

            $field?->setAttribute('placeholder', __($field->getLabel()));
        }
    }
}
