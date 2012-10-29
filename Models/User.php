<?php
/**
 * Aldu\Auth\Models\User
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
 * @package       Aldu\Auth\Models
 * @uses          Aldu\Core
 * @since         AlduPHP(tm) v1.0.0
 * @license       Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 */

namespace Aldu\Auth\Models;
use Aldu\Core;
use Aldu\Core\Net\HTTP;

class User extends Core\Model
{
  protected static $configuration = array(
    __CLASS__ => array(
      'datasource' => array(
        'options' => array(
          'sort' => array(
            'name' => 1
          )
        ),
        'authentication' => array(
          'id' => 'name',
          'password' => 'password'
        ),
        'ldap' => array(
          'type' => 'openldap',
          'openldap' => array(
            'base' => 'ou=people',
            'rdn' => 'uid',
            'filter' => array(
              'objectClass' => 'posixAccount'
            ),
            'mappings' => array(
              'id' => 'uidNumber',
              'name' => 'uid',
              'firstname' => 'givenName',
              'lastname' => 'sn'
            ),
          ),
          'ad' => array(
            'base' => 'CN=Users',
            'rdn' => 'CN',
            'filter' => array(
              'objectClass' => 'user'
            ),
            'mappings' => array(
              'id' => 'objectSid',
              'name' => 'sAMAccountName',
              'firstname' => 'givenName',
              'lastname' => 'sn'
            )
          )
        )
      )
    )
  );

  protected static $attributes = array(
    'password' => array(
      'encrypt' => true
    )
  );

  protected static $relations = array(
    'has' => array(
      'Aldu\Core\Model' => array(
        'acl' => array(
          'type' => array(
            'read',
            'edit',
            'delete'
          ),
          'default' => array(
            'read'
          )
        )
      )
    ),
    'belongs' => array(
      'Aldu\Auth\Models\Group' => true
    )
  );

  public $name;
  public $password;
  public $firstname;
  public $lastname;
  public $mail;

  public function save()
  {
    parent::save();
    $request = HTTP\Request::instance();
    if ($this === $request->aro) {
      $id = static::cfg('datasource.authentication.id');
      $pw = static::cfg('datasource.authentication.password');
      $request->updateAro(get_class($this), $id, $pw, false);
    }
  }

  public function label()
  {
    return "{$this->lastname} {$this->firstname}";
  }
}
