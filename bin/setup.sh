#!/bin/bash

# Exit if any command fails
set -e

# Include useful functions
. "$(dirname "$0")/includes.sh"

# Change to the expected directory
cd "$(dirname "$0")/.."

echo $(status_message "Starting the Luehrsen // Heinrich development enviroment..." )

# Check Node and NVM are installed
. "$(dirname "$0")/install-npm.sh"

# Check Docker is installed and running
. "$(dirname "$0")/install-docker.sh"

CURRENT_URL="localhost"

echo "\nWelcome to your XenForo\n"
echo "Run $(action_format "grunt watch"), then open $(action_format "$CURRENT_URL") to get started!"
