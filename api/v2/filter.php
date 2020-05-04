<?php
function filter($filter) {
  if ($filter == '*') {
    return;
  }
  if (empty($filter)) {
    return ' ';
  }
  
  $filterArray = array_filter(explode(',', $filter));
  
  $_pfilters = '';
  $_ptags = '';
  foreach($filterArray as $index => $filterItem) {
    if (substr($filterItem, 0, 1) != '-'){
      if (substr($filterItem, 0, 5) == 'hash:'){
        // return 'AND LOWER(m.hash) = LOWER(' . pg_escape_literal(dbConnection::getInstance()->getDB(), substr($filterItem, 5)) . ')';
        return "AND " . "LOWER(m.hash) = LOWER('" . substr($filterItem, 5) . "')";
      }
      else if (substr($filterItem, 0, 7) == 'author:'){
        if ($_pfilters != '') {
          $_pfilters .= ' OR ';
        }
        $_pfilters .= "LOWER(m.author) = LOWER('" . substr($filterItem, 7) . "') ";
      }
      //unsupported in api v2
      /*else if (substr($filterItem, 0, 7) == 'status:'){
        if ($_pfilters != '') {
          $_pfilters .= ' OR ';
        }
        $_pfilters .= 'LOWER(status) = LOWER(' . pg_escape_literal(dbConnection::getInstance()->getDB(), substr($filterItem, 7)) . ') ';
      }*/
      
      else if (substr($filterItem, 0, 5) == 'name:'){
        if ($_pfilters != '') {
          $_pfilters .= ' OR ';
        }
        // $_pfilters .= 'LOWER(m.name) = LOWER(' . pg_escape_literal(dbConnection::getInstance()->getDB(), substr($filterItem, 5)) . ') ';
        $_pfilters .= "LOWER(m.name) = LOWER('" . substr($filterItem, 5) . "') ";
      }
      else if (substr($filterItem, 0, 10) == 'discordid:'){
        if ($_pfilters != '') {
          $_pfilters .= ' OR ';
        }
        // $_pfilters .= 'm.discordid = ' . pg_escape_literal(dbConnection::getInstance()->getDB(), substr($filterItem, 10)) . ' ';
        // $_pfilters .= "m.discordid = CAST('" . substr($filterItem, 10) . "' AS bigint) ";
        $_pfilters .= "m.discordid = " . substr($filterItem, 10) . " ";
      }
      else if (substr($filterItem, 0, 3) == 'id:'){
        if ($_pfilters != '') {
          $_pfilters .= ' AND ';
        }
        // $_pfilters .= 'm.discordid = ' . pg_escape_literal(dbConnection::getInstance()->getDB(), substr($filterItem, 10)) . ' ';
        // $_pfilters .= "m.discordid = CAST('" . substr($filterItem, 10) . "' AS bigint) ";
        $_pfilters .= "m.id = '" . substr($filterItem, 3) . "' ";
      }
      else if (substr($filterItem, 0, 4) == 'tag:') {
        $_ptags .= '"' . substr($filterItem, 4) . '",';
      }
      else {
//        $_ptags .= ($filterItem . ',');
        // $_pfilters .= 'LOWER(m.name) LIKE LOWER(' .pg_escape_literal(dbConnection::getInstance()->getDB(), "%$filterItem%") . ') ';
        $_pfilters .= "LOWER(m.name) LIKE LOWER('%" . $filterItem . "%') ";
      }
    }
  }
  if ($_ptags != '') {
    // $_ptags = ('LOWER(m.tags::text)::text[] && ' . pg_escape_literal(dbConnection::getInstance()->getDB(), '{' . strtolower(substr($_ptags, 0, -1)) . '}'));
    $_ptags = "LOWER(m.tags::text)::text[] && '{" . strtolower(substr($_ptags, 0, -1)) . "}'";
    // $_ptags = 'LOWER(m.tags::text)::text[] && {' . strtolower($_ptags) . '}';
    if ($_pfilters != '') {
      $_ptags = ' OR ' . $_ptags;
    }
    $_pfilters .= $_ptags;
  }

  if ($_pfilters != '') {
    $_pfilters = 'AND ' . $_pfilters . '';
  }

  $_nfilters = '';
  $_ntags = '';
  foreach($filterArray as $index=>$filterItem) {
    if (substr($filterItem, 0, 1) == '-'){
      $filterItem = substr($filterItem, 1);
      if (substr($filterItem, 0, 7) == 'author:'){
        if ($_nfilters != '') {
          $_nfilters .= ' OR ';
        }
        // $_nfilters .= 'LOWER(m.author) <> ' . "LOWER(" . pg_escape_literal(dbConnection::getInstance()->getDB(), substr($filterItem, 7)) . ")";
        $_nfilters .= "LOWER(m.author) <> LOWER('" . substr($filterItem, 7) . "')";
      }
      //unsupported in api v2
      /*else if (substr($filterItem, 0, 7) == 'status:'){
        if ($_nfilters != '') {
          $_nfilters .= ' OR ';
        }
        $_nfilters .= 'LOWER(status) <> ' . "LOWER(" . pg_escape_literal(dbConnection::getInstance()->getDB(), substr($filterItem, 7)) . ")";
      } */
      else if (substr($filterItem, 0, 5) == 'name:'){
        if ($_nfilters != '') {
          $_nfilters .= ' OR ';
        }
        // $_nfilters .= 'LOWER(m.name) <> ' . "LOWER(" . pg_escape_literal(dbConnection::getInstance()->getDB(), substr($filterItem, 5)) . ")";
        $_nfilters .= "LOWER(m.name) <> LOWER('" . substr($filterItem, 5) . "')";
      }
      else if (substr($filterItem, 0, 10) == 'discordid:'){
        if ($_nfilters != '') {
          $_nfilters .= ' OR ';
        }
        // $_nfilters .= 'm.discordid <> ' . pg_escape_literal(dbConnection::getInstance()->getDB(), substr($filterItem, 10));
        $_nfilters .= "m.discordid <> " . substr($filterItem, 10) . "";
      }
      else if (substr($filterItem, 0, 4) == 'tag:') {
        $_ntags .= '"' . substr($filterItem, 4) . '",'; 
      }
      else {
//        $_ntags .= ($filterItem . ','); 
        // $_nfilters .= 'LOWER(m.name) NOT LIKE LOWER(' .pg_escape_literal(dbConnection::getInstance()->getDB(), "%$filterItem%") . ') ';
        $_nfilters .= "LOWER(m.name) NOT LIKE LOWER('%" . $filterItem . "%') ";
      }
    }
  }
  if ($_ntags != '') {
    // $_ntags = ('NOT(LOWER(m.tags::text)::text[] && ' . pg_escape_literal(dbConnection::getInstance()->getDB(), '{' . strtolower(substr($_ntags, 0, -1)) . '}') . ')');
    $_ntags = "NOT(LOWER(m.tags::text)::text[] && '{" . strtolower(substr($_ntags, 0, -1)) . "}')";
    if ($_nfilters != '') {
      $_ntags = ' OR ' . $_ntags;
    }
    $_nfilters .= $_ntags;
  }
  
  if ($_nfilters != '') {
    $_nfilters = 'AND ( ' . $_nfilters . ' )';
  }
  return $_pfilters . ' ' . $_nfilters;

  $log = fopen('/var/www/modelsaber/filters.log', 'a');
  fwrite($log, $_pfilters . ' ' . $_nfilters . '\n');
  fclose($log);
}
?>