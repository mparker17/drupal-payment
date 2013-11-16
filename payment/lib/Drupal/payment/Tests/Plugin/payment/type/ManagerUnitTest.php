<?php

/**
 * @file
 * Contains class \Drupal\payment\Tests\Plugin\payment\type\ManagerUnitTest.
 */

namespace Drupal\payment\Tests\Plugin\payment\type;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\payment\Payment;

use Drupal\Tests\UnitTestCase;

/**
 * Tests \Drupal\payment\Plugin\payment\type\Manager.
 */
class ManagerUnitTest extends UnitTestCase {

  /**
   * The plugin factory used for testing.
   *
   * @var \Drupal\Component\Plugin\Factory\FactoryInterface
   */
  protected $factory;

  /**
   * The plugin manager under test.
   *
   * @var \Drupal\payment\Plugin\payment\type\Manager|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $manager;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'description' => '',
      'name' => '\Drupal\payment\Plugin\payment\type\Manager unit test',
      'group' => 'Payment',
    );
  }

  /**
   * {@inheritdoc
   */
  public function setUp() {
    $this->factory = $this->getMock('\Drupal\Component\Plugin\Factory\FactoryInterface');

    $this->manager = $this->getMockBuilder('\Drupal\payment\Plugin\payment\type\Manager')
      ->disableOriginalConstructor()
      ->setMethods(NULL)
      ->getMock();
    $property = new \ReflectionProperty($this->manager, 'factory');
    $property->setAccessible(TRUE);
    $property->setValue($this->manager, $this->factory);
  }

  /**
   * Tests createInstance().
   */
  public function testCreateInstance() {
    $existing_plugin_id = 'payment_unavailable';
    $non_existing_plugin_id = $this->randomName();
    $this->factory->expects($this->at(0))
      ->method('createInstance')
      ->with($non_existing_plugin_id)
      ->will($this->throwException(new PluginException()));
    $this->factory->expects($this->at(1))
      ->method('createInstance')
      ->with($existing_plugin_id);
    $this->factory->expects($this->at(2))
      ->method('createInstance')
      ->with($existing_plugin_id);
    $this->manager->createInstance($non_existing_plugin_id);
    $this->manager->createInstance($existing_plugin_id);
  }
}