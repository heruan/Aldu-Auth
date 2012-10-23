<?php
/**
 * Aldu\Auth\Models\Group
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

class Group extends Core\Model
{
  protected static $configuration = array(__CLASS__ => array(
    'datasource' => array(
      'ldap' => array(
        'type' => 'openldap',
        'openldap' => array(
          'base' => 'ou=groups',
          'rdn' => 'cn',
          'objectClass' => 'posixGroup',
          'mappings' => array(
            'id' => 'gidNumber',
            'name' => 'cn',
            'members' => 'memberUid'
          ),
          'references' => array(
            'members' => array(
              'class' => 'Aldu\Auth\Models\User',
              'attribute' => 'name'
            )
          )
        ),
        'ad' => array(
          'base' => 'CN=Groups',
          'rdn' => 'CN',
          'objectClass' => 'group',
          'mappings' => array(
          )
        )
      )
    )
  ));

  protected static $attributes = array(
    'members' => array(
      'type' => 'Aldu\Auth\Models\User',
      'multiple' => true
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
          'default'=> array('read')
        )
      )
    )
  );

  public $name;
  public $members;

  public function hasMember($model)
  {
    return in_array($model, $this->members);
  }
}
