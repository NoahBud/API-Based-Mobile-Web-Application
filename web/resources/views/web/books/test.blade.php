{{-- <input type="text" id="isbnInput" placeholder="Entrez un ISBN">
<button onclick="fetchBook()">Rechercher</button>
<div id="bookInfo"></div>

<script>
function fetchBook() {
    let isbn = document.getElementById("isbnInput").value;

    fetch('http://127.0.0.1:8000/api/books/isbn/${isbn}')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            let authors = "Non renseigné";
            if (data.authors && data.authors.length > 0) {
                authors = data.authors.map(author => author.name).join(", ");
            }

            document.getElementById("bookInfo").innerHTML = `
                <h3>${data.book.name}</h3>
                <p><strong>ISBN :</strong> ${data.book.ISBN}</p>
                <p><strong>Genre :</strong> ${data.book.genre || "Non renseigné"}</p>
                <p><strong>Description :</strong> ${data.book.description || "Aucune description disponible"}</p>
                <p><strong>Éditeur :</strong> ${data.book.editor ? data.book.editor.name : "Non renseigné"}</p>
                <p><strong>Auteur(s) :</strong> ${authors || "Aucun auteur renseigné"}</p>
            `;
        })
        .catch(error => {
            console.error("Erreur:", error);
            document.getElementById("bookInfo").innerHTML = `<p style="color:red;">Erreur: ${error.message}</p>`;
        });
}
</script> --}}
