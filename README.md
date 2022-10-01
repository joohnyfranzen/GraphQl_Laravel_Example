<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


<p align="center"><a href="https://graphql.org/" target="_blank"><img src="https://res.cloudinary.com/codersociety/image/fetch/w_1200,h_900,c_fill/https://cdn.codersociety.com/uploads/graphql-reasons.png" width="400" alt="GraphQl Logo"></a></p>


Hello again, my name is Jonathan and today I'm learning GraphQl

For this project you must have php 8+ installed and Laravel 9+, or you can just install GraphQl in your project with the comands below...
#
run the database migrations with

```php artisan migrate```
#
Create some fake users in the database with

```
php artisan tinker
\App\Models\User::factory(10)->create()
```
#
To install graphql you use

```composer require graphql```
#
But I have used with npm

```npm install graphql --save```
#
then you install nuwave/lighthouse package with
```composer require nuwave/lighthouse```
#
To get the Ide
```php artisan lighthouse:ide-helper```
#
and the preview of lighthouse
```php artisan vendor:publish --tag=lighthouse-config```
#
that`s not nedeed, but if you wanna get the basic of lighthouse you should read it
#
To have a interactive playground source install
```composer require mll-lab/laravel-graphql```


##### And have fun


After you set you server up 
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
On Schema
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
```
mutation{
	deleteUser(id:12){
    name
  }
}
```
On Schema
```
type Mutation {
    deleteUser(
        id: ID!
    ):User @delete
}
```
#
7. To overwrite an User
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
On Schema
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
```php artisan make:model Post -m```
#
On Post migration add the fields title as string, content as text and user_id as foreign key just like these:
```
$table->string('title');
$table->string('content');         $table->foreignId('user_id')->constrained()->cascadeOnDelete();
```
#
Run php artisan migrate
#
Then use the command to create PostFactory
```php artisan make:factory PostFactory -m Post```
#
In your PostFactory you will need to set the creation Model, just like you have set in your migration, it will be like:
```
'title' => $this->faker->sentence(),
'content' => $this->faker->paragraph(),
'user_id' => function(){
return User::factory()->create()->id;
```
#
All right, almost forgot, you need to import user Model...
Now you can make use of your PostFactory with Tinker
```
php artisan tinker
Post::factory()->count(2)->create()
```
After created on ```localhost/graphql-playground```
Query for the posts
```
{
	posts{
  	title,
  	content,
	}
}
```
With Schema like
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
```
public function author()
{
    return $this->belongsTo(User::class, 'user_id');
}
```
The user_id field refers to what column should be used to return the information about relation...
#
Now on schema you should add a callback function to Query it
```
type Post {
    author: User @belongsTo
}
```
#
And in the graphql playground you can request to posts the author name, just like this
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
```
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
```
#
On you GraphQl Schema you can rework your User by id to get that relationed posts
```
type User {
    posts: [Post]! @hasMany
}

```
#
Now in GraphQl playground type something like this, of course, with the user id and posts that have relation.
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
```php artisan serve tinker```
```Post::factory()->count(5)->create(['user_id'=>10])```
#


#### Validation @Rules

On your Schema lets add some validation requirements for the createUser

For email lets add that it should to be provided and email, and it need to be unique

```
type Mutation {
    createUser(
        email: String! @rules(apply: ["email", "unique"])
        ): User! @create
}

```

And For password lets say it should have at least eight characters

```
type Mutation {
    createUser(
        password: String! @rules(apply: ["min:8"])
        ): User! @create
}
```

Now on playground try to create a new User out of that parameters, like these

```
mutation{
  createUser(
    name: "maik",
    email: "maikom.com"
    password: "maik"
  ){
    name
  }
}
```

#### Schema Imports 

For making a clean code thats a good way to separate your Query from schema...
Inside your GraphQl folder create a file named user.graphql,
Now inside of it grab the Query for the user from schema and paste it on user.graphql
```
type Query {
    user(id: ID @Eq): User @find
    users: [User!]! @all
    users_paginate: [User!]! @paginate
}
```

To call it on your schema file you should import it with:
```
#import user.graphql
```
##### IMPORTANT TIP 
To dont overwrite the information imported by the query you need to import in the bottom of the schema.graphql

You can bring the Mutation and Tyoe for user to that user.graphql file

The same aplies to Post, before type query in your two new files apply extended, and the problem of rewriting is gone...
```
extends type Query
```

#### Autentication

In your graphql folder create a new schema called auth.graphql, import it in the schema

in your terminal create a mutation for Login with the command
```
php artisan lighthouse:mutation Login
```
You can find your created file at App/GraphQL/Mutations/Login
Inside of it we are gonna use auth sanctum that is provided in the laravel sanctum autentication
The function gonna be like this
```
        $user = User::where('email', $args['email'])->first();

        if(! $user || ! Hash::check($args['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentils are incorrect.'],
            ]);
        }
        return $user->createToken($args['device'])->plainTextToken;
```

#
Some troubles happens with the authentication method for getting the token, and after a few hours debuggin and googling, I find the problem that crashed autentication...

#### The query for create a new user on user.graphql havent been hashed, and I tried to get a hashed password, so...

For resolve this issue in type Mutation for createUser, you need to add @hash in the end of password, just like, this...
```
type Mutation {
    createUser(
        name: String!
        email: String! @rules(apply: ["email", "unique:users"])
        password: String! @rules(apply: ["min:8"]) @hash
        ): User! @create
```
#
Now you can create a new password and its gonna be hashed...

In your auth.graphql, add a Mutation named login, with email, password and device, yes, device, long explanation.

```
extend type Mutation{
    login(
        email: String!, 
        password: String!
        device: String!
    ): String!
}
```

By the way, I have created a normal controller for Test and Debugg, because the return of Graphql dont accept some terms, and I did not find a reasonable solution to fix it...
...
Now, on your playground call for your email, password, and set device as web, like this...
```
mutation{
  login(
    email: "joohnyfffffff@gmail.com",
    password: "12345678",
    device:"web"
  )
}
```

#### Get Auth Information

Now that we have our token we can provide to the request for getting something hidden for another user...
Lets start with a simple command that give us the name and email of our user...

In your auth.graphql create a Query called me calling the User, then give the @auth and @guard to become protected information...
```
extend type Query {
    me: User! @guard @auth
}
```
Now on your bottom playground open HTTP HEADERS and provide your bearer token...
```
{
  "Authorization" :"Bearer 6|9hvZoE739dLqapM3uCe6ff5LFOaSEnvjroEU7Ffh"
}
```

Try it, and you will se that some changes must to be done... haha

same issue as here...

On your lighthouse.php Http/middleware, inside of it add:
```
\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
```
Now, down there, in guard, change the api, for sanctum, then try again to see that still something wrong... Sorry bad habit...

On verifyCsrfToken on the same config folder, inside protected add '/graphql'...
Now it should work correctly...
 


Thats all for today
#

