# Task

Create a system that calculates the popularity of a certain word. 
For a given word, the system should search for a GitHub issue using the number of results 
for ```{word} rocks``` as a positive result and ```{word} sucks``` as a negative. 
The result should be a popularity rating of the given word from 0-10 as a ratio of the 
positive result to the total number of results. The results should be saved in a local 
database so that future queries for the same words are faster. 
In the future, the addition/change of providers is expected (eg Twitter will be used 
instead of GitHub), so the system should be designed accordingly.

## Requirements

- GitHub personal access token - [how to get one](https://github.com/settings/tokens)

## Install instruction

1. clone the repository
    ```
    git clone https://github.com/KrasomirMioc/word_popularity.git
    ```
2. copy .env to .env.local
    ```
    cp .env .env.local
    ```
3. insert data into .env.local for DATABASE_URL and GITHUB_TOKEN keys
4. create database
    ```
    bin/console doctrine:database:create
    ```
5. create database table
    ```
    bin/console doctrine:migrations:migrate
    ```
6. start server
    ```
    symfony server:start
    ```

## How to use API

Go to url and add your word to search:
```php
GET https://127.0.0.1:8000/api/v1/words/search?term={your word to search}
```
- and for the search term *'php'* you will get:
```json
{
   "term": "php",
   "score": 4.97
}
```
- for the search term *'javascript*' you will get:
```json
{
    "term": "javascript",
    "score": 4.66
}
```
- if you search for a word *'maslačak*' you will get:
```json
{
    "term": "maslačak",
    "score": 0
}
```
- if you **don't enter a search word**, you will get:
```json
{
   "message": "Parameter term must be present and can not be empty.",
   "status": 422
}
```
- if you **omit GITHUB_TOKEN**, you wil get:
```json
{
   "status": 401,
   "message": "HTTP/2 401  returned for \"https://api.github.com/search/issues?q={your word}"
}
```

### How to change search provider

> - create a new provider that will implement the existing search provider interface ```App\Interface\SearchProviderInterface.php```
> - in ```service.yaml``` in interfaces section change the registration of the existing provider with a new one
> ```yaml
> #interfaces
> # change this line
> App\Interface\SearchProviderInterface: '@App\Provider\GitHubProvider'
> # with this line
> App\Interface\SearchProviderInterface: '@App\Provider\{NewProvider}'
> ```







