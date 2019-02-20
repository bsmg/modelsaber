<?php
function filter($db, $filter) {
  if ($filter == '*') return;
  
  $filterArray = explode(',', $filter);
  
  $_pfilters = '';
  $_ptags = '';
  foreach($filterArray as $index=>$filterItem) {
    if (substr($filterItem, 0, 1) != '-'){
      if (substr($filterItem, 0, 5) == 'hash:'){
        return 'AND LOWER(hash) = LOWER(' . pg_escape_literal($db, substr($filterItem, 5)) . ')';
      }
      else if (substr($filterItem, 0, 7) == 'author:'){
        if ($_pfilters != '') $_pfilters .= ' OR ';
        $_pfilters .= 'LOWER(author) = ' . "LOWER(" . pg_escape_literal($db, substr($filterItem, 7)) . ") ";

      }
      else if (substr($filterItem, 0, 5) == 'name:'){
        if ($_pfilters != '') $_pfilters .= ' OR ';
        $_pfilters .= 'LOWER(name) = ' . "LOWER(" . pg_escape_literal($db, substr($filterItem, 5)) . ") ";
      } 
      else {
        $_ptags .= ($filterItem . ','); 
      }
    }
  }
  if ($_ptags != '') {
    $_ptags = ('LOWER(tags::text)::text[] && ' . pg_escape_literal($db, '{' . strtolower(substr($_ptags, 0, -1)) . '}'));
    if ($_pfilters != '') $_ptags = ' OR ' . $_ptags;
    $_pfilters .= $_ptags;
  }

  if ($_pfilters != '') $_pfilters = 'AND ( ' . $_pfilters . ' )';

  $_nfilters = '';
  $_ntags = '';
  foreach($filterArray as $index=>$filterItem) {
    if (substr($filterItem, 0, 1) == '-'){
      $filterItem = substr($filterItem, 1);
      if (substr($filterItem, 0, 7) == 'author:'){
        if ($_nfilters != '') $_nfilters .= ' OR ';
        $_nfilters .= 'LOWER(author) <> ' . "LOWER(" . pg_escape_literal($db, substr($filterItem, 7)) . ")";
      }
      else if (substr($filterItem, 0, 5) == 'name:'){
        if ($_nfilters != '') $_nfilters .= ' OR ';
        $_nfilters .= 'LOWER(name) <> ' . "LOWER(" . pg_escape_literal($db, substr($filterItem, 5)) . ")";
      } 
      else {
        $_ntags .= ($filterItem . ','); 
      }
    }
  }
  if ($_ntags != '') {
    $_ntags = ('NOT(LOWER(tags::text)::text[] && ' . pg_escape_literal($db, '{' . strtolower(substr($_ntags, 0, -1)) . '}') . ')');
    if ($_nfilters != '') $_ntags = ' OR ' . $_ntags;
    $_nfilters .= $_ntags;
  }

  if ($_nfilters != '') $_nfilters = 'AND ( ' . $_nfilters . ' )';
  return $_pfilters . ' ' . $_nfilters;

  $log = fopen('/var/www/modelsaber/filters.log', 'a');
  fwrite($log, $_pfilters . ' ' . $_nfilters . '\n');
  fclose($log);
}
?>