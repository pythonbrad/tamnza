path="/opt/lampp/bin"
phpunit_cmd="$path/php $path/phpunit.phar"

# We execute all the tests
for test in $(find ./tamnza | grep Test.php$);do
    echo "Testing of " $test...;
    $phpunit_cmd $test --colors auto || break;
done