#!/usr/bin/env bash
# Script to test the Tamnza Application

_cookie_jar=/tmp/cookie
_header=/tmp/header

start_server()
{
    # Start the PHP Server
    php -S localhost:80 -t tamnza &

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

stop_server()
{
    pid=$(pidof php)
    if [ ! -z "$pid" ]; then
        kill $pid
    fi
}

test_connect()
{
    header=$(curl -I localhost --cookie-jar $_cookie_jar -s --show-error)
    echo '--- HEADER ---'
    echo "$header"
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi
}

test_session()
{
    # We verify if the server set a cookie
    header=$(curl -I localhost --cookie $_cookie_jar -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if (grep '^Set-Cookie:' <<< $header); then
        return 1
    fi
}

test_404()
{
    header=$(curl -I localhost/?url=/a/b/c/d -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 404' <<< $header); then
        return 1
    fi
}

test_500()
{
    header=$(curl -I localhost/?url=/error -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 500' <<< $header); then
        return 1
    fi
}

test_signup()
{
    header=$(curl -I localhost/?url=/signup -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi
}

test_signup_teacher()
{
    header=$(curl -I localhost/?url=/teacher_signup -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi

    # Both password don't match
    if ! (curl localhost/?url=/teacher_signup -d username=teacher\
     -d password1=teacher -d password2=wrong -s --show-error -D $_header -o /dev/null\
      && grep '^HTTP/1.1 200' < $_header); then
        return 2
    fi

    # Success
    if ! (curl localhost/?url=/teacher_signup -d username=teacher\
     -d password1=teacher -d password2=teacher -s --show-error -D $_header -o /dev/null\
      && grep '^HTTP/1.1 301' < $_header); then
        return 3
    fi

    # Username already used
    if ! (curl localhost/?url=/teacher_signup -d username=teacher\
     -d password1=teacher -d password2=teacher -s --show-error -D $_header -o /dev/null\
      && grep '^HTTP/1.1 200' < $_header); then
        return 4
    fi
}

test_signup_student()
{
    header=$(curl -I localhost/?url=/student_signup -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi

    # We load subjects
    subjects=""

    for subject in $(
        curl --url localhost/?url=/student_signup -s --show-error\
         | grep "form-check-input" |\
          sed "s/.* value=\"//" | sed 's/\" .*//'); do
        subjects="$subjects&interests[]=$subject"
    done
    
    # Both password don't match
    if ! (curl localhost/?url=/student_signup -d username=student\
     -d password1=student -d password2=wrong --data-raw "${subjects}"\
      -s --show-error -D $_header -o /dev/null\
      && grep '^HTTP/1.1 200' < $_header); then
        return 2
    fi
    
    # Success
    if ! (curl localhost/?url=/student_signup -d username=student\
     -d password1=student -d password2=student --data-raw "${subjects}"\
      -s --show-error -D $_header -o /dev/null\
      && grep '^HTTP/1.1 301' < $_header); then
        return 3
    fi

    # Username already used
    if ! (curl localhost/?url=/student_signup -d username=student\
     -d password1=student -d password2=student --data-raw "${subjects}"\
      -s --show-error -D $_header -o /dev/null\
      && grep '^HTTP/1.1 200' < $_header); then
        return 4
    fi
}

test_login()
{
    header=$(curl -I localhost/?url=/login -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^HTTP/1.1 200' <<< $header); then
        return 1
    fi

    # Wrong data
    if ! (curl localhost/?url=/login -d username=student\
     -d password=wrong -s --show-error -D $_header -o /dev/null\
      && grep '^HTTP/1.1 200' < $_header); then
        return 2
    fi
}

test_login_teacher()
{
    if ! (curl localhost/?url=/login --cookie-jar $_cookie_jar -d username=teacher\
     -d password=teacher -s --show-error -D $_header -o /dev/null\
      && grep '^HTTP/1.1 301' < $_header); then
        return 1
    fi

    # We verify the login
    header=$(curl -I localhost --cookie $_cookie_jar -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^Location: ?url=/teacher' <<< $header); then
        return 1
    fi
}

test_login_student()
{
    if ! (curl localhost/?url=/login --cookie-jar $_cookie_jar -d username=student\
     -d password=student -s --show-error -D $_header -o /dev/null\
      && grep '^HTTP/1.1 301' < $_header); then
        return 1
    fi

    # We verify the login
    header=$(curl -I localhost --cookie $_cookie_jar -s --show-error)
    echo '--- HEADER ---'
    echo "$header";
    echo '--------------'
    if ! (grep '^Location: ?url=/student' <<< $header); then
        return 1
    fi
}

start_test()
{
    nb_test=-1
    for test in start_server test_connect test_session test_404\
     test_500 test_signup test_signup_teacher test_signup_student\
     test_login test_login_teacher test_login_student; do
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