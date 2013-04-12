#!/bin/bash

php tools/concat.php
pandoc one-page.md -o Getting-Hired.odt
rm one-page.md
