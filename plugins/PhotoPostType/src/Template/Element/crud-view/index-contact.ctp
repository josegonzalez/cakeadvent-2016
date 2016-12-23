<?= implode("<br>", array_filter([
    $context->get('shipping_name'),
    $context->get('shipping_address_line_1'),
    sprintf(
        '%s, %s %s',
        $context->get('shipping_address_city'),
        $context->get('shipping_address_state'),
        $context->get('shipping_address_zip')
    ),
    $context->get('shipping_address_country'),
    $context->get('email'),
]));
