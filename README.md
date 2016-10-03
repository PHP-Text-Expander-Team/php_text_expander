# PHP Text Expander

#### _PHP Week 5 Group Project, 9.30.2016_

#### By _**Ryan Loos, Stephen Newkirk, Martin Cartledge, Matthew Brandenburg**_

## Description

_The Text Expander application allows a user to input text and assign that text with a shorthand code. When the user input the shorthand code the program will return the assigned text._

## Setup/Installation Requirements

* _Clone this repository to your desktop_
* _Run composer install in Terminal_
* _Start up Apache and MySQL_
* _CREATE DATABASE expander;_
* _USE expander;_
* _CREATE TABLE snippets (id serial PRIMARY KEY, shortcut VARCHAR (255), text TEXT);_
* _start a server in web directory (php -S localhost:8000)_
* _Go to localhost:8000 in web browser_
* _Enjoy_

## Behavior Driven Development

|Behavior|Input|Output|
|--------|:---:|-----:|
|Program can take in user input and return input|"test"|"test"|
|User can save a Snippet instance in the database|"te" "test"|"te" "test"|
|The program can retrieve the saved Snippet by id number|"1", "2"|"test 1", "test 2"|
|User can see all saved Snippets|click show all|"te", "fe", "je"|
|User can modify an existing Snippet|"re == test"|"te == test"|
|User can delete an individual Snippet|"te", click "delete"|""|
|User can delete all Snippets|click delete all|""|

## Known Bugs

_None yet_

## Support and contact details

_Dem PHP Boys: DemPHPboys@gmail.com_

## Technologies Used

* HTML
* PHP
* TWIG 1.0
* SILEX 1.1
* MySQL

### License

*This webpage is licensed under the GPL license.*

Copyright (c) 2016 **Dem PHP Boys**
