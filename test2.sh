#!/bin/bash
#
# Perform some verification on the Tamnza project.
# NB: We don't verify the views, just the working of the controllers.

HOST=localhost:8080
COOKIE_JAR=/tmp/cookie
TEACHER_COOKIE_JAR=/tmp/teacher_cookie
STUDENT_COOKIE_JAR=/tmp/student_cookie
HEADER=/tmp/header
OUTPUT=/tmp/output

#######################################
# Start the application with the PHP built-in web server.
# Globals:
#   HOST
# Arguments:
#   None
#######################################
start_server() {
    # Start the PHP Server
    php -S $HOST -t tamnza &

    # We wait the PHP server to start
    counter=3

    while ( ! pidof php && [ $counter -gt 0 ]); do
        counter=$(expr $counter - 1)
        sleep .5
        echo Retry \($counter\)...
    done

    # We verify if an error is got
    if [ $counter -eq 0 ]; then
        return 1
    fi
}

#######################################
# Stop the PHP built-in web server if running.
# Globals:
#   None
# Arguments:
#   None
#######################################
stop_server() {
    pid=$(pidof php)
    if [ ! -z "$pid" ]; then
        kill $pid
    fi
}

#######################################
# Verify if the home page works.
# Globals:
#   HOST
#   COOKIE_JAR
# Arguments:
#   None
#######################################
test_connect() {
    header=$(curl -I $HOST --cookie-jar $COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header"
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi
}

#######################################
# Verify if the session has been created by the server.
# Globals:
#   HOST
#   COOKIE_JAR
# Arguments:
#   None
#######################################
test_session() {
    # We verify if the server set a cookie
    header=$(curl -I $HOST --cookie $COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if (grep '^Set-Cookie:' <<< $header); then
        return 1
    fi
}

#######################################
# Verify if the 404 page works.
# Globals:
#   HOST
# Arguments:
#   None
#######################################
test_404() {
    header=$(curl -I $HOST/?url=/a/b/c/d -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 404' <<< $header); then
        return 1
    fi
}

#######################################
# Verify if the 500 page works.
# Globals:
#   HOST
# Arguments:
#   None
#######################################
test_500() {
    header=$(curl -I $HOST/?url=/error -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 500' <<< $header); then
        return 1
    fi
}

#######################################
# Verify if the signup page works
# Globals:
#   HOST
# Arguments:
#   None
#######################################
test_signup() {
    header=$(curl -I $HOST/?url=/signup -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi
}

#######################################
# Verify if it's possible to signup as teacher
# Globals:
#   HOST
#   HEADER
# Arguments:
#   None
#######################################
test_signup_teacher() {
    header=$(curl -I $HOST/?url=/teacher_signup -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi

    # Both password don't match
    if ! (curl $HOST/?url=/teacher_signup -d username=teacher\
     -d password1=teacher -d password2=wrong -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 2
    fi

    # Success
    if ! (curl $HOST/?url=/teacher_signup -d username=teacher\
     -d password1=teacher -d password2=teacher -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 301' < $HEADER\
      && ! grep '^Location: ?url=/error' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 3
    fi

    # Username already used
    if ! (curl $HOST/?url=/teacher_signup -d username=teacher\
     -d password1=teacher -d password2=teacher -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 4
    fi
}

#######################################
# Verify if it's possible to signup as student.
# Globals:
#   HOST
#   HEADER
# Arguments:
#   None
#######################################
test_signup_student() {
    header=$(curl -I $HOST/?url=/student_signup -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi

    # We load subjects
    subjects=''

    for subject in $(curl --url $HOST/?url=/student_signup -s --show-error\
     | grep 'form-check-input'\
     | sed 's/.* value="//'\
     | sed 's/".*//'); do
        subjects="$subjects&interests[]=$subject"
    done
    
    # Both password don't match
    if ! (curl $HOST/?url=/student_signup -d username=student\
     -d password1=student -d password2=wrong --data-raw "${subjects}"\
     -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 2
    fi
    
    # Success
    if ! (curl $HOST/?url=/student_signup -d username=student\
     -d password1=student -d password2=student --data-raw "${subjects}"\
     -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 301' < $HEADER\
      && ! grep '^Location: ?url=/error' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 3
    fi

    # Username already used
    if ! (curl $HOST/?url=/student_signup -d username=student\
     -d password1=student -d password2=student --data-raw "${subjects}"\
     -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 4
    fi
}

#######################################
# Verify if the login page works.
# Globals:
#   HOST
#   HEADER
# Arguments:
#   None
#######################################
test_login() {
    header=$(curl -I $HOST/?url=/login -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi

    # Wrong data
    if ! (curl $HOST/?url=/login -d username=student\
     -d password=wrong -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 2
    fi
}

#######################################
# Verify if it's possible to login as teacher.
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
#   HEADER
# Arguments:
#   None
#######################################
test_login_teacher() {
    if ! (curl $HOST/?url=/login --cookie-jar $TEACHER_COOKIE_JAR -d username=teacher\
     -d password=teacher -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 301' < $HEADER\
      && ! grep '^Location: ?url=/error' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 1
    fi

    # We verify the home redirection
    header=$(curl -I $HOST --cookie $TEACHER_COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 301' < $HEADER && ! grep '^Location: ?url=/error' <<< $header); then
        return 2
    fi
}

#######################################
# Verify if it's possible to login as student.
# Globals:
#   HOST
#   STUDENT_COOKIE_JAR
#   HEADER
# Arguments:
#   None
#######################################
test_login_student() {
    if ! (curl $HOST/?url=/login --cookie-jar $STUDENT_COOKIE_JAR -d username=student\
     -d password=student -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 301' < $HEADER\
      && ! grep '^Location: ?url=/error' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 1
    fi

    # We verify the login
    header=$(curl -I $HOST --cookie $STUDENT_COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 301' < $HEADER && ! grep '^Location: ?url=/error' <<< $header); then
        return 2
    fi
}

#######################################
# Verify if the teacher home page works.
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
# Arguments:
#   None
#######################################
test_teacher_home_without_quiz() {
    if (curl $HOST?url=/teacher --cookie $TEACHER_COOKIE_JAR -s --show-error\
     | grep '?url=/teacher/quiz/[0-9]*/change'); then
        return 1
    fi
}

test_teacher_home_with_quiz() {
    if ! (curl $HOST?url=/teacher --cookie $TEACHER_COOKIE_JAR -s --show-error\
     | grep '?url=/teacher/quiz/[0-9]*/change'); then
        return 1
    fi
}

#######################################
# Verify if it's possible to add a quiz
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
#   HEADER
# Arguments:
#   None
#######################################
test_add_quiz() {
    header=$(curl -I $HOST/?url=/teacher/quiz/add --cookie $TEACHER_COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi

    # Wrong data
    if ! (curl $HOST/?url=/teacher/quiz/add -d name -d subject\
     --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 2
    fi

    # We select one subject
    subject=$(curl $HOST/?url=/teacher/quiz/add --cookie $TEACHER_COOKIE_JAR -s --show-error\
     | sort\
     | grep '<option.*</option>' -o -m 1\
     | sed 's/.*="//'\
     | sed 's/".*//');

    # Success
    if ! (curl $HOST/?url=/teacher/quiz/add -d name=quiz -d subject=$subject\
     --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 301' < $HEADER\
      && grep '^Location: ?url=/teacher/quiz/[0-9]*/change' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 3
    fi
}

#######################################
# Get a quiz
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
# Arguments:
#   None
# Output:
#   None | String
#######################################
get_quiz() {
    curl $HOST/?url=/teacher --cookie $TEACHER_COOKIE_JAR -s --show-error\
     | grep '?url=/teacher/quiz/[0-9]*/change' -o -m 1\
     | sed 's/.*z\///'\
     | sed 's/\/.*//'
}

#######################################
# Verify if it's possible to modify a quiz
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
#   HEADER
# Arguments:
#   None
#######################################
test_change_quiz() {
    # We select one quiz
    quiz=$(get_quiz)

    if [ -z $quiz ]; then
        return 1
    fi

    header=$(curl -I $HOST/?url=/teacher/quiz/$quiz/change --cookie $TEACHER_COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 2
    fi

    # Wrong data
    if ! (curl $HOST/?url=/teacher/quiz/$quiz/change -d name -d subject\
     --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 3
    fi

    # Success
    if ! (curl $HOST/?url=/teacher/quiz/$quiz/change -d name=quiz -d subject=$subject\
     --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 301' < $HEADER\
      && grep '^Location: ?url=/teacher/quiz/[0-9]*/change' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 4
    fi
}

#######################################
# Verify if the quiz result page works.
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
# Arguments:
#   None
#######################################
test_quiz_result_without_respondents() {
    # We select one quiz
    quiz=$(get_quiz)

    if [ -z $quiz ]; then
        return 1
    fi

    header=$(curl -I $HOST/?url=/teacher/quiz/$quiz/results --cookie $TEACHER_COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 2
    fi

    if ! (curl $HOST?url=/teacher/quiz/$quiz/results --cookie $TEACHER_COOKIE_JAR -s --show-error\
     | grep '<strong>0</strong>'); then
        return 3
    fi
}

test_quiz_result_with_respondents() {
    # We select one quiz
    quiz=$(get_quiz)

    if [ -z $quiz ]; then
        return 1
    fi

    header=$(curl -I $HOST/?url=/teacher/quiz/$quiz/results --cookie $TEACHER_COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 2
    fi

    if (curl $HOST?url=/teacher/quiz/$quiz/results --cookie $TEACHER_COOKIE_JAR -s --show-error\
     | grep '<strong>0</strong>'); then
        return 3
    fi
}

#######################################
# Verify if it's possible to delete a quiz.
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
#   HEADER
# Arguments:
#   None
#######################################
test_delete_quiz() {
    # We select one quiz
    quiz=$(get_quiz)

    if [ -z $quiz ]; then
        return 1
    fi

    header=$(curl -I $HOST/?url=/teacher/quiz/$quiz/delete --cookie $TEACHER_COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 2
    fi

    if ! (curl $HOST?url=/teacher/quiz/$quiz/delete --data-raw 'null'\
     --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 301' < $HEADER\
      && grep '^Location: ?url=/teacher' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 3
    fi
}

#######################################
# Verify if it's possible to add a question
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
#   HEADER
# Arguments:
#   None
#######################################
test_add_question() {
    # We select one quiz
    quiz=$(get_quiz)

    if [ -z $quiz ]; then
        return 1
    fi

    header=$(curl -I $HOST/?url=/teacher/quiz/$quiz/question/add --cookie $TEACHER_COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 2
    fi

    # Wrong data
    if ! (curl $HOST/?url=/teacher/quiz/$quiz/question/add -d text\
     --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 3
    fi

    # Success
    if ! (curl $HOST/?url=/teacher/quiz/$quiz/question/add -d text=text\
     --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 301' < $HEADER\
      && grep '^Location: ?url=/teacher/quiz/[0-9]*/question/[0-9]*' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 4
    fi
}

#######################################
# Get one question
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
# Arguments:
#   None
# Ouput:
#   None | String
#######################################
get_question() {
    curl $HOST/?url=/teacher/quiz/$quiz/change --cookie $TEACHER_COOKIE_JAR -s --show-error\
     | grep "?url=/teacher/quiz/$quiz/question/[0-9]*" -o -m 1\
     | sed 's/.*n\///'
}

#######################################
# Verify if it's possible to change a question
# Globals:
#   HOST
#   TEACHER_COOKIE_JAR
#   HEADER
# Arguments:
#   None
#######################################
test_change_question() {
    # We select one quiz
    quiz=$(get_quiz)

    if [ -z $quiz ]; then
        return 1
    fi

    # We select one question
    question=$(get_question)

    if [ -z $question ]; then
        return 2
    fi

    header=$(curl -I $HOST/?url=/teacher/quiz/$quiz/question/$question --cookie $TEACHER_COOKIE_JAR -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 3
    fi

    # Wrong data
    if ! (curl $HOST/?url=/teacher/quiz/$quiz/question/$question -d text\
     --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 4
    fi

    # 1 answer left
    if ! (curl $HOST/?url=/teacher/quiz/$quiz/question/$question\
     -d text=text --data-raw 'answer-ids[]=-1' -d answer--1-text=answer-1\
     -d answer--1-is_correct=on --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 5
    fi

    # no correct answer
    if ! (curl $HOST/?url=/teacher/quiz/$quiz/question/$question\
     -d text=text --data-raw 'answer-ids[]=-1&answer-ids[]=-2'\
     -d answer--1-text=answer-1 -d answer--2-text=answer-2\
     --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 200' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 6
    fi

    # success
    if ! (curl $HOST/?url=/teacher/quiz/$quiz/question/$question\
     -d text=text --data-raw 'answer-ids[]=-1&answer-ids[]=-2'\
     -d answer--1-text=answer-1 -d answer--2-text=answer-2\
     -d answer--1-is_correct=on --cookie $TEACHER_COOKIE_JAR -s --show-error -D $HEADER &> $OUTPUT\
      && grep '^HTTP/1.1 301' < $HEADER\
      && grep '^Location: ?url=/teacher/quiz/[0-9]*/change' < $HEADER); then
        cat $HEADER
        cat $OUTPUT
        return 7
    fi

    # exceed number of answer

}

#######################################
# Start the tests sequentially.
# Globals:
#   None
# Arguments:
#   None
#######################################
start_test() {
    nb_test=-1
    for test in start_server test_connect test_session test_404\
     test_500 test_signup test_signup_teacher test_signup_student\
     test_login test_login_teacher test_teacher_home_without_quiz\
     test_add_quiz test_teacher_home_with_quiz test_change_quiz\
     test_quiz_result_without_respondents test_add_question test_change_question\
     test_delete_quiz test_login_student test_quiz_result_with_respondents; do
        nb_test=$(expr $nb_test + 1)
        echo ================[Test] ${test}================
        if $test; then
            echo ${test} OK
        else
            err_code=$?
            echo ${test} "FAILED($err_code)"
            return $err_code
        fi
    done
    echo =============================================
    echo Ran $nb_test tests.
}

# We stop the server if running
stop_server

start_test

stop_server

exit $?