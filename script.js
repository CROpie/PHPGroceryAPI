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
}

onload = init
