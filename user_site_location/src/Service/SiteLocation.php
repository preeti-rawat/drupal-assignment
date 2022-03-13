<?php
namespace Drupal\user_site_location\Service;

use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Component\Datetime\TimeInterface;


/**
 * {@inheritdoc}
 * Share site related data
*/
class SiteLocation {
    /**
     * @var Drupal\Core\Database\Connection
     */
    protected $connection;

    /**
     * current_user service
     * @var Drupal\Core\Session\AccountInterface
     */
    private $currentUser;

    /**
     * @var Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * @var Drupal\user_config\Entity\UserConfigInterface
     */
    protected $userConfigStorage;

    /**
     * @var Drupal\Component\Datetime\TimeInterface
     */
    protected $time;

    
    protected $userConfigData;

    /**
     * {@inheritdoc}
     * @param Drupal\Core\Database\Connection
     * @param Drupal\Core\Session\AccountInterface
     * @param Drupal\Core\Entity\EntityTypeManagerInterface
     * @param \Drupal\Component\Datetime\TimeInterface
     */
    public function __construct(
        Connection $connection,
        AccountInterface $current_user,
        EntityTypeManagerInterface $entity_type_manager,
        TimeInterface $time
    ) {
        $this->connection = $connection;
        $this->currentUser = $current_user;
        $this->entityTypeManager = $entity_type_manager;
        $this->userConfigStorage = $this->entityTypeManager->getStorage("user_config");
        $this->time = $time;
    }

    /*
     * Get current time based on users location(timezone)
     */
    public function getUserCurrentTime($user_id) { 
        if(empty($user_config_data = $this->userConfigStorage->loadByProperties(['uid' => $user_id]))){
            return null;
        }
        $user_config_data = reset($user_config_data);
        $timezone = $user_config_data->timezone;
        $current_time = $this->time->getCurrentTime();
        if($timezone){
            $current_datetime = DrupalDateTime::createFromTimestamp($current_time, $timezone);
            return $current_datetime->format("jS M Y - h:i A");
        }
        $current_datetime = DrupalDateTime::createFromTimestamp($current_time);
        return $current_datetime->format("jS M Y - h:i A");
    }

    /**
     * Get users location
     */
    public function getUserLocation($user_id) { 
        if(empty($user_config_data = $this->userConfigStorage->loadByProperties(['uid' => $user_id]))){
            return null;
        }
        $user_config_data = reset($user_config_data);
        $location = '';
        if(isset($user_config_data->city)){
            $location .= $user_config_data->city;
        } 
        if(isset($user_config_data->country)){
            $location .= ", " .$user_config_data->country;
        }
        return $location;
    }

}