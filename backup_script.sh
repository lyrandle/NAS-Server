#!/bin/bash

# Define the backup source and destination
SOURCE_DIR="/mnt/nas"   # Update this to the actual directory you want to back up
BACKUP_DIR="/var/backups"      # Update this to the actual backup destination directory
DATE=$(date +\%F)

# Ensure backup directory exists
mkdir -p "$BACKUP_DIR"

# Create a backup using tar with relative paths
/bin/tar -czf "$BACKUP_DIR/backup_$DATE.tar.gz" -C "$SOURCE_DIR" .

