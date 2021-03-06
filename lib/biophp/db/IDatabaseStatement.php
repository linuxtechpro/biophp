<?php

namespace biophp\db;

/**
 * A prepared statement.
 *
 * Some methods in that class are purposely commented out. Due to a change in
 * how PHP defines PDOStatement, we can't define a signature for those methods
 * that will work the same way between versions older than 5.2.6 and later
 * versions.
 *
 * Please refer to http://bugs.php.net/bug.php?id=42452 for more details.
 *
 * Child implementations should either extend PDOStatement:
 * @code
 * class DatabaseStatement_oracle extends PDOStatement implements DatabaseStatementInterface {}
 * @endcode
 * or implement their own class, but in that case they will also have to
 * implement the Iterator or IteratorArray interfaces before
 * DatabaseStatementInterface:
 * @code
 * class DatabaseStatement_oracle implements Iterator, DatabaseStatementInterface {}
 * @endcode
 */
interface IDatabaseStatement extends \Traversable {

  /**
   * Executes a prepared statement
   *
   * @param $args
   *   An array of values with as many elements as there are bound parameters in
   *   the SQL statement being executed.
   * @param $options
   *   An array of options for this query.
   *
   * @return
   *   TRUE on success, or FALSE on failure.
   */
  public function execute($args = array(), $options = array());

  /**
   * Gets the query string of this statement.
   *
   * @return
   *   The query string, in its form with placeholders.
   */
  public function getQueryString();

  /**
   * Returns the number of rows affected by the last SQL statement.
   *
   * @return
   *   The number of rows affected by the last DELETE, INSERT, or UPDATE
   *   statement executed.
   */
  public function rowCount();

  /**
   * Sets the default fetch mode for this statement.
   *
   * See http://php.net/manual/en/pdo.constants.php for the definition of the
   * constants used.
   *
   * @param $mode
   *   One of the PDO::FETCH_* constants.
   * @param $a1
   *   An option depending of the fetch mode specified by $mode:
   *   - for PDO::FETCH_COLUMN, the index of the column to fetch
   *   - for PDO::FETCH_CLASS, the name of the class to create
   *   - for PDO::FETCH_INTO, the object to add the data to
   * @param $a2
   *   If $mode is PDO::FETCH_CLASS, the optional arguments to pass to the
   *   constructor.
   */
  // public function setFetchMode($mode, $a1 = NULL, $a2 = array());

  /**
   * Fetches the next row from a result set.
   *
   * See http://php.net/manual/en/pdo.constants.php for the definition of the
   * constants used.
   *
   * @param $mode
   *   One of the PDO::FETCH_* constants.
   *   Default to what was specified by setFetchMode().
   * @param $cursor_orientation
   *   Not implemented in all database drivers, don't use.
   * @param $cursor_offset
   *   Not implemented in all database drivers, don't use.
   *
   * @return
   *   A result, formatted according to $mode.
   */
  // public function fetch($mode = NULL, $cursor_orientation = NULL, $cursor_offset = NULL);

  /**
   * Returns a single field from the next record of a result set.
   *
   * @param $index
   *   The numeric index of the field to return. Defaults to the first field.
   *
   * @return
   *   A single field from the next record.
   */
  public function fetchField($index = 0);

  /**
   * Fetches the next row and returns it as an object.
   *
   * The object will be of the class specified by DatabaseStatementInterface::setFetchMode()
   * or stdClass if not specified.
   */
  // public function fetchObject();

  /**
   * Fetches the next row and returns it as an associative array.
   *
   * This method corresponds to PDOStatement::fetchObject(), but for associative
   * arrays. For some reason PDOStatement does not have a corresponding array
   * helper method, so one is added.
   *
   * @return
   *   An associative array.
   */
  public function fetchAssoc();

  /**
   * Returns an array containing all of the result set rows.
   *
   * @param $mode
   *   One of the PDO::FETCH_* constants.
   * @param $column_index
   *   If $mode is PDO::FETCH_COLUMN, the index of the column to fetch.
   * @param $constructor_arguments
   *   If $mode is PDO::FETCH_CLASS, the arguments to pass to the constructor.
   *
   * @return
   *   An array of results.
   */
  // function fetchAll($mode = NULL, $column_index = NULL, array $constructor_arguments);

  /**
   * Returns an entire single column of a result set as an indexed array.
   *
   * Note that this method will run the result set to the end.
   *
   * @param $index
   *   The index of the column number to fetch.
   *
   * @return
   *   An indexed array.
   */
  public function fetchCol($index = 0);

  /**
   * Returns the entire result set as a single associative array.
   *
   * This method is only useful for two-column result sets. It will return an
   * associative array where the key is one column from the result set and the
   * value is another field. In most cases, the default of the first two columns
   * is appropriate.
   *
   * Note that this method will run the result set to the end.
   *
   * @param $key_index
   *   The numeric index of the field to use as the array key.
   * @param $value_index
   *   The numeric index of the field to use as the array value.
   *
   * @return
   *   An associative array.
   */
  public function fetchAllKeyed($key_index = 0, $value_index = 1);

  /**
   * Returns the result set as an associative array keyed by the given field.
   *
   * If the given key appears multiple times, later records will overwrite
   * earlier ones.
   *
   * @param $key
   *   The name of the field on which to index the array.
   * @param $fetch
   *   The fetchmode to use. If set to PDO::FETCH_ASSOC, PDO::FETCH_NUM, or
   *   PDO::FETCH_BOTH the returned value with be an array of arrays. For any
   *   other value it will be an array of objects. By default, the fetch mode
   *   set for the query will be used.
   *
   * @return
   *   An associative array.
   */
  public function fetchAllAssoc($key, $fetch = NULL);
}
