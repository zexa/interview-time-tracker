Time tracking app

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



$offset = $page * self::page_size; // method should provide page
$id = $this->jwtManager->getUserIdFromToken();

ReportFormat::csv()

SELECT * FROM tasks
WHERE user=$id 
OFFSET $offset
LIMIT self::page_size;


----

Endpoint: GET /login
Description: Show login form

Endpoint: POST /login
Description: Login
Response: jwt

Endpoint: GET /register
Description: Show register form

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

