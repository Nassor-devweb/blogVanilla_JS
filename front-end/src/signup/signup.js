import '../assets/styles/styles.scss'

const form = document.querySelector('.data-form')

form.addEventListener('submit', async (e) => {
    e.preventDefault()
    const user_name = document.querySelector('#user_name').value
    const user_email = document.querySelector('#user_email').value
    const user_password = document.querySelector('#user_password').value
    const user_photo = document.querySelector('#user_photo').value
    const formdata = new FormData()
    formdata.append('user_name', user_name)
    formdata.append('user_email', user_email)
    formdata.append('user_password', user_password)
    formdata.append('user_photo', user_photo)
    try {
        const querrySaveUser = await fetch('http://localhost:3000/signup.php', {
            method: 'POST',

            body: formdata
        })
        console.log(querrySaveUser)
    } catch (err) {
        console.log(err)
    }

})