# Request examples

### Load specific record (by row Primary Key - ID):

```
http://drupal.docker.localhost/api/pets_owners?_format=json&id=1 
```
### Edit specific record (by row Primary Key - ID):

```
 http://drupal.docker.localhost/api/pets_owners/edit
```
Body for POST method (in JSON format):  
```JSON
{
    "id": "1",
    "name": "Tom",
    "gender": "Cruise",
    "prefix": "mr",
    "age": "31",
    "mother_name": "Lola",
    "father_name": "Thomas",
    "have_pets": "1",
    "pets_name": "Sebek",
    "email": "vip@cruise.tom"
}
```
### Delete specific record (by row Primary Key - ID):
```
http://drupal.docker.localhost/api/pets_owners/?id=1
```
### Load full list of records with pagination possibility and filtering option by age:
```
http://drupal.docker.localhost/api/pets_owners/list?page=1&limit=3&age=15
```
