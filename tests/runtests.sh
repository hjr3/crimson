#!/bin/bash

DIR=`dirname $0`

phpunit --bootstrap $DIR/TestHelper.php $DIR/crimsontest/AllTests.php
