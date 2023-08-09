import '../assets/styles/styles.scss'

const form = document.querySelector('.data-form')

form.addEventListener('submit', async (e) => {
    e.preventDefault()
    const user_name = document.querySelector('#user_name').value
    const user_email = document.querySelector('#user_email').value
    const user_password = document.querySelector('#user_password').value
    const user_photo = document.querySelector('#user_photo').files[0]
    console.dir(user_photo)
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
        if (querrySaveUser.ok) {
            location.href = './login.html'
        } else {
            let msgErreur;
            const response = await querrySaveUser.json();
            // if(querrySaveUser.status === 401){
            //     msgErreur = 
            // }
            msgErreur = response.erreur;
            const err = document.querySelector('.erreur');
            if (err) {
                err.remove()
            }
            let span = document.createElement('span');
            span.classList.add('erreur');
            span.textContent = msgErreur
            form.insertAdjacentElement('beforeend', span)
        }
        console.log(querrySaveUser)
    } catch (err) {
        console.log(err)
    }

})