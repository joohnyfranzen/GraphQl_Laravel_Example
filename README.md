<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Hello again, my name is Jonathan and today I'm learning GraphQl

For this project you must have php 8+ installed and Laravel 9+, or you can just install GraphQl in your project with the comands below...

run the database migrations with
#
```php artisan migrate```
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
```composer require graphql```
#
But I have used with npm
#

```npm install graphql --save```
#
then you install nuwave/lighthouse package with
#
```composer require nuwave/lighthouse```
#
To get the Ide
#
```php artisan lighthouse:ide-helper```
#
and the preview of lighthouse
#
```php artisan vendor:publish --tag=lighthouse-config```
#
that`s not nedeed, but if you wanna get the basic of lighthouse you should read it
#
To have a interactive playground source install
```composer require mll-lab/laravel-graphql```


###### And have fun


After you set you server up 
#
```php artisan serve```
on 
```localhost/graphql-playground```
test the commands

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
#
5. To update a User
#
```
mutation{
  updateUser(
    id: 12,
    name: "Maxini"
  ){
    name
  }
}
```
#
On Schema
#
```
type Mutation {
    updateUser(
        id: ID!,
        name: String,
        email: String,
        password: String
        ): User! @update
}
```
#
6. To delete a User
#
```
mutation{
	deleteUser(id:12){
    name
  }
}
```
#
On Schema
#
```
type Mutation {
    deleteUser(
        id: ID!
    ):User @delete
}
```
#
7. To overwrite an User
#
```
mutation{
	upsertUser(
    id:12, 
    name:"Josshny", 
    email:"1234@gmail.com", 
    password: "123556"){
    name,
    email,
    id
}
```
#
On Schema
#
```
type Mutation {
    upsertUser(
        id: ID!
        name: String!
        email:String!
        password: String!
    ): User! @upsert
}
```
#
For continue you need to add a Model, lets call it Post, -m to simplify it and made a migration
#
```php artisan make:model Post -m```
#
On Post migration add the fields title as string, content as text and user_id as foreign key just like these:
#
```
$table->string('title');
$table->string('content');         $table->foreignId('user_id')->constrained()->cascadeOnDelete();
```
#
Run php artisan migrate
#
Use the command to create PostFactory
#
```php artisan make:factory PostFactory -m Post```
#
In your PostFactory you will need to set the creation Model, just like you have set in your migration, it will be like:
#
```
'title' => $this->faker->sentence(),
'content' => $this->faker->paragraph(),
'user_id' => function(){
return User::factory()->create()->id;
```
#
All right, almost forgot, you need to import user Model...
#
Now you can make use of your PostFactory with Tinker
#
```
php artisan tinker
Post::factory()->count(2)->create()
```
After created on ```localhost/graphql-playground```
#
Query for the posts
#
```
{
	posts{
  	title,
  	content,
	}
}
```
#
With Schema like
#
```
type Query {
    posts: [Post!]! @all
}

type Post {
    id: ID
    title: String
    content: String
}
```
#
If we want to get a Relation for post with the user who created it, in Post model you need to set the Relation between them.
#
```
public function author()
{
    return $this->belongsTo(User::class, 'user_id');
}
```
#
The user_id field refers to what column should be used to return the information about relation...
#
Now on schema you should add a callback function to Query it
#
```
type Post {
    author: User @belongsTo
}
```
#
And in the graphql playground you can request to posts the author name, just like this
#
```
{
    posts{
       title,
       author{
            name
        }
	}
}
```
#
It should return the title with the name of who writed the post
#
Now we should try to get posts by user...
In the User model create a relation function hasMany, like this.
#
```
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
```
#
On you GraphQl Schema you can rework you User by id to get that relationed posts
#
```
type User {
    posts: [Post]! @hasMany
}

```
#
Now in GraphQl playground type something like this, of course, with the user id and posts that have relation.
#
```
{
	user(id: 18){
        name,
        posts{
            title
        }
	}
}
```
#
Try adding more posts with the factory using your user_id...
#
```php artisan serve tinker```
```Post::factory()->count(5)->create(['user_id'=>10])```
#


Thats all for today
#

