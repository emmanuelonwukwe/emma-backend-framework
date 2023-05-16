#!/bin/bash
#bash ./backend/linux/aliases_setup.sh;

#copy save path for add command clone check
if  [ -e ~/.emma_backend_framework_clone/emma-backend-framework ]; then
echo '';
else
    mkdir ~/.emma_backend_framework_clone;
    mkdir ~/.emma_backend_framework_clone/emma-backend-framework;
    cp -r . ~/.emma_backend_framework_clone/emma-backend-framework;
fi

if  [ -e ~/bin ]; then
    cp ./backend/linux/emma ~/bin;
    echo "Success, Emma backend Set up completed You can now add emma backends to your projects root anytime. get help -: emma backend list";
else
    mkdir ~/bin;
    #export PATH="~/bin:$PATH";
    cp ./backend/linux/emma ~/bin;
    echo "Success, Emma backend Set up completed You can now add emma backends to your projects root anytime anywhere. get help -: emma backend list";
fi