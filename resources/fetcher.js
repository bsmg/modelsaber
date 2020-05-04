const baseUrl = document.getElementById('fetcher').getAttribute('data-webroot');

const modelItems = document.getElementsByClassName('items')[0];
const sortDirectionElement = document.getElementById('sortDirection');
const sortElement = document.getElementById('currentSort');
const limitElement = document.getElementById('pageLimit');
const preFilter = '<span class="tag is-small is-rounded">';
const preFilterNegative = '<span class="tag is-small is-danger is-rounded">';
const postFilter = '<button onclick="this.parentElement.parentElement.removeChild(this.parentElement);filterUpdate()" class="delete is-small"></button></span>';
const filterPool = document.getElementById('filterPool');
const filterInput = document.getElementById('filterInput');

let currentPage = modelItems.dataset.page;
let sortDirection = getCookie('sort_dir');
let sortType = getCookie('sort');
let pageLimit = getCookie('limit');
let filterStringFull = '*';
let modelType = getCookie('modelType');
let status = getCookie('status');
const date = new Date("19 January 2038");
var path = window.location.pathname.split('/');
const type = path[path.length - 2].slice(0, -1).toLowerCase();
//const type = window.location.pathname.toLocaleLowerCase().slice(1, -2);

if (pageLimit == '') pageLimit = 24;
if (sortDirection == '') {
  sortDirection;
}

function getCookie(name) {
  value = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
  return value ? value.pop() : '';
}

function setCookie(name, value, path='/') {
  document.cookie = name + '=' + ( value || "" ) + '; expires=' + date.toUTCString() + '; path=' + path;
}

function pageLimitSet(limit) {
  pageLimit = limit;
  limitElement.innerHTML = limit + ' Per Page';
  setCookie("limit", limit, window.location.pathname);
  currentPage = 1;
  modelItems.dataset.page = currentPage;

  fetcher();
}

function sortDirectionToggle() {
  switch (sortDirectionElement.dataset.sort) {
    case "Ascending":
      setCookie('sort_dir', 'Descending', window.location.pathname);
      sortDirectionElement.dataset.sort = "Descending";
      sortDirectionElement.innerHTML = '<i class="fas fa-arrow-down"></i>';
      fetcher();
      break
    case "Descending":
      setCookie('sort_dir', 'Ascending', window.location.pathname);
      sortDirectionElement.dataset.sort = "Ascending";
      sortDirectionElement.innerHTML = '<i class="fas fa-arrow-up"></i>';
      fetcher();
      break
  }
}

function changePage(page) {
  currentPage = page;
  modelItems.dataset.page = page;
  fetcher();
}

function changeModelType(type) {
    setCookie('modelType', type, window.location.pathname);
}

function changeStatus(approval) {
    setCookie('status', approval, window.location.pathname);
}

function sort(type) {
  switch (type) {
    case "name":
      setCookie("sort", "Name");
      sortElement.innerText = 'Name';
      fetcher();
      break
    case "author":
      setCookie("sort", "Author");
      sortElement.innerText = 'Author';
      fetcher();
      break
    case "date":
      setCookie("sort", "Date");
      sortElement.innerText = 'Date';
      fetcher();
      break
    case "votes":
      setCookie("sort", "Votes");
      sortElement.innerText = 'Votes';
      fetcher();
      break
  }
}

