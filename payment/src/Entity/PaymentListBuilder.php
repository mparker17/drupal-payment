<?php

/**
 * @file
 * Contains \Drupal\payment\Entity\PaymentListBuilder.
 */

namespace Drupal\payment\Entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Lists payment entities.
 */
class PaymentListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $row['updated'] = t('Last updated');
    $row['status'] = t('Status');
    $row['amount'] = t('Amount');
    $row['payment_method'] = t('Payment method');
    $row['owner'] = t('Payer');
    $row['operations'] = t('Operations');

    return $row;
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $payment) {
    /** @var \Drupal\payment\Entity\PaymentInterface $payment */
    $row['data']['updated'] = format_date($payment->getChangedTime());

    $status_definition = $payment->getStatus()->getPluginDefinition();
    $row['data']['status'] = $status_definition['label'];

    /** @var \Drupal\currency\Entity\CurrencyInterface $currency */
    $currency = entity_load('currency', $payment->getCurrencyCode());
    if (!$currency) {
      $currency = entity_load('currency', 'XXX');
    }
    $row['data']['amount'] = $currency->formatAmount($payment->getAmount());

    $row['data']['payment_method'] = $payment->getPaymentMethod() ? $payment->getPaymentMethod()->getPluginLabel() : t('Unavailable');

    if ($payment->getOwner()) {
      $owner = $payment->getOwner();
      $url = $owner->urlInfo();
      $owner_data = l($owner->label(), $url->getInternalPath(), $url->getOptions());
    }
    else {
      $owner_data = t('Unavailable');
    }
    $row['data']['owner'] = $owner_data;

    $operations = $this->buildOperations($payment);
    $row['data']['operations']['data'] = $operations;

    return $row;
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);

    if ($entity->access('view')) {
      $operations['view'] = array(
        'title' => $this->t('View'),
      ) + $entity->urlInfo()->toArray();
    }

    return $operations;
  }
}