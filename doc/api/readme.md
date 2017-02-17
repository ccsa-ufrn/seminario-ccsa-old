### Main Page Content Provider API

#### GET /tgs
About: Returns all listables thematic groups  
Endpoint: ``http://PATH_TO_CCSA/index.php?/api/tgs``  
Method: ``GET``  
Requires: **nothing**
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
    'name': user name,
    'email': user email,
    'subject': message subject,
    'message': message content
}
```
Returns:
```
{
    'status': 'error|success',
    'message': 'about the error|succes message',
    'data': {
        'id': generated message id,
        'name': user name,
        'email': user email,
        'subject': message subject,
        'message': message content
    }
}
```
