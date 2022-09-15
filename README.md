<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Hello again, my name is Jonathan and today I'm learning GraphQl

For this project you must have php 8+ installed and Laravel 9+, or you can just install GraphQl in your project with the comands below...




After you set you server up "php artisan serve" on "localhost/graphql-playground" test the commands

1. For get one specific user
```
{
  user(id:3){
    name,
  }
}
```
On query
```
type Query {
    user(id: ID @Eq): User @find
```

2. For get all users
{  
  users{
    name,
    id,
  }
}
```
On
```
type Query {
    users: [User!]! @all,
}

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

For create a User
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
