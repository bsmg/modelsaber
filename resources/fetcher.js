const modelItems = document.getElementsByClassName('items')[0]
const sortDirectionElement = document.getElementById('sortDirection')
const sortElement = document.getElementById('currentSort')
const limitElement = document.getElementById('pageLimit')
const preFilter = '<span class="tag is-small is-rounded">'
const preFilterNegative = '<span class="tag is-small is-danger is-rounded">'
const postFilter = '<button onclick="this.parentElement.parentElement.removeChild(this.parentElement);filterUpdate()" class="delete is-small"></button></span>'

let currentPage = modelItems.dataset.page
let sortDirection = getCookie('sort_dir')
let sortType = getCookie('sort')
let pageLimit = getCookie('limit')
let filterStringFull = '*'
const date = new Date("19 January 2038")
const type = window.location.pathname.toLocaleLowerCase().slice(1, -2)

if (pageLimit == '') pageLimit = 24
if (sortDirection == '') {
  sortDirection
}

function getCookie(name) {
  value = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
  return value ? value.pop() : '';
}

function setCookie(name, value, path='/') {
  document.cookie = name + '=' + ( value || "" ) + '; expires=' + date.toUTCString() + '; path=' + path
}

function pageLimitSet(limit) {
  pageLimit = limit
  limitElement.innerHTML = limit + ' Per Page'
  setCookie("limit", limit, window.location.pathname)
  currentPage = 1
  modelItems.dataset.page = currentPage

  fetcher()
}

function sortDirectionToggle() {
  switch (sortDirectionElement.dataset.sort) {
    case "Ascending":
      setCookie('sort_dir', 'Descending', window.location.pathname)
      sortDirectionElement.dataset.sort = "Descending"
      sortDirectionElement.innerHTML = '<i class="fas fa-arrow-down"></i>'
      fetcher()
      break
    case "Descending":
      setCookie('sort_dir', 'Ascending', window.location.pathname)
      sortDirectionElement.dataset.sort = "Ascending"
      sortDirectionElement.innerHTML = '<i class="fas fa-arrow-up"></i>'
      fetcher()
      break
  }
}

function changePage(page) {
  currentPage = page
  modelItems.dataset.page = page
  fetcher()
}

function sort(type) {
  switch (type) {
    case "name":
      setCookie("sort", "Name")
      sortElement.innerText = 'Name'
      fetcher()
      break
    case "author":
      setCookie("sort", "Author")
      sortElement.innerText = 'Author'
      fetcher()
      break
    case "date":
      setCookie("sort", "Date")
      sortElement.innerText = 'Date'
      fetcher()
      break
  }
}

function fetcher() {
  modelItems.innerHTML=''
  fetch('https://modelsaber.com/cards.php', {
    method: 'POST',
    body: 'type=' + type + '&sort=' + sortElement.innerText + '&sort_dir=' + sortDirectionElement.dataset.sort + '&page=' + currentPage + '&limit=' + pageLimit + '&filter=' + filterStringFull +'&mode=fetch',
    headers: { 'Content-type': 'application/x-www-form-urlencoded' }
  })
  .then(function(response) {
    return response.text().then(function(text){
      modelItems.innerHTML=text
    })
  })
}

function filterAdd(filter = null) {
  let filterString = null
  if (filter == null) {
    filterString = filterInput.value
  } else {
    filterString = filter
  }
  if (filterString === '') return false
  if (filterString.substring(0,1) === '-'){
    filterPool.insertAdjacentHTML('beforeend', preFilterNegative + filterString + postFilter)
  } else {
    filterPool.insertAdjacentHTML('beforeend', preFilter + filterString + postFilter)
  }
  filterUpdate()
  filterInput.value = ''
}

function filterUpdate() {
  let filterList = ''
  Array.from(filterPool.getElementsByTagName('span')).forEach(
    function (element) {
      filterList += element.innerText + ','
    }
  )
  if (filterList === ''){
    filterStringFull = '*'
    fetcher()
  } else {
    filterStringFull = filterList.substring(0,filterList.length - 1)
    console.log(filterStringFull)
    fetcher()
  }
}

filterInput.addEventListener('keyup', function(event) {
  event.preventDefault()
  if (event.keyCode === 13) {
    filterAdd()
  }
})