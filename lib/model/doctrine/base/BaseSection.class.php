<?php

/**
 * BaseSection
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $lnr
 * @property integer $anlage_id
 * @property string $inhalt
 * @property string $tip
 * @property Anlage $Anlage
 * @property Bild $Bild
 * 
 * @method integer getLnr()       Returns the current record's "lnr" value
 * @method integer getAnlageId()  Returns the current record's "anlage_id" value
 * @method string  getInhalt()    Returns the current record's "inhalt" value
 * @method string  getTip()       Returns the current record's "tip" value
 * @method Anlage  getAnlage()    Returns the current record's "Anlage" value
 * @method Bild    getBild()      Returns the current record's "Bild" value
 * @method Section setLnr()       Sets the current record's "lnr" value
 * @method Section setAnlageId()  Sets the current record's "anlage_id" value
 * @method Section setInhalt()    Sets the current record's "inhalt" value
 * @method Section setTip()       Sets the current record's "tip" value
 * @method Section setAnlage()    Sets the current record's "Anlage" value
 * @method Section setBild()      Sets the current record's "Bild" value
 * 
 * @package    openZIM
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSection extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('section');
        $this->hasColumn('lnr', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('anlage_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('inhalt', 'string', 100000, array(
             'type' => 'string',
             'length' => 100000,
             ));
        $this->hasColumn('tip', 'string', 1000, array(
             'type' => 'string',
             'length' => 1000,
             ));


        $this->index('section_lnr', array(
             'fields' => 
             array(
              0 => 'anlage_id',
              1 => 'lnr',
             ),
             'type' => 'unique',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Anlage', array(
             'local' => 'anlage_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Bild', array(
             'local' => 'id',
             'foreign' => 'section_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}