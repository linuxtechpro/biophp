<?php
namespace biophp\db\chado\dao;

/**
 * Publication Data Access Object. 
 * Data access object that provides methods for interfacing with
 * the chado database publication tables.
 *
 * All these classes rely on PDO, make sure it is installed.
 * 
 * @author Randall Svancara
 */


class PublicationDAO extends BaseDAO {


  /**
   * Constructor
   */
  public function __construct() {
    
    parent::__construct();  // Call parent constructor
    
  }
  
  /**
   * A method for finding publications
   *
   * Where must be in the form of an array
   *
   * $where = array(
   *                 'expression'=>array(
                                     'name' => 'field_name',
                                     'operator => '=',
                                     'value' => '
                                   )
   )
   * 
   * @param Array $where What you want ot restrict by
   * @param String $limit The number of records you want returned
   * @param String $offset Where to start at
   * @return Array An array of Pub objects
   */
  public function FindAllPubs($where=NULL,$limit=NULL,$offset=NULL) {
    
    // Query fragment
    $sql = "SELECT
              pub_id,
              title,
              volumetitle,
              volume, 
              series_name,
              issue,
              pyear,
              pages, 
              miniref,
              uniquename,
              type_id,  
              is_obsolete,  
              publisher,
              pubplace
            FROM pub";
              
  
    
  }
  



}

