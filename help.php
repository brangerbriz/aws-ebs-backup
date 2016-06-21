<?php


define('help_es',"Herramienta para AWS que ayuda a crear snapshots y a determinar cuantos de estos snapshots se quieren mantener.

Parametros obligatorios:

volume-id: Identificador del ebs 
quantity   : Cantidad de snapshots a mantener en el sistema

Ejemplo:

volum-id:  vol-1c5a6df04
quantity:   3

  backup_ebs.php vol-1c5a6df04 3

Hará el backup de vol-1c5a6df04 y mantendrá un máximo de 3 snapshots en el sistema. Si ya existieran 3 snapshots de el ebs, el programa borra el snapshot más viejo antes de crear el nuevo snaps");



define('help',"AWS tool that helps create snapshots and to determine how many of these snapshots are to be maintained.

Required parameters:

volume-id: identifier ebs
quantity: Number of snapshots to keep in the system

Example:

volum-id: vol-1c5a6df04
quantity: 3

  backup_ebs.php vol-1c5a6df04 3

It will do the backup vol-1c5a6df04 and maintain a maximum of 3 snapshots on the system. If there are already 3 snapshots of the ebs, the program deletes the oldest snapshot before creating the new snapshot.");

