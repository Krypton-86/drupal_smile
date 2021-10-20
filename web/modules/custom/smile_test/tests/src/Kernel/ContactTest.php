<?php

namespace Drupal\Tests\smile_test\Kernel;

use Drupal\smile_test\Entity\Client;
use Drupal\KernelTests\KernelTestBase;

/**
 * Test basic CRUD operations for our Client entity type.
 *
 * @group smile_test
 * @group examples
 *
 * @ingroup smile_test
 */
class ClientTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['smile_test', 'options', 'user'];

  /**
   * Basic CRUD operations on a Client entity.
   */
  public function testEntity() {
    $this->installEntitySchema('smile_test_client');
    $entity = Client::create([
      'name' => 'Name',
      'first_name' => 'Firstname',
      'user_id' => 0,
      'role' => 'user',
    ]);
    $this->assertNotNull($entity);
    $this->assertEquals(SAVED_NEW, $entity->save());
    $this->assertEquals(SAVED_UPDATED, $entity->set('role', 'administrator')->save());
    $entity_id = $entity->id();
    $this->assertNotEmpty($entity_id);
    $entity->delete();
    $this->assertNull(client::load($entity_id));
  }

}
