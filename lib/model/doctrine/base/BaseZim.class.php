<?php

/**
 * BaseZim
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * @property Doctrine_Collection $Phonenumbers
 * 
 * @method string              getName()         Returns the current record's "name" value
 * @method Doctrine_Collection getPhonenumbers() Returns the current record's "Phonenumbers" collection
 * @method Zim                 setName()         Sets the current record's "name" value
 * @method Zim                 setPhonenumbers() Sets the current record's "Phonenumbers" collection
 * 
 * @package    openZIM
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseZim extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('zim');
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Stunde as Phonenumbers', array(
             'local' => 'id',
             'foreign' => 'zim_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}