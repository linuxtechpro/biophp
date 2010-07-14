<?php

namespace biophp\db;

/**
 * This class creates a select query using methods
 * to build an internal array representation of a
 * a select query.
 *
 * A compile function will then transform the array
 * into SQL query using the compile method.
 *
 *
 * You can retrieve the generated query from this class
 * using the getQueryString method.
 *
 * @todo Finish this class
 */


class SelectQuery extends Query implements IQuery{
  
  /**
   * Create a new select query object
   *
   * @param string $table The select table
   * @param string $alias The alias for the table
   */
  public function __construct($table, $alias) {
    
    $this->query['table'][$table] = array(
      'type' => 'primary',
      'alias' => $alias,
      
    );
    
  }
  
  /**
   * Add a new field to the query.  Fields contain the
   * field name, the alias for the field and the table prefix.
   *
   * Due to the complexity of queries, all tables will have
   * a table alias.  
   *
   * @param String $field The name of the field
   * @param String $alias The name of the field, example p.pub_id as pub_id
   * @param String $table_alias the name of the alias for the table, example p.pub_id, p is the table alias.
   */
  public function addField($field, $alias, $table_alias) {
    
    $this->query['field'][$field] = array(
      'alias' => $alias,
      'table_alias' => $table_alias,
    );
    
  }
  
  /**
   * Add a constraint to the select query
   * Constraints  come in the form of =, <>, <, >,
   */
  public function addConstraint($constraint_name, $type, $value, $conjunction) {
    
    
    
  }
  
  public function addConstraintGroup($group_name, $conjunction, $parent=NULL) {
    
  }
  
  public function compile() {
    
    //TODO: Create a compiler factory to support multiple databases

    
  }
  
  
  public function getQueryArray() {
    
    
  }
  
  public function getQueryString() {
    
  }
  
}