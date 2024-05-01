async function handleLogout() {
  const userData = JSON.parse(sessionStorage.getItem('userData'))

  // no user to log out anyway
  if (!userData) return

  const response = await fetch(`/api/auth/logout?userId=${userData.userId}`)

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
    sessionStorage.setItem('userData', JSON.stringify(json.userData))
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
}

onload = init
