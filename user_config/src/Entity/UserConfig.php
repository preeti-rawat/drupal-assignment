<?php

namespace Drupal\user_config\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the User config entity.
 *
 * @ConfigEntityType(
 *   id = "user_config",
 *   label = @Translation("User config"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\user_config\UserConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\user_config\Form\UserConfigForm",
 *       "edit" = "Drupal\user_config\Form\UserConfigForm",
 *       "delete" = "Drupal\user_config\Form\UserConfigDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\user_config\UserConfigHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "user_config",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/user_config/{user_config}",
 *     "add-form" = "/admin/structure/user_config/add",
 *     "edit-form" = "/admin/structure/user_config/{user_config}/edit",
 *     "delete-form" = "/admin/structure/user_config/{user_config}/delete",
 *     "collection" = "/admin/structure/user_config"
 *   }
 * )
 */
class UserConfig extends ConfigEntityBase implements UserConfigInterface {

	/**
	 * The User config ID.
	 *
	 * @var string
	 */
	protected $id;

	// /**
	//  * The User config label.
	//  *
	//  * @var string
	//  */
	// protected $label;

	/**
	 * The user config UUID.
	 *
	 * @var string
	 */
	public $uuid;

	/**
	 * The User id.
	 *
	 * @var int
	 */
	public $uid;
	
	/**
     * The User country.
     * @var string
     */
    public $country;

    /**
     * The User city.
     * @var string
     */
    public $city;

    /**
     * The User timezone.
     * @var select
     */
    public $timezone;

}
