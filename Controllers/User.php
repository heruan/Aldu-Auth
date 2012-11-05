<?php
/**
 * Aldu\Auth\Controllers\User
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
 * @package       Aldu\Auth\Controllers
 * @uses          Aldu\Core
 * @since         AlduPHP(tm) v1.0.0
 * @license       Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 */

namespace Aldu\Auth\Controllers;
use Aldu\Core;

class User extends Core\Controller
{
  public function login()
  {
    if ($this->request->is('post')) {
      $class = get_class($this->model);
      $data = $this->request->data($class);
      if ($attributes = array_shift($data)) {
        $idKey = $class::cfg('datasource.authentication.id') ? : 'name';
        $pwKey = $class::cfg('datasource.authentication.password') ? : 'password';
        $id = $attributes[$idKey];
        $password = $attributes[$pwKey];
        if ($user = $this->model->authenticate($id, $password)) {
          $password = $this->request->cipher->encrypt($password);
          $this->request->updateAro($class, $id, $password);
          if ($redirect = $this->request->data('redirect')) {
            $this->router->redirect($redirect);
          }
        }
        else {
          $this->request->session->delete("Aldu\Core\Net\HTTP\Request::updateAro");
          $this->request->aro = null;
          $this->response->message($this->view->locale->t("Authentication failed."), LOG_ERR);
        }
      }
    }
    return $this->view->login();
  }

  public function logout()
  {
    if ($this->request->aro) {
      $key = 'Aldu\Core\Net\HTTP\Request::updateAro';
      $this->request->session->delete($key);
      $this->request->aro = null;
      $this->view->logout();
    }
    return $this->router->back();
  }

  public function profile($id)
  {
    return $this->edit($id);
  }
}
