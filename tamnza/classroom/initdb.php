<?php

/*
	This script permit to initialize the database
*/

namespace Tamnza\App\Classroom;

use Tamnza\Database;

$db = new Database\BaseDAO('', '', []);

$db->exec("
CREATE TABLE IF NOT EXISTS classroom_user (
	id INTEGER AUTO_INCREMENT,
	password VARCHAR(128) NOT NULL,
	last_login DATETIME NULL,
	username VARCHAR(150) NOT NULL UNIQUE,
	last_name VARCHAR(150) NOT NULL,
	email VARCHAR(254) NOT NULL,
	is_active BOOL NOT NULL,
	date_joined DATETIME NOT NULL,
	is_student BOOL NOT NULL,
	is_teacher BOOL NOT NULL,
	first_name VARCHAR(150) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS classroom_subject (
	id INTEGER AUTO_INCREMENT,
	name VARCHAR(30) NOT NULL,
	color VARCHAR(7) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS classroom_student (
	user_id INTEGER NOT NULL,
	PRIMARY KEY (user_id),
	FOREIGN KEY (user_id) REFERENCES classroom_user (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS classroom_student_interests (
	id INTEGER AUTO_INCREMENT,
	student_id INTEGER NOT NULL,
	subject_id INTEGER NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (student_id) REFERENCES classroom_student (user_id) ON DELETE CASCADE,
	FOREIGN KEY (subject_id) REFERENCES classroom_subject (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS classroom_quiz (
	id INTEGER AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	owner_id INTEGER NOT NULL,
	subject_id INTEGER NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (owner_id) REFERENCES classroom_user (id) ON DELETE CASCADE,
	FOREIGN KEY (subject_id) REFERENCES classroom_subject (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS classroom_question (
	id INTEGER AUTO_INCREMENT,
	text VARCHAR(255) NOT NULL,
	quiz_id INTEGER NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (quiz_id) REFERENCES classroom_quiz (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS classroom_answer (
	id INTEGER AUTO_INCREMENT,
	text VARCHAR(255) NOT NULL,
	is_correct BOOL NOT NULL,
	question_id INTEGER NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (question_id) REFERENCES classroom_question (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS classroom_takenquiz (
	id INTEGER AUTO_INCREMENT,
	score real NOT NULL,
	date DATETIME NOT NULL,
	quiz_id INTEGER NOT NULL,
	student_id INTEGER NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (quiz_id) REFERENCES classroom_quiz (id) ON DELETE CASCADE,
	FOREIGN KEY (student_id) REFERENCES classroom_student (user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS classroom_studentanswer (
	id INTEGER AUTO_INCREMENT,
	answer_id INTEGER NOT NULL,
	student_id INTEGER NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (answer_id) REFERENCES classroom_answer (id) ON DELETE CASCADE,
	FOREIGN KEY (student_id) REFERENCES classroom_student (user_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS classroom_php_session (
	session_key VARCHAR(40) NOT NULL,
	session_data TEXT NOT NULL,
	expire_date DATETIME NOT NULL,
	PRIMARY KEY (session_key)
);

# We clean the database
SET SESSION foreign_key_checks=OFF;
TRUNCATE classroom_user;
TRUNCATE classroom_subject;
TRUNCATE classroom_student;
TRUNCATE classroom_student_interests;
TRUNCATE classroom_quiz;
TRUNCATE classroom_question;
TRUNCATE classroom_answer;
TRUNCATE classroom_takenquiz;
TRUNCATE classroom_studentanswer;
TRUNCATE classroom_php_session;
SET SESSION foreign_key_checks=ON;
");
