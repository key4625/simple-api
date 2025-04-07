# Simple API

Questa è una semplice applicazione API PHP che utilizza SQLite per la gestione dei dati.

## Requisiti

- PHP 8.1 o superiore
- Composer

## Installazione

1. Clona il repository:
   ```sh
   git clone https://github.com/keysol/simple-api.git
   cd simple-api
   ```
2. Installa le dipendenze con Composer:
   ```
   composer install
   ```
3. Crea il database e la tabella eseguendo il file database.php:
   ```
   php src/config/database.php
   ```
4. Popola il database con dati di esempio eseguendo il file seed.php:
   ```
   php src/config/seed.php
   ```
5. Copia il file `example.env` e rinominalo in `.env`, quindi modifica i valori secondo le tue esigenze:
   ```
   cp example.env .env
   ```
   Modifica il contenuto del file `.env` per includere il tuo token segreto e l'URL remoto:
   ```
   SECRET_TOKEN=your_secret_token
   REMOTE_URL="https://your_url/"
   ```

## Utilizzo
Accedi all'API tramite il browser o strumenti come Postman.


## Endpoint API

- GET /posts - Recupera tutti i post.
- GET /posts/{id} - Recupera un post specifico.
- POST /posts - Crea un nuovo post.
- PUT /posts/{id} - Aggiorna un post esistente.
- DELETE /posts/{id} - Elimina un post.
- POST /register - Registra un nuovo utente (richiede `username`, `email`, `password`, `user_type` e `privacy_accepted`).  
  **Nota:** La logica di registrazione non è ancora implementata.
- POST /login - Effettua il login e ottieni un token di autorizzazione.

Per modificare gli endpoint e aggiungere nuove funzionalità, modifica il file src/routes/api.php.

# Registrazione
curl -X POST http://localhost:8000/register \
  -H "Content-Type: application/json" \
  -d '{"username": "test", "email": "test@email.com", "password": "test", "user_type": "user", "privacy_accepted": 1}'

# Login
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"email": "test@email.com", "password": "test"}'

## Esempi di richieste cURL
Recupera tutti i post

```
curl -X GET http://localhost:8000/posts
```
Recupera un post specifico
```
curl -X GET http://localhost:8000/posts/1
```
Crea un nuovo post (autorizzazione con JWT):
```
curl -X POST http://localhost:8000/posts \
  -H "Authorization: Bearer your_jwt_token" \
  -F "title=Nuovo Post" \
  -F "content=Contenuto del nuovo post" \
  -F "image=@/path/to/your/image.jpg" \
  -F "category=Categoria 1"
```
Aggiorna un post esistente
```
curl -X PUT http://localhost:8000/posts/1 \
  -H "Authorization: Bearer your_secret_token" \
  -F "title=Post Aggiornato" \
  -F "content=Contenuto aggiornato" \
  -F "image=@/path/to/your/image.jpg" \
  -F "category=Categoria 2"
```
Elimina un post
```
curl -X DELETE http://localhost:8000/posts/1 \
  -H "Authorization: Bearer your_secret_token"
```

Registra un nuovo utente
```
curl -X POST http://localhost:8000/register \
  -H "Content-Type: application/json" \
  -d '{"username": "test", "email": "test@email.com", "password": "test", "user_type": "user", "privacy_accepted": 1}'
```

Effettua il login
```
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"email": "test@email.com", "password": "test"}'
```

## Esempi di utilizzo con JavaScript (Fetch API)

Recupera tutti i post:
```javascript
fetch('http://localhost:8000/posts')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Errore:', error));
```

Registra un nuovo utente:
```javascript
fetch('http://localhost:8000/register', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ username: 'test', email: 'test@email.com', password: 'test', user_type: 'user', privacy_accepted: 1 })
})
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Errore:', error));
```

Effettua il login:
```javascript
fetch('http://localhost:8000/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ email: 'test@email.com', password: 'test' })
})
  .then(response => response.json())
  .then(data => console.log('Token:', data.token))
  .catch(error => console.error('Errore:', error));
```

Crea un nuovo post:
```javascript
const formData = new FormData();
formData.append('title', 'Nuovo Post');
formData.append('content', 'Contenuto del nuovo post');
formData.append('image', document.querySelector('input[type="file"]').files[0]);
formData.append('category', 'Categoria 1');

fetch('http://localhost:8000/posts', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer your_jwt_token' },
  body: formData
})
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Errore:', error));
```

## Sicurezza
Gli endpoint di creazione, modifica e cancellazione richiedono un token di autorizzazione JWT. Modifica il file `.env` per aggiungere il tuo `SECRET_TOKEN`. Assicurati di mantenere il token segreto e non condividerlo con nessuno.
Questo è un livello di sicurezza di base. Per un'applicazione più complessa, considera l'utilizzo di un sistema di autenticazione più avanzato come OAuth o JWT.
L'inserimento di files è limitato a immagini di tipo jpg, jpeg, png e gif con dimensioni massime di 5MB.
Tutti gli endpoint sono protetti da attacchi sql injection grazie all'utilizzo di prepared statements.