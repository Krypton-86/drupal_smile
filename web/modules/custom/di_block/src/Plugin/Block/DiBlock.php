<?php

namespace Drupal\di_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\di_service\CustomService;
use Psr\Container\ContainerInterface;

/**
 * @Block(
 *   id = "di_block",
 *   admin_label = @Translation("DI Block")
 * )
 */
class DiBlock extends BlockBase implements ContainerFactoryPluginInterface {
  protected $repository;
  protected $currentUser;

  /**
   * Construct a new controller.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CustomService $repository, AccountInterface $currentUser) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->repository = $repository;
    $this->currentUser = $currentUser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('di_service.custom_service'),
      $container->get('current_user'),
    );
  }

  /**
   *
   */
  public function build() {
    $currentUser = $this->currentUser->getDisplayName();
    $node = $this->repository->getNode();
    $user_data = $this->repository->getUserData();
    $all_active = $this->repository->getAmountOfAllActive();
    $number_of_user = $this->repository->getCurrentUserPosition();
    $content = $this->t('Content for you');

    $list['info']['current_user'] = $currentUser;
    $list['info']['user_info'] = $user_data;
    $list['info']['all_active'] = $all_active;
    $list['info']['number_of_user'] = $number_of_user;
    $list['info']['content'] = $content;
    $list['info']['node'] = $node;
    $dop = $node['#cache']['tags'][0];
    return [
      '#theme'    => 'di_block_theme',
      '#list'     => $list,
      '#attached' => [
        'library' => ['di_block/style'],
      ],
      '#cache' => [
        'tags' => ['node_list'],
        'max-age' => -1,
        'contexts' => ['user.roles:authenticated'],
      ],
    ];

  }

  /**
  * Disable block cache.
  *
  * @return integer
  *   Cache age life time.
  */
  // Public function getCacheMaxAge(){
  //    return 0;
  //  }//end getCacheMaxAge()
}
