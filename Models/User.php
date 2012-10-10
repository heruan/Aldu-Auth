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

class User extends Core\Model
{
  protected static $configuration = array(
    'datasource' => array(
      'ldap' => array(
        'type' => 'openldap',
        'openldap' => array(
          'base' => 'ou=people',
          'rdn' => 'uid',
          'objectClass' => 'posixAccount',
          'mappings' => array(
            'id' => 'uidNumber',
            'name' => 'uid',
            'firstname' => 'givenName',
            'lastname' => 'sn'
          )
        ),
        'ad' => array(
          'base' => 'CN=Users',
          'rdn' => 'CN',
          'objectClass' => 'user',
          'mappings' => array(
            'id' => 'objectGUID',
            'name' => 'sAMAccountName',
            'firstname' => 'givenName',
            'lastname' => 'sn'
          )
        )
      )
    )
  );

  protected static $relations = array(
    'has' => array(
      'Aldu\Core\Model' => array(
        'acl' => array(
          'type' => array(
            'read',
            'update',
            'delete'
          ),
          'default'=> array('read')
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
}
