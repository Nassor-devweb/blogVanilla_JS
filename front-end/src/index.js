import './assets/styles/styles.scss'

const section = document.querySelector('.content')

function affichage(data_article) {
    const allArticle = data_article.map((curr) => {
        const li = document.createElement('li')
        li.classList.add('article')

        const photo_user = document.createElement('img')
        photo_user.src = `${curr.user_photo}`;
        photo_user.alt = 'photo de profil';

        const title = document.createElement('h2');
        title.textContent = `${curr.category_article}`;
        console.log(curr.date_created)
        const span = document.createElement('span');
        const date = curr.date_created;
        span.textContent = date.toLocaleString('fr-FR');

        const content = document.createElement('p')
        content.textContent = `${curr.content_article}`

        const buttonContenaire = document.createElement('div')
        buttonContenaire.classList.add('btn-content')

        const button_primary = document.createElement('button')
        button_primary.classList.add('btn')
        button_primary.classList.add('btn-primary')
        button_primary.textContent = 'Supprimer';

        const button_secondary = document.createElement('button')
        button_secondary.classList.add('btn')
        button_secondary.classList.add('btn-secondary')
        button_secondary.textContent = 'Modifier'

        buttonContenaire.append(button_secondary, button_primary)

        li.append(photo_user, title, span, content, buttonContenaire);
        return li
    })

    const ul = document.createElement('ul');
    ul.classList.add('article_contenaire');
    ul.append(...allArticle);
    section.innerHTML = '';
    section.append(ul)
}

function getAllArticle() {
    fetch('http://localhost:3000/routes/index.php')
        .then((resp) => {
            resp.json()
                .then((data) => {
                    console.log(data)
                    affichage(data)
                })
                .catch((err) => {
                    console.log(err)
                })
        })
        .catch((err) => {
            console.log(err)
        })
}

getAllArticle()