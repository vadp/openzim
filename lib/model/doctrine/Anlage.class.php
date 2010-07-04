<?php

require_once(dirname(__FILE__).'/../../odtphp/library/odf.php');
require_once(dirname(__FILE__).'/../../htmlconverter/library/htmlConverter.php');

/**
 * Anlage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    openZIM
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Anlage extends BaseAnlage
{
	public function save(Doctrine_Connection $conn = null)
	{  
	
		$conn = $conn ? $conn : $this->getTable()->getConnection();
  		$conn->beginTransaction();
  		try
  		{
 			$ret = parent::save($conn);
 			$this->updateLuceneIndex();
     			$conn->commit();
 
    			return $ret;
  		}
  		catch (Exception $e)
  		{
    			$conn->rollBack();
    			throw $e;
  		}

	}

	public function delete(Doctrine_Connection $conn = null)
	{
  		$index = AnlageTable::getLuceneIndex();
 
  		foreach ($index->find('pk:'.$this->getId()) as $hit)
  		{
    			$index->delete($hit->id);
  		}
 
  		return parent::delete($conn);
	}
 
	public function getName($query = '*')
    	{
       		return $this->getKuerzel().$this->getLnr();
    	}

	public function updateLuceneIndex()
	{
  		$index = AnlageTable::getLuceneIndex();
 
  		// remove existing entries
  		foreach ($index->find('pk:'.$this->getId()) as $hit)
  		{
    			$index->delete($hit->id);
  		}
 
  		$doc = new Zend_Search_Lucene_Document();
 
  		// store primary key to identify it in the search results
  		$doc->addField(Zend_Search_Lucene_Field::Keyword('pk', $this->getId()));
 
  		// index anlage fields
  		$doc->addField(Zend_Search_Lucene_Field::UnStored('name', $this->getName(), 'utf-8'));
  		$doc->addField(Zend_Search_Lucene_Field::UnStored('ziel', $this->getZiel(), 'utf-8'));
  		$doc->addField(Zend_Search_Lucene_Field::UnStored('inhalt', $this->getInhalt(), 'utf-8'));
 
  		// add anlage to the index
  		$index->addDocument($doc);
  		$index->commit();
	}
	
        public function generateOdf()
	{
		
		$htmlConverter = new htmlConverter();
		$convertedInhalt = $htmlConverter->getODF($this->getInhalt());	
		$convertedZiel = $htmlConverter->getODF($this->getZiel());
		$convertedMethode = $htmlConverter->getODF($this->getMethode());
		$convertedMaterial = $htmlConverter->getODF($this->getMaterial());
		$convertedTip = $htmlConverter->getODF($this->getTip());

		$odf = new odf(dirname(__FILE__).'/../../odftmp/Anlage_template.odt');
	   	$odf->setStyleVars('stunde', $this->getStunde()->getLnr(), false);
	   	$odf->setStyleVars('kuerzel', $this->getKuerzel(), false);
	   	$odf->setStyleVars('lnr', $this->getLnr(), false);
	   	$odf->setVars('zeit', $this->getZeit(), false);
		$odf->setVars('ziel', $convertedZiel, false,'UTF-8');
		$odf->setVars('tip', $convertedTip, false,'UTF-8');
		$odf->setVars('Inhalt', $convertedInhalt, false,'UTF-8');
		$odf->setVars('methode', $convertedMethode, false,'UTF-8');
		$odf->setVars('material', $convertedMaterial, false,'UTF-8');
	   	$odf->setVars('zeit', $this->getZeit(), false);
 		$bilder = $odf->setSegment('bilder');
                foreach ( $this->getBilder() as $bild ){
		  $bilder->titel($bild->getCaption());
    		  $bilder->setImage('bild',sfConfig::get('sf_upload_dir').'/bilder/'.$bild->getPath());
		  $bilder->merge();
		}
		$odf->mergeSegment($bilder);
		$odf->exportAsAttachedFile ($this->getName().'.odt');  

        }

	public function __toString()
	{
		return $this->getName().' -- '.$this->getZiel();
	}

}
