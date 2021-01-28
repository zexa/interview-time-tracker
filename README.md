# interview-time-tracking
A homework task from a web development company.

The task consists of writing a little time tracking application.

This repository stores the API code for the above mentioned application.

Please find the instructions for the frontend part of the application below:
TODO: Add frontend link here

## Requirements
* docker-compose (tested with version 1.27.4)
* docker (tested with version 19.03.13-ce, build 4484c46d9d

## Usage
```
git clone ...
cd interview-time-tracker
docker-compose -f docker/docker-compose.yml up -d
docker-compose -f docker/docker-compose.yml exec fpm /bin/sh
composer install
bin/console doctrine:migrations:migrate
```

Afterwards, the application should be reachable via http://localhost:8080

## Thoughts

### Task entity structure
```
task_private {
	id: string,
	user: User,
	title: string,
	comment: string,
	date: DateTimeImmutable,
	time_spent: DateInterval,
}

task_public {
  title: string,
  comment: string,
  date: DateTimeImmutable,
  time_spent: DateInterval,
}
```

### Pagination
```
$offset = $page * self::page_size; // method should provide page
$id = $this->jwtManager->getUserIdFromToken();
ReportFormat::csv()
```

```
SELECT * FROM tasks
WHERE user=$id 
OFFSET $offset
LIMIT self::page_size;
```

### Endpoints
Endpoint: POST /login
Description: Login
Response: jwt

Endpoint: POST /register
Description: Register
Response: jwt

Endpoint: GET /tasks
Description: Get all tasks
Query Parameters:
* page
Response: [task_public]

Endpoint: POST /tasks
Description: Create task
Payload: task_public

Endpoint: GET /report
Description: Get report
Query Parameters:
* date_from: DateTimeImmutable
* date_to: DateTimeImmutable
* format: ReportFormat

