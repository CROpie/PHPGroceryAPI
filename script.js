async function handleSaveEditItem(itemsData, i) {
  const JWT = sessionStorage.getItem('token')

  // no JWT so don't bother connecting to the backend
  if (!JWT) {
    // print not logged in message
    return
  }

  const editedItemData = {}

  for (const key of Object.keys(itemsData[i])) {
    editedItemData[key] = document.getElementById(`input-${key}-${i}`).value
  }

  const response = await fetch('/api/data/items', {
    method: 'PUT',
    body: JSON.stringify(editedItemData),
    headers: {
      'Content-Type': 'application/json',
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

function handleEditItem(itemsData, i) {
  renderItems(itemsData)

  // insert input into td's
  for (const [key, value] of Object.entries(itemsData[i])) {
    const td = document.getElementById(`${key}-${i}`)
    td.innerHTML = `
    <input 
      type=${typeof value === 'string' ? 'text' : 'number'}
      id='input-${key}-${i}' 
      value=${value}
      ${key === 'itemId' && 'disabled'}
    >`
  }

  // replace edit button with cancel & save buttons
  const editTd = document.getElementById(`edit-${i}`)
  editTd.innerHTML = `
    <button type="button" id="cancelBtn">Cancel></button>
    <button type="button" id="saveBtn">Save></button>
  `

  document.getElementById('cancelBtn').addEventListener('click', () => renderItems(itemsData))
  document
    .getElementById('saveBtn')
    .addEventListener('click', () => handleSaveEditItem(itemsData, i))
}

async function handleAddItem(event) {
  event.preventDefault()

  const JWT = sessionStorage.getItem('token')

  // no JWT so don't bother connecting to the backend
  if (!JWT) {
    // print not logged in message
    return
  }

  const formData = new FormData(event.target)

  const response = await fetch('/api/data/items', {
    method: 'POST',
    body: formData,
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

  // edit button
  const thEdit = document.createElement('th')
  thEdit.textContent = 'edit'
  headerRow.appendChild(thEdit)

  // delete button
  const thDel = document.createElement('th')
  thDel.textContent = 'del'
  headerRow.appendChild(thDel)

  itemsTableHeader.appendChild(headerRow)

  // render table body
  for (let i = 0; i < itemsData.length; i++) {
    const bodyRow = document.createElement('tr')

    for (const [key, value] of Object.entries(itemsData[i])) {
      const td = document.createElement('td')
      td.textContent = value
      td.id = `${key}-${i}`
      bodyRow.appendChild(td)
    }

    // edit button
    const editTd = document.createElement('td')
    editTd.id = `edit-${i}`

    const editBtn = document.createElement('button')
    editBtn.textContent = 'E'
    editBtn.addEventListener('click', () => handleEditItem(itemsData, i))

    editTd.appendChild(editBtn)
    bodyRow.appendChild(editTd)

    // delete button
    const delTd = document.createElement('td')
    delTd.id = `del-${i}`

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
  document.getElementById('addItemForm').addEventListener('submit', handleAddItem)
}

onload = init
