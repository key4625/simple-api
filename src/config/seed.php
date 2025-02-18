<?php
require_once 'database.php';

$posts = [
    [
        'title' => 'Notizia 1',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 1'
    ],
    [
        'title' => 'Notizia 2',
       'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 2'
    ],
    [
        'title' => 'Notizia 3',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 3'
    ],
    [
        'title' => 'Notizia 4',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 4'
    ],
    [
        'title' => 'Notizia 5',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 5'
    ],
    [
        'title' => 'Notizia 6',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 1'
    ],
    [
        'title' => 'Notizia 7',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 2'
    ],
    [
        'title' => 'Notizia 8',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 3'
    ],
    [
        'title' => 'Notizia 9',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 4'
    ],
    [
        'title' => 'Notizia 10',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 5'
    ],
    [
        'title' => 'Notizia 11',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 1'
    ],
    [
        'title' => 'Notizia 12',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 2'
    ],
    [
        'title' => 'Notizia 13',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 3'
    ],
    [
        'title' => 'Notizia 14',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 4'
    ],
    [
        'title' => 'Notizia 15',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo.',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 5'
    ],
    [
        'title' => 'Notizia 16',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo. 16',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 1'
    ],
    [
        'title' => 'Notizia 17',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo. 17',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 2'
    ],
    [
        'title' => 'Notizia 18',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo. 18',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 3'
    ],
    [
        'title' => 'Notizia 19',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo. 19',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 4'
    ],
    [
        'title' => 'Notizia 20',
        'content' => 'Questo è un contenuto più lungo per la notizia 1. Include più dettagli e informazioni per fornire una descrizione completa e approfondita della notizia. Può includere citazioni, statistiche, e altri elementi rilevanti per rendere il contenuto più interessante e informativo. 20',
        'image' => 'https://picsum.photos/800/600',
        'category' => 'Categoria 5'
    ]
];

$users = [
    [
        'email' => 'test@email.com',
        'username' => 'test',
        'password' => password_hash('test', PASSWORD_BCRYPT),
        'privacy_accepted' => 1
    ],
    [
        'email' => 'admin@email.com',
        'username' => 'admin',
        'password' => password_hash('admin', PASSWORD_BCRYPT),
        'privacy_accepted' => 1
    ]
];

try {
    $db->beginTransaction();

    // Inserisci i post
    $stmt = $db->prepare("INSERT INTO posts (title, content, image, category) VALUES (:title, :content, :image, :category)");

    foreach ($posts as $post) {
        $stmt->bindParam(':title', $post['title']);
        $stmt->bindParam(':content', $post['content']);
        $stmt->bindParam(':image', $post['image']);
        $stmt->bindParam(':category', $post['category']);
        $stmt->execute();
    }

    // Inserisci gli utenti
    $stmt = $db->prepare("INSERT INTO users (email, password, privacy_accepted) VALUES (:email, :password, :privacy_accepted)");

    foreach ($users as $user) {
        $stmt->bindParam(':email', $user['email']);
        $stmt->bindParam(':password', $user['password']);
        $stmt->bindParam(':privacy_accepted', $user['privacy_accepted']);
        $stmt->execute();
    }

    $db->commit();
    echo "20 notizie e 1 utente inseriti con successo.";
} catch (PDOException $e) {
    $db->rollBack();
    echo "Error: " . $e->getMessage();
}
?>