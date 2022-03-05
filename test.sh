if [ -z $PHPUNIT_CMD ]; then
    echo "You need to config the PHPUNIT_CMD variable"
    exit 1
fi

# We execute all the tests
for test in $(find ./tamnza | grep Test.php$); do
    echo "Testing of " $test...
    $PHPUNIT_CMD $test --colors auto
    if [ ! $? -eq 0 ]; then
        echo ERROR: $test
        exit 1
    fi
done
