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
   ``
3. Crea il database e la tabella eseguendo il file database.php:
   ```
   php src/config/database.php
   ```
4. Popola il database con dati di esempio eseguendo il file seed.php:
   ```
   php src/config/seed.php
   ```
5. Nella root del progetto rinomina il file env.example in .env  e aggiungi il token segreto:
   ```
   SECRET_TOKEN=your_secret_token
   ```


## Utilizzo
Avvia il server locale:
```
php -S localhost:8000 -t src/routes
```
Accedi all'API tramite il browser o strumenti come Postman.


## Endpoint API

- GET /simple-api/posts - Recupera tutti i post.
- GET /simple-api/posts/{id} - Recupera un post specifico.
- POST /simple-api/posts - Crea un nuovo post.
- PUT /simple-api/posts/{id} - Aggiorna un post esistente.
- DELETE /simple-api/posts/{id} - Elimina un post.

Per modificare gli endpoint e aggiungere nuove funzionalità, modifica il file src/routes/api.php.

# Registrazione
curl -X POST http://localhost:8000/simple-api/register \
  -H "Content-Type: application/json" \
  -d '{"email": "test@email.com", "password": "test"}'

# Login
curl -X POST http://localhost:8000/simple-api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "test@email.com", "password": "test"}'

## Esempi di richieste cURL
Recupera tutti i post

```
curl -X GET http://localhost:8000/simple-api/posts
```
Recupera un post specifico
```
curl -X GET http://localhost:8000/simple-api/posts/1
```
Crea un nuovo post
```
curl -X POST http://localhost:8000/simple-api/posts \
  -H "Authorization: Bearer your_jwt_token" \
  -F "title=Nuovo Post" \
  -F "content=Contenuto del nuovo post" \
  -F "image=@/path/to/your/image.jpg" \
  -F "category=Categoria 1"
```
Aggiorna un post esistente
```
curl -X PUT http://localhost:8000/simple-api/posts/1 \
  -H "Authorization: Bearer your_secret_token" \
  -F "title=Post Aggiornato" \
  -F "content=Contenuto aggiornato" \
  -F "image=@/path/to/your/image.jpg" \
  -F "category=Categoria 2"
```
Elimina un post
```
curl -X DELETE http://localhost:8000/simple-api/posts/1 \
  -H "Authorization: Bearer your_secret_token"
``
```


## Sicurezza
Gli endpoint di creazione, modifica e cancellazione richiedono un token di autorizzazione. Modifica il file .env per aggiungere il tuo token segreto. Assicurati di mantenere il token segreto e non condividerlo con nessuno.
Questo è un livello di sicurezza di base. Per un'applicazione più complessa, considera l'utilizzo di un sistema di autenticazione più avanzato come OAuth o JWT.
L'inserimento di files è limitato a immagini di tipo jpg, jpeg, png e gif con dimensioni massime di 5MB.
Tutti gli endpoint sono protetti da attacchi sql injection grazie all'utilizzo di prepared statements.