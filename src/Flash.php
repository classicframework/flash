<?php

namespace classicframework\flash;

class Flash
{
  protected $session = null;
  protected $key = '_flash';

  public function __construct($session = null)
  {
    $this->session = $session;
  }

  public function set_session($session)
  {
    $this->session = $session;
    return $this;
  }

  public function set($type, $message)
  {
    $this->ensure_session();

    $messages = $this->session->get($this->key, array());

    $type = (string) $type;

    if (!isset($messages[$type]) || !is_array($messages[$type])) {
      $messages[$type] = array();
    }

    $messages[$type][] = (string) $message;

    $this->session->set($this->key, $messages);

    return true;
  }

  public function success($message)
  {
    return $this->set('success', $message);
  }

  public function error($message)
  {
    return $this->set('error', $message);
  }

  public function warning($message)
  {
    return $this->set('warning', $message);
  }

  public function info($message)
  {
    return $this->set('info', $message);
  }

  public function get($type = null)
  {
    $this->ensure_session();

    $messages = $this->session->get($this->key, array());

    if ($type === null) {
      $this->session->delete($this->key);
      return $messages;
    }

    $type = (string) $type;

    $result = isset($messages[$type]) ? $messages[$type] : array();

    if (isset($messages[$type])) {
      unset($messages[$type]);
    }

    if (empty($messages)) {
      $this->session->delete($this->key);
    } else {
      $this->session->set($this->key, $messages);
    }

    return $result;
  }

  public function has($type = null)
  {
    $this->ensure_session();

    $messages = $this->session->get($this->key, array());

    if ($type === null) {
      return !empty($messages);
    }

    return isset($messages[(string) $type]) && !empty($messages[(string) $type]);
  }

  public function clear()
  {
    $this->ensure_session();

    $this->session->delete($this->key);

    return true;
  }

  protected function ensure_session()
  {
    if (!is_object($this->session)) {
      throw new \Exception('Flash session service is missing.');
    }

    if (!method_exists($this->session, 'get') || !method_exists($this->session, 'set')) {
      throw new \Exception('Flash session service must have get() and set() methods.');
    }
  }
}