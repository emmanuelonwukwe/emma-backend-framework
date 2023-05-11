#!/bin/bash
LINE="============================================================";
#first copy the latest emma aliasese to the user home
cp ./backend/linux/.emma_backend_aliases.bash ~;

#check the bashrc file
if [    -e  ~/.bashrc    ]; then
    if grep "source $HOME/.emma_backend_aliases.bash" $HOME/.bashrc; then
        echo $LINE;
        echo "===============    MESSAGE  ====================="
        echo $LINE;
        echo "emma_backend commands already registered on this computer [user .bashrc]. To start using emma_backend_ ...commands, You may need to run this command#: source $HOME/.bashrc";
    else
        #Append to load the emma alias in the bashrc file
        echo "source $HOME/.emma_backend_aliases.bash" >> ~/.bashrc;

        echo $LINE;
        echo "===============    MESSAGE  ====================="
        echo $LINE;

        echo "emma_backend commands successfully registered in this computer [user .bashrc]. To start using emma_backend_ ....commands, You may need to run this command#: source $HOME/.bashrc";
    fi
else
    #create the bashrc file for him and add the source of the alias to be loaded
    echo "source $HOME/.emma_backend_aliases.bash" > ~/.bashrc;

    echo $LINE;
    echo "===============    MESSAGE  ====================="
    echo $LINE;

    echo "emma_backend commands successfully registered in this computer [user .bashrc]. To start using emma_backend_ ...commands, You may need to run this command#: source $HOME/.bashrc";
fi

#Try loadoading the aliases to the user machine// else the user has to do $: source $HOME/.bashrc
source ~/.bashrc;