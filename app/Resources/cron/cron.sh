#!/bin/bash

FileYouWantToTest='/var/www/ApacheDynamicVHost/app/Resources/cron/restart'
if [ -f $FileYouWantToTest ]
    then
        /etc/init.d/apache2 restart
        rm -f $FileYouWantToTest
fi
exit 0