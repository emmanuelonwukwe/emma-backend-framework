#!/bin/bash
LINE="==========================================================";
ERROR="#######################################################";

MAKE="Migration";

MAKE_PATH="./backend/database/migrations";

echo $LINE;
echo "Enter the migration table name or type 0 to quit";
read CLASSNAME;


if [    $CLASSNAME == 0 ]; then
    echo $ERROR;
    echo "You have quited this action and no $MAKE table was made";
else
    MAKE_FILE="$MAKE_PATH/$CLASSNAME.php";

    if [    -e  $MAKE_FILE ]; then
        echo $ERROR;
        echo "Sorry $CLASSNAME $MAKE table already exists. Try another table name";
    else
        cp ./backend/linux/Makes/Make$MAKE.php $MAKE_FILE;

        #Change the Make$Make to this class name
        sed -i "s/Make$MAKE/$CLASSNAME/g" $MAKE_FILE;

        echo $LINE;
        echo "Success $CLASSNAME $MAKE table generated in $MAKE_PATH";

    fi
fi