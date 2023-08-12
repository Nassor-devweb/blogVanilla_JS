import '../assets/styles/styles.scss'

const form = document.querySelector('form');

form.addEventListener('submit', (e) => {
    e.preventDefault()
    const user_password = document.querySelector('#password').value;
    const user_email = document.querySelector('#email').value
    const data_user = { user_email, user_password };
    fetch('http://localhost:3000/routes/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data_user)
    })
        .then((resp) => {
            if (resp.ok) {
                location.href = './index.html'
            } else if (resp.status === 401) {
                resp.json()
                    .then((data) => {
                        console.log(data)
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            }
        })
        .catch((err) => {
            console.log(err)
        })
})