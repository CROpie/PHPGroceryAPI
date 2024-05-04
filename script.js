async function handleAddItemXml(event) {
  event.preventDefault()

  const JWT = sessionStorage.getItem('token')

  // no JWT so don't bother connecting to the backend
  if (!JWT) {
    // print not logged in message
    return
  }

  const formData = new FormData(event.target)

  // generating XML using a template string
  let xmlData = `
    <item>
      <name>${formData.get('itemname')}</name>
      <amount>${formData.get('itemamount')}</amount>
    </item>
  `

  // generating XML using DOM tree
  let xmlDoc = document.implementation.createDocument('', 'item', null)
  const root = xmlDoc.documentElement
  const nameElem = xmlDoc.createElement('name')
  nameElem.textContent = formData.get('itemname')
  root.appendChild(nameElem)

  const amountElem = xmlDoc.createElement('amount')
  amountElem.textContent = formData.get('itemamount')
  root.appendChild(amountElem)

  const xmlString = new XMLSerializer().serializeToString(xmlDoc)

  // console.log(xmlData)
  // console.log(xmlString)

  // the only difference between the two is the amount of whitespace
  // whitespace between elements in XML doesn't matter, but inside elements does
  // therefore xmlData and xmlString are considered identical. Both work for posting the data.

  const response = await fetch('/api/data/xmlitems', {
    method: 'POST',
    body: xmlData,
    headers: {
      Authorization: `Bearer ${JWT}`,
      'Content-Type': 'application/xml',
    },
  })

  if (!response.ok) {
    console.log('something went wrong...')
    return
  }

  // get the response as text
  const xmlText = await response.text()

  // Use JSON.stringify to visualize whitespace and newlines
  // Using this command located two newline characters which was preventing the XML from being handled properly
  console.log(`Raw XML Response:`, JSON.stringify(xmlText))

  /* xmlText looked like this:
  \n\n
  <?xml version="1.0"?>
  <root>
    <success>1</success>
    <message />
    <data>
      <item>
        <itemId>4</itemId>
        <name>apples</name>
        <amount>11</amount>
      </item>

      <item>
        <itemId>5</itemId>
        <name>tomatoes</name>
        <amount>8</amount>
      </item>
    </data>
  </root>

  */

  // parse the XML text into a DOM-like structure
  const parser = new DOMParser()
  const xmlResponseDoc = parser.parseFromString(xmlText, 'application/xml')
  const dataTag = xmlResponseDoc.getElementsByTagName('data')[0]
  const xmlItems = dataTag.getElementsByTagName('item')
  console.log(xmlItems)

  // process the XML
  const items = []

  for (const xmlItem of xmlItems) {
    const itemId = parseInt(xmlItem.getElementsByTagName('itemId')[0].textContent, 10)
    const name = xmlItem.getElementsByTagName('name')[0].textContent
    const amount = parseInt(xmlItem.getElementsByTagName('amount')[0].textContent, 10)

    items.push({ itemId, name, amount })
  }

  renderItems(items)
}

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
  document.getElementById('addItemFormXml').addEventListener('submit', handleAddItemXml)
}

onload = init
