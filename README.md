# Code Cheats

#### _PHP Week 5 Group Project, 10.2.2016-10.7.2016_

#### By _**Ryan Loos, Stephen Newkirk, Martin Cartledge, Matthew Brandenburg**_

## User Story

_As a power user, I want an application that will allow me to save text and assign it a shortcut so that I can use that text later without having to retype it.  
	*	It must save my shortcut and text input.
	*	I must be able to access saved shortcuts._

As a developer, I want a program that can save templates of code and text I write every day. I would like to be able to modify my templates by adding text variables that can be replaced whenever I have to reuse the template.
  * I must be able to save and access large templates of code and text.
  * I must be able to add variables when I create a template.
  * I must be able to replace those variables with text later when I want to use my template_

## Description

_The Code Cheats application allows a user to input text and assign that text a shortcut. When the user selects the shortcut the program will return the assigned text._

## Setup/Installation Requirements

* _Clone this repository to your desktop_
* _Run composer install in Terminal_
* _Start up Apache and MySQL_
* _CREATE DATABASE expander;_
* _USE expander;_
* _CREATE TABLE snippets (id serial PRIMARY KEY, shortcut VARCHAR (255), text LONGTEXT);_
* _start a server in web directory (php -S localhost:8000)_
* _Go to localhost:8000 in web browser_
* _Enjoy_

## Behavior Driven Development

|Behavior|Input|Output|
|--------|:---:|-----:|
|User can save a Snippet instance and shortcut in the database|"em" => "me@email.com"|"em" => "me@email.com"|
|User can save variable into input |"em" => |"me@(variable.com)"|
|Before final output, user can change information in variable |"me@(variable.com)"|"me@gmail.com"|
|The program can retrieve the saved Snippet by its shortcut|"em"|"me@email.com"|
|User can see all saved Snippets|click dropdown|"em", "class", "getset"|
|User can modify an existing Snippet|"em == me@email.com"|"em == you@email.com"|
|User can delete an individual Snippet|"te", click "delete"|""|
|User can delete all Snippets|click delete all|""|

## Known Bugs

_When highlighting text to replace it with a variable, the original text does not delete_

## Support and contact details

* _Team lead - Ryan Loos @ rloos289@gmail.com_
* _Team member - Stephen Newkirk @ newkirk771@gmail.com_
* _Team member - Martin Cartledge @ martincartledge90@gmail.com_
* _Team member - Matthew Brandenburg @ matt.bran87@gmail.com_

## Technologies Used

* HTML
* PHP
* TWIG 1.0
* SILEX 1.1
* MySQL

### License

*This webpage is licensed under the GPL license.*

Copyright (c) 2016 **Code Cheats**

