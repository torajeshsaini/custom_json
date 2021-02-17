<?php

namespace Drupal\custom_json\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CustomJsonApiController extends ControllerBase
{

  /**
   * @param $siteapikey
   * @param $nid
   * @return JsonResponse
   */
  public function content_json($siteapikey, $nid)
  {

    $this->siteKeyValidation($siteapikey);

    if (!is_numeric($nid)) {
      throw new AccessDeniedHttpException();
    }

    $nodeData = $this->get_node_data($nid);
    return new JsonResponse(['data' => $nodeData->toArray()]);
  }

  /**
   * @param $keyVal
   */
  public function siteKeyValidation($keyVal)
  {
    $site_config = $this->config('system.site');
    $site_api_key = $site_config->get('siteapikey');
    if ($keyVal != $site_api_key) {
      throw new AccessDeniedHttpException();
    }
  }

  /**
   * @param $nid
   * @return $node
   */
  function get_node_data($nid)
  {

    $valid_node_id = \Drupal::entityQuery('node')
      ->condition('type', 'page')
      ->condition('nid', $nid)
      ->execute();

    $access_check = false;
    if ($valid_node_id) {
      $node = Node::load($nid);
      $user = User::load(\Drupal::currentUser()->id());
      $access_check = $node->access('view', $user);
    }

    if (!$access_check) {
      throw new AccessDeniedHttpException();
    }
    return $node;
  }
}
