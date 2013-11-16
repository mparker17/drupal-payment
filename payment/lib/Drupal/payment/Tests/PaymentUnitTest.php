<?php

/**
 * @file
 * Contains
 * \Drupal\payment\Test\PaymentUnitTest.
 */

namespace Drupal\payment_reference\Test;

use Drupal\payment\Payment;
use Drupal\payment_reference\PaymentReference;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Tests \Drupal\payment_reference\PaymentReference.
 */
class PaymentReferenceUnitTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'description' => '',
      'group' => 'Payment',
      'name' => '\Drupal\payment\Payment unit test',
    );
  }

  /**
   * Tests lineItemManager().
   */
  public function testLineItemManager() {
    $container = new Container();
    $line_item_manager = $this->getMockBuilder('\Drupal\payment\Plugin\payment\line_item\Manager')
      ->disableOriginalConstructor()
      ->getMock();
    $container->set('plugin.manager.payment.line_item', $line_item_manager);
    \Drupal::setContainer($container);
    $this->assertSame($line_item_manager, Payment::lineItemManager());
  }

  /**
   * Tests methodManager().
   */
  public function testMethodManager() {
    $container = new Container();
    $method_manager = $this->getMockBuilder('\Drupal\payment\Plugin\payment\method\Manager')
      ->disableOriginalConstructor()
      ->getMock();
    $container->set('plugin.manager.payment.method', $method_manager);
    \Drupal::setContainer($container);
    $this->assertSame($method_manager, Payment::methodManager());
  }

  /**
   * Tests statusManager().
   */
  public function testStatusManager() {
    $container = new Container();
    $status_manager = $this->getMockBuilder('\Drupal\payment\Plugin\payment\status\Manager')
      ->disableOriginalConstructor()
      ->getMock();
    $container->set('plugin.manager.payment.status', $status_manager);
    \Drupal::setContainer($container);
    $this->assertSame($status_manager, Payment::statusManager());
  }

  /**
   * Tests typeManager().
   */
  public function testTypeManager() {
    $container = new Container();
    $type_manager = $this->getMockBuilder('\Drupal\payment\Plugin\payment\type\Manager')
      ->disableOriginalConstructor()
      ->getMock();
    $container->set('plugin.manager.payment.type', $type_manager);
    \Drupal::setContainer($container);
    $this->assertSame($type_manager, Payment::typeManager());
  }

}