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
- POST /login - Effettua il login e ottieni un token di autorizzazione.
- POST /verify-token - Verifica la validità di un token JWT.

Per modificare gli endpoint e aggiungere nuove funzionalità, modifica il file src/routes/api.php.

## Gestione immagini

Il campo `image` dei post supporta due modalità:

| Modalità | Come si invia | Cosa viene salvato nel DB |
|---|---|---|
| **Upload file** | `multipart/form-data` con il campo `image` come file | URL assoluto del file salvato in `uploads/` (es. `https://your_url/uploads/img_xxx.jpg`) |
| **URL stringa** | Campo `image` come testo in `$_POST` o JSON | La stringa URL così com'è (es. un indirizzo remoto) |

**Formati accettati per l'upload:** `jpg`, `jpeg`, `png`, `gif`, `webp`  
**Dimensione massima:** 5 MB  
**Risposta:** oltre ai campi del post, viene restituito il campo `image` con l'URL finale.

### Comportamento su aggiornamento (PUT)
- Se si include un nuovo file, il vecchio file locale viene cancellato automaticamente e sostituito.
- Se si omette il campo `image`, l'immagine esistente viene mantenuta invariata.
- Se l'immagine precedente era un URL remoto (non hosted su questa API) non viene toccata.

### Comportamento su cancellazione (DELETE)
- Se il campo `image` del post contiene un file salvato nella cartella `uploads/` locale, il file viene eliminato dal server.
- Se contiene un URL remoto (host diverso), non viene eseguita nessuna operazione sul file.

---

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
Crea un nuovo post con file immagine (autorizzazione con JWT):
```
curl -X POST http://localhost:8000/posts \
  -H "Authorization: Bearer your_jwt_token" \
  -F "title=Nuovo Post" \
  -F "content=Contenuto del nuovo post" \
  -F "image=@/path/to/your/image.jpg" \
  -F "category=Categoria 1"
```
Risposta:
```json
{ "id": 1, "image": "https://your_url/uploads/img_xxx.jpg" }
```

Crea un nuovo post con URL immagine remota:
```
curl -X POST http://localhost:8000/posts \
  -H "Authorization: Bearer your_jwt_token" \
  -H "Content-Type: application/json" \
  -d '{"title":"Nuovo Post","content":"Contenuto","image":"https://example.com/foto.jpg","category":"Categoria 1"}'
```

Aggiorna un post esistente (sostituendo il file immagine)
```
curl -X PUT http://localhost:8000/posts/1 \
  -H "Authorization: Bearer your_secret_token" \
  -F "title=Post Aggiornato" \
  -F "content=Contenuto aggiornato" \
  -F "image=@/path/to/your/new_image.jpg" \
  -F "category=Categoria 2"
```

Aggiorna un post mantenendo l'immagine esistente (ometti il campo image):
```
curl -X PUT http://localhost:8000/posts/1 \
  -H "Authorization: Bearer your_secret_token" \
  -F "title=Post Aggiornato" \
  -F "content=Contenuto aggiornato" \
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

Verifica un token JWT:
```
curl -X POST http://localhost:8000/verify-token \
  -H "Content-Type: application/json" \
  -d '{"token": "your_jwt_token"}'
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

Crea un nuovo post con file immagine:
```javascript
const formData = new FormData();
formData.append('title', 'Nuovo Post');
formData.append('content', 'Contenuto del nuovo post');
formData.append('image', document.querySelector('input[type="file"]').files[0]); // File oggetto
formData.append('category', 'Categoria 1');

fetch('http://localhost:8000/posts', {
  method: 'POST',
  headers: { 'Authorization': 'Bearer your_jwt_token' },
  // NON impostare Content-Type: multipart/form-data viene gestito automaticamente da FormData
  body: formData
})
  .then(response => response.json())
  .then(data => console.log('Post creato, immagine:', data.image)) // data.image = URL salvato
  .catch(error => console.error('Errore:', error));
```

Crea un nuovo post con URL immagine remota:
```javascript
fetch('http://localhost:8000/posts', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer your_jwt_token',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    title: 'Nuovo Post',
    content: 'Contenuto del nuovo post',
    image: 'https://example.com/foto.jpg', // URL remoto, non viene scaricato né modificato
    category: 'Categoria 1'
  })
})
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Errore:', error));
```

Verifica un token:
```javascript
fetch('http://localhost:8000/verify-token', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ token: 'your_jwt_token' })
})
  .then(response => response.json())
  .then(data => {
    if (data.valid) {
      console.log('Token valido:', data.data);
    } else {
      console.error('Token non valido:', data.message);
    }
  })
  .catch(error => console.error('Errore:', error));
```

## Sicurezza
Gli endpoint di creazione, modifica e cancellazione richiedono un token di autorizzazione JWT. Modifica il file `.env` per aggiungere il tuo `SECRET_TOKEN`. Assicurati di mantenere il token segreto e non condividerlo con nessuno.
Questo è un livello di sicurezza di base. Per un'applicazione più complessa, considera l'utilizzo di un sistema di autenticazione più avanzato come OAuth o JWT.
L'inserimento di files è limitato a immagini di tipo jpg, jpeg, png e gif con dimensioni massime di 5MB.
Tutti gli endpoint sono protetti da attacchi sql injection grazie all'utilizzo di prepared statements.