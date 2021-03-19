function deleteEmploi(id) {
    console.log(id)
    Swal.fire({
        title: 'Vous voulez vraiment archiver cet offre d\'emploi?',
        text: "Cet action est irrversible",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url:'https://127.0.0.1:8000/emploi/delete',
                method:"POST",
                data:{id:id},
                beforeSend:function(){
                    $('#loader').show()
                },
                success:function(data)
                {
                    $('#loader').hide()
                    $('#emploi'+id).hide()
                    Swal.fire(
                        'Supprimé!',
                        'Offre supprimée avec succés.',
                        'success'
                    )
                }
            })

        }
    })
}







function connexionRequired() {
    Swal.fire({
        icon: 'Ereur',
        title: 'Oops...',
        text: 'Veuillez vous authentifier pour pouvoir postuler a cet offre d\'emploi !',
        footer: "<a class='btn btn-outline-success me-2' href='/login'  type='submit'>Connexion</a>\n"
    })
}

function blockPostuler() {
    Swal.fire({
        icon: 'Ereur',
        title: 'Oops...',
        text: 'Vous ne pouvez pas postuler en tant qu\'entreprise !',
       // footer: "<a class='btn btn-outline-success me-2' href='/login'  type='submit'>Connexion</a>\n"
    })
}
function postulerEmploi(id) {
    console.log(id)
    Swal.fire({
        title: 'Vous voulez vraiment postuler pour cet offre d\'emploi?',
        text: "NB: Votre CV sera automatiquement envoyé a l'entreprise concerné",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, postuler',
        cancelButtonText: 'Annuler',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url:'https://127.0.0.1:8000/emploi/postuler',
                method:"POST",
                data:{id:id},
                beforeSend:function(){
                    $('#loader').show()
                },
                success:function(data)
                {
                    $('#loader').hide()
                     Swal.fire(
                        'Envoyé!',
                        'Votre demande a été transmise avec succés!',
                        'success'
                    )
                }
            })

        }
    })
}


function getData() {
    var id=$('#categorie').val()
    window.location.href = "/emploi/list?categorie="+id;

}
