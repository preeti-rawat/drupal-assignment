<?php

namespace Drupal\user_config\Controller; 

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\user_config\Entity\UserConfigInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\user\UserInterface;

/**
 * Class UserConfigController.
 *
 *  Returns responses for User config routes.
 */
class UserConfigController extends ControllerBase implements ContainerInjectionInterface {

	/**
	 * Provides the profile submission form.
	 *
	 * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
	 *   The route match.
	 * @param \Drupal\user\Entity\UserInterface $user
	 *   The user account.
	 * @param \Drupal\profile\Entity\ProfileTypeInterface $profile_type
	 *   The profile type entity for the profile.
	 *
	 * @return array
	 *   A profile submission form.
	 */
	public function addUserConfig(RouteMatchInterface $route_match, UserInterface $user) {
		$profile = $this->entityTypeManager()->getStorage('user_config')->create([
			'uid' => $user->id()
		]);

		return $this->entityFormBuilder()->getForm($profile, 'add', ['uid' => $user->id()]);
	}

	/**
	 * Provides the profile edit form.
	 *
	 * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
	 *   The route match.
	 * @param \Drupal\user\Entity\UserInterface $user
	 *   The user account.
	 * @param \Drupal\profile\Entity\ProfileInterface $profile
	 *   The profile entity to edit.
	 *
	 * @return array
	 *   The profile edit form.
	 */
	public function editUserConfig(RouteMatchInterface $route_match, UserInterface $user, UserConfigInterface $profile) {
		return $this->entityFormBuilder()->getForm($profile, 'edit');
	}

	/**
	 * The _title_callback for the add profile form route.
	 *
	 * @param \Drupal\profile\Entity\ProfileTypeInterface $profile_type
	 *   The current profile type.
	 *
	 * @return string
	 *   The page title.
	 */
	public function addPageTitle() {
		// @todo: edit profile uses this form too?
		return $this->t('Create @label', ['@label' => 'User config']);
	}

	/**
	 * Provides profile create form.
	 *
	 * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
	 *   The route match.
	 * @param \Drupal\user\Entity\UserInterface $user
	 *   The user account.
	 * @param \Drupal\profile\Entity\ProfileTypeInterface $profile_type
	 *   The profile type entity for the profile.
	 *
	 * @return array
	 *   Returns form array.
	 */
	public function userConfigForm(RouteMatchInterface $route_match, UserInterface $user) {
		$active_profile = $this->entityTypeManager()
            ->getStorage('user_config')
            ->loadByProperties([
                'uid' => $user->id()
			]);
		reset($active_profile);
		$active_profile = current($active_profile);
		// display an add form
		// if there are no entities, or an edit for the current.
		// If there is an active profile, provide edit form.
		if ($active_profile) {
			return $this->editUserConfig($route_match, $user, $active_profile);
		}
		// Else show the add form.
		return $this->addUserConfig($route_match, $user);
	}
}