function fetcher() {
  console.log('Fetching');
  modelItems.innerHTML='';
  var query = getDefaultQueryParameters();

  if (type == 'profil') {
    console.log('Fetching profile filters');
    query = addQueryParameter(query, 'user', get('user'));
    fetch(baseUrl + '/resources/includes/fetchFilters.php?' + getPlatform(), {
      method: 'POST',
      body: query,
      headers: { 'Content-type': 'application/x-www-form-urlencoded' }
    }).then(function(response) {
      return response.text().then(function(text){
        modelItems.innerHTML=text;
      });
    });
  } else if (type == 'manag') {
    console.log('Fetching admin filters');
    query = addQueryParameter(query, 'modelType', modelType);
    query = addQueryParameter(query, 'status', status);
    fetch(baseUrl + '/resources/includes/fetchFilters.php?' + getPlatform(), {
      method: 'POST',
      body: query,
      headers: { 'Content-type': 'application/x-www-form-urlencoded' }
    }).then(function(response) {
      return response.text().then(function(text){
        modelItems.innerHTML=text;
      });
    });
  } else {
    console.log('Fetching filters');
    fetch(baseUrl + '/resources/includes/fetchFilters.php?' + getPlatform(), {
      method: 'POST',
      body: query,
      headers: { 'Content-type': 'application/x-www-form-urlencoded' }
    }).then(function(response) {
      return response.text().then(function(text){
        modelItems.innerHTML=text;
      });
    });
  }
  
}

function get(name){
  const parts = window.location.href.split('?');
  if (parts.length > 1) {
    name = encodeURIComponent(name);
    const params = parts[1].split('&');
    const found = params.filter(el => (el.split('=')[0] === name) && el);
    if (found.length) return decodeURIComponent(found[0].split('=')[1]);
  }
}
function hasGetParameter(name){
  const parts = window.location.href.split('?');
  var found = false;
  if (parts.length > 1) {
    name = encodeURIComponent(name);
    const params = parts[1].split('&');
    found = params.includes(name);
  }
  return found;
}

function getPlatform() {
  output = 'pc';
  if (hasGetParameter('quest')) {
    output = 'quest';
  }

  return output;
}

function addQueryParameter(query = '', key, value) {
  if (isEmpty(key) || isEmpty(value)) {
    return query;
  }

  if (!isEmpty(query)) {
    query += '&';
  }

  query += key + '=' + value;

  return query;
}

function getDefaultQueryParameters() {
  var query = '';
  query = addQueryParameter(query, 'type', type);
  query = addQueryParameter(query, 'sort', sortElement.innerText);
  query = addQueryParameter(query, 'sort_dir', sortDirectionElement.dataset.sort);
  query = addQueryParameter(query, 'page', currentPage);
  query = addQueryParameter(query, 'limit', pageLimit);
  query = addQueryParameter(query, 'filter', filterStringFull);
  query = addQueryParameter(query, 'mode', 'fetch');

  return query;
}

function filterAdd(filter = null) {
  currentPage = 1;
  modelItems.dataset.page = 1;
  let filterString = null;
  if (filter == null) {
    filterString = filterInput.value;
  } else {
    filterString = filter;
  }
  if (filterString === '') return false;
  if (filterString.substring(0,1) === '-'){
    filterPool.insertAdjacentHTML('beforeend', preFilterNegative + filterString + postFilter);
  } else {
    filterPool.insertAdjacentHTML('beforeend', preFilter + filterString + postFilter);
  }
  filterUpdate();
  filterInput.value = '';
}

function filterUpdate() {
  let filterList = '';
  Array.from(filterPool.getElementsByTagName('span')).forEach(
    function (element) {
        element.classList.add('no-hover');
        var value = element.innerText.toLowerCase();
        if (value == "tag:custom colors") {
            element.classList.add('has-border-gay');
        } else if (value == "tag:anime") {
            element.classList.add('has-border-weeb-trash');
        } else if (value == "tag:custom trail") {
            element.classList.add('has-border-trail');
        } else if (value == "darkmodeplz") {
            darkToggle(true);
        } else if (value == "lightmodeplz") {
            darkToggle(false);
        }
      filterList += element.innerText + ',';
    }
  );
  if (filterList === ''){
    filterStringFull = '*';
    fetcher();
  } else {
    filterStringFull = filterList.substring(0,filterList.length - 1);
    console.log(filterStringFull);
    fetcher();
  }
}

filterInput.addEventListener('keyup', function(event) {
  event.preventDefault();
  if (event.keyCode === 13) {
    filterAdd();
  }
});