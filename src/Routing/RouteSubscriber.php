<?php

namespace Drupal\custom_json\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events
 */
class RouteSubscriber extends RouteSubscriberBase
{
  /**
   * @inheritDoc
   */
  protected function alterRoutes(RouteCollection $collection)
  {
    if ($route = $collection->get('system.site_information_settings'))
      $route->setDefault('_form', '\Drupal\custom_json\Form\ExtendedSiteInformationForm');
  }
}
