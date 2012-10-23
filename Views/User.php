<?php
/**
 * Aldu\Auth\Views\User
 *
 * AlduPHP(tm) : The Aldu Network PHP Framework (http://aldu.net/php)
 * Copyright 2010-2012, Aldu Network (http://aldu.net)
 *
 * Licensed under Creative Commons Attribution-ShareAlike 3.0 Unported license (CC BY-SA 3.0)
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Giovanni Lovato <heruan@aldu.net>
 * @copyright     Copyright 2010-2012, Aldu Network (http://aldu.net)
 * @link          http://aldu.net/php AlduPHP(tm) Project
 * @package       Aldu\Auth\Views
 * @uses          Aldu\Core
 * @since         AlduPHP(tm) v1.0.0
 * @license       Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 */

namespace Aldu\Auth\Views;

use Aldu\Auth;
use Aldu\Core;
use Aldu\Core\View\Helper\HTML;

class User extends Core\View
{
  protected static $configuration = array(
    __CLASS__ => array(
      'panel' => array(
        'login' => 'form'
      ), 'table' => array(
        'columns' => array(
          'name', 'mail', 'firstname', 'lastname'
        )
      )
    )
  );

  public function login()
  {
    $class = get_class($this->model);
    if (is_a($this->request->aro, $class)) {
      $a = new HTML('a', $this->locale->t("Logout"), array(
        'href' => $this->model->url('logout')
      ));
      $this->response
          ->message($this->locale->t("Logged in as %s %s. (%s)", $this->model->name(), $this->request->aro->name, $a));
    }
    $form = new HTML\Form($this->model, __FUNCTION__, array(
      'redirect' => $this->request->referer ? : $this->router->basePath
    ));
    $id = $class::cfg('datasource.authentication.id') ? : 'name';
    switch ($id) {
    case 'mail':
      $form
          ->email($id,
              array(
                'title' => $this->locale->t("User's e-mail"), 'description' => $this->locale->t("User's e-mail for authentication."), 'required' => true,
                'readonly' => false
              ));
      break;
    default:
      $form
          ->text($id, array(
            'title' => $this->locale->t("User's name"), 'required' => true, 'readonly' => false
          ));
    }
    $password = $class::cfg('datasource.authentication.password') ? : 'password';
    $form
        ->password($password, array(
          'title' => $this->locale->t("User's password"), 'required' => true, 'readonly' => false
        ));
    $form->submit(__FUNCTION__, array(
          'title' => $this->locale->t("Login")
        ));
    switch ($this->render) {
    case 'return':
      return $form;
    case 'embed':
      return $this->response->body($form);
    case 'page':
    default:
      $page = new HTML\Page();
      $page->theme();
      $page->title($this->locale->t("Login"));
      $page->compose($form);
      return $this->response->body($page);
    }
  }

  public function logout()
  {
    $a = new HTML('a', $this->locale->t("Login back"), array(
      'href' => $this->model->url('login')
    ));
    $this->response->message($this->locale->t("Successfully logged out. (%s)", $a));
  }

  public static function stats($block, $element)
  {
    $locale = Core\Locale::instance();
    $request = Core\Net\HTTP\Request::instance();
    $response = Core\Net\HTTP\Response::instance();
    $cache = Core\Cache::instance();
    $ul = new HTML('ul');
    $ul->li("Cache: " . var_export((bool)$cache->enabled, true));
    $ul->li("Cache stored: " . $cache->stored());
    $ul->li("Cache fetched: " . $cache->fetched());
    $ul->li("Time: " . $request->time() . "s");
    return $ul;
  }

  public static function panel($block, $element)
  {
    $locale = Core\Locale::instance();
    $request = Core\Net\HTTP\Request::instance();
    if (is_a($request->aro, 'Aldu\Auth\Models\User')) {
      $user = $request->aro;
      $ul = new HTML('ul.menu.aldu-auth-user-panel');
      $ul->li()->a($locale->t("Hello %s", $user->firstname))->href = $user->url('profile');
      $ul->li()->a($locale->t("Logout"))->href = $user->url('logout');
      return $ul;
    }
    else {
      $self = new self(new Auth\Models\User());
      switch (static::cfg('panel.login')) {
      case 'link':
        $list = new HTML('ul.menu.aldu-auth-user-panel');
        $anchor = $list->li()->a($self->locale->t('Login'));
        $anchor->href = $self->model->url('login');
        return $list;
      case 'form':
      default:
        $self->render = 'return';
        return $self->login();
      }
    }
    return null;
  }
}