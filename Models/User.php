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
        'ad' => array(
          'base' => 'CN=Users', 'rdn' => 'CN', 'objectClass' => 'user',
          'mappings' => array(
            'name' => 'sAMAccountName', 'firstname' => 'givenName',
            'lastname' => 'sn'
          )
        )
      )
    )
  );

  protected $name;
  protected $password;
  protected $firstname;
  protected $lastname;
  protected $mail;
}