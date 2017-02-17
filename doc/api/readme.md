### Main Page Content Provider API

#### GET /news/[all]
About: returns news  
Endpoint: ``http://PATH_TO_CCSA/index.php?/api/news``  
Method: ``GET``  
Returns:
```
{
    'status': 'error|success',
    'message': 'Erro na conexão ao banco de dados',
    'data': [{
        'title': 'INSCRIÇÃO PARA MONITORES NO SPCCSA',
        'text':'O XXI Seminário de Pesquisa...',
        'created_at': '2017-02-10 11:13:10'
    }]
}
```
- **Note**: The default request '/news' returns only 3 news: one fixed (the first one)
And two others no fixed news. Using the slug /all appended to url it returns all news
From database.

#### POST /login
About: log in the system  
Endpoint: ``http://PATH_TO_CCSA/index.php?/api/login``  
Method: ``POST``  
Requires:
```
{
    'email': 'mail@example.com',
    'pass': 'user_pass'
}
```
Returns:
```
{
    'status': 'error|success',
    'message': 'successfuly logged in'
}
```

#### POST /new_user
About: Record a new user at the system  
Endpoint: ``http://PATH_TO_CCSA/index.php?/api/new_user``  
Method: ``POST``  
Requires:
```
{
    'name': 'User Name',
    'email': 'mail@example.com',
    'cpf': '123.456.789-90',
    'type': 'instructor|student|noacademic',
    'institution': 'UFRN',
    'phone': '84 9 9876-5432',
    'pass': 'user_pass',
    'pass-repeate': 'user_pass'
}
```
Returns:
```
{
    'status': 'error|success',
    'message': 'Usuário cadastrado com sucesso',
    'mail': true|false,
    'data': {
        'id': 42,
        'name': 'User Name',
        'email': 'mail@example.com',
        'type': 'instructor|student|noacademic',
        'institution': 'UFRN'
    }
}
```
- **Note**: The 'mail' field in the response indicate if the confirmation mail has successfuly sent

#### GET /tgs
About: Returns all listables thematic groups  
Endpoint: ``http://PATH_TO_CCSA/index.php?/api/tgs``  
Method: ``GET``  
Returns:
```
{
    'status': 'error|success',
    'data': [
        {
            'name': area name,
            'tgs': [
                {
                    'name': thematic group's name,
                    'syllabus': thematic group's syllabus,
                    'coordinators': string with coordinators names
                }
            ]
        }
    ]
}
```


#### POST /message
About: Record a message from user  
Endpoint: ``http://PATH_TO_CCSA/index.php?/api/message``  
Method: ``POST``  
Requires:
```
{
    'name': 'User Name',
    'email': 'mail@example.com',
    'subject': 'Message subject',
    'message': 'Message content'
}
```
Returns:
```
{
    'status': 'error|success',
    'message': 'about the error|succes message',
    'data': {
        'id': 42,
        'name': 'User Name',
        'email': 'mail@example.com',
        'subject': 'Message subject',
        'message': 'Message content'
    }
}
```
