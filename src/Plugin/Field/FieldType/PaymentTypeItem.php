<?php

/**
 * @file
 * Contains \Drupal\payment\Plugin\Field\FieldType\PaymentTypeItem.
 */

namespace Drupal\payment\Plugin\Field\FieldType;

/**
 * Provides a plugin collection for payment type plugins.
 *
 * @FieldType(
 *   id = "plugin:payment_type"
 * )
 */
class PaymentTypeItem extends PaymentAwarePluginCollectionItem {}
