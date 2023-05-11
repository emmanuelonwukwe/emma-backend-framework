#!/bin/bash
SEARCH="source $HOME/.emma_backend_aliases.bash";
REPLACE=;

sed  -i "s#$SEARCH#$REPLACE#g" $HOME/.bashrc;

#Try loading the alias file again as the emma command aliases are off the .bashrc file.
source $HOME/.bashrc;

echo $LINE;
echo "===============    MESSAGE  ====================="
echo $LINE;
echo "emma_backend commands removed from this comouter [user .bashrc]. To delete emma_backend_ ...commands, You may need to run this command now#: source $HOME/.bashrc";