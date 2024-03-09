# Brain Challenge

### Also available in Portuguese

## About the project

Personal project presenting my postgraduate final work, MBA in Full Stack Web Development, at PUC-MG.

This propect consists in a web app that allows you to create your own gamification environment.
The main focus here is to encourage users to solve tasks and earn points/awards.

It can be used by a company, school or any other organization that needs to build an
environment to help them improve their processes.

## How to use it

The project can be accessed through the link: http://144.22.158.0.

There are three types of users:

- Students
- Teachers
- Administrators

## Students

- Login: student@student.com
- Password: student

It's the one that is going to study and solve tasks.

The students can go to the teachers and mark presence at their booths, see the progress
of their tasks, check the presentations they can attend, the schedules, see their awards
and also answer the questions of the presentations they attend.

## Teachers

- Login: teacher@teacher.com
- Password: teacher

It's the one that is going to create presentations and questions for it.

During the event, they can have some booths and share QR codes for the students to mark
their attendance.

## Administrators

- Login: admin@admin.com
- Password: admin

The responsibles to manage the event.

They can create the events, teachers, students and everything else in the system.
All features can be managed via the admin control panel.

## Installation guide

Clone the project and enter the directory:
```
git clone https://github.com/VFDouglas/brain-challenge.git && cd brain-challenge
```
Create a `.env` file:
```
cp .env.example .env
```
Run the commands:
```
docker compose up --build -d

# Enter the container
docker compose exec php sh
composer install
php artisan key:generate
npm ci && npm run dev
```
