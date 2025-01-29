#!/bin/bash

# Set Anaconda Python as the default for PHP
echo "Updating PHP environment to use Anaconda Python..."

# Add Anaconda Python to PATH
export PATH="/Users/samyelbakouri/opt/anaconda3/bin:$PATH"
export PYTHON_PATH="/Users/samyelbakouri/opt/anaconda3/bin/python"

# Save the changes to the shell profile (for persistence)
echo 'export PATH="/Users/samyelbakouri/opt/anaconda3/bin:$PATH"' >> ~/.bash_profile
echo 'export PYTHON_PATH="/Users/samyelbakouri/opt/anaconda3/bin/python"' >> ~/.bash_profile

# Apply changes to the current session
source ~/.bash_profile

# Verify the Python path
echo "PHP will now use: $(which python)"
echo "PHP's Python version: $(python --version)"
