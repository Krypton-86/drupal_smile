<?php

namespace Drupal\Tests\smile_test\Functional;

use Drupal\smile_test\Entity\Client;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Tests\examples\Functional\ExamplesBrowserTestBase;
use Drupal\Core\Url;

/**
 * Tests the basic functions of the Content Entity Example module.
 *
 * @ingroup smile_test
 *
 * @group smile_test
 * @group examples
 */
class ContentEntityExampleTest extends ExamplesBrowserTestBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public static $modules = ['smile_test', 'block', 'field_ui'];

  /**
   * Basic tests for Content Entity Example.
   */
  public function testContentEntityExample() {
    $assert = $this->assertSession();

    $web_user = $this->drupalCreateUser([
      'add Client entity',
      'edit Client entity',
      'view Client entity',
      'delete Client entity',
      'administer Client entity',
      'administer smile_test_client display',
      'administer smile_test_client fields',
      'administer smile_test_client form display',
    ]);

    // Anonymous User should not see the link to the listing.
    $assert->pageTextNotContains('Content Entity Example');

    $this->drupalLogin($web_user);

    // Web_user user has the right to view listing.
    $assert->linkExists('Content Entity Example');

    $this->clickLink('Content Entity Example');

    // WebUser can add entity content.
    $assert->linkExists('Add Client');

    $this->clickLink($this->t('Add Client'));

    $assert->fieldValueEquals('name[0][value]', '');
    $assert->fieldValueEquals('name[0][value]', '');
    $assert->fieldValueEquals('name[0][value]', '');
    $assert->fieldValueEquals('name[0][value]', '');

    $user_ref = $web_user->name->value . ' (' . $web_user->id() . ')';
    $assert->fieldValueEquals('user_id[0][target_id]', $user_ref);

    // Post content, save an instance. Go back to list after saving.
    $edit = [
      'name[0][value]' => 'test name',
      'first_name[0][value]' => 'test first name',
      'role' => 'administrator',
    ];
    $this->drupalPostForm(NULL, $edit, 'Save');

    // Entity listed.
    $assert->linkExists('Edit');
    $assert->linkExists('Delete');

    $this->clickLink('test name');

    // Entity shown.
    $assert->pageTextContains('test name');
    $assert->pageTextContains('test first name');
    $assert->pageTextContains('administrator');
    $assert->linkExists('Add Client');
    $assert->linkExists('Edit');
    $assert->linkExists('Delete');

    // Delete the entity.
    $this->clickLink('Delete');

    // Confirm deletion.
    $assert->linkExists('Cancel');
    $this->drupalPostForm(NULL, [], 'Delete');

    // Back to list, must be empty.
    $assert->pageTextNotContains('test name');

    // Settings page.
    $this->drupalGet('admin/structure/smile_test_client_settings');
    $assert->pageTextContains('client Settings');

    // Make sure the field manipulation links are available.
    $assert->linkExists('Settings');
    $assert->linkExists('Manage fields');
    $assert->linkExists('Manage form display');
    $assert->linkExists('Manage display');
  }

  /**
   * Test all paths exposed by the module, by permission.
   */
  public function testPaths() {
    $assert = $this->assertSession();

    // Generate a Client so that we can test the paths against it.
    $client = Client::create([
      'name' => 'somename',
      'first_name' => 'Joe',
      'role' => 'administrator',
    ]);
    $client->save();

    // Gather the test data.
    $data = $this->providerTestPaths($client->id());

    // Run the tests.
    foreach ($data as $datum) {
      // drupalCreateUser() doesn't know what to do with an empty permission
      // array, so we help it out.
      if ($datum[2]) {
        $user = $this->drupalCreateUser([$datum[2]]);
        $this->drupalLogin($user);
      }
      else {
        $user = $this->drupalCreateUser();
        $this->drupalLogin($user);
      }
      $this->drupalGet($datum[1]);
      $assert->statusCodeEquals($datum[0]);
    }
  }

  /**
   * Data provider for testPaths.
   *
   * @param int $client_id
   *   The id of an existing Client entity.
   *
   * @return array
   *   Nested array of testing data. Arranged like this:
   *   - Expected response code.
   *   - Path to request.
   *   - Permission for the user.
   */
  protected function providerTestPaths($client_id) {
    return [
      [
        200,
        '/smile_test_client/' . $client_id,
        'view Client entity',
      ],
      [
        403,
        '/smile_test_client/' . $client_id,
        '',
      ],
      [
        200,
        '/smile_test_client/list',
        'view Client entity',
      ],
      [
        403,
        '/smile_test_client/list',
        '',
      ],
      [
        200,
        '/smile_test_client/add',
        'add Client entity',
      ],
      [
        403,
        '/smile_test_client/add',
        '',
      ],
      [
        200,
        '/smile_test_client/' . $client_id . '/edit',
        'edit Client entity',
      ],
      [
        403,
        '/smile_test_client/' . $client_id . '/edit',
        '',
      ],
      [
        200,
        '/client/' . $client_id . '/delete',
        'delete Client entity',
      ],
      [
        403,
        '/client/' . $client_id . '/delete',
        '',
      ],
      [
        200,
        'admin/structure/smile_test_client_settings',
        'administer Client entity',
      ],
      [
        403,
        'admin/structure/smile_test_client_settings',
        '',
      ],
    ];
  }

  /**
   * Test add new fields to the Client entity.
   */
  public function testAddFields() {
    $web_user = $this->drupalCreateUser([
      'administer Client entity',
      'administer smile_test_client display',
      'administer smile_test_client fields',
      'administer smile_test_client form display',
    ]);

    $this->drupalLogin($web_user);
    $entity_name = 'smile_test_client';
    $add_field_url = 'admin/structure/' . $entity_name . '_settings/fields/add-field';
    $this->drupalGet($add_field_url);
    $field_name = 'test_name';
    $edit = [
      'new_storage_type' => 'list_string',
      'label' => 'test name',
      'field_name' => $field_name,
    ];

    $this->drupalPostForm(NULL, $edit, 'Save and continue');
    $expected_path = $this->buildUrl('admin/structure/' . $entity_name . '_settings/fields/' . $entity_name . '.' . $entity_name . '.field_' . $field_name . '/storage');

    // Fetch url without query parameters.
    $current_path = strtok($this->getUrl(), '?');
    $this->assertEquals($expected_path, $current_path);
  }

  /**
   * Ensure admin and permissioned users can create Clients.
   */
  public function testCreateAdminPermission() {
    $assert = $this->assertSession();
    $add_url = Url::fromRoute('smile_test.client_add');

    // Create a Client entity object so that we can query it for it's annotated
    // properties. We don't need to save it.
    /* @var $client \Drupal\smile_test\Entity\Client */
    $client = Client::create();

    // Create an admin user and log them in. We use the entity annotation for
    // admin_permission in order to validate it. We also have to add the view
    // list permission because the add form redirects to the list on success.
    $this->drupalLogin($this->drupalCreateUser([
      $client->getEntityType()->getAdminPermission(),
      'view Client entity',
    ]));

    // Post a Client.
    $edit = [
      'name[0][value]' => 'Test Admin Name',
      'first_name[0][value]' => 'Admin First Name',
      'role' => 'administrator',
    ];
    $this->drupalPostForm($add_url, $edit, 'Save');
    $assert->statusCodeEquals(200);
    $assert->pageTextContains('Test Admin Name');

    // Create a user with 'add Client entity' permission. We also have to add
    // the view list permission because the add form redirects to the list on
    // success.
    $this->drupalLogin($this->drupalCreateUser([
      'add Client entity',
      'view Client entity',
    ]));

    // Post a Client.
    $edit = [
      'name[0][value]' => 'Mere Mortal Name',
      'first_name[0][value]' => 'Mortal First Name',
      'role' => 'user',
    ];
    $this->drupalPostForm($add_url, $edit, 'Save');
    $assert->statusCodeEquals(200);
    $assert->pageTextContains('Mere Mortal Name');

    // Finally, a user who can only view should not be able to get to the add
    // form.
    $this->drupalLogin($this->drupalCreateUser([
      'view Client entity',
    ]));
    $this->drupalGet($add_url);
    $assert->statusCodeEquals(403);
  }

}
