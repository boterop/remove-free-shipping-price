#!/bin/bash

mkdir -p build

zip -r build/remove-free-shipping-price.zip . -x "*.git*" -x "*build*" -x "*.gitignore" -x "*.github*" -x "*.vscode*"
