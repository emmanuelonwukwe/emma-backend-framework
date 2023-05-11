#!/bin/bash
LINE="==========================================================";
ERROR="#######################################################";

MAKE="Exception";

MAKE_PATH="./backend/app/Http/Exceptions";

echo $LINE;
echo "Enter the $MAKE class name or type 0 to quit";
read CLASSNAME;


if [    $CLASSNAME == 0 ]; then
    echo $ERROR;
    echo "You have quited this action and no $MAKE class was made";
else
    MAKE_FILE="$MAKE_PATH/$CLASSNAME.php";

    if [    -e  $MAKE_FILE ]; then
        echo $ERROR;
        echo "Sorry $CLASSNAME $MAKE already exists. Try another class name";
    else
        cp ./backend/linux/Makes/Make$MAKE.php $MAKE_FILE;

        #Change the Make$Make to this class name
        sed -i "s/Make$MAKE/$CLASSNAME/g" $MAKE_FILE;

        echo $LINE;
        echo "Success $CLASSNAME $MAKE generated in $MAKE_PATH";

    fi
fi