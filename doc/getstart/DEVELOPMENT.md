# Development Guide

This project use MVC mode to develop.

## Structure

- M (Model): `app/models`: connect to database, get data from database, and return data to controller.
- V (View): `public/views`: show data to user, and get data from user.
- C (Controller): `app/controllers`: control the core logics, get data from model, and send data to view.

There are other helper modules:

- `app/bootstrap`: initialize the project
- `app/config`: store the configuration of the project
- `app/database`: store the data files and sql functions
- `app/routes`: store the routes (page navigation) of the project
- `app/tools`: store the tools for other modules