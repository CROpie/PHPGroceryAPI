async function handleDelItem(id) {
  const JWT = sessionStorage.getItem('token')

  // no JWT so don't bother connecting to the backend
  if (!JWT) {
    // print not logged in message
    return
  }

  const response = await fetch(`/api/data/items?id=${id}`, {
    method: 'DELETE',
    headers: {
      Authorization: `Bearer ${JWT}`,
    },
  })

  if (!response.ok) {
    console.log('something went wrong...')
    return
  }

  const json = await response.json()
  console.log(json)

  if (!json.success) return

  renderItems(json.data)
}

// written to be dynamic, as in will print out a variable number of table headers and table body rows,
// based on the database table itself
function renderItems(itemsData) {
  console.log('rendering items')

  const itemsTableHeader = document.getElementById('itemsTableHeader')
  const itemsTableBody = document.getElementById('itemsTableBody')

  itemsTableHeader.innerHTML = ''
  itemsTableBody.innerHTML = ''

  if (itemsData.length === 0) {
    itemsTableHeader.innerHTML = '<p>No data to display.</p>'
    return
  }

  const itemKeys = Object.keys(itemsData[0])

  const headerRow = document.createElement('tr')

  // render table header
  for (let i = 0; i < itemKeys.length; i++) {
    const th = document.createElement('th')
    th.textContent = itemKeys[i]
    headerRow.appendChild(th)
  }
  // delete button
  const th = document.createElement('th')
  th.textContent = 'del'
  headerRow.appendChild(th)

  itemsTableHeader.appendChild(headerRow)

  // render table body
  for (let i = 0; i < itemsData.length; i++) {
    const bodyRow = document.createElement('tr')

    for (const value of Object.values(itemsData[i])) {
      const td = document.createElement('td')
      td.textContent = value
      bodyRow.appendChild(td)
    }

    // delete button
    const delTd = document.createElement('td')
    const delBtn = document.createElement('button')
    delBtn.textContent = 'X'
    delBtn.addEventListener('click', () => handleDelItem(itemsData[i][itemKeys[0]]))

    delTd.appendChild(delBtn)
    bodyRow.appendChild(delTd)

    itemsTableBody.appendChild(bodyRow)
  }
}

async function handleGetItems() {
  const JWT = sessionStorage.getItem('token')

  // no JWT so don't bother connecting to the backend
  if (!JWT) {
    // print not logged in message
    return
  }

  const response = await fetch('/api/data/items', {
    headers: {
      Authorization: `Bearer ${JWT}`,
    },
  })

  if (!response.ok) {
    console.log('something went wrong...')
    return
  }

  const json = await response.json()
  console.log(json)

  if (!json.success) return

  renderItems(json.data)
}

async function handleLogout() {
  const JWT = sessionStorage.getItem('token')

  // no user to log out anyway
  if (!JWT) return

  sessionStorage.removeItem('token')

  const response = await fetch(`/api/auth/logout?token=${JWT}`)

  if (!response.ok) {
    console.log('something went wrong...')
    return
  }

  const json = await response.json()
  console.log(json)
}

async function handleLogin(event) {
  event.preventDefault()

  console.log(event.target)

  const formData = new FormData(event.target)

  const response = await fetch('/api/auth/login', {
    method: 'POST',
    body: formData,
  })

  if (!response.ok) {
    console.log('something went wrong...')
    return
  }

  const json = await response.json()
  console.log(json)

  if (json.success) {
    sessionStorage.setItem('token', json.JWT)
  }

  //   if (!json.success) {
  //     document.getElementById('errorMsg').textContent = json.message
  //     return
  //   }

  // document.getElementById('errorMsg').textContent = 'Successfully logged in.'
}

async function handleRegister(event) {
  event.preventDefault()

  console.log(event.target)

  const formData = new FormData(event.target)

  const response = await fetch('/api/auth/register', {
    method: 'POST',
    body: formData,
  })

  if (!response.ok) {
    console.log('something went wrong...')
    return
  }

  const json = await response.json()
  console.log(json)

  //   if (!json.success) {
  //     document.getElementById('errorMsg').textContent = json.message
  //     return
  //   }

  // document.getElementById('errorMsg').textContent = 'Successfully logged in.'
}

function init() {
  document.getElementById('registerForm').addEventListener('submit', handleRegister)
  document.getElementById('loginForm').addEventListener('submit', handleLogin)
  document.getElementById('logoutBtn').addEventListener('click', handleLogout)
  document.getElementById('getItemsBtn').addEventListener('click', handleGetItems)
}

onload = init
