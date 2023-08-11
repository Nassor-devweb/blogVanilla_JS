import './assets/styles/styles.scss'


function getAllArticle(){
    fetch('http://localhost:3000/index.php')
    .then((resp) => {
        resp.json()
        .then((data) => {
            console.log(data)
        })
        .catch((err) =>{
            console.log(err)
        })
    })
    .catch((err) =>{
        console.log(err)
    })
}

getAllArticle()