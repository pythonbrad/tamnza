mysqld_safe --skip-grant-tables &
# We wait the complete
while ( ! mysqlshow && [ $counter -gt 0 ]);
do
    counter=$(expr $counter - 1);
    sleep .1;
    echo Retry \($counter\)...;
done
# We verify if an error is got
if [ $counter -eq 0 ];
then
    exit 1;
fi
