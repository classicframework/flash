<?php

namespace classicframework\flash;

use classicframework\core\App;
use classicframework\core\BridgeInterface;

class Bridge implements BridgeInterface
{
  public static function register(App $app)
  {
    $session = $app->get_service('session');

    $flash = new Flash($session);

    $app->set_service('flash', $flash);
  }
}