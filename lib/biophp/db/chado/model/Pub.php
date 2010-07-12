<?php

namespace biophp\db\chado\model;



/**
 * Publication object
 * Represents a publication stored in chado.
 */
class Pub {

  protected $pub_id;
  protected $title; // Descriptive general heading.
  protected $volumetitle; //Title of part if one of a series.
  protected $volume; 
  protected $series_name; // -- Full name of (journal) series.
  protected $issue;
  protected $pyear;
  protected $pages; // -- Page number range[s], e.g. 457--459, viii + 664pp, lv--lvii.
  protected $miniref;
  protected $uniquename;
  protected $type_id;  // The type of the publication (book, journal, poem, graffiti, etc). Uses pub cv.
  protected $is_obsolete;  //boolean
  protected $publisher;
  protected $pubplace;

  
  
  public function __construct($pub_id=0,
                              $title="",
                              $volumetitle="",
                              $volume="",
                              $series_name="",
                              $issue="",
                              $pyear=0,
                              $pages="",
                              $miniref="",
                              $uniquename="",
                              $type_id=0,
                              $is_obsolete=FALSE,
                              $publisher="",
                              $pubplace="") {
    
    
    
    $this->pub_id = $pub_id;
    $this->title = $title;
    $this->volumetitle = $volumetitle;
    $this->volume = $volume;
    $this->series_name = $series_name;
    $this->issue = $issue;
    $this->pyear = $pyear;
    $this->pages = $pages;
    $this->miniref = $miniref;
    $this->uniquename = $uniquename;
    $this->type_id = $type_id;
    $this->is_obsolete = $is_obsolete;
    $this->publisher = $publisher;
    $this->pubplace = $pubplace;
    
  }
  
  
  /**
   * Magic setter
   */
  public function __set($name, $value) {

    $this->$name = $value;
    
  }
  
  /**
   * Magic getter
   */
  public function __get($name) {
    
    if(isset($this->$name)) {
      
      return $this->$name;
    
    } else {
      
      throw new \Exception("Unable to find property $name in Config class.");
      
    }
  
  }
    
    
    
}