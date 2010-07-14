<?php

namespace biophp\db;

interface IQuery {
  
  public function compile();
  
  public function getQueryArray();
  
  public function getQueryString();

}