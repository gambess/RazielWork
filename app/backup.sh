#!/bin/bash

# Datos de la conexion
USER=backups.sistemas
PASSWORD='admin23.-'
HOST=2.139.144.177
PORT=6022

/var/www/sgsd/current/app/console cron:backupdb --no-debug

cd /tmp/

sshpass -p ${PASSWORD} sftp -P ${PORT} ${USER}@${HOST} << CMD
cd SGSD
mput dump_*
bye
CMD

mv dump_* /home/sgsd/backups

#/var/www/sgsd/current/app/console cron:cleandb --all=7 --ferr=30 --process-isolation --no-debug


