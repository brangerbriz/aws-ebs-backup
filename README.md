##AWS tool that helps create snapshots and to determine how many of these snapshots are to be maintained.

Required parameters:

volume-id: identifier ebs
max-backups: Number of snapshots to keep in the system
description: Describe the snapshot
server: Servername


###Example:
```bash
php backup_ebs.php volume-id=vol-ccd77822 max-backups=168 description="Repositories" server="PAMM"
```
