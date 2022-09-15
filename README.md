<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Hello again, my name is Jonathan and today I'm learning GraphQl

For this project you must have php 8+ installed and Laravel 9+, or you can just install GraphQl in your project with the comands below...

run the database migrations with
#
```
php artisan migrate
```
#
Create some fake users in the database with
#
```
php artisan tinker
\App\Models\User::factory(10)->create()
```
#
To install graphql you use
#
```
composer require graphql
```
#
But I have used with npm
#

```
npm install graphql --save
```
#
then you install nuwave/lighthouse package with
#
```composer require nuwave/lighthouse```
#
```php artisan lighthouse:ide-helper```
#
To get the Ide
#
and the preview of lighthouse
#
```
php artisan vendor:publish --tag=lighthouse-config
```
that`s not nedeed, but if you wanna get the basic of lighthouse you should read it
#
To have a interactive playground source install
```
composer require mll-lab/laravel-graphiql
```
######And have fun


After you set you server up ```php artisan serve``` on ```localhost/graphql-playground``` test the commands

Don`t forget to have the Type of Model used on schema.graphql, like ->
```
type User {
    id: ID
    name: String
    email: String
}
```
#
1. For get one specific user
```
{
  user(id:3){
    name,
  }
}
```
On Schema
```
type Query {
    user(id: ID @Eq): User @find
```
#
2. For get all users
```
{  
  users{
    name,
    id,
  }
}
```
On Schema
```
type Query {
    users: [User!]! @all,
}

```
#
3. For paginate a query serach
```
{
    users_paginate(first:2, page:3){
        data{
            name,
            id
    },
    paginatorInfo{
      hasMorePages,
      total,
      currentPage
    }
  }
}
```
On Schema
```
type Query {
    users_paginate: [User!]! @paginate
}
```
#
4. For create a User
```
mutation{
  createUser(
    name: "Joohny", 
    email: "joohnyfff@gmail.com"
  	password: "123456"
  ){
    name
  }
}
```
On Schema
```
type Mutation {
    createUser(
        name: String!
        email: String!
        password: String!
        ): User! @create
}
```
