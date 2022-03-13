<?php
namespace Drupal\user_site_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\user_site_location\Service\SiteLocation;

/**
 *
 * @Block(
 *   id = "site_location",
 *   admin_label = @Translation("User Site Location"),
 *   category = @Translation("Block to show the site on the basis of the user location")
 * )
 */
class SiteLocationBlock extends BlockBase implements ContainerFactoryPluginInterface{
    /**
     * current_user service
     * @var Drupal\Core\Session\AccountInterface
     */
    private $currentUser;

    /**
     * @var Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $siteData;

    /**
     * @var Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * {@inheritdoc}
     * @param Drupal\Core\Session\AccountInterface
     * @param Drupal\Core\Entity\EntityTypeManagerInterface
     * @param Drupal\user_site_location\Service\SiteLocation
     */
    public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        AccountInterface $current_user,
        EntityTypeManagerInterface $entity_type_manager,
        SiteLocation  $site_data
    ) {
        
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->currentUser = $current_user;
        $this->entityTypeManager = $entity_type_manager;
        $this->siteData = $site_data;
    }

    /**
	 * @param Symfony\Component\DependencyInjection\ContainerInterface;
	 * @param array configuration
	 * @param string plugin_id
	 * @param mixed plugin_definition
	 * @param Drupal\Core\Session\AccountInterface
     * @param Drupal\Core\Entity\EntityTypeManagerInterface
     * @param Drupal\user_site_location\Service\SiteLocation
	 */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('current_user'),
            $container->get('entity_type.manager'),
            $container->get('user_site_location.data_handler')
        );
    }

    /**
     * {@inheritdoc}
    */
    public function build(){
        if(empty($user_config = $this->entityTypeManager->getStorage("user_config")->loadByProperties(['uid' => $this->currentUser->id()]))){
            return null;
        }
        $current_time = $this->siteData->getUserCurrentTime($this->currentUser->id());
        $location = $this->siteData->getUserLocation($this->currentUser->id());
        
        $user_config = reset($user_config);
        $response = [
            '#theme' => 'user_site_location',
            '#current_time' => !empty($current_time) ? $current_time : $this->t('N/A'),
            '#location' => !empty($location) ? $location : $this->t('N/A'),
            '#cache' => [
                'context' => ['user', 'url'],
                'tags' => $user_config->getCacheTags(),
            ]
        ];
        return $response;
    }

}

