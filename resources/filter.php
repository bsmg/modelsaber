<?php
function filter($filter) {
  if ($filter == '*') {
    return;
  }
  if (empty($filter)) {
//    return ' ';
    return ['query' => ' ', 'params' => []];
  }
  
  $filterArray = array_filter(explode(',', $filter));
  
  $filterIndex = 0;
  
  $_pfilters = '';
  $_ptags = '';
  foreach($filterArray as $index => $filterItem) {
    if (substr($filterItem, 0, 1) != '-'){
      if (substr($filterItem, 0, 5) == 'hash:'){
        $output['query'] = 'AND LOWER(hash) = LOWER(:phash)';
        $output['params'][':phash'] = substr($filterItem, 5);
        return $output;
//        return 'AND LOWER(hash) = LOWER(' . substr($filterItem, 5) . ')';
      }
      else if (substr($filterItem, 0, 7) == 'author:'){
        if ($_pfilters != '') {
          $_pfilters .= ' OR ';
        }
//        $_pfilters .= 'LOWER(author) = LOWER(' . substr($filterItem, 7) . ') ';
        $_pfilters .= "LOWER(author) = LOWER(:pauthor$filterIndex) ";
        $params[':pauthor' . $filterIndex] = substr($filterItem, 7);
        $filterIndex++;
      }
      else if (substr($filterItem, 0, 7) == 'status:'){
        if ($_pfilters != '') {
          $_pfilters .= ' OR ';
        }
//        $_pfilters .= 'LOWER(status) = LOWER(' . substr($filterItem, 7) . ') ';
        $_pfilters .= "LOWER(status) = LOWER(:pstatus$filterIndex) ";
        $params[':pstatus' . $filterIndex] = substr($filterItem, 7);
        $filterIndex++;
      }
      
      else if (substr($filterItem, 0, 5) == 'name:'){
        if ($_pfilters != '') {
          $_pfilters .= ' OR ';
        }
//        $_pfilters .= 'LOWER(name) = LOWER(' . substr($filterItem, 5) . ') ';
        $_pfilters .= "LOWER(name) = LOWER(:pname$filterIndex) ";
        $params[':pname' . $filterIndex] = substr($filterItem, 5);
        $filterIndex++;
      }
      else if (substr($filterItem, 0, 10) == 'discordid:'){
        if ($_pfilters != '') {
          $_pfilters .= ' OR ';
        }
//        $_pfilters .= 'discordid = ' . substr($filterItem, 10) . ' ';
        $_pfilters .= "discordid = :pdiscordid$filterIndex ";
        $params[':pdiscordid' . $filterIndex] = substr($filterItem, 10);
        $filterIndex++;
      }
      else if (substr($filterItem, 0, 4) == 'tag:') {
        $_ptags .= (substr($filterItem, 4) . ',');
//        $_ptags .= ':tag' . ',';
//        $params[':tag'] = substr($filterItem, 4);
//        $filterIndex++;
      }
      else {
//        $_ptags .= ($filterItem . ',');
//        $_pfilters .= "LOWER(name) LIKE LOWER('%$filterItem%') ";
        $_pfilters .= "LOWER(name) LIKE LOWER(:psearch$filterIndex) ";
        $params[':psearch' . $filterIndex] = "%$filterItem%";
        $filterIndex++;
      }
    }
  }
  if ($_ptags != '') {
//    $_ptags = ('LOWER(tags::text)::text[] && {' . strtolower(substr($_ptags, 0, -1)) . '}');
    $params[':ptag'] = '{' . strtolower(substr($_ptags, 0, -1)) . '}';
    $_ptags = ('LOWER(tags::text)::text[] && :ptag');
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
  foreach($filterArray as $index => $filterItem) {
    if (substr($filterItem, 0, 1) == '-'){
      $filterItem = substr($filterItem, 1);
      if (substr($filterItem, 0, 7) == 'author:'){
        if ($_nfilters != '') {
          $_nfilters .= ' OR ';
        }
//        $_nfilters .= 'LOWER(author) <> ' . "LOWER(" . substr($filterItem, 7) . ")";
        $_nfilters .= 'LOWER(author) <> ' . "LOWER(:nauthor$filterIndex) ";
        $params[':nauthor' . $filterIndex] = substr($filterItem, 7);
        $filterIndex++;
      }
      else if (substr($filterItem, 0, 7) == 'status:'){
        if ($_nfilters != '') {
          $_nfilters .= ' OR ';
        }
//        $_nfilters .= 'LOWER(status) <> ' . "LOWER(" . substr($filterItem, 7) . ") ";
        $_nfilters .= 'LOWER(status) <> ' . "LOWER(:nstatus$filterIndex) ";
        $params[':nstatus' . $filterIndex] = substr($filterItem, 7);
        $filterIndex++;
      } 
      else if (substr($filterItem, 0, 5) == 'name:'){
        if ($_nfilters != '') {
          $_nfilters .= ' OR ';
        }
//        $_nfilters .= 'LOWER(name) <> ' . "LOWER(" . substr($filterItem, 5) . ") ";
        $_nfilters .= 'LOWER(name) <> ' . "LOWER(:nname$filterIndex) ";
        $params[':nname' . $filterIndex] = substr($filterItem, 5);
        $filterIndex++;
      }
      else if (substr($filterItem, 0, 10) == 'discordid:'){
        if ($_nfilters != '') {
          $_nfilters .= ' OR ';
        }
//        $_nfilters .= 'discordid <> ' . substr($filterItem, 10);
        $_nfilters .= "discordid <> :ndiscordid$filterIndex";
        $params[':ndiscordid' . $filterIndex] = substr($filterItem, 10);
        $filterIndex++;
      }
      else if (substr($filterItem, 0, 4) == 'tag:') {
        $_ntags .= (substr($filterItem, 4) . ','); 
      }
      else {
//        $_ntags .= ($filterItem . ','); 
//        $_nfilters .= "LOWER(name) NOT LIKE LOWER('%$filterItem%') ";
        $_nfilters .= "LOWER(name) NOT LIKE LOWER(:nsearch$filterIndex) ";
        $params[':nsearch' . $filterIndex] = "%$filterItem%";
        $filterIndex++;
      }
    }
  }
  if ($_ntags != '') {
    $params[':ntag'] = '{' . strtolower(substr($_ntags, 0, -1)) . '}';
//    $_ntags = ('NOT(LOWER(tags::text)::text[] && {' . strtolower(substr($_ntags, 0, -1)) . '})');
    $_ntags = ('NOT(LOWER(tags::text)::text[] && :ntag)');
    if ($_nfilters != '') {
      $_ntags = ' OR ' . $_ntags;
    }
    $_nfilters .= $_ntags;
  }
  
  if ($_nfilters != '') {
    $_nfilters = 'AND ( ' . $_nfilters . ' )';
  }
  $output['query'] = $_pfilters . ' ' . $_nfilters;
  $output['params'] = $params;
  return $output;
//  return $_pfilters . ' ' . $_nfilters;

  $log = fopen('/var/www/modelsaber/filters.log', 'a');
  fwrite($log, $_pfilters . ' ' . $_nfilters . '\n');
  fclose($log);
}
?>